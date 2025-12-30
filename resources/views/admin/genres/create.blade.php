@extends('admin.layout')

@section('title', 'Tambah Genre')
@section('brand', 'INKOMI Admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Tambah Genre</h2>
    <p class="text-gray-600">Buat genre baru untuk mengkategorikan komik</p>
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
    <form action="{{ route('admin.genres.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Genre</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Slug (opsional)</label>
            <input type="text" name="slug" value="{{ old('slug') }}" placeholder="otomatis dibuat jika kosong" class="w-full px-3 py-2 border rounded">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.genres.index') }}" class="px-4 py-2 mr-3 bg-gray-100 rounded">Batal</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection
