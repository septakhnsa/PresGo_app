@extends('layouts.mahasiswa')

@section('title', 'Login - PresGo')

@section('content')
<div class="login-page-wrapper">

    <div class="login-box">

        {{-- Logo & Title --}}
        <div class="login-logo-area">
            <div class="logo-squircle">
                <span class="logo-p">P</span>
            </div>
            <h1 class="login-title">
                Presensi Online<br>Akademika Mahasiswa
            </h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('mahasiswa.login.submit') }}" method="POST" id="loginForm">
            @csrf

            <div class="figma-field-wrap">
                <input type="text" name="login" id="login"
                    placeholder="NIM atau Email"
                    value="{{ old('login', $rememberedLogin ?? '') }}"
                    required autofocus>
            </div>

            <div class="figma-field-wrap">
                <input type="password" name="password" id="password"
                    placeholder="Password" required>
                <button type="button" class="toggle-eye" data-target="password">
                    <i class="fa-solid fa-eye-slash"></i>
                </button>
            </div>

            <div class="options-row">
                <label class="figma-checkbox">
                    <input type="checkbox" name="ingat_nim" value="1"
                        {{ $rememberedLogin ?? false ? 'checked' : '' }}>
                    <span>Ingat NIM</span>
                </label>
                <a href="{{ route('mahasiswa.forgot-password') }}" class="forgot-pwd-link">Lupa Password?</a>
            </div>

            <div class="figma-btn-row">
                <button type="submit" class="figma-btn-login">LOGIN</button>
                <button type="button" class="figma-btn-finger" id="openFingerBtn" aria-label="Login dengan sidik jari">
                    <i class="fa-solid fa-fingerprint"></i>
                </button>
            </div>
        </form>

        <p class="register-hint">
            Belum punya akun?
            <a href="{{ route('mahasiswa.register') }}" class="register-link">Daftar</a>
        </p>

    </div>

    {{-- Fingerprint Modal --}}
    <div class="modal-overlay" id="fingerModal">
        <div class="modal-card">
            <i class="fa-solid fa-fingerprint"></i>
            <h3>Touch ID for &ldquo;Presensi Online Akademika Mahasiswa&rdquo;</h3>
            <p>Scan your fingerprint please</p>
            <div class="modal-note" id="fingerNote"></div>
            <button type="button" class="modal-cancel" id="cancelFingerBtn">Cancel</button>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>

/* ── Override parent layout ───────────────────────────────────── */
html, body {
    height: auto !important;
    min-height: 100% !important;
    overflow-x: hidden !important;
    overflow-y: auto !important;
    margin: 0 !important;
    padding: 0 !important;
}

.app-screen {
    height: auto !important;
    min-height: 100vh !important;
    overflow: visible !important;
    display: block !important;
    background: #1A5E35 !important;
}

/* ── Full page green wrapper ──────────────────────────────────── */
.login-page-wrapper {
    min-height: 100vh;
    width: 100%;
    background-color: #1A5E35;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
    box-sizing: border-box;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* ── Login box — NO background, NO card, NO shadow ───────────── */
.login-box {
    width: 100%;
    max-width: 400px;
    box-sizing: border-box;
}

/* ── Logo area ────────────────────────────────────────────────── */
.login-logo-area {
    text-align: center;
    margin-bottom: 32px;
}

.logo-squircle {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: #F0F5F1;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 3px 14px rgba(0,0,0,0.20);
}

.logo-p {
    font-family: 'Palatino Linotype', Palatino, 'Book Antiqua', Georgia, serif;
    font-size: 56px;
    font-style: italic;
    font-weight: 700;
    color: #1A5E35;
    line-height: 1;
    display: block;
    padding-bottom: 4px;
}

.login-title {
    color: #ffffff;
    font-size: 20px;
    font-weight: 700;
    line-height: 1.5;
    margin: 0;
    letter-spacing: 0.2px;
}

/* ── Alert ────────────────────────────────────────────────────── */
.alert {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 11px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
    border: 1px solid transparent;
    box-sizing: border-box;
}

.alert-success {
    background: rgba(74, 222, 128, 0.18);
    color: #bbf7d0;
    border-color: rgba(74, 222, 128, 0.35);
}

.alert-error {
    background: rgba(248, 113, 113, 0.18);
    color: #fecaca;
    border-color: rgba(248, 113, 113, 0.35);
}

/* ── Input fields ─────────────────────────────────────────────── */
.figma-field-wrap {
    position: relative;
    margin-bottom: 14px;
}

.figma-field-wrap input {
    width: 100%;
    padding: 14px 16px;
    border-radius: 10px;
    border: none;
    background: #ffffff;
    font-family: inherit;
    font-size: 14px;
    color: #111827;
    outline: none;
    box-sizing: border-box;
    transition: box-shadow 0.2s;
}

.figma-field-wrap input:focus {
    box-shadow: 0 0 0 3px rgba(253, 224, 71, 0.50);
}

.figma-field-wrap input::placeholder {
    color: #9CA3AF;
    font-weight: 400;
}

.figma-field-wrap input[type="password"] {
    padding-right: 46px;
}

.toggle-eye {
    position: absolute;
    right: 13px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9CA3AF;
    cursor: pointer;
    font-size: 15px;
    padding: 4px;
    line-height: 1;
}

.toggle-eye:hover {
    color: #6B7280;
}

/* ── Options row ──────────────────────────────────────────────── */
.options-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 4px 0 26px;
}

.figma-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.figma-checkbox input[type="checkbox"] {
    appearance: none;
    -webkit-appearance: none;
    width: 17px;
    height: 17px;
    border-radius: 4px;
    border: 1.5px solid rgba(255,255,255,0.55);
    background: rgba(255,255,255,0.15);
    cursor: pointer;
    position: relative;
    flex-shrink: 0;
    transition: all 0.15s;
    box-sizing: border-box;
}

.figma-checkbox input[type="checkbox"]:checked {
    background: #FDE047;
    border-color: #FDE047;
}

.figma-checkbox input[type="checkbox"]:checked::after {
    content: "";
    position: absolute;
    left: 4px;
    top: 1px;
    width: 5px;
    height: 9px;
    border: 2.5px solid #1A5E35;
    border-top: none;
    border-left: none;
    transform: rotate(45deg);
}

.figma-checkbox span {
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    user-select: none;
}

.forgot-pwd-link {
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    opacity: 0.90;
}

.forgot-pwd-link:hover {
    opacity: 1;
    text-decoration: underline;
}

/* ── Buttons ──────────────────────────────────────────────────── */
.figma-btn-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 28px;
}

.figma-btn-login {
    flex: 1;
    padding: 15px;
    background: #f9d521;
    color: #111827;
    border: none;
    border-radius: 999px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 1px;
    cursor: pointer;
    transition: transform 0.1s, box-shadow 0.15s;
    box-shadow: 0 2px 10px rgba(253, 224, 71, 0.40);
}

.figma-btn-login:hover {
    box-shadow: 0 4px 20px rgba(243, 221, 112, 0.6);
}

.figma-btn-login:active {
    transform: scale(0.98);
}

.figma-btn-finger {
    width: 50px;
    height: 50px;
    min-width: 50px;
    border-radius: 50%;
    background: transparent;
    border: 2px solid #f0ce28;
    color: #f0ce28;
    font-size: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s;
}

.figma-btn-finger:hover {
    background: rgba(253, 224, 71, 0.15);
}

/* ── Footer ───────────────────────────────────────────────────── */
.register-hint {
    text-align: center;
    color: rgba(255, 255, 255, 0.85);
    font-size: 13px;
    font-weight: 500;
    margin: 0;
}

.register-link {
    color: #ffffff;
    font-weight: 800;
    text-decoration: none;
}

.register-link:hover {
    text-decoration: underline;
}

/* ── Fingerprint Modal ────────────────────────────────────────── */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.55);
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 24px;
    box-sizing: border-box;
}

.modal-overlay.is-open {
    display: flex;
}

.modal-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 36px 32px 28px;
    text-align: center;
    max-width: 300px;
    width: 100%;
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.30);
    box-sizing: border-box;
}

.modal-card > .fa-fingerprint {
    font-size: 54px;
    color: #1A5E35;
    margin-bottom: 14px;
    display: block;
}

.modal-card h3 {
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 8px;
    line-height: 1.5;
}

.modal-card p {
    font-size: 13px;
    color: #6B7280;
    margin: 0 0 12px;
}

.modal-note {
    font-size: 12px;
    color: #EF4444;
    min-height: 18px;
    margin-bottom: 14px;
    font-weight: 500;
}

.modal-cancel {
    padding: 10px 28px;
    border: 1.5px solid #E5E7EB;
    border-radius: 999px;
    background: #ffffff;
    color: #374151;
    font-family: inherit;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.15s;
}

.modal-cancel:hover {
    background: #F9FAFB;
}

</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.toggle-eye').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input  = document.getElementById(btn.dataset.target);
            var icon   = btn.querySelector('i');
            var isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash', !isPass);
            icon.classList.toggle('fa-eye', isPass);
        });
    });

    var fingerModal     = document.getElementById('fingerModal');
    var openFingerBtn   = document.getElementById('openFingerBtn');
    var cancelFingerBtn = document.getElementById('cancelFingerBtn');
    var fingerNote      = document.getElementById('fingerNote');
    var loginForm       = document.getElementById('loginForm');

    openFingerBtn.addEventListener('click', function () {
        fingerNote.textContent = '';
        fingerModal.classList.add('is-open');

        var loginVal = document.getElementById('login').value.trim();
        var passVal  = document.getElementById('password').value.trim();

        if (!loginVal || !passVal) {
            setTimeout(function () {
                fingerNote.textContent = 'Isi NIM & Password dulu untuk pakai login cepat ini.';
            }, 900);
            return;
        }

        setTimeout(function () { loginForm.submit(); }, 1400);
    });

    cancelFingerBtn.addEventListener('click', function () {
        fingerModal.classList.remove('is-open');
    });
</script>
@endpush