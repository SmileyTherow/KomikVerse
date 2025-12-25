@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center min-vh-80 py-5">
    <div class="col-lg-10">
        <div class="card border-0 overflow-hidden">
            <div class="row g-0">
                <!-- Left Side - Form -->
                <div class="col-lg-6 p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <div class="gradient-bg rounded-xl p-3 me-3">
                                <i class="fas fa-book text-white fa-2x"></i>
                            </div>
                            <h2 class="mb-0">inkomi</h2>
                        </div>
                        <h2 class="fw-bold text-gray-800">Buat Akun Baru</h2>
                        <p class="text-muted">Bergabunglah dengan komunitas pecinta komik</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required
                                       autocomplete="name"
                                       autofocus
                                       placeholder="Nama lengkap Anda">
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autocomplete="email"
                                       placeholder="nama@email.com">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-phone text-muted"></i>
                                </span>
                                <input type="tel"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       autocomplete="tel"
                                       placeholder="08xxxxxxxxxx">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Alamat</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 align-items-start pt-3">
                                    <i class="fas fa-home text-muted"></i>
                                </span>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address"
                                          name="address"
                                          rows="3"
                                          placeholder="Alamat lengkap Anda">{{ old('address') }}</textarea>
                            </div>
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password"
                                       class="form-control border-start-0 @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Minimal 8 karakter"
                                       oninput="checkPasswordStrength(this.value)">
                                <button class="btn btn-outline-secondary password-toggle" type="button" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <!-- Password Strength Meter -->
                            <div class="password-strength-meter mt-2">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" id="passwordStrengthBar" role="progressbar" style="width: 0%;"></div>
                                </div>
                                <small class="text-muted">Gunakan kombinasi huruf, angka, dan simbol</small>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password"
                                       class="form-control border-start-0"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Ulangi password">
                            </div>
                            <div id="passwordMatchMessage" class="mt-2 small"></div>
                        </div>

                        <!-- Terms -->
                        <div class="mb-4 form-check">
                            <input type="checkbox"
                                   class="form-check-input @error('terms') is-invalid @enderror"
                                   id="terms"
                                   name="terms"
                                   required>
                            <label class="form-check-label" for="terms">
                                Saya setuju dengan
                                <a href="{{ route('terms') }}" class="text-decoration-none fw-semibold" target="_blank">Syarat & Ketentuan</a>
                                dan
                                <a href="{{ route('privacy') }}" class="text-decoration-none fw-semibold" target="_blank">Kebijakan Privasi</a>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-gradient w-100 py-3 fw-semibold mb-4" id="submitBtn">
                            <span id="submitText">Daftar Sekarang</span>
                            <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="text-muted mb-0">Sudah punya akun?
                                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-primary">Masuk di sini</a>
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Right Side - Illustration/Info -->
                <div class="col-lg-6 gradient-bg text-white p-4 p-lg-5">
                    <div class="d-flex flex-column h-100">
                        <div class="mb-4">
                            <h1 class="display-6 fw-bold mb-4">Bergabunglah dengan Komunitas Kami</h1>
                            <p class="lead opacity-90 mb-5">Dapatkan akses ke koleksi komik terlengkap dan nikmati berbagai manfaat sebagai member.</p>

                            <!-- Benefits -->
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-white/20 rounded-xl p-3 me-3">
                                            <i class="fas fa-gift fa-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="fw-bold mb-2">Welcome Gift</h4>
                                            <p class="opacity-90 mb-0">Dapatkan 1 komik gratis untuk pinjaman pertama Anda sebagai member baru</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-white/20 rounded-xl p-3 me-3">
                                            <i class="fas fa-star fa-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="fw-bold mb-2">Priority Access</h4>
                                            <p class="opacity-90 mb-0">Akses lebih cepat ke komik baru dan edisi terbatas sebelum member lain</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-white/20 rounded-xl p-3 me-3">
                                            <i class="fas fa-users fa-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="fw-bold mb-2">Komunitas Eksklusif</h4>
                                            <p class="opacity-90 mb-0">Bergabung dengan grup diskusi dan event khusus untuk member</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonial -->
                        <div class="mt-auto">
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-white/20 rounded-circle p-3 me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Budi Santoso</h5>
                                        <p class="small opacity-80 mb-0">Member sejak 2022</p>
                                    </div>
                                </div>
                                <p class="mb-0 fst-italic">"inkomi mengubah cara saya membaca manga. Koleksinya lengkap dan sistem peminjamannya sangat mudah!"</p>
                            </div>
                        </div>
                    </div>
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

    .password-strength-meter .progress-bar {
        transition: width 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    // Password strength checker
    function checkPasswordStrength(password) {
        const bar = document.getElementById('passwordStrengthBar');
        let strength = 0;

        if (password.length === 0) {
            bar.style.width = '0%';
            bar.className = 'progress-bar';
            return;
        }

        // Length check
        if (password.length >= 8) strength += 25;

        // Contains lowercase
        if (/[a-z]/.test(password)) strength += 25;

        // Contains uppercase
        if (/[A-Z]/.test(password)) strength += 25;

        // Contains numbers or special chars
        if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength += 25;

        // Update bar
        bar.style.width = strength + '%';

        // Update color based on strength
        bar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');
        if (strength <= 25) {
            bar.classList.add('bg-danger');
        } else if (strength <= 50) {
            bar.classList.add('bg-warning');
        } else if (strength <= 75) {
            bar.classList.add('bg-info');
        } else {
            bar.classList.add('bg-success');
        }
    }

    // Password confirmation check
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        const messageDiv = document.getElementById('passwordMatchMessage');

        if (confirmPassword.length === 0) {
            messageDiv.textContent = '';
            messageDiv.className = 'mt-2 small';
            return;
        }

        if (password === confirmPassword) {
            messageDiv.textContent = '✓ Password cocok';
            messageDiv.className = 'mt-2 small text-success';
        } else {
            messageDiv.textContent = '✗ Password tidak cocok';
            messageDiv.className = 'mt-2 small text-danger';
        }
    });

    // Form submission with loading state
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const loadingSpinner = document.getElementById('loadingSpinner');

        // Validate terms checkbox
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Anda harus menyetujui Syarat & Ketentuan untuk melanjutkan.');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('d-none');
        loadingSpinner.classList.remove('d-none');
    });
</script>
@endpush
@endsection
