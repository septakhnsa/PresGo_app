@extends('layouts.mahasiswa')

@section('title', 'Lupa Password - PresGo')

@section('content')
<div class="fp-screen">

    {{-- Area hijau atas --}}
    <div class="fp-header">
        <a href="{{ route('mahasiswa.login') }}" class="fp-back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

    {{-- Kartu putih bawah --}}
    <div class="fp-card">

        {{-- Ikon gembok mencuat --}}
        <div class="fp-icon-circle">
            <svg width="52" height="62" viewBox="0 0 52 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 26V18C9 9.163 16.163 2 25 2C33.837 2 41 9.163 41 18V26"
                      stroke="#1A5E35" stroke-width="4.5" stroke-linecap="round" fill="none"/>
                <rect x="3" y="24" width="44" height="36" rx="7" fill="#1A5E35"/>
                <text x="25" y="50" text-anchor="middle"
                      fill="white"
                      font-family="Plus Jakarta Sans, Arial, sans-serif"
                      font-size="22"
                      font-weight="900">?</text>
            </svg>
        </div>

        {{-- Konten --}}
        <div class="fp-scroll">
            <div class="fp-body">

                <h1 class="fp-title">Lupa Password?</h1>
                <p class="fp-subtitle">
                    Masukkan email aktif anda.<br>
                    Akan kami kirimkan kode OTP untuk verifikasi
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

                <form action="{{ route('mahasiswa.forgot-password.submit') }}" method="POST">
                    @csrf
                    <div class="fp-field-group">
                        <label for="email">Email</label>
                        <input
                            type="email" name="email" id="email"
                            placeholder="email@mhs.kampus.ac.id"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                    <button type="submit" class="fp-btn-otp">Kirim Kode OTP</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ── Override parent ──────────────────────────── */
html, body {
    height: 100% !important;
    overflow: hidden !important;
    margin: 0 !important;
    padding: 0 !important;
}

.app-screen {
    height: 100% !important;
    overflow: hidden !important;
    display: flex !important;
    flex-direction: column !important;
}

/* ── Wrapper utama full hijau ─────────────────── */
.fp-screen {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    background: #1A5E35;
    overflow: hidden;
    position: relative;
}

/* ── Area hijau atas (±38% layar) ────────────── */
.fp-header {
    flex: 0 0 38%;
    display: flex;
    align-items: flex-start;
    padding: 44px 20px 0;
    flex-shrink: 0;
}

/* Tombol back */
.fp-back-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #ffffff26;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 15px;
    text-decoration: none;
    transition: background 0.2s;
}
.fp-back-btn:hover { background: #ffffff40; }

/* ── Kartu abu-abu bawah ──────────────────────── */
.fp-card {
    flex: 1;
    background: #F1F5F9;
    border-radius: 28px 28px 0 0;
    overflow: visible;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    margin-top: -90px;
}

/* ── Ikon gembok mencuat ke atas ──────────────── */
.fp-icon-circle {
    margin-top: -56px;
    margin-bottom: 16px;
    flex-shrink: 0;
    position: relative;
    z-index: 5;
    width: 113px;
    height: 108px;
    border-radius: 50%;
    background: #DCEEE1;
    border: 6px solid #F1F5F9;
    box-shadow: 0 6px 20px #00000022;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ── Area scroll ──────────────────────────────── */
.fp-scroll {
    flex: 1;
    width: 100%;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.fp-scroll::-webkit-scrollbar { display: none; }

/* ── Isi konten ───────────────────────────────── */
.fp-body {
    padding: 4px 28px 48px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 480px;
    margin: 0 auto;
    width: 100%;
    box-sizing: border-box;
}

/* ── Judul & subtitle ─────────────────────────── */
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
    line-height: 1.65;
    margin: 0 0 24px;
}

/* ── Alert ────────────────────────────────────── */
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
    box-sizing: border-box;
}
.fp-alert-error   { background: #FEE2E2; color: #DC2626; }
.fp-alert-success { background: #D1FAE5; color: #065F46; }

/* ── Form ─────────────────────────────────────── */
form { width: 100%; }

.fp-field-group {
    width: 100%;
    margin-bottom: 20px;
    text-align: left;
}
.fp-field-group label {
    display: block;
    color: #374151;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 8px;
}
.fp-field-group input {
    width: 100%;
    padding: 13px 16px;
    border-radius: 10px;
    border: 1.5px solid #D1D5DB;
    background: #fff;
    font-family: inherit;
    font-size: 14px;
    color: #374151;
    outline: none;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.fp-field-group input::placeholder { color: #9CA3AF; }
.fp-field-group input:focus {
    border-color: #1A5E35;
    box-shadow: 0 0 0 3px #1a5e3520;
}

/* ── Tombol ───────────────────────────────────── */
.fp-btn-otp {
    width: 100%;
    padding: 14px;
    background: #1A5E35;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    letter-spacing: 0.2px;
    transition: background 0.2s, transform 0.1s;
}
.fp-btn-otp:hover  { background: #154a2a; }
.fp-btn-otp:active { transform: scale(0.98); }
</style>
@endpush