<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\User;
use App\Models\Borrowing;

class DashboardController extends Controller
{
    public function index()
    {
        $totalComics = Comic::count();
        $totalUsers = User::count();
        $activeBorrowings = Borrowing::where('status', Borrowing::STATUS_DIPINJAM)->count();
        $lateCount = Borrowing::where('status', Borrowing::STATUS_TERLAMBAT)->count();
        $pendingRequests = Borrowing::where('status', Borrowing::STATUS_REQUESTED)->count();

        $recentActivities = Borrowing::with(['user','comic'])
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalComics',
            'totalUsers',
            'activeBorrowings',
            'lateCount',
            'pendingRequests',
            'recentActivities'
        ));
    }
}
