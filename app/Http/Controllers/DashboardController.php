<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // aktif = status 'dipinjam'
        $activeBorrowings = $user->borrowings()->with('comic')
            ->where('status', 'dipinjam')
            ->orderBy('borrowed_at')
            ->get();

        // recent history (termasuk returned/terlambat/requested)
        $recent = $user->borrowings()->with('comic')->orderByDesc('created_at')->take(6)->get();

        return view('dashboard', [
            'user' => $user,
            'activeBorrowings' => $activeBorrowings,
            'recent' => $recent,
        ]);
    }
}
