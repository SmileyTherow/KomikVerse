<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Comic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // Basic metrics
        $totalBorrowings = Borrowing::count();
        $totalUsers = User::count();
        $activeBorrowings = Borrowing::where('status', Borrowing::STATUS_DIPINJAM)->count();
        $lateCount = Borrowing::where('status', Borrowing::STATUS_TERLAMBAT)->count();

        // Borrowing trend - last 6 months
        $months = [];
        $borrowingCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = Carbon::now()->startOfMonth()->subMonths($i);
            $end = (clone $start)->endOfMonth();
            $months[] = $start->isoFormat('MMM YYYY');
            $borrowingCounts[] = Borrowing::whereBetween('borrowed_at', [$start, $end])->count();
        }

        // User activity (last 7 days) - counts per day (borrowing created)
        $days = [];
        $userActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $days[] = $day->format('D');
            $userActivity[] = Borrowing::whereDate('created_at', $day)->count();
        }

        // Top users (by borrow count)
        $topUsersRaw = DB::table('borrowings')
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topUsers = $topUsersRaw->map(function ($r) {
            $u = User::find($r->user_id);
            return [
                'name' => $u ? $u->name : '—',
                'id' => $r->user_id,
                'total' => $r->total,
            ];
        });

        // Top comics
        $topComicsRaw = DB::table('borrowings')
            ->select('comic_id', DB::raw('count(*) as total'))
            ->groupBy('comic_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topComics = $topComicsRaw->map(function ($r) {
            $c = Comic::find($r->comic_id);
            return [
                'title' => $c ? $c->title : '—',
                'id' => $r->comic_id,
                'total' => $r->total,
            ];
        });

        return view('admin.statistics', compact(
            'totalBorrowings',
            'totalUsers',
            'activeBorrowings',
            'lateCount',
            'months',
            'borrowingCounts',
            'days',
            'userActivity',
            'topUsers',
            'topComics'
        ));
    }
}
