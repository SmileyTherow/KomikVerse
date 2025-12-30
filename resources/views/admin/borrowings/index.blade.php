@extends('admin.layout')

@section('title', 'Kelola Peminjaman')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Kelola Peminjaman</h2>
    <p class="text-gray-600">Approve, tolak, atau tandai pengembalian peminjaman komik</p>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="font-semibold mb-3">Permintaan Menunggu</h3>
        @if($requests->isEmpty())
            <p class="text-gray-500">Tidak ada permintaan peminjaman.</p>
        @else
            <div class="space-y-3">
                @foreach($requests as $r)
                    <div class="p-3 border rounded flex items-start justify-between">
                        <div>
                            <div class="text-sm text-gray-600">{{ $r->user->email }}</div>
                            <div class="font-medium">{{ $r->user->name }}</div>
                            <div class="text-sm text-gray-600">{{ $r->comic->title ?? '-' }}</div>
                            <div class="text-xs text-gray-500">Requested: {{ $r->requested_at ? $r->requested_at->format('d M Y H:i') : '-' }}</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('admin.borrowings.approve', $r->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded" onclick="return confirm('Setujui peminjaman ini?')">Approve</button>
                            </form>

                            <form action="{{ route('admin.borrowings.reject', $r->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-2 bg-red-100 text-red-700 rounded" onclick="return confirm('Tolak peminjaman ini?')">Tolak</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="font-semibold mb-3">Peminjaman Aktif</h3>
        @if($active->isEmpty())
            <p class="text-gray-500">Tidak ada peminjaman aktif.</p>
        @else
            <div class="space-y-3">
                @foreach($active as $a)
                    <div class="p-3 border rounded flex items-start justify-between">
                        <div>
                            <div class="text-sm text-gray-600">{{ $a->user->email }}</div>
                            <div class="font-medium">{{ $a->user->name }}</div>
                            <div class="text-sm text-gray-600">{{ $a->comic->title ?? '-' }}</div>
                            <div class="text-xs text-gray-500">Dipinjam: {{ $a->borrowed_at ? $a->borrowed_at->format('d M Y') : '-' }}</div>
                            <div class="text-xs text-gray-500">Due: {{ $a->due_at ? $a->due_at->format('d M Y') : '-' }}</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('admin.borrowings.return', $a->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded" onclick="return confirm('Proses pengembalian?')">Tandai Kembali</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
