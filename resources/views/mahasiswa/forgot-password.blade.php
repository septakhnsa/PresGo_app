@extends('layouts.mahasiswa')

@section('title', 'Lupa Password - PresGo')

@section('content')
<div class="fp-screen">

    {{-- Header hijau --}}
    <div class="fp-header">
        <a href="{{ route('mahasiswa.login') }}" class="fp-back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

    {{-- Kartu putih --}}
    <div class="fp-card">

        {{-- Ikon gembok: margin-top negatif agar mencuat ke area hijau --}}
        {{-- fp-card punya overflow:visible jadi TIDAK terpotong --}}
        <div class="fp-icon-circle">
            <svg width="56" height="66" viewBox="0 0 56 66" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Shackle (baut atas gembok) -->
                <path d="M10 28V20C10 10.059 18.059 2 28 2C37.941 2 46 10.059 46 20V28" 
                      stroke="#1A5E35" stroke-width="5" stroke-linecap="round" fill="none"/>
                <!-- Badan gembok -->
                <rect x="4" y="26" width="48" height="38" rx="7" fill="#1A5E35"/>
                <!-- Tanda tanya -->
                <text x="28" y="53" text-anchor="middle" 
                      fill="white" 
                      font-family="Plus Jakarta Sans, Arial, sans-serif" 
                      font-size="22" 
                      font-weight="900">?</text>
            </svg>
        </div>

        {{-- Semua konten ada di sini, bisa scroll --}}
        <div class="fp-scroll">
            <div class="fp-body">
                <h1 class="fp-title">Lupa Password?</h1>
                <p class="fp-subtitle">
                    Masukkan email aktif anda.<br>
                    Akan kami kirimkan kode OTP untuk verifikasi.
                </p>

                @if (session('success'))
                    <div class="fp-alert fp-alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="fp-alert fp-alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('mahasiswa.forgot-password.submit') }}" method="POST" id="forgotForm">
                    @csrf
                    <div class="fp-field-group">
                        <label for="email">Email</label>
                        <input
                            type="email" name="email" id="email"
                            placeholder="email@mhs.kampus.ac.id"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="fp-btn-wrap">
                        <button type="submit" class="fp-btn-otp">Kirim Kode OTP</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
html, body { height: 100%; overflow: hidden; }

/* ── Layar penuh hijau ─────────────────────────── */
.fp-screen {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    background: #1A5E35;
    position: relative;
    overflow: hidden;
}

/* ── Header hijau (±42% tinggi) ────────────────── */
.fp-header {
    flex: 0 0 42%;
    display: flex;
    align-items: flex-start;
    padding: 40px 20px 0;
    flex-shrink: 0;
}

/* Tombol kembali */
.fp-back-btn {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px; text-decoration: none;
    transition: background 0.2s;
}
.fp-back-btn:hover { background: rgba(255,255,255,0.25); }

/* ── Kartu putih ────────────────────────────────── */
.fp-card {
    flex: 1;
    background: #F1F5F9;
    border-radius: 28px 28px 0 0;
    /* overflow VISIBLE agar ikon tidak terpotong ke atas */
    overflow: visible;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    margin-top: -32px;
}

/* ── Ikon gembok (mencuat ke area hijau) ─────────── */
.fp-icon-circle {
    margin-top: -54px;
    margin-bottom: 20px;
    flex-shrink: 0;
    position: relative;
    z-index: 5;

    width: 112px; height: 112px;
    border-radius: 50%;
    background: #DCEEE1;
    border: 6px solid #F1F5F9;
    box-shadow: 0 6px 24px rgba(0,0,0,0.13);
    display: flex; align-items: center; justify-content: center;
}

/* ── Area scroll (di dalam kartu) ───────────────── */
.fp-scroll {
    flex: 1;
    width: 100%;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}

/* ── Isi konten ─────────────────────────────────── */
.fp-body {
    padding: 0 28px 48px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 420px;
    margin: 0 auto;
}

/* ── Teks ───────────────────────────────────────── */
.fp-title {
    color: #1A5E35;
    font-size: 20px;
    font-weight: 800;
    margin: 0 0 8px;
}
.fp-subtitle {
    color: #6B7280;
    font-size: 13px;
    font-weight: 500;
    line-height: 1.6;
    margin: 0 0 28px;
}

/* ── Alert ──────────────────────────────────────── */
.fp-alert {
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
.fp-alert-error   { background: #FEE2E2; color: #DC2626; }
.fp-alert-success { background: #D1FAE5; color: #065F46; }

/* ── Form ───────────────────────────────────────── */
.fp-field-group {
    width: 100%;
    margin-bottom: 24px;
    text-align: left;
}
.fp-field-group label {
    display: block;
    color: #6B7280;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 8px;
}
.fp-field-group input {
    width: 100%;
    padding: 14px 16px;
    border-radius: 12px;
    border: 1.5px solid #D1D5DB;
    background: #fff;
    font-family: inherit;
    font-size: 14px;
    color: #374151;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.fp-field-group input::placeholder { color: #9CA3AF; }
.fp-field-group input:focus {
    border-color: #1A5E35;
    box-shadow: 0 0 0 3px rgba(26,94,53,0.10);
}

/* ── Tombol ─────────────────────────────────────── */
.fp-btn-wrap { width: 100%; display: flex; justify-content: center; }
.fp-btn-otp {
    width: 100%; max-width: 240px;
    padding: 14px 20px;
    background: #1A5E35; color: #fff;
    border: none; border-radius: 999px;
    font-size: 14px; font-weight: 800;
    cursor: pointer; letter-spacing: 0.3px;
    transition: background-color 0.2s, transform 0.1s;
}
.fp-btn-otp:hover  { background: #154a2a; }
.fp-btn-otp:active { transform: scale(0.98); }
</style>
@endpush