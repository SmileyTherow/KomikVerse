@extends('admin.layout')

@section('title', 'Statistik')

@section('content')
<!-- paste HTML structure based on your static example, simplified for dynamic data -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Statistik Sistem</h2>
            <p class="text-gray-600">Analisis data dan tren sistem peminjaman komik</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Total Peminjaman</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalBorrowings }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">User Aktif</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Peminjaman Aktif</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $activeBorrowings }}</p>
        <p class="text-sm text-red-600 mt-1">{{ $lateCount }} terlambat</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Tingkat Keterlambatan</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">
            @if($totalBorrowings) {{ round($lateCount / max(1, $totalBorrowings) * 100, 1) }}% @else 0% @endif
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Tren Peminjaman 6 Bulan Terakhir</h3>
        </div>
        <div class="chart-container">
            <canvas id="borrowingTrendChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Aktivitas User per Hari</h3>
        </div>
        <div class="chart-container">
            <canvas id="userActivityChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Top 5 User Teraktif</h3>
        <div class="space-y-3">
            @foreach($topUsers as $u)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <div class="font-medium">{{ $u['name'] }}</div>
                        <div class="text-sm text-gray-500">ID: USR{{ $u['id'] }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold">{{ $u['total'] }}</div>
                        <div class="text-sm text-gray-500">peminjaman</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Top 5 Komik Terpopuler</h3>
        <div class="space-y-3">
            @foreach($topComics as $c)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <div class="font-medium">{{ $c['title'] }}</div>
                        <div class="text-sm text-gray-500">ID: {{ $c['id'] }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold">{{ $c['total'] }}</div>
                        <div class="text-sm text-gray-500">peminjaman</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = @json($months);
    const borrowingCounts = @json($borrowingCounts);
    const days = @json($days);
    const userActivity = @json($userActivity);

    const ctx1 = document.getElementById('borrowingTrendChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: borrowingCounts,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,0.08)',
                fill: true,
                tension: 0.3,
                borderWidth: 3
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins:{legend:{display:false}} }
    });

    const ctx2 = document.getElementById('userActivityChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: days,
            datasets: [{
                label: 'Aktivitas',
                data: userActivity,
                backgroundColor: days.map((d,i) => i===2 ? 'rgba(79,70,229,0.9)' : 'rgba(79,70,229,0.6)'),
                borderColor: '#4f46e5',
                borderWidth: 1
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins:{legend:{display:false}} }
    });
</script>
@endsection
