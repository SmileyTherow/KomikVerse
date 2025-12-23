<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComicController extends Controller
{
    public function index(Request $request)
    {
        $comics = Comic::with('category','genres')->orderByDesc('created_at')->get();
        return view('comics.index', compact('comics'));
    }

    public function show($id)
    {
        $comic = Comic::with('category','genres')->findOrFail($id);

        $userActiveCount = 0;
        $userHasThis = false;

        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            $userActiveCount = $user->borrowings()->where('status', 'dipinjam')->count();
            $userHasThis = $user->borrowings()
                ->where('comic_id', $comic->id)
                ->where('status', 'dipinjam')
                ->exists();
        }

        return view('comics.show', compact('comic', 'userActiveCount', 'userHasThis'));
    }
}
