@extends('layouts.mahasiswa')

@section('title', 'Verifikasi OTP - PresGo')

@section('content')
<div class="otp-screen">

    {{-- Header hijau --}}
    <div class="otp-header">
        <a href="{{ route('mahasiswa.forgot-password') }}" class="otp-back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

    {{-- Kartu putih --}}
    <div class="otp-card">

        {{-- Ikon amplop mencuat ke area hijau --}}
        <div class="otp-icon-circle">
            <i class="fa-solid fa-envelope"></i>
        </div>

        {{-- Scroll area --}}
        <div class="otp-scroll">
            <div class="otp-body">

                @if ($errors->any())
                    <div class="otp-alert otp-alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <h1 class="otp-title">Verifikasi OTP</h1>
                <p class="otp-subtitle">
                    Silahkan Cek Email Anda.<br>
                    Kode OTP 5 digit telah dikirim ke
                </p>
                <p class="otp-email">{{ $maskedEmail ?? 'stu***@mhs.kampus.ac.id' }}</p>

                <form action="{{ route('mahasiswa.otp.verify') }}" method="POST" id="otpForm">
                    @csrf

                    <div class="otp-row">
                        <input type="text" name="otp_1" maxlength="1" inputmode="numeric" class="otp-box" autofocus>
                        <input type="text" name="otp_2" maxlength="1" inputmode="numeric" class="otp-box">
                        <input type="text" name="otp_3" maxlength="1" inputmode="numeric" class="otp-box">
                        <input type="text" name="otp_4" maxlength="1" inputmode="numeric" class="otp-box">
                        <input type="text" name="otp_5" maxlength="1" inputmode="numeric" class="otp-box">
                    </div>

                    <button type="submit" class="otp-btn-submit">Kirim Kode OTP</button>
                </form>

                <p class="otp-resend-text">
                    Belum terima kode?
                    <a href="{{ route('mahasiswa.otp.resend') }}" class="otp-resend-link">Kirim ulang</a>
                </p>

            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
html, body { height: 100%; overflow: hidden; }

/* ── Layar penuh hijau ─────────────────────────── */
.otp-screen {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    background: #1A5E35;
    position: relative;
    overflow: hidden;
}

/* ── Header hijau (±42% tinggi) ────────────────── */
.otp-header {
    flex: 0 0 42%;
    display: flex;
    align-items: flex-start;
    padding: 40px 20px 0;
    flex-shrink: 0;
}

/* Tombol kembali */
.otp-back-btn {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px; text-decoration: none;
    transition: background 0.2s;
}
.otp-back-btn:hover { background: rgba(255,255,255,0.25); }

/* ── Kartu putih ────────────────────────────────── */
.otp-card {
    flex: 1;
    background: #F1F5F9;
    border-radius: 28px 28px 0 0;
    overflow: visible;           /* biarkan ikon mencuat ke atas */
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    margin-top: -115px;
}

/* ── Ikon amplop mencuat ke area hijau ──────────── */
.otp-icon-circle {
    margin-top: -38px;
    margin-bottom: 20px;
    flex-shrink: 0;
    position: relative;
    z-index: 20;

    width: 111px; height: 107px;   /* oval / pill shape */
    border-radius: 999px;
    background: #DCEEE1;
    border: 5px solid #fff;
    box-shadow: 0 4px 18px rgba(0,0,0,0.10);
    display: flex; align-items: center; justify-content: center;
}

.otp-icon-circle .fa-envelope {
    font-size: 26px;
    color: #1A5E35;
}

/* ── Area scroll ────────────────────────────────── */
.otp-scroll {
    flex: 1;
    width: 100%;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}

/* ── Isi konten ─────────────────────────────────── */
.otp-body {
    padding: 0 28px 48px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 420px;
    margin: 0 auto;
}

/* ── Alert ──────────────────────────────────────── */
.otp-alert {
    width: 100%;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 12.5px;
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    text-align: left;
}
.otp-alert-error { background: #FEE2E2; color: #DC2626; }

/* ── Teks ───────────────────────────────────────── */
.otp-title {
    color: #111827;
    font-size: 20px;
    font-weight: 800;
    margin: 0 0 8px;
}
.otp-subtitle {
    color: #6B7280;
    font-size: 13px;
    font-weight: 500;
    line-height: 1.6;
    margin: 0 0 4px;
}
.otp-email {
    color: #1A5E35;
    font-size: 13px;
    font-weight: 700;
    margin: 0 0 28px;
    text-decoration: underline;
    text-underline-offset: 2px;
}

/* ── OTP boxes ──────────────────────────────────── */
.otp-row {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 0;
}

.otp-box {
    width: 52px;
    height: 60px;
    text-align: center;
    font-size: 24px;
    font-weight: 800;
    color: #1A5E35;
    border: 2px solid #1A5E35;
    border-radius: 12px;
    background: #fff;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    font-family: inherit;
}
.otp-box:focus {
    border-color: #0f3d22;
    box-shadow: 0 0 0 3px rgba(26,94,53,0.12);
}

/* ── Tombol submit ──────────────────────────────── */
.otp-btn-submit {
    display: block;
    width: 100%;
    margin-top: 32px;
    padding: 15px;
    background: #1A5E35;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 800;
    cursor: pointer;
    font-family: inherit;
    letter-spacing: 0.3px;
    transition: background 0.2s, transform 0.1s;
}
.otp-btn-submit:hover  { background: #154a2a; }
.otp-btn-submit:active { transform: scale(0.98); }

/* ── Kirim ulang ────────────────────────────────── */
.otp-resend-text {
    color: #6B7280;
    font-size: 13px;
    font-weight: 500;
    margin-top: 20px;
}
.otp-resend-link {
    color: #DC2626;
    font-weight: 700;
    text-decoration: none;
}
.otp-resend-link:hover { text-decoration: underline; }
</style>
@endpush

@push('scripts')
<script>
    var otpInputs = document.querySelectorAll('.otp-box');
    otpInputs.forEach(function (input, index) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
        // Paste support
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            var pasteData = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            pasteData.split('').forEach(function(char, i) {
                if (otpInputs[index + i]) otpInputs[index + i].value = char;
            });
        });
    });
</script>
@endpush