@extends('admin.layout')

@section('title', 'Aktivitas Terkini')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Aktivitas Terkini</h2>
    <p class="text-gray-600">Riwayat aktivitas sistem peminjaman komik</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">Timeline Aktivitas</h3>
        <p class="text-sm text-gray-600" id="activityCount">Menampilkan {{ $activities->count() }} aktivitas terbaru</p>
    </div>

    <div class="divide-y divide-gray-100" id="activityList">
        @foreach($activities as $act)
            @php
                // tentukan background class berdasarkan tipe aktivitas
                $type = $act['type'] ?? 'other';
                $bgClass = 'bg-amber-100';
                if ($type === 'borrow') $bgClass = 'bg-blue-100';
                elseif ($type === 'return') $bgClass = 'bg-green-100';
                elseif ($type === 'late') $bgClass = 'bg-red-100';
                elseif ($type === 'add') $bgClass = 'bg-purple-100';

                // hitung selisih hari (fallback 0)
                $days = isset($act['created_at']) ? $act['created_at']->diffInDays(\Carbon\Carbon::now()) : 0;
                $createdAt = $act['created_at'] ?? null;
            @endphp

            <div class="activity-item p-6 hover:bg-gray-50" data-type="{{ $type }}" data-days="{{ $days }}">
                <div class="flex items-start">
                    <div class="w-10 h-10 {{ $bgClass }} rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        @if($type === 'borrow')
                            <i class="fas fa-book text-blue-600"></i>
                        @elseif($type === 'return')
                            <i class="fas fa-check text-green-600"></i>
                        @elseif($type === 'late')
                            <i class="fas fa-exclamation text-red-600"></i>
                        @elseif($type === 'add')
                            <i class="fas fa-plus text-purple-600"></i>
                        @else
                            <i class="fas fa-user-plus text-amber-600"></i>
                        @endif
                    </div>

                    <div class="flex-1">
                        <div class="flex justify-between">
                            <div>
                                @if(in_array($type, ['borrow','return','late']))
                                    <p class="text-gray-800">
                                        <span class="font-medium">{{ $act['user_name'] ?? '—' }}</span>
                                        {{ $type === 'borrow' ? ' meminjam ' : ($type === 'return' ? ' mengembalikan ' : ' terlambat mengembalikan ') }}
                                        <span class="font-medium">{{ $act['comic_title'] ?? '-' }}</span>
                                    </p>
                                @elseif($type === 'add')
                                    <p class="text-gray-800">Komik baru ditambahkan: <span class="font-medium">{{ $act['comic_title'] }}</span></p>
                                @else
                                    <p class="text-gray-800">User baru terdaftar: <span class="font-medium">{{ $act['user_name'] ?? '-' }}</span></p>
                                @endif

                                <div class="flex items-center mt-1">
                                    <span class="status-badge {{ $type === 'borrow' ? 'bg-blue-100 text-blue-800' : ($type === 'return' ? 'bg-green-100 text-green-800' : ($type === 'late' ? 'bg-red-100 text-red-800' : 'bg-purple-100 text-purple-800')) }} mr-3">
                                        {{ ucfirst($type) }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        <i class="far fa-clock mr-1"></i> {{ $createdAt ? $createdAt->format('d M Y, H:i') : '-' }}
                                    </span>
                                </div>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>

                        @if(isset($act['details']) && is_array($act['details']))
                            <div class="mt-3 ml-14">
                                <p class="text-sm text-gray-600">
                                    @if($type === 'borrow')
                                        Deadline: <span class="font-medium">{{ optional($act['details']['due_at'])->format('d M Y') ?? '-' }}</span>
                                    @elseif($type === 'return')
                                        Return time: <span class="font-medium">{{ optional($act['details']['returned_at'])->format('d M Y') ?? '-' }}</span>
                                    @elseif($type === 'add')
                                        Genre: <span class="font-medium">{{ $act['details']['genre'] ?? '-' }}</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
        <p class="text-sm text-gray-600">Menampilkan {{ $activities->count() }} aktivitas</p>
        <div class="flex space-x-2">
            <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="px-3 py-1 bg-indigo-600 text-white rounded-md text-sm font-medium">1</button>
            <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">2</button>
            <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<script>
    const activityItems = document.querySelectorAll('.activity-item');
    const searchInput = document.getElementById('searchActivity');
    const activityCountEl = document.getElementById('activityCount');

    function applyFilter(currentFilter='all', currentDateFilter='0') {
        let visibleCount = 0;
        activityItems.forEach(item => {
            const type = item.getAttribute('data-type');
            const days = parseInt(item.getAttribute('data-days')) || 0;
            const typeMatch = currentFilter === 'all' || type === currentFilter;
            const dateMatch = currentDateFilter === '0' || days <= parseInt(currentDateFilter);
            const searchText = (searchInput?.value || '').toLowerCase();
            const textMatch = searchText === '' || item.textContent.toLowerCase().includes(searchText);
            if (typeMatch && dateMatch && textMatch) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        if (activityCountEl) activityCountEl.textContent = `Menampilkan ${visibleCount} aktivitas`;
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', () => applyFilter());
    }
    applyFilter();
</script>
@endsection
