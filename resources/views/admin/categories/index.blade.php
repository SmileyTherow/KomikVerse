@extends('admin.layout')

@section('title', 'Kategori Komik')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Kategori</h1>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Tambah
            Kategori</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Jumlah Komik</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            {{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                        <td class="px-4 py-3">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $category->slug }}</td>
                        <td class="px-4 py-3">{{ \App\Models\Comic::where('category_id', $category->id)->count() }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded mr-2">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                class="inline-block" onsubmit="return confirm('Hapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-100 text-red-800 rounded">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
