@extends('admin.layout')

@section('title', 'Edit Komik')

@section('content')
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Edit Komik</h2>
        <p class="text-gray-600">Perbarui data komik</p>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <form action="{{ route('admin.comics.update', $comic->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Judul Komik</label>
                        <input type="text" name="title" value="{{ old('title', $comic->title) }}"
                            class="w-full px-4 py-3 border rounded-lg">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Kategori</label>
                        <select name="category_id" class="w-full px-4 py-3 border rounded-lg" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (old('category_id', $comic->category_id) == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Penulis</label>
                        <input type="text" name="author" value="{{ old('author', $comic->author) }}"
                            class="w-full px-4 py-3 border rounded-lg">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Penerbit</label>
                        <input type="text" name="publisher" value="{{ old('publisher', $comic->publisher) }}"
                            class="w-full px-4 py-3 border rounded-lg">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Tahun Terbit</label>
                        <input type="number" name="year" value="{{ old('year', $comic->year) }}"
                            class="w-full px-4 py-3 border rounded-lg">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">ISBN</label>
                        <input type="text" name="isbn" value="{{ old('isbn', $comic->isbn) }}"
                            class="w-full px-4 py-3 border rounded-lg" placeholder="978-xxxxxxxxxx">
                        @error('isbn')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 mb-2 font-medium">Sinopsis</label>
                    <textarea name="synopsis" rows="5" class="w-full px-4 py-3 border rounded-lg">{{ old('synopsis', $comic->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Genre</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($genres as $g)
                                <label class="inline-flex items-center px-3 py-2 bg-gray-50 rounded-lg border">
                                    <input type="checkbox" name="genres[]" value="{{ $g->id }}" class="mr-2"
                                        {{ in_array($g->id, old('genres', $comic->genres->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <span>{{ $g->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Cover Komik</label>
                        @if ($comic->cover_path)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $comic->cover_path) }}" alt="cover"
                                    class="w-28 h-36 object-cover rounded">
                            </div>
                        @endif
                        <input type="file" name="cover" accept="image/*" class="w-full">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 items-end">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Jumlah Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', $comic->stock) }}"
                            class="w-32 px-4 py-3 border rounded-lg">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Jumlah Halaman</label>
                        <input type="number" name="page_count" value="{{ old('page_count', $comic->page_count) }}"
                            min="0" class="w-32 px-4 py-3 border rounded-lg" placeholder="48">
                        @error('page_count')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Bahasa</label>
                        <select name="language" class="w-full md:w-48 px-4 py-3 border rounded-lg">
                            <option value="">Pilih Bahasa</option>
                            <option value="ID" {{ old('language', $comic->language) == 'ID' ? 'selected' : '' }}>Indonesia - Bahasa
                                Indonesia</option>
                            <option value="EN" {{ old('language', $comic->language) == 'EN' ? 'selected' : '' }}>Inggris - English
                            </option>
                            <option value="JP" {{ old('language', $comic->language) == 'JP' ? 'selected' : '' }}>Jepang - 日本語</option>
                            <option value="KR" {{ old('language', $comic->language) == 'KR' ? 'selected' : '' }}>Korea - 한국어</option>
                            <option value="CN" {{ old('language', $comic->language) == 'CN' ? 'selected' : '' }}>Cina - 中文</option>
                        </select>
                        @error('language')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Status</label>
                        <select name="status" class="w-full px-4 py-3 border rounded-lg">
                            <option value="available" {{ $comic->status == 'available' ? 'selected' : '' }}>Tersedia
                            </option>
                            <option value="coming_soon" {{ $comic->status == 'coming_soon' ? 'selected' : '' }}>Segera
                                Hadir
                            </option>
                            <option value="out_of_stock" {{ $comic->status == 'out_of_stock' ? 'selected' : '' }}>Stok
                                Habis
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.comics.index') }}" class="px-4 py-2 bg-gray-100 rounded">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
