@extends('admin.layout')

@section('title', 'Edit Genre')
@section('brand', 'INKOMI Admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Edit Genre</h2>
    <p class="text-gray-600">Ubah detail genre</p>
</div>

@if($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.genres.update', $genre->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Genre</label>
            <input type="text" name="name" value="{{ old('name', $genre->name) }}" required class="w-full px-3 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Slug (opsional)</label>
            <input type="text" name="slug" value="{{ old('slug', $genre->slug) }}" class="w-full px-3 py-2 border rounded">
        </div>

        <div class="flex justify-between items-center">
            <div>
                <form action="{{ route('admin.genres.destroy', $genre->id) }}" method="POST" onsubmit="return confirm('Hapus genre ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-2 bg-red-100 text-red-700 rounded">Hapus</button>
                </form>
            </div>

            <div>
                <a href="{{ route('admin.genres.index') }}" class="px-4 py-2 mr-3 bg-gray-100 rounded">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection
