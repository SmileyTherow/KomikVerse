@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        @if ($comic->cover || $comic->cover_path)
                            <img src="{{ asset($comic->cover ?? $comic->cover_path) }}" class="img-fluid"
                                alt="{{ $comic->title }}">
                        @else
                            <img src="https://via.placeholder.com/300x400?text=No+Cover" class="img-fluid" alt="no-cover">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h3 class="card-title">{{ $comic->title }}</h3>
                            <p class="mb-1"><strong>Author:</strong> {{ $comic->author ?? '-' }}</p>
                            <p class="mb-1"><strong>Publisher:</strong> {{ $comic->publisher ?? '-' }}</p>
                            <p class="mb-1"><strong>Kategori:</strong> {{ $comic->category?->name ?? '-' }}</p>
                            <p class="mb-1"><strong>Genres:</strong>
                                @foreach ($comic->genres as $g)
                                    <span class="badge bg-light text-dark">{{ $g->name }}</span>
                                @endforeach
                            </p>

                            <p class="mt-3">{{ $comic->synopsis ?? $comic->description }}</p>

                            <p class="mt-2"><strong>Stok:</strong> {{ $comic->stock }}</p>

                            @auth
                                @if (!auth()->user()->email_verified_at)
                                    <div class="alert alert-warning">Verifikasi email diperlukan untuk meminjam.</div>
                                @elseif($userActiveCount >= 3)
                                    <div class="alert alert-danger">Anda sudah memiliki 3 pinjaman aktif. Selesaikan salah satu
                                        sebelum meminjam lagi.</div>
                                @elseif($userHasThis)
                                    <div class="alert alert-info">Anda sedang meminjam komik ini.</div>
                                @else
                                    <form action="{{ route('borrowings.request') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="comic_id" value="{{ $comic->id }}">
                                        <button class="btn btn-success" {{ $comic->stock <= 0 ? 'disabled' : '' }}>
                                            {{ $comic->stock <= 0 ? 'Habis' : 'Pinjam' }}
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">Login untuk meminjam</a>
                            @endauth

                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('comics.index') }}" class="btn btn-link">Kembali</a>
        </div>
    </div>
@endsection
