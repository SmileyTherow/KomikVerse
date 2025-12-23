@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h2>Halo, {{ $user->name }}</h2>
            <p>
                Status verifikasi email:
                @if ($user->email_verified_at)
                    <span class="badge bg-success">Terverifikasi</span>
                @else
                    <span class="badge bg-warning">Belum Verifikasi</span>
                    <a href="{{ route('otp.verify.show') }}" class="btn btn-sm btn-outline-primary ms-2">Verifikasi
                        sekarang</a>
                @endif
            </p>

            <h4 class="mt-4">Pinjaman aktif ({{ $activeBorrowings->count() }})</h4>
            @if ($activeBorrowings->isEmpty())
                <div class="alert alert-info">Tidak ada pinjaman aktif.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Komik</th>
                            <th>Borrowed At</th>
                            <th>Due At</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activeBorrowings as $b)
                            <tr>
                                <td><a href="{{ route('comics.show', $b->comic->id) }}">{{ $b->comic->title }}</a></td>
                                <td>{{ $b->borrowed_at?->format('Y-m-d') }}</td>
                                <td>{{ $b->due_at?->format('Y-m-d') }}</td>
                                <td>{{ $b->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <h5 class="mt-4">Riwayat terkahir</h5>
            @if ($recent->isEmpty())
                <div class="text-muted">Belum ada aktivitas.</div>
            @else
                <ul class="list-group">
                    @foreach ($recent as $r)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold">{{ $r->comic?->title }}</div>
                                <small class="text-muted">Status: {{ $r->status }}</small>
                            </div>
                            <span class="badge bg-secondary">{{ $r->created_at->format('Y-m-d') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Info Akun</h5>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Telepon:</strong> {{ $user->phone ?? '-' }}</p>
                    <a href="{{ route('borrowings.index') }}" class="btn btn-primary">Lihat semua pinjaman</a>
                </div>
            </div>
        </div>
    </div>
@endsection
