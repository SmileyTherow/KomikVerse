@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-book-reader me-2 text-primary"></i> Pinjamanku
            </h4>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr class="text-muted small text-uppercase">
                        <th>#</th>
                        <th>Komik</th>
                        <th>Status</th>
                        <th>Permintaan</th>
                        <th>Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Kembali</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($items as $b)
                        <tr>
                            <td class="fw-semibold">{{ $b->id }}</td>

                            <td>
                                <i class="fas fa-book text-secondary me-1"></i>
                                {{ $b->comic?->title ?? '-' }}
                            </td>

                            <td>
                                @php
                                    $badge = match($b->status) {
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'returned' => 'primary',
                                        'rejected' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp

                                <span class="badge rounded-pill bg-{{ $badge }}">
                                    {{ ucfirst($b->status) }}
                                </span>
                            </td>

                            <td>{{ $b->requested_at ?? '-' }}</td>
                            <td>{{ $b->borrowed_at ?? '-' }}</td>
                            <td>
                                <span class="fw-semibold text-danger">
                                    {{ $b->due_at ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $b->returned_at ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                Belum ada data peminjaman
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
