@extends('layouts.mahasiswa')

@section('title', 'Daftar Akun - PresGo')

@section('content')
<div class="app-screen">

    <div style="padding: 64px 28px 24px; text-align: center;">
        <div style="
            width: 90px;
            height: 90px;
            border-radius: 22px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        ">
            <span style="
                font-family: 'Palatino Linotype', Palatino, 'Book Antiqua', Georgia, serif;
                font-size: 62px;
                font-style: italic;
                font-weight: 700;
                color: #1B5E35;
                line-height: 1;
                display: block;
                padding-bottom: 4px;
                -webkit-text-stroke: 1px #1B5E35;
            ">P</span>
        </div>

        <h1 style="color: #fff; font-size: 22px; font-weight: 800; margin-top: 20px; line-height: 1.3;">
            Daftar akun
        </h1>
        <p style="color: #b0c4b1; font-size: 13px; font-weight: 500; margin-top: 6px;">
            Harap melengkapi data untuk mendaftar
        </p>
    </div>

    <div style="padding: 8px 28px 40px; flex: 1;">

        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation" style="margin-top:1px;"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('mahasiswa.register.submit') }}" method="POST" id="registerForm">
            @csrf

            <div class="field-group">
                <label for="name">Nama Lengkap</label>
                <div class="field-wrap">
                    <input type="text" name="name" id="name" placeholder="ketik disini.." value="{{ old('name') }}" required autofocus>
                </div>
            </div>

            <div class="field-group">
                <label for="email">Email</label>
                <div class="field-wrap">
                    <input type="email" name="email" id="email" placeholder="ketik disini.." value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="field-group" style="margin-bottom: 32px;">
                <label for="password">Password</label>
                <div class="field-wrap">
                    <input type="password" name="password" id="password" placeholder="ketik disini.." required minlength="8">
                    <button type="button" class="toggle-eye" data-target="password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; margin-bottom: 0;">Register</button>
        </form>

        <p style="text-align: center; color: #E7E2CE; font-size: 13px; font-weight: 600; margin-top: 24px;">
            Sudah punya akun? <a href="{{ route('mahasiswa.login') }}" class="link-gold">Login</a>
        </p>
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
</script>
@endpush