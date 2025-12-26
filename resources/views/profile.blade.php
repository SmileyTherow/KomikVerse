@extends('layouts.app')

@section('content')
    @php
        $totalBorrowed = $totalBorrowed ?? ($user->borrowings()->count() ?? 0);
        $activeCount = $activeCount ?? ($user->borrowings()->where('status', 'dipinjam')->count() ?? 0);
        $userGenres = $userGenres ?? [];
    @endphp

    <div class="container py-4">
        <div class="mb-4">
            <h2 class="h3 fw-bold">Profil Saya</h2>
            <p class="text-muted mb-0">Kelola informasi akun dan preferensi Anda</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card overflow-hidden mb-4">
                    <div style="height:120px; background:linear-gradient(90deg,#60a5fa,#7c3aed);"></div>
                    <div class="card-body text-center">
                        <div class="mb-3" style="margin-top:-54px;">
                            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                style="width:108px;height:108px;background:#fff;border:6px solid #fff;">
                                @if (!empty($user->avatar))
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="avatar" class="rounded-circle"
                                        style="width:96px;height:96px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:96px;height:96px;background:linear-gradient(90deg,#60a5fa,#7c3aed); color:#fff; font-size:28px; font-weight:700;">
                                        {{ strtoupper(substr($user->name ?? (auth()->user()->name ?? 'U'), 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <div class="text-muted mb-3">Anggota sejak {{ optional($user->created_at)->format('M Y') ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="card p-3">
                    <h6 class="fw-bold">Statistik Akun</h6>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Pinjaman</span>
                            <span class="fw-semibold">{{ $totalBorrowed }} komik</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Sedang Dipinjam</span>
                            <span class="fw-semibold">{{ $activeCount }} komik</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status Keanggotaan</span>
                            <span class="badge bg-success">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Pribadi</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input id="name" name="name" type="text" class="form-control"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" name="email" type="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">No. Telepon</label>
                                    <input id="phone" name="phone" type="tel" class="form-control"
                                        value="{{ old('phone', $user->phone) }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select id="gender" name="gender" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="male" @if (old('gender', $user->gender) == 'male') selected @endif>
                                            Laki-laki</option>
                                        <option value="female" @if (old('gender', $user->gender) == 'female') selected @endif>
                                            Perempuan</option>
                                        <option value="other" @if (old('gender', $user->gender) == 'other') selected @endif>Lainnya
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label">Alamat</label>
                                    <input id="address" name="address" type="text" class="form-control"
                                        value="{{ old('address', $user->address) }}">
                                </div>

                                @if (!empty($hasBio))
                                    <div class="col-12">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea id="bio" name="bio" rows="3" class="form-control">{{ old('bio', $user->bio) }}</textarea>
                                    </div>
                                @endif

                                <div class="col-12">
                                    <label for="avatar" class="form-label">Foto Profil</label>
                                    <input id="avatar" name="avatar" type="file" class="form-control"
                                        accept="image/*">
                                    <div class="form-text">Format: jpg/png. Maks 2MB.</div>
                                </div>

                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">Batal</a>
                                    <button type="submit" class="btn btn-gradient">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-semibold">Akun & Keamanan</h6>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Email:</strong></p>
                                <p class="text-muted mb-2">{{ $user->email }}</p>
                            </div>

                            <div class="col-md=6">
                                <p class="mb-1"><strong>Terverifikasi:</strong></p>
                                <p class="mb-0">
                                    @if ($user->email_verified_at)
                                        <span class="badge bg-success">Ya</span>
                                    @else
                                        <span class="badge bg-warning">Belum</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <hr>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
