@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Flash messages --}}
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="row g-0">
            <div class="col-md-4" style="background:linear-gradient(135deg,#3b82f6,#8b5cf6); color:#fff;">
                <div class="p-4 text-center">
                    @if($comic->cover_path)
                        <img src="{{ asset('storage/'.$comic->cover_path) }}" alt="{{ $comic->title }}" class="img-fluid mb-3" style="max-height:360px; object-fit:cover;">
                    @else
                        <div style="height:360px; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-book-open fa-5x text-white-50"></i>
                        </div>
                    @endif

                    <h4 class="mt-3">{{ $comic->title }}</h4>
                    <small class="d-block text-white-50">{{ $comic->author ?? '-' }}</small>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title">{{ $comic->title }}</h3>
                    <p class="text-muted mb-1">Publisher: {{ $comic->publisher ?? '-' }}</p>
                    <p class="text-muted">Kategori: {{ $comic->category?->name ?? '-' }}</p>

                    <div class="mb-3">
                        <strong>Status Stok:</strong>
                        @if($comic->stock > 0)
                            <span class="badge bg-success">Tersedia ({{ $comic->stock }})</span>
                        @else
                            <span class="badge bg-secondary">Kosong</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h5>Sinopsis</h5>
                        <p>{{ $comic->description ?? 'Belum ada sinopsis.' }}</p>
                    </div>

                    <div class="mb-4 d-flex gap-2">
                        @auth
                            {{-- kondisi: jika user sudah pinjam item ini (userHasThis true) maka jangan tampilkan tombol pinjam --}}
                            @if($userHasThis)
                                <button class="btn btn-secondary" disabled>Sudah Dipinjam</button>
                            @else
                                {{-- jika stok habis --}}
                                @if($comic->stock <= 0)
                                    <button class="btn btn-secondary" disabled>Stok Habis</button>
                                @else
                                    {{-- Form peminjaman mengacu ke BorrowingController::requestBorrow --}}
                                    <form action="{{ route('borrowings.request') }}" method="POST" class="mb-0">
                                        @csrf
                                        <input type="hidden" name="comic_id" value="{{ $comic->id }}">
                                        <button type="submit" class="btn btn-primary"
                                            @if($userActiveCount >= 3) disabled title="Batas pinjam tercapai (3)">Batas Peminjaman @else>Pinjam Komik Ini @endif
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Login untuk Pinjam</a>
                        @endauth

                        <a href="{{ route('comics.index') }}" class="btn btn-outline-secondary">Kembali ke Daftar</a>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Info lain: Tahun {{ $comic->year ?? '-' }} • Halaman: {{ $comic->pages ?? '-' }} • ISBN: {{ $comic->isbn ?? '-' }}</small>
                    </div>

                    {{-- Komik serupa: gunakan relasi genres atau kategori bila ada --}}
                    @if($comic->genres && $comic->genres->count())
                        <div class="mt-4">
                            <h6>Genre</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($comic->genres as $g)
                                    <span class="badge bg-light text-dark">{{ $g->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- Related / rekomendasi (opsional) --}}
    @if(isset($related) && $related->count())
        <h5>Komik Serupa</h5>
        <div class="row g-3">
            @foreach($related as $r)
                <div class="col-12 col-md-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h6 class="fw-bold">{{ \Illuminate\Support\Str::limit($r->title, 60) }}</h6>
                            <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($r->description ?? '-', 80) }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <small class="text-muted">Stok: {{ $r->stock }}</small>
                                <a href="{{ route('comics.show', $r->id) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
