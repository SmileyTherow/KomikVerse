<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comic;
use App\Models\Genre;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ComicController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $status = $request->get('status');

        $query = Comic::with('genres')->latest();

        if ($q) {
            $query->where('title', 'like', "%{$q}%");
        }
        if ($status) {
            $query->where('status', $status);
        }

        $comics = $query->paginate(12);

        return view('admin.comics.index', compact('comics'));
    }

    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        return view('admin.comics.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'language' => 'nullable|string|max:50',
            'isbn' => 'nullable|string|max:50',
            'synopsis' => 'required|string|min:20',
            'stock' => 'required|integer|min:0',
            'status' => 'required|string|in:available,coming_soon,out_of_stock',
            'genres' => 'nullable|array',
            'genres.*' => 'integer|exists:genres,id',
            'cover' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover_path'] = $path;
        }

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);

        $comic = Comic::create([
            'title' => $data['title'],
            'author' => $data['author'] ?? null,
            'publisher' => $data['publisher'] ?? null,
            'year' => $data['year'] ?? null,
            'language' => $data['language'] ?? null,
            'isbn' => $data['isbn'] ?? null,
            'description' => $data['synopsis'],
            'stock' => $data['stock'],
            'status' => $data['status'],
            'cover_path' => $data['cover_path'] ?? null,
            'slug' => $data['slug'],
        ]);

        if (!empty($data['genres'])) {
            $comic->genres()->sync($data['genres']);
        }

        return redirect()->route('admin.comics.index')->with('success', 'Komik berhasil ditambahkan.');
    }

    public function edit(Comic $comic)
    {
        $genres = Genre::orderBy('name')->get();
        $comic->load('genres');
        return view('admin.comics.edit', compact('comic','genres'));
    }

    public function update(Request $request, Comic $comic)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'language' => 'nullable|string|max:50',
            'isbn' => 'nullable|string|max:50',
            'synopsis' => 'required|string|min:20',
            'stock' => 'required|integer|min:0',
            'status' => 'required|string|in:available,coming_soon,out_of_stock',
            'genres' => 'nullable|array',
            'genres.*' => 'integer|exists:genres,id',
            'cover' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('cover')) {
            // hapus file lama jika ada
            if ($comic->cover_path && Storage::disk('public')->exists($comic->cover_path)) {
                Storage::disk('public')->delete($comic->cover_path);
            }
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover_path'] = $path;
        }

        $comic->title = $data['title'];
        $comic->author = $data['author'] ?? null;
        $comic->publisher = $data['publisher'] ?? null;
        $comic->year = $data['year'] ?? null;
        $comic->language = $data['language'] ?? null;
        $comic->isbn = $data['isbn'] ?? null;
        $comic->description = $data['synopsis'];
        $comic->stock = $data['stock'];
        $comic->status = $data['status'];
        if (!empty($data['cover_path'])) {
            $comic->cover_path = $data['cover_path'];
        }
        $comic->save();

        $comic->genres()->sync($data['genres'] ?? []);

        return redirect()->route('admin.comics.index')->with('success', 'Data komik berhasil diperbarui.');
    }

    public function destroy(Comic $comic)
    {
        try {
            if ($comic->cover_path && Storage::disk('public')->exists($comic->cover_path)) {
                Storage::disk('public')->delete($comic->cover_path);
            }
            $comic->genres()->detach();
            $comic->delete();
            return redirect()->route('admin.comics.index')->with('success', 'Komik dihapus.');
        } catch (\Throwable $e) {
            Log::error('Delete comic error: '.$e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus komik.');
        }
    }
}
