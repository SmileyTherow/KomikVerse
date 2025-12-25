@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-3">Syarat & Ketentuan</h1>
                <p class="text-muted">Terakhir diperbarui: {{ date('d F Y') }}</p>
            </div>

            <!-- Content -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <!-- Intro -->
                    <div class="mb-5">
                        <p class="lead">Selamat datang di <strong>inkomi</strong> - Platform peminjaman komik dan manga digital. Dengan mengakses atau menggunakan layanan kami, Anda menyetujui untuk terikat oleh syarat dan ketentuan berikut.</p>
                    </div>

                    <!-- Sections -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">1. Definisi</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong>"Platform"</strong> mengacu pada website inkomi dan aplikasi terkait.</li>
                            <li class="mb-2"><strong>"Pengguna"</strong> adalah individu yang telah mendaftar dan menggunakan layanan kami.</li>
                            <li class="mb-2"><strong>"Komik"</strong> mengacu kepada konten digital yang tersedia untuk dipinjam di platform kami.</li>
                            <li class="mb-2"><strong>"Peminjaman"</strong> adalah periode dimana Pengguna memiliki akses untuk membaca komik yang dipinjam.</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">2. Pendaftaran Akun</h3>
                        <p>Untuk menggunakan layanan peminjaman, Anda harus:</p>
                        <ul>
                            <li class="mb-2">Berusia minimal 13 tahun atau telah mendapat izin dari orang tua/wali.</li>
                            <li class="mb-2">Menyediakan informasi yang akurat dan lengkap saat pendaftaran.</li>
                            <li class="mb-2">Menjaga kerahasiaan akun dan password Anda.</li>
                            <li class="mb-2">Bertanggung jawab atas semua aktivitas yang terjadi di akun Anda.</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">3. Layanan Peminjaman</h3>
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-2">3.1. Masa Peminjaman</h5>
                            <ul>
                                <li class="mb-2">Setiap komik dapat dipinjam maksimal 7 hari.</li>
                                <li class="mb-2">Maksimal 5 komik dapat dipinjam secara bersamaan.</li>
                                <li class="mb-2">Perpanjangan peminjaman dapat diajukan maksimal 1 kali.</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-semibold mb-2">3.2. Keterlambatan</h5>
                            <ul>
                                <li class="mb-2">Denda keterlambatan: Rp 2.000 per hari per komik.</li>
                                <li class="mb-2">Akun akan dinonaktifkan sementara jika memiliki denda > Rp 20.000.</li>
                                <li class="mb-2">Komik yang hilang atau rusak akan dikenakan biaya penggantian.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">4. Hak Pengguna</h3>
                        <ul>
                            <li class="mb-2">Mengakses katalog komik yang tersedia.</li>
                            <li class="mb-2">Meminjam komik sesuai dengan kuota yang ditentukan.</li>
                            <li class="mb-2">Mengajukan perpanjangan peminjaman.</li>
                            <li class="mb-2">Memberikan rating dan ulasan untuk komik yang telah dibaca.</li>
                            <li class="mb-2">Mengakses riwayat peminjaman pribadi.</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">5. Kewajiban Pengguna</h3>
                        <ul>
                            <li class="mb-2">Mengembalikan komik tepat waktu.</li>
                            <li class="mb-2">Tidak membagikan akun kepada orang lain.</li>
                            <li class="mb-2">Tidak melakukan screenshot, download, atau distribusi komik ilegal.</li>
                            <li class="mb-2">Menghormati hak cipta dan kekayaan intelektual.</li>
                            <li class="mb-2">Tidak menggunakan platform untuk aktivitas ilegal.</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">6. Pembatalan dan Penghentian</h3>
                        <p>inkomi berhak untuk:</p>
                        <ul>
                            <li class="mb-2">Menghentikan atau menangguhkan akun yang melanggar ketentuan.</li>
                            <li class="mb-2">Mengubah, menangguhkan, atau menghentikan layanan kapan saja.</li>
                            <li class="mb-2">Menghapus akun yang tidak aktif selama lebih dari 1 tahun.</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">7. Batasan Tanggung Jawab</h3>
                        <p>inkomi tidak bertanggung jawab atas:</p>
                        <ul>
                            <li class="mb-2">Kerusakan akibat penggunaan yang tidak tepat.</li>
                            <li class="mb-2">Kegagalan akses karena masalah teknis di luar kendali kami.</li>
                            <li class="mb-2">Konten komik yang diproduksi oleh penerbit pihak ketiga.</li>
                            <li class="mb-2">Kehilangan data akibat force majeure.</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">8. Perubahan Ketentuan</h3>
                        <p>Kami dapat memperbarui Syarat & Ketentuan ini dari waktu ke waktu. Perubahan akan diberitahukan melalui email atau notifikasi di platform. Penggunaan berkelanjutan setelah perubahan berarti Anda menerima ketentuan yang diperbarui.</p>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">9. Kontak</h3>
                        <p>Untuk pertanyaan tentang Syarat & Ketentuan ini, silakan hubungi:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-envelope me-2"></i> legal@inkomi.com</li>
                            <li class="mb-2"><i class="fas fa-phone me-2"></i> 021-1234-5678</li>
                            <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Komik No. 123, Jakarta</li>
                        </ul>
                    </div>

                    <!-- Acceptance -->
                    <div class="alert alert-info border-0 bg-light-info mt-5">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-2x text-info"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading">Penerimaan Ketentuan</h5>
                                <p class="mb-0">Dengan mendaftar dan menggunakan layanan inkomi, Anda mengakui bahwa Anda telah membaca, memahami, dan menyetujui semua ketentuan di atas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ route('register.show') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Pendaftaran
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
