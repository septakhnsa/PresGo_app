@extends('layouts.mahasiswa')

@section('title', 'Login - PresGo')

@section('content')
<div class="app-screen">
    <div style="padding: 56px 28px 32px; text-align: center;">
        <div style="
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        ">
            <span style="
                font-family: 'Palatino Linotype', Palatino, 'Book Antiqua', Georgia, serif;
                font-size: 56px;
                font-style: italic;
                font-weight: 700;
                color: #1B5E35;
                line-height: 1;
                display: block;
                padding-bottom: 4px;
                -webkit-text-stroke: 1px #1B5E35;
            ">P</span>
        </div>
        <h1 style="color: #fff; font-size: 19px; font-weight: 800; margin-top: 18px; line-height: 1.4;">
            Presensi Online<br>Akademika Mahasiswa
        </h1>
    </div>

    <div style="padding: 0 28px 32px; flex: 1;">

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
            <div class="field-group">
                <label for="login">NIM</label>
                <div class="field-wrap">
                    <input type="text" name="login" id="login" placeholder="NIM"
                        value="{{ old('login', $rememberedLogin ?? '') }}" required autofocus>
                </div>
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <div class="field-wrap">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <button type="button" class="toggle-eye" data-target="password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex; align-items:center; justify-content: space-between; margin-bottom: 24px;">
                <label class="checkbox-row">
                    <input type="checkbox" name="ingat_nim" value="1" {{ $rememberedLogin ?? false ? 'checked' : '' }}>
                    Ingat NIM
                </label>
               <a href="{{ route('mahasiswa.forgot-password') }}" class="link-muted" style="font-size: 12.5px;">Forgot Password?</a>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn-gold">LOGIN</button>
                <button type="button" class="btn-finger" id="openFingerBtn" aria-label="Login dengan sidik jari">
                    <i class="fa-solid fa-fingerprint"></i>
                </button>
            </div>
        </form>

        <p style="text-align: center; color: #E7E2CE; font-size: 13px; font-weight: 600; margin-top: 22px;">
            Belum punya akun? <a href="{{ route('mahasiswa.register') }}" class="link-gold">Daftar</a>
        </p>
    </div>

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

@push('scripts')
<script>
    document.querySelectorAll('.toggle-eye').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.target);
            var icon = btn.querySelector('i');
            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
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