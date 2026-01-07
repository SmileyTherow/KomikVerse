@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card overflow-hidden">
                <div class="row g-0">
                    <!-- Left: Form -->
                    <div class="col-md-6 p-4">
                        <div class="mb-3 text-center">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div class="me-2" style="width:54px;height:54px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
                                    <i class="fas fa-book text-white fs-4"></i>
                                </div>
                                <h4 class="mb-0" style="background:linear-gradient(90deg,#764ba2,#e53e3e);-webkit-background-clip:text;background-clip:text;color:transparent;font-weight:700;">inkomi</h4>
                            </div>
                            <h5 class="mb-1">Buat Akun Baru</h5>
                            <p class="text-muted small mb-0">Bergabunglah dengan komunitas pecinta komik</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}"
                                       class="form-control @error('name') is-invalid @enderror" required autofocus>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Kata Sandi</label>
                                    <div class="input-group">
                                        <input id="password" name="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               required>
                                        <button type="button" class="btn btn-outline-secondary password-toggle" data-target="password" title="Tampilkan / sembunyikan">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-text">Minimal 8 karakter. Gunakan kombinasi huruf, angka, dan simbol.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password"
                                           class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3 mt-3">
                                <label for="phone" class="form-label">No. Telepon</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                                       class="form-control @error('phone') is-invalid @enderror">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <input id="address" name="address" type="text" value="{{ old('address') }}"
                                       class="form-control @error('address') is-invalid @enderror">
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                                        <option value="">Pilih</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="bio" class="form-label">Bio (opsional)</label>
                                    <input id="bio" name="bio" type="text" value="{{ old('bio') }}" class="form-control @error('bio') is-invalid @enderror">
                                    @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3 mt-4">
                                <div class="form-check">
                                    <input name="terms" class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms" {{ old('terms') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="terms">
                                        Saya setuju dengan <a href="{{ route('terms') }}">Syarat & Ketentuan</a> dan <a href="{{ route('privacy') }}">Kebijakan Privasi</a>
                                    </label>
                                    @error('terms') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-gradient px-4">Daftar</button>
                            </div>

                            <div class="text-center mt-3">
                                <small class="text-muted">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></small>
                            </div>
                        </form>
                    </div>

                    <!-- Right: Info / illustration -->
                    <div class="col-md-6 gradient-bg text-white p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="fw-bold">Bergabunglah dengan Komunitas Kami</h4>
                            <p class="small mb-4">Dapatkan akses ke koleksi komik terlengkap dan nikmati berbagai manfaat sebagai member.</p>

                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <strong>Welcome Gift</strong>
                                    <div class="small opacity-90">Dapatkan 1 komik gratis untuk pinjaman pertama Anda sebagai member baru</div>
                                </li>
                                <li class="mb-3">
                                    <strong>Priority Access</strong>
                                    <div class="small opacity-90">Akses lebih cepat ke komik baru dan edisi terbatas</div>
                                </li>
                                <li>
                                    <strong>Komunitas Eksklusif</strong>
                                    <div class="small opacity-90">Bergabung dengan grup diskusi dan event khusus untuk member</div>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-3 small">
                            Dengan mendaftar, Anda setuju dengan syarat layanan kami.
                        </div>
                    </div>
                </div> <!-- /.row -->
            </div> <!-- /.card -->
        </div>
    </div>
</div>
@endsection
