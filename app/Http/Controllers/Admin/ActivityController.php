<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Comic;
use App\Models\User;
use Illuminate\Support\Collection;

class ActivityController extends Controller
{
    public function index()
    {
        // Recent borrowings
        $borrowings = Borrowing::with(['user','comic'])
            ->orderByDesc('created_at')
            ->take(50)
            ->get()
            ->map(function($b) {
                $type = 'borrow';
                if ($b->status === Borrowing::STATUS_DIKEMBALIKAN) $type = 'return';
                if ($b->status === Borrowing::STATUS_TERLAMBAT) $type = 'late';
                return [
                    'type' => $type,
                    'created_at' => $b->created_at,
                    'user_name' => $b->user?->name,
                    'user_email' => $b->user?->email,
                    'comic_title' => $b->comic?->title,
                    'details' => [
                        'borrowed_at' => $b->borrowed_at,
                        'due_at' => $b->due_at,
                        'returned_at' => $b->returned_at,
                    ],
                ];
            });

        // New comics
        $comics = Comic::orderByDesc('created_at')->take(20)->get()->map(function($c) {
            return [
                'type' => 'add',
                'created_at' => $c->created_at,
                'comic_title' => $c->title,
                'details' => [
                    'genre' => $c->genres->pluck('name')->join(', '),
                ],
            ];
        });

        // New users
        $users = User::orderByDesc('created_at')->take(20)->get()->map(function($u) {
            return [
                'type' => 'user',
                'created_at' => $u->created_at,
                'user_name' => $u->name,
                'user_email' => $u->email,
            ];
        });

        // Merge and sort by created_at desc
        $activities = Collection::make()
            ->merge($borrowings)
            ->merge($comics)
            ->merge($users)
            ->sortByDesc('created_at')
            ->values()
            ->take(80);

        return view('admin.activity', compact('activities'));
    }
}
