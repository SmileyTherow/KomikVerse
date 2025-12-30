@extends('admin.layout')

@section('title', 'Kelola Genre')
@section('brand', 'INKOMI Admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Kelola Genre</h2>
        <p class="text-gray-600">Tambah, ubah, dan hapus genre komik</p>
    </div>
    <a href="{{ route('admin.genres.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded shadow hover:bg-indigo-700">
        <i class="fas fa-plus mr-2"></i> Tambah Genre
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b">
        <form method="GET" action="{{ route('admin.genres.index') }}" class="flex gap-3">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari genre..." class="w-full px-3 py-2 border rounded">
            <button class="px-4 py-2 bg-gray-100 rounded">Cari</button>
        </form>
    </div>

    <div class="p-4">
        @if($genres->isEmpty())
            <p class="text-gray-500">Belum ada genre.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-sm text-gray-500 border-b">
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Nama</th>
                            <th class="py-3 px-4">Slug</th>
                            <th class="py-3 px-4">Jumlah Komik</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($genres as $g)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 align-top">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4 align-top">{{ $g->name }}</td>
                                <td class="py-3 px-4 align-top">{{ $g->slug }}</td>
                                <td class="py-3 px-4 align-top">{{ $g->comics()->count() }}</td>
                                <td class="py-3 px-4 align-top">
                                    <a href="{{ route('admin.genres.edit', $g->id) }}" class="inline-flex items-center px-3 py-1 bg-gray-100 rounded text-sm mr-2">
                                        <i class="fas fa-edit mr-2"></i>Edit
                                    </a>

                                    <form action="{{ route('admin.genres.destroy', $g->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus genre ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded text-sm">
                                            <i class="fas fa-trash mr-2"></i>Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
