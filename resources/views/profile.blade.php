@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Profil Saya</h3>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Telepon:</strong> {{ $user->phone ?? '-' }}</p>
            <p><strong>Alamat:</strong> {{ $user->address ?? '-' }}</p>
            <p><strong>Terverifikasi:</strong>
                @if($user->email_verified_at)
                    <span class="badge bg-success">Ya</span>
                @else
                    <span class="badge bg-warning">Belum</span>
                @endif
            </p>
        </div>
    </div>
</div>
@endsection
