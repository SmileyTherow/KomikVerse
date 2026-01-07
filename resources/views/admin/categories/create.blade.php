@extends('admin.layout')

@section('title', 'Tambah Kategori')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Tambah Kategori</h1>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded shadow">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border rounded"
                    required>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-100 rounded">Batal</a>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
@endsection
