@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3>Verifikasi OTP</h3>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kode OTP (6 digit)</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" required
                        maxlength="6">
                </div>

                <button class="btn btn-primary" type="submit">Verifikasi</button>
            </form>

            <hr>

            <form method="POST" action="{{ route('otp.resend') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Kirim ulang OTP ke Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email untuk kirim ulang"
                        required>
                </div>
                <button class="btn btn-secondary" type="submit">Kirim Ulang OTP</button>
            </form>
        </div>
    </div>
@endsection
