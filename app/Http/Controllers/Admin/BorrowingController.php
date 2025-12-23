<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is.admin');
    }

    public function index()
    {
        $requests = Borrowing::with(['user','comic'])->where('status', 'requested')->orderBy('requested_at')->get();
        return view('admin.borrowings.index', compact('requests'));
    }

    public function approve(Request $request, $id)
    {
        $admin = $request->user();
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'requested') {
            return back()->withErrors(['msg' => 'Hanya request yang dapat disetujui.']);
        }

        try {
            DB::transaction(function () use ($borrowing, $admin) {
                $comic = Comic::where('id', $borrowing->comic_id)->lockForUpdate()->first();
                if (!$comic || $comic->stock <= 0) {
                    throw new \Exception('Komik habis/stok 0.');
                }

                $comic->stock = max(0, $comic->stock - 1);
                $comic->save();

                $borrowing->status = 'dipinjam';
                $borrowing->approved_at = now();
                $borrowing->borrowed_at = now();
                $borrowing->due_at = now()->addDays(7);
                $borrowing->admin_id = $admin->id;
                $borrowing->save();
            });
        } catch (\Throwable $e) {
            return back()->withErrors(['msg' => 'Gagal approve: '.$e->getMessage()]);
        }

        return redirect()->route('admin.borrowings.index')->with('status', 'Permintaan disetujui.');
    }

    public function processReturn(Request $request, $id)
    {
        $admin = $request->user();
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'dipinjam') {
            return back()->withErrors(['msg' => 'Hanya peminjaman yang sedang dipinjam dapat diproses pengembaliannya.']);
        }

        try {
            DB::transaction(function () use ($borrowing, $admin) {
                $comic = Comic::where('id', $borrowing->comic_id)->lockForUpdate()->first();
                $comic->stock = $comic->stock + 1;
                $comic->save();

                $borrowing->returned_at = now();
                $borrowing->admin_id = $admin->id;
                $borrowing->status = ($borrowing->due_at && now()->gt($borrowing->due_at)) ? 'terlambat' : 'dikembalikan';
                $borrowing->save();
            });
        } catch (\Throwable $e) {
            return back()->withErrors(['msg' => 'Gagal proses pengembalian: '.$e->getMessage()]);
        }

        return redirect()->route('admin.borrowings.index')->with('status', 'Pengembalian diproses.');
    }
}
