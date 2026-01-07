@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <div style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 12px 24px; border-radius: 50px;">
        <h1 style="margin: 0; color: white; font-size: 24px;">
            <i class="fas fa-book" style="margin-right: 10px;"></i>KomikVerse
        </h1>
    </div>
</div>

# 🔐 Verifikasi Akun Anda

Halo **{{ $name ?? 'Pengguna' }}**,

Untuk melanjutkan proses verifikasi akun Anda, masukkan kode One-Time Password (OTP) berikut:

@component('mail::panel', ['style' => 'background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-left: 4px solid #667eea; padding: 25px;'])
<div style="text-align: center;">
    <div style="font-size: 36px; font-weight: 800; letter-spacing: 15px; color: #2d3748; margin: 15px 0; font-family: 'Courier New', monospace;">
        {{ $code }}
    </div>
    <div style="font-size: 14px; color: #718096; margin-top: 10px;">
        <i class="fas fa-clock" style="margin-right: 5px;"></i> Kode berlaku hingga: **{{ $expiresAt }}**
    </div>
</div>
@endcomponent

<div style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 10px; padding: 15px; margin: 25px 0;">
    <div style="display: flex; align-items: center;">
        <div style="background-color: #f39c12; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            <strong style="color: #d35400;">⚠️ Keamanan Penting:</strong>
            <p style="margin: 5px 0 0 0; color: #7d6608;">
                • Jangan berikan kode ini kepada siapapun<br>
                • Kode hanya berlaku satu kali pakai<br>
                • Tim KomikVerse tidak akan meminta kode OTP Anda
            </p>
        </div>
    </div>
</div>

@component('mail::button', ['url' => '#', 'color' => 'primary', 'style' => 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 25px; padding: 12px 30px;'])
<i class="fas fa-check-circle" style="margin-right: 8px;"></i> Verifikasi Sekarang
@endcomponent

<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; text-align: center; color: #718096; font-size: 13px;">
    <p style="margin: 5px 0;">
        <i class="fas fa-question-circle" style="margin-right: 5px;"></i>
        Butuh bantuan?
        <a href="mailto:support@komikverse.com" style="color: #667eea; text-decoration: none;">
            support@komikverse.com
        </a>
    </p>
    <p style="margin: 5px 0;">
        <i class="fas fa-globe" style="margin-right: 5px;"></i>
        KomikVerse © {{ date('Y') }} • Platform Komik Digital
    </p>
    <div style="margin-top: 15px;">
        <a href="#" style="margin: 0 10px; color: #718096; text-decoration: none;">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="#" style="margin: 0 10px; color: #718096; text-decoration: none;">
            <i class="fab fa-facebook"></i>
        </a>
        <a href="#" style="margin: 0 10px; color: #718096; text-decoration: none;">
            <i class="fab fa-twitter"></i>
        </a>
    </div>
</div>

@endcomponent
