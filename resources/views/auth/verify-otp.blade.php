@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Verifikasi Email</h1>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($errors->has('resend'))
        <div class="alert alert-danger">{{ $errors->first('resend') }}</div>
    @endif

    <p>Kami telah mengirim kode verifikasi 6-digit ke email Anda. Masukkan kodenya di bawah ini.</p>

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <div class="mb-3">
            <label for="code" class="form-label">Kode OTP</label>
            <input id="code" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required autofocus>
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary" type="submit">Verifikasi</button>
    </form>

    <form class="mt-3" method="POST" action="{{ route('otp.resend') }}">
        @csrf
        <button class="btn btn-link" type="submit">Kirim ulang kode</button>
    </form>
</div>
@endsection
