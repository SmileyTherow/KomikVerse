@extends('admin.layout')

@section('title', 'Edit Kategori')

@section('content')
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Edit Kategori</h2>
        <p class="text-gray-600">Perbarui nama kategori</p>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-100 rounded">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
