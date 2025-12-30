@extends('layouts.app')

@section('content')
    <div class="row justify-content-center align-items-center min-vh-80 py-5">
        <div class="col-lg-10">
            <div class="card border-0 overflow-hidden">
                <div class="row g-0">
                    <!-- Left Side - Illustration/Info -->
                    <div class="col-lg-6 gradient-bg text-white p-4 p-lg-5">
                        <div class="d-flex flex-column h-100">
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-white/20 rounded-xl p-3 me-3">
                                        <i class="fas fa-book fa-2x"></i>
                                    </div>
                                    <h2 class="mb-0">inkomi</h2>
                                </div>
                                <h1 class="display-6 fw-bold mb-4">Selamat Datang Kembali!</h1>
                                <p class="lead opacity-90">Masuk ke akun Anda untuk melanjutkan petualangan komik dan manga
                                    favorit.</p>
                            </div>

                            <div class="mt-auto">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white/20 rounded-circle p-2 me-3">
                                                <i class="fas fa-book-open"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1">500+ Judul Komik</h5>
                                                <p class="small opacity-90 mb-0">Koleksi terlengkap dari berbagai genre</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white/20 rounded-circle p-2 me-3">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1">Pinjam 7 Hari</h5>
                                                <p class="small opacity-90 mb-0">Waktu cukup untuk menikmati cerita</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="col-lg-6 p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-gray-800">Masuk ke Akun</h2>
                            <p class="text-muted">Masukkan detail akun Anda untuk melanjutkan</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required
                                        autocomplete="email" autofocus placeholder="nama@email.com">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password"
                                        class="form-control border-start-0 @error('password') is-invalid @enderror"
                                        id="password" name="password" required autocomplete="current-password"
                                        placeholder="Masukkan password">
                                    <button class="btn btn-outline-secondary password-toggle" type="button"
                                        data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-gradient w-100 py-3 fw-semibold mb-4">
                                <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Akun
                            </button>

                            <!-- Social Login (Optional) -->
                            @if (config('services.google.client_id') || config('services.facebook.client_id'))
                                <div class="row g-2 mb-4">
                                    @if (config('services.google.client_id'))
                                        <div class="col">
                                            <a href="{{ route('login.google') }}" class="btn btn-outline-secondary w-100">
                                                <i class="fab fa-google text-danger me-2"></i> Google
                                            </a>
                                        </div>
                                    @endif
                                    @if (config('services.facebook.client_id'))
                                        <div class="col">
                                            <a href="{{ route('login.facebook') }}" class="btn btn-outline-secondary w-100">
                                                <i class="fab fa-facebook text-primary me-2"></i> Facebook
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- OTP Notice -->
                            <div class="alert alert-info border-0 bg-light-info mb-4">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="alert-heading mb-1">Verifikasi OTP</h6>
                                        <p class="small mb-0">Setelah login, Anda akan menerima kode OTP 6 digit melalui
                                            email untuk verifikasi keamanan.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Sign Up Link -->
                            <div class="text-center">
                                <p class="text-muted mb-0">Belum punya akun?
                                    <a href="{{ route('register.show') }}"
                                        class="text-decoration-none fw-semibold text-primary">Daftar Sekarang</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .min-vh-80 {
                min-height: 80vh;
            }

            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .btn-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                color: white;
            }

            .btn-gradient:hover {
                opacity: 0.9;
                color: white;
            }

            .divider:after,
            .divider:before {
                content: "";
                flex: 1;
                height: 1px;
                background: #e0e0e0;
            }
        </style>
    @endpush
@endsection
