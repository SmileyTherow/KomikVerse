<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComicController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $genreId = $request->query('genre');
        $status = $request->query('status');

        // Normalisasi status: form kamu memakai "available" dan "out" (ubah 'out' -> 'out_of_stock')
        if ($status === 'out') {
            $status = 'out_of_stock';
        }

        $query = Comic::with(['genres', 'category'])->latest();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%")
                    ->orWhere('publisher', 'like', "%{$q}%")
                    ->orWhereHas('category', function ($c) use ($q) {
                        $c->where('name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('genres', function ($g) use ($q) {
                        $g->where('name', 'like', "%{$q}%");
                    });
            });
        }

        if (!empty($genreId)) {
            $query->whereHas('genres', function ($g) use ($genreId) {
                $g->where('genres.id', $genreId);
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $comics = $query->paginate(12)->withQueryString();

        // Untuk menampilkan daftar genre di dropdown
        $genres = Genre::orderBy('name')->get();

        return view('comics.index', compact('comics', 'genres'));
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
