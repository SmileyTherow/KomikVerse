<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Comic;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show user dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Pinjaman aktif milik user (status 'dipinjam')
        $activeBorrowings = Borrowing::with('comic')
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->orderByDesc('borrowed_at')
            ->get();

        // Riwayat terakhir (limit 6)
        $recent = Borrowing::with('comic')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        // Komik rekomendasi / terbaru (untuk tampilan rekomendasi)
        $comics = Comic::with('category')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('dashboard', compact('user', 'activeBorrowings', 'recent', 'comics'));
    }
}
