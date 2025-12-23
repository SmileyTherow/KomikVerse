@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>Daftar Komik</h1>
            <div class="row">
                @foreach ($comics as $comic)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $comic->title }}</h5>
                                <p class="card-text">{{ Str::limit($comic->synopsis, 100) }}</p>
                                <p><strong>Kategori:</strong> {{ $comic->category?->name }}</p>
                                <p><strong>Stok:</strong> {{ $comic->stock }}</p>
                                <a href="{{ route('comics.show', $comic->id) }}" class="btn btn-primary">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
