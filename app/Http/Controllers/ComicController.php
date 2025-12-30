<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComicController extends Controller
{
    public function index(Request $request)
    {
        $comics = Comic::with('category','genres')->orderByDesc('created_at')->get();
        return view('comics.index', compact('comics'));
    }

    public function show($comicParam)
    {
        // Load comic robustly (support id or slug)
        if ($comicParam instanceof Comic) {
            $comic = $comicParam;
        } else {
            $comic = Comic::with(['category', 'genres'])->where(function ($q) use ($comicParam) {
                if (is_numeric($comicParam)) {
                    $q->where('id', $comicParam);
                } else {
                    $q->where('slug', $comicParam)->orWhere('id', $comicParam);
                }
            })->firstOrFail();
        }

        // fallback mappings (page_count -> pages) — optional
        if (empty($comic->pages) && !empty($comic->page_count)) {
            $comic->pages = $comic->page_count;
        }

        // likes count & if current user liked this
        $likesCount = $comic->likes()->count();
        $userHasLiked = false;
        $user = Auth::user();
        if ($user) {
            // safest: query pivot table to avoid ambiguous SQL
            $userHasLiked = DB::table('comic_likes')
                ->where('user_id', $user->id)
                ->where('comic_id', $comic->id)
                ->exists();
        }

        // variables already used by view: userHasThis, userActiveCount, related...
        $userHasThis = false;
        $userActiveCount = 0;
        $related = collect();
        // (you can keep existing borrowing/related logic you had)

        return view('comics.show', compact('comic', 'userHasThis', 'userActiveCount', 'related', 'likesCount', 'userHasLiked'));
    }

    /**
     * Like a comic (POST). Requires auth middleware on route.
     */
    public function like(Comic $comic)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // create pivot if not exists
        DB::table('comic_likes')->updateOrInsert(
            ['user_id' => $user->id, 'comic_id' => $comic->id],
            ['created_at' => now(), 'updated_at' => now()]
        );

        if (request()->wantsJson()) {
            return response()->json([
                'liked' => true,
                'likes' => $comic->likes()->count(),
            ]);
        }

        return back();
    }

    /**
     * Unlike a comic (DELETE). Requires auth middleware on route.
     */
    public function unlike(Comic $comic)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        DB::table('comic_likes')->where('user_id', $user->id)->where('comic_id', $comic->id)->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'liked' => false,
                'likes' => $comic->likes()->count(),
            ]);
        }

        return back();
    }
}
