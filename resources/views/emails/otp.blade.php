@component('mail::message')
# Kode Verifikasi Anda

Kode verifikasi (OTP) Anda adalah:

@component('mail::panel')
{{ $code }}
@endcomponent

Kode ini berlaku sampai: **{{ $expiresAt }}**.

Jika Anda tidak melakukan permintaan ini, abaikan email ini.

Terima kasih,<br>
KomikVerse
@endcomponent
