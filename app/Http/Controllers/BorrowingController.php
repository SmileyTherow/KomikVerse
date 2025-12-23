<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Comic;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified.email');
    }

    // show user's borrowing list
    public function index(Request $request)
    {
        $user = $request->user();
        $items = Borrowing::with('comic')->where('user_id', $user->id)->orderByDesc('created_at')->get();
        return view('borrowings.index', compact('items'));
    }

    // create a borrowing request
    public function requestBorrow(Request $request)
    {
        $request->validate([
            'comic_id' => 'required|exists:comics,id',
        ]);

        $user = $request->user();

        $active = Borrowing::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        if ($active >= 3) {
            return back()->withErrors(['comic_id' => 'Batas pinjam (3) telah tercapai.']);
        }

        $comic = Comic::findOrFail($request->comic_id);

        if ($comic->stock <= 0) {
            return back()->withErrors(['comic_id' => 'Komik habis/stok 0.']);
        }

        $borrowing = Borrowing::create([
            'comic_id' => $comic->id,
            'user_id' => $user->id,
            'status' => 'requested',
            'requested_at' => now(),
        ]);

        return redirect()->route('borrowings.index')->with('status', 'Permintaan peminjaman dikirim. Tunggu persetujuan admin.');
    }
}
