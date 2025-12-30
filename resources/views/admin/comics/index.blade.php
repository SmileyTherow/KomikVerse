@extends('admin.layout')

@section('title', 'Kelola Komik')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Kelola Komik</h2>
        <p class="text-gray-600">Tambah, edit, atau hapus komik dari koleksi</p>
    </div>
    <a href="{{ route('admin.comics.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center">
        <i class="fas fa-plus mr-2"></i>Tambah Komik Baru
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('admin.comics.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" name="q" value="{{ request('q') }}" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg" placeholder="Cari judul komik...">
        </div>

        <div class="flex flex-wrap gap-3">
            <select name="status" class="border border-gray-300 rounded-lg px-4 py-3">
                <option value="">Semua Status</option>
                <option value="available" {{ request('status')=='available' ? 'selected' : '' }}>Tersedia</option>
                <option value="out_of_stock" {{ request('status')=='out_of_stock' ? 'selected' : '' }}>Habis</option>
            </select>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Filter</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left text-gray-500 text-sm">
                    <th class="py-4 px-6 font-medium">Judul</th>
                    <th class="py-4 px-6 font-medium">Penulis</th>
                    <th class="py-4 px-6 font-medium">Genre</th>
                    <th class="py-4 px-6 font-medium">Stok</th>
                    <th class="py-4 px-6 font-medium">Status</th>
                    <th class="py-4 px-6 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comics as $comic)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            @if($comic->cover_path)
                                <img src="{{ asset('storage/'.$comic->cover_path) }}" alt="cover" class="w-10 h-12 object-cover rounded mr-3">
                            @else
                                <div class="w-10 h-12 bg-gradient-to-r from-gray-300 to-gray-400 rounded mr-3"></div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800">{{ $comic->title }}</p>
                                <p class="text-sm text-gray-500">{{ $comic->publisher ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">{{ $comic->author ?? '-' }}</td>
                    <td class="py-4 px-6">
                        @foreach($comic->genres as $g)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full mr-1">{{ $g->name }}</span>
                        @endforeach
                    </td>
                    <td class="py-4 px-6"><span class="font-medium">{{ $comic->stock }}</span></td>
                    <td class="py-4 px-6">
                        @if($comic->status === 'available')
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Tersedia</span>
                        @elseif($comic->status === 'out_of_stock')
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">Habis</span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">{{ $comic->status }}</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.comics.edit', $comic->id) }}" class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 flex items-center justify-center">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form action="{{ route('admin.comics.destroy', $comic->id) }}" method="POST" onsubmit="return confirm('Hapus komik ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 flex items-center justify-center">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-500">Belum ada komik.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
        <p class="text-gray-600 text-sm">Menampilkan {{ $comics->count() }} dari {{ $comics->total() ?? $comics->count() }} komik</p>
        <div>
            {{ $comics->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
