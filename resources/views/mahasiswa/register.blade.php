@extends('layouts.mahasiswa')

@section('title', 'Daftar Akun - PresGo')

@section('content')
<div class="app-screen figma-register-screen">
    
    <div class="register-scroll-container">
        {{-- Stripe and Logo --}}
        <div class="register-header-area">
            <div class="register-stripe"></div>
            <div class="register-logo-circle">
                <span class="logo-p">P</span>
            </div>
        </div>

        {{-- Title & Subtitle --}}
        <div class="register-title-area">
            <h1 class="register-title">Daftar akun</h1>
            <p class="register-subtitle">Harap melengkapi data untuk mendaftar</p>
        </div>

        <div class="register-content-wrap">

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation" style="margin-top:1px;"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('mahasiswa.register.submit') }}" method="POST" id="registerForm">
                @csrf

                <div class="figma-form-group">
                    <label>Nama Lengkap</label>
                    <div class="figma-field-wrap">
                        <input type="text" name="name" id="name" placeholder="ketik disini.." value="{{ old('name') }}" required autofocus>
                    </div>
                </div>

                <div class="figma-form-group">
                    <label>Email</label>
                    <div class="figma-field-wrap">
                        <input type="email" name="email" id="email" placeholder="ketik disini.." value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="figma-form-group" style="margin-bottom: 12px;">
                    <label>Password</label>
                    <div class="figma-field-wrap">
                        <input type="password" name="password" id="password" placeholder="ketik disini.." required minlength="8">
                        <button type="button" class="toggle-eye" data-target="password">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="figma-btn-row">
                    <button type="submit" class="figma-btn-register">Register</button>
                    <button type="button" class="figma-btn-finger" id="openFingerBtn" aria-label="Register dengan sidik jari">
                        <i class="fa-solid fa-fingerprint"></i>
                    </button>
                </div>
            </form>

            <p class="login-hint">
                Sudah punya akun? <a href="{{ route('mahasiswa.login') }}" class="login-link">Login</a>
            </p>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* 100% Match Figma Register Design */
    html, body { margin: 0; }

    .figma-register-screen {
        background-color: #1A5E35 !important;
        background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
        background-size: 26px 26px;
        position: relative;
        min-height: 100vh;
        display: flex;
        overflow: hidden;
    }

    .figma-register-screen::before,
    .figma-register-screen::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.07) 0%, transparent 70%);
        z-index: 0;
        pointer-events: none;
    }

    .figma-register-screen::before {
        width: 420px;
        height: 420px;
        top: -160px;
        left: -160px;
    }

    .figma-register-screen::after {
        width: 520px;
        height: 520px;
        bottom: -220px;
        right: -200px;
    }

    .register-scroll-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center; /* Center the whole block vertically if there's extra space */
        padding: 32px 0;
        position: relative;
        width: 100%;
        z-index: 1;
    }

    @media (min-width: 600px) {
        .register-scroll-container {
            max-width: 440px;
            margin: 0 auto;
            width: 100%;
        }
    }

    /* Stripe is absolute, centered behind the logo */
    .register-header-area {
        position: relative;
        text-align: center;
        padding-top: 12px;
        margin-bottom: 8px;
    }

    .register-stripe {
        position: absolute;
        top: 46px; /* Offset from top */
        left: 0;
        right: 0;
        height: 60px;
        background-color: #EFEBE0; /* Light cream/beige matching image */
        z-index: 1;
    }

    .register-logo-circle {
        position: relative;
        z-index: 2;
        width: 104px;
        height: 104px;
        border-radius: 50%; /* Circle */
        background: #F8F9FA;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Stylized 'P' matching login */
    .logo-p {
        font-family: 'Palatino Linotype', Palatino, 'Book Antiqua', Georgia, serif;
        font-size: 64px;
        font-style: italic;
        font-weight: 700;
        color: #1A5E35;
        line-height: 1;
        display: block;
        padding-bottom: 6px;
        padding-right: 4px;
        -webkit-text-stroke: 1.5px #6A997C;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    /* Title area */
    .register-title-area {
        text-align: center;
        margin-bottom: 14px;
        position: relative;
        z-index: 2;
    }

    .register-title {
        color: #fff;
        font-size: 19px;
        font-weight: 800;
        line-height: 1.4;
        margin: 0 0 4px 0;
    }

    .register-subtitle {
        color: #E7E2CE; /* Slightly off-white for subtitle */
        font-size: 11px;
        font-weight: 500;
        margin: 0;
    }

    /* Form Area */
    .register-content-wrap {
        position: relative;
        z-index: 2;
        padding: 0 28px 8px;
    }

    .figma-form-group {
        margin-bottom: 10px;
        text-align: left;
    }

    .figma-form-group label {
        display: block;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .figma-field-wrap {
        position: relative;
    }

    .figma-field-wrap input {
        width: 100%;
        padding: 13px 16px;
        border-radius: 8px;
        border: none;
        background: #fff !important;
        font-family: inherit;
        font-size: 13.5px;
        color: #333;
        outline: none;
        box-sizing: border-box;
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
        color: #6B7280;
        cursor: pointer;
        font-size: 14px;
    }

    /* Buttons */
    .figma-btn-row {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 4px;
        margin-bottom: 12px;
    }

    .figma-btn-register {
        flex: 1;
        padding: 14px;
        background: #FDE047;
        color: #111827;
        border: none;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: transform 0.1s;
    }

    .figma-btn-register:active {
        transform: scale(0.98);
    }

    .figma-btn-finger {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 50%;
        background: transparent;
        border: 2px solid #FDE047;
        color: #FDE047;
        font-size: 20px;
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
    .login-hint {
        text-align: center;
        color: #fff;
        font-size: 12px;
        font-weight: 500;
        margin: 0;
    }

    .login-link {
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
</script>
@endpush