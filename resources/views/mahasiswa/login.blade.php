@extends('layouts.mahasiswa')

@section('title', 'Login - PresGo')

@section('content')
<div class="app-screen figma-login-screen">
    
    {{-- Main Content --}}
    <div class="login-content-wrap">
        
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
                <i class="fa-solid fa-circle-check" style="margin-top:1px;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation" style="margin-top:1px;"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('mahasiswa.login.submit') }}" method="POST" id="loginForm">
            @csrf
            
            {{-- NIM Input --}}
            <div class="figma-field-wrap">
                <input type="text" name="login" id="login" placeholder="NIM" value="{{ old('login', $rememberedLogin ?? '') }}" required autofocus>
            </div>

            {{-- Password Input --}}
            <div class="figma-field-wrap" style="margin-bottom: 4px;">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="button" class="toggle-eye" data-target="password">
                    <i class="fa-solid fa-eye-slash"></i>
                </button>
            </div>

            {{-- Forgot Password --}}
            <div class="forgot-pwd-row">
                <a href="{{ route('mahasiswa.forgot-password') }}" class="forgot-pwd-link">Forgot Password?</a>
            </div>

            {{-- Checkbox Ingat NIP --}}
            <div class="checkbox-container">
                <label class="figma-checkbox">
                    <input type="checkbox" name="ingat_nim" value="1" {{ $rememberedLogin ?? false ? 'checked' : '' }}>
                    Ingat NIP
                </label>
            </div>

            {{-- Buttons --}}
            <div class="figma-btn-row">
                <button type="submit" class="figma-btn-login">LOGIN</button>
                <button type="button" class="figma-btn-finger" id="openFingerBtn" aria-label="Login dengan sidik jari">
                    <i class="fa-solid fa-fingerprint"></i>
                </button>
            </div>
        </form>

        <p class="register-hint">
            Belum punya akun? <a href="{{ route('mahasiswa.register') }}" class="register-link">Daftar</a>
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
    /* 100% Figma Match Styles (Second Design) */
    .figma-login-screen {
        background-color: #1A5E35 !important;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .login-content-wrap {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 32px 32px;
    }

    @media (min-width: 768px) {
        .login-content-wrap {
            max-width: 450px;
            margin: 0 auto;
            width: 100%;
        }
    }

    .login-logo-area {
        text-align: center;
        margin-bottom: 36px;
    }

    .logo-squircle {
        width: 104px;
        height: 104px;
        border-radius: 50%; /* Perfect circle */
        background: #F8F9FA;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .logo-p {
        font-family: 'Palatino Linotype', Palatino, 'Book Antiqua', Georgia, serif;
        font-size: 64px;
        font-style: italic;
        font-weight: 700;
        color: #1A5E35; /* Dark green matching bg */
        line-height: 1;
        display: block;
        padding-bottom: 6px;
        padding-right: 4px;
        -webkit-text-stroke: 1.5px #6A997C; /* Light teal stroke */
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .login-title {
        color: #fff;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.4;
        margin: 0;
        letter-spacing: 0.3px;
    }

    /* Inputs */
    .figma-field-wrap {
        position: relative;
        margin-bottom: 16px;
    }

    .figma-field-wrap input {
        width: 100%;
        padding: 15px 16px;
        border-radius: 8px;
        border: none;
        background: #fff !important;
        font-family: inherit;
        font-size: 14px;
        color: #333;
        outline: none;
    }

    .figma-field-wrap input::placeholder {
        color: #9CA3AF;
        font-weight: 500;
    }

    .figma-field-wrap input[type="password"] {
        padding-right: 46px;
    }

    .figma-field-wrap .toggle-eye {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9CA3AF;
        cursor: pointer;
        font-size: 15px;
    }

    /* Links & Checkboxes */
    .forgot-pwd-row {
        text-align: right;
        margin-bottom: 32px; /* Large gap before Ingat NIP */
    }

    .forgot-pwd-link {
        color: #fff;
        font-size: 12px;
        text-decoration: none;
        font-weight: 600;
    }

    .checkbox-container {
        margin-bottom: 16px; /* Gap before LOGIN button */
        display: flex;
        justify-content: flex-start;
    }

    .figma-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    /* Custom Checkbox to match Figma (white square) */
    .figma-checkbox input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 16px;
        height: 16px;
        background-color: #fff;
        border-radius: 2px;
        border: none;
        cursor: pointer;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .figma-checkbox input[type="checkbox"]:checked::after {
        content: "✓";
        color: #1A5E35;
        font-size: 12px;
        font-weight: 900;
        position: absolute;
    }

    /* Buttons */
    .figma-btn-row {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 32px;
    }

    .figma-btn-login {
        flex: 1;
        padding: 15px;
        background: #FDE047;
        color: #111827;
        border: none;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        transition: transform 0.1s;
        letter-spacing: 0.5px;
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
        border: 2px solid #FDE047;
        color: #FDE047;
        font-size: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
    }

    .figma-btn-finger:hover {
        background: rgba(253, 224, 71, 0.15);
    }

    /* Footer Text */
    .register-hint {
        text-align: center;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        margin: 0;
    }

    .register-link {
        color: #fff;
        font-weight: 800;
        text-decoration: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.toggle-eye').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.target);
            var icon = btn.querySelector('i');
            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            
            if (isPassword) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
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

        setTimeout(function () {
            loginForm.submit();
        }, 1400);
    });

    cancelFingerBtn.addEventListener('click', function () {
        fingerModal.classList.remove('is-open');
    });
</script>
@endpush