<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genre;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::orderBy('name')->get();
        return view('admin.genres.index', compact('genres'));
    }

    public function create()
    {
        return view('admin.genres.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191|unique:genres,name',
            'slug' => 'nullable|string|max:191|unique:genres,slug',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
            // ensure slug unique
            $i = 1;
            $base = $data['slug'];
            while (Genre::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $base . '-' . $i++;
            }
        }

        Genre::create($data);

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil ditambahkan.');
    }

    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191|unique:genres,name,' . $genre->id,
            'slug' => 'nullable|string|max:191|unique:genres,slug,' . $genre->id,
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
            // ensure slug unique
            $i = 1;
            $base = $data['slug'];
            while (Genre::where('slug', $data['slug'])->where('id', '!=', $genre->id)->exists()) {
                $data['slug'] = $base . '-' . $i++;
            }
        }

        $genre->update($data);

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil diperbarui.');
    }

    public function destroy(Genre $genre)
    {
        // optionally check if genre is attached to comics and prevent deletion
        if ($genre->comics()->count() > 0) {
            return redirect()->route('admin.genres.index')->with('error', 'Genre masih digunakan oleh komik, tidak bisa dihapus.');
        }

        $genre->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil dihapus.');
    }
}
