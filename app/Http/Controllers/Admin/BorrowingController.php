<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Notifications\BorrowingApproved;
use App\Notifications\BorrowingReturned;
use App\Notifications\BorrowingRejected;

class BorrowingController extends Controller
{
    public function index()
    {
        $requests = Borrowing::where('status', Borrowing::STATUS_REQUESTED)
            ->with(['user', 'comic'])
            ->orderBy('requested_at', 'desc')
            ->get();

        $active = Borrowing::where('status', Borrowing::STATUS_DIPINJAM)
            ->with(['user', 'comic'])
            ->orderBy('borrowed_at', 'desc')
            ->get();

        return view('admin.borrowings.index', compact('requests', 'active'));
    }

    public function approve(Request $request, $id)
    {
        $borrowing = Borrowing::where('id', $id)
            ->where('status', Borrowing::STATUS_REQUESTED)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $comic = Comic::where('id', $borrowing->comic_id)->lockForUpdate()->first();

            if (!$comic) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Komik tidak ditemukan.');
            }

            if ($comic->stock <= 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Stok komik tidak cukup.');
            }

            $comic->stock = max(0, $comic->stock - 1);
            $comic->save();

            $now = Carbon::now();
            $due = $now->copy()->addDays(7);

            $borrowing->status = Borrowing::STATUS_DIPINJAM;
            $borrowing->approved_at = $now;
            $borrowing->borrowed_at = $now;
            $borrowing->due_at = $due;
            $borrowing->admin_id = $request->user()->id;
            $borrowing->save();

            DB::commit();

            $borrowing->user->notify(new BorrowingApproved($borrowing));

            return redirect()->back()->with('success', 'Peminjaman disetujui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Approve borrowing error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses approve. ' . $e->getMessage());
        }
    }

    public function processReturn(Request $request, $id)
    {
        $borrowing = Borrowing::where('id', $id)
            ->where('status', Borrowing::STATUS_DIPINJAM)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $comic = Comic::where('id', $borrowing->comic_id)->lockForUpdate()->first();

            if (!$comic) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Komik tidak ditemukan.');
            }

            $comic->stock = $comic->stock + 1;
            $comic->save();

            $now = Carbon::now();
            $borrowing->returned_at = $now;
            $borrowing->admin_id = $request->user()->id;

            if ($borrowing->due_at && $now->gt($borrowing->due_at)) {
                $borrowing->status = Borrowing::STATUS_TERLAMBAT;
            } else {
                $borrowing->status = Borrowing::STATUS_DIKEMBALIKAN;
            }

            $borrowing->save();

            DB::commit();

            $borrowing->user->notify(new BorrowingReturned($borrowing));

            return redirect()->back()->with('success', 'Pengembalian diproses.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Return borrowing error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses pengembalian. ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $borrowing = Borrowing::where('id', $id)
            ->where('status', Borrowing::STATUS_REQUESTED)
            ->firstOrFail();

        try {
            $borrowing->status = Borrowing::STATUS_REJECTED;
            $borrowing->admin_id = $request->user()->id;
            $borrowing->save();

            // Notifikasi (opsional) — buat BorrowingRejected notification jika mau
            if (method_exists($borrowing->user, 'notify')) {
                try {
                    $borrowing->user->notify(new BorrowingRejected($borrowing));
                } catch (\Throwable $ex) {
                    Log::warning('Notify reject failed: ' . $ex->getMessage());
                }
            }

            return redirect()->back()->with('success', 'Permintaan peminjaman ditolak.');
        } catch (\Throwable $e) {
            Log::error('Reject borrowing error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menolak permintaan. ' . $e->getMessage());
        }
    }
}
