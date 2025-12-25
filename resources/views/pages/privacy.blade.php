@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-3">Kebijakan Privasi</h1>
                <p class="text-muted">Terakhir diperbarui: {{ date('d F Y') }}</p>
            </div>

            <!-- Content -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <!-- Intro -->
                    <div class="mb-5">
                        <p class="lead">Di <strong>inkomi</strong>, kami menghargai dan melindungi privasi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda ketika menggunakan platform kami.</p>
                    </div>

                    <!-- Sections -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">1. Informasi yang Kami Kumpulkan</h3>

                        <div class="mb-4">
                            <h5 class="fw-semibold mb-2">1.1. Informasi yang Anda Berikan</h5>
                            <ul>
                                <li class="mb-2"><strong>Data Registrasi:</strong> Nama, email, nomor telepon, alamat</li>
                                <li class="mb-2"><strong>Data Profil:</strong> Foto profil, preferensi genre, riwayat membaca</li>
                                <li class="mb-2"><strong>Data Transaksi:</strong> Riwayat peminjaman, pembayaran denda</li>
                                <li class="mb-2"><strong>Konten yang Dibuat:</strong> Ulasan, rating, komentar</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-semibold mb-2">1.2. Informasi yang Otomatis Terkumpul</h5>
                            <ul>
                                <li class="mb-2"><strong>Data Teknis:</strong> Alamat IP, jenis browser, perangkat</li>
                                <li class="mb-2"><strong>Data Penggunaan:</strong> Halaman yang dikunjungi, waktu akses</li>
                                <li class="mb-2"><strong>Data Lokasi:</strong> Informasi lokasi umum (jika diizinkan)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">2. Penggunaan Informasi</h3>
                        <p>Kami menggunakan informasi Anda untuk:</p>
                        <ul>
                            <li class="mb-2">Menyediakan dan meningkatkan layanan peminjaman komik</li>
                            <li class="mb-2">Memproses transaksi dan peminjaman</li>
                            <li class="mb-2">Mengirim notifikasi dan pembaruan layanan</li>
                            <li class="mb-2">Memberikan rekomendasi komik yang personal</li>
                            <li class="mb-2">Meningkatkan keamanan dan mencegah penipuan</li>
                            <li class="mb-2">Memenuhi kewajiban hukum yang berlaku</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">3. Berbagi Informasi</h3>
                        <p>Kami <strong>tidak akan</strong> menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga. Informasi mungkin dibagikan dengan:</p>

                        <div class="mb-4">
                            <h5 class="fw-semibold mb-2">3.1. Penyedia Layanan</h5>
                            <ul>
                                <li class="mb-2"><strong>Hosting dan Infrastruktur:</strong> Untuk penyimpanan data</li>
                                <li class="mb-2"><strong>Payment Gateway:</strong> Untuk proses pembayaran</li>
                                <li class="mb-2"><strong>Email Service:</strong> Untuk pengiriman notifikasi</li>
                                <li class="mb-2"><strong>Analytics:</strong> Untuk analisis penggunaan platform</li>
                            </ul>
                            <p class="text-muted small">Semua penyedia layanan terikat dengan perjanjian kerahasiaan.</p>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-semibold mb-2">3.2. Kepatuhan Hukum</h5>
                            <p>Kami dapat membuka informasi jika diperlukan oleh hukum atau untuk:</p>
                            <ul>
                                <li class="mb-2">Mematuhi proses hukum yang sah</li>
                                <li class="mb-2">Melindungi hak, properti, atau keamanan inkomi</li>
                                <li class="mb-2">Mencegah atau menyelidiki aktivitas ilegal</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">4. Keamanan Data</h3>
                        <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasional yang sesuai untuk melindungi informasi Anda:</p>
                        <ul>
                            <li class="mb-2"><strong>Enkripsi:</strong> Data sensitif dienkripsi selama transmisi dan penyimpanan</li>
                            <li class="mb-2"><strong>Firewall:</strong> Perlindungan terhadap akses tidak sah</li>
                            <li class="mb-2"><strong>Access Control:</strong> Pembatasan akses berdasarkan kebutuhan</li>
                            <li class="mb-2"><strong>Audit Rutin:</strong> Pemeriksaan keamanan berkala</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">5. Penyimpanan Data</h3>
                        <ul>
                            <li class="mb-2">Data disimpan selama diperlukan untuk tujuan yang sah</li>
                            <li class="mb-2">Data akun disimpan selama akun aktif atau sesuai hukum</li>
                            <li class="mb-2">Data transaksi disimpan selama 5 tahun untuk keperluan pajak</li>
                            <li class="mb-2">Anda dapat meminta penghapusan data dengan menghubungi kami</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">6. Hak Pengguna</h3>
                        <p>Anda memiliki hak untuk:</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h5 class="fw-semibold mb-2">Akses dan Koreksi</h5>
                                        <p class="small mb-0">Mengakses dan memperbarui informasi pribadi Anda</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h5 class="fw-semibold mb-2">Penghapusan</h5>
                                        <p class="small mb-0">Meminta penghapusan data pribadi Anda</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h5 class="fw-semibold mb-2">Pembatasan</h5>
                                        <p class="small mb-0">Membatasi pemrosesan data Anda</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h5 class="fw-semibold mb-2">Portabilitas</h5>
                                        <p class="small mb-0">Menerima data Anda dalam format terstruktur</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">7. Cookie dan Teknologi Pelacakan</h3>
                        <p>Kami menggunakan cookie untuk:</p>
                        <ul>
                            <li class="mb-2">Mengingat preferensi Anda</li>
                            <li class="mb-2">Menganalisis penggunaan platform</li>
                            <li class="mb-2">Meningkatkan pengalaman pengguna</li>
                        </ul>
                        <p>Anda dapat mengontrol cookie melalui pengaturan browser Anda.</p>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">8. Perlindungan Anak</h3>
                        <p>Layanan kami tidak ditujukan untuk anak di bawah 13 tahun. Jika kami mengetahui telah mengumpulkan data anak di bawah 13 tahun, kami akan menghapusnya segera.</p>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">9. Perubahan Kebijakan</h3>
                        <p>Kami dapat memperbarui Kebijakan Privasi ini. Perubahan signifikan akan diberitahukan melalui email atau notifikasi di platform. Penggunaan berkelanjutan setelah perubahan berarti Anda menerima kebijakan yang diperbarui.</p>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 border-bottom pb-2">10. Kontak</h3>
                        <p>Untuk pertanyaan tentang Kebijakan Privasi atau hak privasi Anda:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-envelope me-2"></i> privacy@inkomi.com</li>
                            <li class="mb-2"><i class="fas fa-phone me-2"></i> 021-1234-5678</li>
                            <li><i class="fas fa-clock me-2"></i> Senin-Jumat, 09:00-17:00 WIB</li>
                        </ul>
                    </div>

                    <!-- GDPR Notice -->
                    <div class="alert alert-warning border-0 bg-light-warning mt-5">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-shield-alt fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading">Perlindungan Data</h5>
                                <p class="mb-0">Kami berkomitmen untuk mematuhi peraturan perlindungan data seperti GDPR. Jika Anda berada di wilayah Eropa, Anda memiliki hak tambahan sesuai dengan GDPR.</p>
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
