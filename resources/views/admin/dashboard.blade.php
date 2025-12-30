@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('brand', 'INKOMI Admin')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Admin</h2>
        <p class="text-gray-600">Tinjau statistik dan aktivitas sistem peminjaman komik</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Total Komik</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalComics }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-book text-blue-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.comics.index') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    Lihat semua <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Total User</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-users text-green-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    Kelola user <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Peminjaman Aktif</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $activeBorrowings }}</p>
                    <p class="text-sm text-red-600 mt-1">{{ $lateCount }} terlambat</p>
                </div>
                <div class="p-3 bg-amber-100 rounded-lg">
                    <i class="fas fa-clock text-amber-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.borrowings.index') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    Lihat peminjaman <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Permintaan Menunggu</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pendingRequests }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-hourglass-half text-purple-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.borrowings.index') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    Approve sekarang <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        {{-- New: Genre card --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Total Genre</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalGenres ?? 0 }}</p>
                </div>
                <div class="p-3 bg-pink-100 rounded-lg">
                    <i class="fas fa-tags text-pink-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.genres.index') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    Kelola genre <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow p-6 flex flex-col h-full">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Aktivitas Terkini</h3>

            <div class="space-y-4 flex-1">
                @forelse($recentActivities as $act)
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-book text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-gray-800">
                                {{ $act->user->name ?? '-' }} meminjam
                                <span class="font-medium">{{ $act->comic->title ?? '-' }}</span>
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $act->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada aktivitas.</p>
                @endforelse
            </div>

            <div class="mt-6 pt-4 border-t">
                <a href="{{ route('admin.activity') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center justify-center">
                    Lihat semua aktivitas <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex flex-col h-full">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Genre Terbaru</h3>

            <div class="flex-1">
                @if (isset($latestGenres) && $latestGenres->count())
                    <ul class="space-y-3">
                        @foreach ($latestGenres as $g)
                            <li class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium">{{ $g->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $g->slug }}</div>
                                </div>
                                <a href="{{ route('admin.genres.edit', $g->id) }}"
                                    class="text-sm text-blue-600 hover:underline">
                                    Edit
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">Belum ada genre.</p>
                @endif
            </div>

            <div class="mt-6 pt-4 border-t text-right">
                <a href="{{ route('admin.genres.index') }}" class="text-sm text-blue-600 hover:underline">
                    Lihat semua genre
                </a>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Peminjaman Terbaru</h3>
            <a href="{{ route('admin.borrowings.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 text-sm border-b">
                        <th class="py-3 px-6 font-medium">User</th>
                        <th class="py-3 px-6 font-medium">Komik</th>
                        <th class="py-3 px-6 font-medium">Tanggal Pinjam</th>
                        <th class="py-3 px-6 font-medium">Deadline</th>
                        <th class="py-3 px-6 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentActivities as $r)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-4 px-6">{{ $r->user->name ?? '-' }}</td>
                            <td class="py-4 px-6">{{ $r->comic->title ?? '-' }}</td>
                            <td class="py-4 px-6">{{ optional($r->borrowed_at)->format('d M Y') }}</td>
                            <td class="py-4 px-6">{{ optional($r->due_at)->format('d M Y') }}</td>
                            <td class="py-4 px-6">
                                @if ($r->status === \App\Models\Borrowing::STATUS_DIPINJAM)
                                    <span
                                        class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Dipinjam</span>
                                @elseif($r->status === \App\Models\Borrowing::STATUS_DIKEMBALIKAN)
                                    <span
                                        class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Dikembalikan</span>
                                @elseif($r->status === \App\Models\Borrowing::STATUS_TERLAMBAT)
                                    <span
                                        class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">Terlambat</span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-medium rounded-full">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
