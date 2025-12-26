@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="container">
        <!-- Page title -->
        <div class="mb-4">
            <h2 class="h3 fw-bold">Daftar Komik & Manga</h2>
            <p class="text-muted mb-0">Pilih komik yang ingin Anda pinjam dari koleksi kami</p>
        </div>

        <!-- Filter & Search -->
        <div class="card mb-4 p-3">
            <form action="{{ route('comics.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5 position-relative">
                    <i class="fas fa-search position-absolute" style="left:12px; top:50%; transform:translateY(-50%); color:#9CA3AF;"></i>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control ps-5" placeholder="Cari judul komik...">
                </div>

                <div class="col-6 col-md-3">
                    <select name="genre" class="form-select">
                        <option value="">Semua Genre</option>
                        @if(isset($genres) && $genres->count())
                            @foreach($genres as $g)
                                <option value="{{ $g->id }}" @if(request('genre') == $g->id) selected @endif>{{ $g->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="available" @if(request('status')=='available') selected @endif>Tersedia</option>
                        <option value="out" @if(request('status')=='out') selected @endif>Habis</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 text-end">
                    <button class="btn btn-gradient w-100">Filter</button>
                </div>
            </form>
        </div>

        <!-- Comics Grid -->
        <div class="row g-4">
            @forelse($comics as $comic)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 overflow-hidden">
                        <!-- cover / colored header -->
                        <div style="height:160px; overflow:hidden;">
                            @if(!empty($comic->cover_path))
                                <img src="{{ asset('storage/'.$comic->cover_path) }}" alt="{{ $comic->title }}" class="w-100" style="height:160px; object-fit:cover;">
                            @elseif(!empty($comic->cover_url))
                                <img src="{{ $comic->cover_url }}" alt="{{ $comic->title }}" class="w-100" style="height:160px; object-fit:cover;">
                            @else
                                <div style="height:160px; background:linear-gradient(90deg,#60a5fa,#7c3aed); display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-book-open text-white" style="font-size:42px;"></i>
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0" style="font-size:1rem;">{{ \Illuminate\Support\Str::limit($comic->title, 60) }}</h5>
                                <span class="badge badge-gradient" style="font-size:0.75rem;">
                                    {{ optional($comic->genres->first())->name ?? ($comic->category?->name ?? '-') }}
                                </span>
                            </div>

                            <p class="text-muted small mb-2">{{ $comic->author ?? '-' }} • {{ $comic->publisher ?? '-' }}</p>

                            <p class="text-muted mb-3" style="flex:0 0 auto;">
                                {{ \Illuminate\Support\Str::limit($comic->description ?? $comic->synopsis ?? '-', 110) }}
                            </p>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block">Stok:</small>
                                    <strong class="text-dark" style="font-size:0.95rem;">
                                        @if($comic->stock > 0)
                                            {{ $comic->stock }} tersedia
                                        @else
                                            Habis
                                        @endif
                                    </strong>
                                </div>

                                @if($comic->stock > 0)
                                    <a href="{{ route('comics.show', $comic->id) }}" class="btn btn-gradient btn-sm">Lihat</a>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>Stok Habis</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card p-4 text-center text-muted">
                        Tidak ada komik ditemukan.
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            @if(isset($comics) && method_exists($comics, 'links'))
                {{ $comics->appends(request()->except('page'))->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
