@extends('layouts.app')

@section('content')
    <style>
        /* ringkas styling modern kompatibel Bootstrap */
        .card-gradient-1 {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            color: #fff;
        }

        .card-gradient-2 {
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            color: #fff;
        }

        .card-gradient-3 {
            background: linear-gradient(135deg, #10b981, #06b6d4);
            color: #fff;
        }

        .card-gradient-4 {
            background: linear-gradient(135deg, #fb923c, #f59e0b);
            color: #fff;
        }

        .pinterest-grid {
            column-gap: 1.5rem;
        }

        @media (min-width: 1200px) {
            .pinterest-grid {
                column-count: 4;
            }
        }

        @media (min-width: 900px) and (max-width:1199px) {
            .pinterest-grid {
                column-count: 3;
            }
        }

        @media (max-width: 899px) {
            .pinterest-grid {
                column-count: 1;
            }
        }

        .pinterest-item {
            break-inside: avoid;
            margin-bottom: 1.25rem;
        }

        .comic-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        .rounded-pill-sm {
            border-radius: 999px;
            padding: .25rem .75rem;
            font-size: .85rem;
        }

        .hero-image-wrapper {
            border-radius: 1.25rem;
            overflow: hidden;
        }

        .hero-image-wrapper img {
            width: 100%;
            height: 380px;
            object-fit: cover;
        }
    </style>

    <div class="py-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="mb-4">
                    <h3 class="fw-bold mb-1">
                        Selamat datang, {{ auth()->user()->name }} 👋
                    </h3>
                    <p class="text-muted mb-0">
                        Temukan komik terbaru dan kelola pinjamanmu dengan mudah.
                    </p>
                </div>


                <div>
                    @if (!empty($user->email_verified_at))
                        <span class="badge rounded-pill-sm bg-success">Email Terverifikasi</span>
                    @else
                        <a href="{{ route('otp.verify.show') }}" class="btn btn-outline-primary btn-sm">Verifikasi Email</a>
                    @endif
                </div>
            </div>

            <div class="card border-0 mb-5 bg-transparent">
                <div class="row align-items-center">

                    <!-- TEXT -->
                    <div class="col-md-6 mb-4 mb-md-0">
                        <span class="badge bg-primary-subtle text-primary mb-3">
                            Sistem Peminjaman Komik
                        </span>

                        <h2 class="fw-bold mb-3">
                            Temukan & Pinjam<br>
                            Komik Favoritmu 📚
                        </h2>

                        <p class="text-muted mb-4">
                            Kelola peminjaman komik dengan mudah, pantau status,
                            dan kembalikan tepat waktu tanpa ribet.
                        </p>

                        <div class="d-flex gap-3">
                            <a href="{{ route('comics.index') }}" class="btn btn-primary px-4">
                                Jelajahi Komik
                            </a>

                            <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary px-4">
                                Pinjaman Saya
                            </a>
                        </div>
                    </div>

                    <!-- IMAGE -->
                    <div class="col-md-6">
                        <div class="hero-image-wrapper shadow-sm">
                            <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1200"
                                alt="Library" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>


            <!-- Stats -->
            @php
                $activeCount = isset($activeBorrowings) ? $activeBorrowings->count() : 0;
                $dueSoonCount = isset($activeBorrowings)
                    ? $activeBorrowings
                        ->filter(function ($b) {
                            return $b->due_at &&
                                now()->diffInDays($b->due_at, false) >= 0 &&
                                now()->diffInDays($b->due_at, false) <= 3;
                        })
                        ->count()
                    : 0;
                $totalBorrowed = isset($recent) ? $recent->count() : 0;
                $wishlistCount = 0;
            @endphp

            <div class="row g-3 mb-5">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Sedang Dipinjam</small>
                            <h3 class="fw-bold mt-2">1</h3>
                            <a href="#" class="small text-primary text-decoration-none">
                                Lihat detail →
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Harus Dikembalikan</small>
                            <h3 class="fw-bold mt-2 text-danger">0</h3>
                            <span class="small text-muted">dalam 3 hari</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Total Dipinjam</small>
                            <h3 class="fw-bold mt-2">2</h3>
                            <a href="#" class="small text-primary text-decoration-none">
                                Riwayat →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Rekomendasi Untukmu</h4>
                <a href="{{ route('comics.index') }}" class="small text-decoration-none">Lihat Semua →</a>
            </div>

            <div class="pinterest-grid mb-5">
                @if (isset($comics) && $comics->count())
                    @foreach ($comics as $comic)
                        <div class="pinterest-item">
                            <div class="card comic-card mb-3">
                                <div class="row g-0">
                                    <div class="col-4" style="min-height:120px; overflow:hidden;">
                                        @if ($comic->cover_path)
                                            <img src="{{ asset('storage/' . $comic->cover_path) }}"
                                                class="img-fluid h-100 w-100" style="object-fit:cover;">
                                        @else
                                            <div style="height:100%; background:#f3f4f6;"></div>
                                        @endif
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body">
                                            <small class="text-muted">{{ $comic->category?->name ?? '-' }}</small>
                                            <h6 class="card-title mt-1">
                                                {{ \Illuminate\Support\Str::limit($comic->title, 60) }}</h6>
                                            <p class="small text-muted mb-2">
                                                {{ \Illuminate\Support\Str::limit($comic->description ?? '-', 80) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">Stok: {{ $comic->stock }}</small>
                                                {{-- TOMBOL DI SINI HANYA MENUNJUKKAN DETAIL, TIDAK LANGSUNG PINJAM --}}
                                                <a href="{{ route('comics.show', $comic->id) }}"
                                                    class="btn btn-sm btn-primary">Lihat</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-muted py-4">Belum ada rekomendasi komik saat ini.</div>
                @endif
            </div>

            <!-- Active Borrowings -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Pinjaman Aktif</h4>
                    <a href="{{ route('borrowings.index') }}" class="small">Lihat Semua →</a>
                </div>

                @if (isset($activeBorrowings) && $activeBorrowings->isNotEmpty())
                    <div class="row g-3">
                        @foreach ($activeBorrowings as $b)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card p-3">
                                    <div class="d-flex">
                                        <div class="me-3"
                                            style="width:72px; height:96px; background:#f3f4f6; border-radius:8px; overflow:hidden;">
                                            @if ($b->comic && $b->comic->cover_path)
                                                <img src="{{ asset('storage/' . $b->comic->cover_path) }}" alt=""
                                                    class="img-fluid h-100 w-100" style="object-fit:cover;">
                                            @endif
                                        </div>
                                        <div class="flex-fill">
                                            <h6 class="fw-bold mb-1">{{ $b->comic?->title ?? '-' }}</h6>
                                            <div class="text-muted small">Dipinjam:
                                                {{ optional($b->borrowed_at)->format('d M Y') ?? optional($b->created_at)->format('d M Y') }}
                                            </div>
                                            <div class="text-muted small">Batas kembali:
                                                <strong>{{ optional($b->due_at)->format('d M Y') ?? '-' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-end">
                                        @php
                                            $labelClass =
                                                $b->status === 'terlambat' ? 'badge bg-danger' : 'badge bg-primary';
                                        @endphp
                                        <span class="{{ $labelClass }}">{{ ucfirst($b->status) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">Tidak ada pinjaman aktif.</div>
                @endif
            </div>

            <!-- Recent activity -->
            <div class="mb-5">
                <h4>Riwayat Terakhir</h4>
                @if (isset($recent) && $recent->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($recent as $r)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold">{{ $r->comic?->title ?? '-' }}</div>
                                    <small class="text-muted">Status: {{ ucfirst($r->status) }}</small>
                                </div>
                                <div class="text-muted small">{{ optional($r->created_at)->format('Y-m-d') }}</div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted">Belum ada aktivitas.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
