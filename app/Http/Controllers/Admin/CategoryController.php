<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Comic;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    // List kategori
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    // Form tambah
    public function create()
    {
        return view('admin.categories.create');
    }

    // Simpan baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120|unique:categories,name',
        ]);

        $slug = Str::slug($data['name']);
        // pastikan slug unik
        $base = $slug;
        $i = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $base . '-' . Str::random(4);
            $i++;
            if ($i > 10) break;
        }

        $category = Category::create([
            'name' => $data['name'],
            'slug' => $slug,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    // Form edit
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Update
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120', Rule::unique('categories','name')->ignore($category->id)],
        ]);

        if ($data['name'] !== $category->name) {
            $slug = Str::slug($data['name']);
            $base = $slug;
            $i = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $base . '-' . Str::random(4);
                $i++;
                if ($i > 10) break;
            }
            $category->slug = $slug;
        }

        $category->name = $data['name'];
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Hapus
    public function destroy(Category $category)
    {
        try {
            // Cegah hapus jika masih ada komik terkait
            if (Comic::where('category_id', $category->id)->exists()) {
                return redirect()->back()->with('error', 'Kategori tidak bisa dihapus: masih ada komik yang menggunakan kategori ini.');
            }

            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Delete category error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus kategori.');
        }
    }
}
