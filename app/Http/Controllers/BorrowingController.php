<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function __construct()
    {
        // request requires auth; admin endpoints also require auth and is_admin check inside methods
        $this->middleware('auth')->only(['request', 'approve', 'processReturn', 'indexForAdmin']);
    }

    /**
     * User requests a borrowing (form posts to this)
     */
    public function request(Request $request)
    {
        $request->validate([
            'comic_id' => 'required|integer|exists:comics,id',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (is_null($user->email_verified_at)) {
            return back()->with('error', 'Akun belum terverifikasi. Lakukan verifikasi email terlebih dahulu.');
        }

        // ensure Borrowing model/table exists
        if (!class_exists(Borrowing::class)) {
            return back()->with('error', 'Fitur peminjaman belum tersedia. Hubungi admin.');
        }

        $comicId = (int) $request->input('comic_id');

        // check user's active borrowings (not returned)
        $activeCount = Borrowing::where('user_id', $user->id)
            ->whereNull('returned_at')
            ->count();

        if ($activeCount >= 3) {
            return back()->with('error', 'Batas peminjaman aktif tercapai (maks 3).');
        }

        // check if user already has this comic active
        $already = Borrowing::where('user_id', $user->id)
            ->where('comic_id', $comicId)
            ->whereNull('returned_at')
            ->exists();

        if ($already) {
            return back()->with('status', 'Anda sudah sedang meminjam komik ini.');
        }

        // create requested borrowing record; admin will approve
        $borrowing = Borrowing::create([
            'user_id' => $user->id,
            'comic_id' => $comicId,
            'status' => 'requested',
            'requested_at' => now(),
        ]);

        return back()->with('status', 'Permintaan peminjaman berhasil dikirim. Tunggu konfirmasi admin.');
    }

    /**
     * Admin: list all requests (optional simple view)
     */
    public function indexForAdmin()
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            abort(403);
        }

        $requests = Borrowing::with(['user','comic'])->orderByDesc('requested_at')->paginate(20);
        return view('admin.borrowings.index', compact('requests'));
    }

    /**
     * Admin approves a borrowing request -> decrement stock, set dipinjam & dates.
     */
    public function approve(Request $request, Borrowing $borrowing)
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            abort(403);
        }

        if ($borrowing->status !== 'requested') {
            return back()->with('error', 'Hanya permintaan dengan status requested yang bisa disetujui.');
        }

        // Transaction: lock comic row, check stock, decrement, update borrowing
        try {
            DB::transaction(function () use ($borrowing, $user) {
                $comic = Comic::where('id', $borrowing->comic_id)->lockForUpdate()->firstOrFail();

                if ($comic->stock <= 0) {
                    throw new \Exception('Stok komik habis, tidak bisa disetujui.');
                }

                // decrement stock
                $comic->stock = max(0, $comic->stock - 1);
                $comic->save();

                // update borrowing
                $borrowing->status = 'dipinjam';
                $borrowing->admin_id = $user->id;
                $borrowing->approved_at = now();
                $borrowing->borrowed_at = now();
                $borrowing->due_at = Carbon::now()->addDays(7);
                $borrowing->save();
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }

        return back()->with('status', 'Permintaan disetujui: stok dikurangi dan peminjaman aktif.');
    }

    /**
     * Admin processes a return: increment stock, set returned_at and status.
     */
    public function processReturn(Request $request, Borrowing $borrowing)
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            abort(403);
        }

        if ($borrowing->returned_at) {
            return back()->with('status', 'Peminjaman sudah dikembalikan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($borrowing, $user) {
                $comic = Comic::where('id', $borrowing->comic_id)->lockForUpdate()->firstOrFail();

                // increment stock
                $comic->stock = $comic->stock + 1;
                $comic->save();

                // set returned_at and status
                $borrowing->returned_at = now();
                $borrowing->admin_id = $user->id;

                if ($borrowing->due_at && now()->greaterThan($borrowing->due_at)) {
                    $borrowing->status = 'terlambat';
                } else {
                    $borrowing->status = 'dikembalikan';
                }

                $borrowing->save();
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }

        return back()->with('status', 'Pengembalian telah diproses dan stok dikembalikan.');
    }
}
