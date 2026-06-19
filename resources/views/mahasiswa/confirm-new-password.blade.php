@extends('layouts.mahasiswa')

@section('title', 'Perbarui Password - PresGo')

@section('content')
<div class="app-screen" style="background-color: #ffffff;">

    <div style="background-color: #1B5E35; height: 64px; flex-shrink: 0;"></div>

    <div style="padding: 56px 28px 32px; flex: 1; text-align: center; display: flex; flex-direction: column; align-items: center;">

        @if ($errors->any())
            <div class="alert alert-error" style="width: 100%; text-align: left;">
                <i class="fa-solid fa-circle-exclamation" style="margin-top:1px;"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Icon lingkaran: gembok + tanda tanya --}}
        <div style="
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background-color: #DCEEE1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            position: relative;
        ">
            <i class="fa-solid fa-lock" style="font-size: 32px; color: #1B5E35;"></i>
            <span style="position: absolute; font-size: 17px; font-weight: 800; color: #1B5E35; top: 26px;">?</span>
        </div>

        <h1 style="color: #1B5E35; font-size: 19px; font-weight: 800; margin: 0 0 8px;">
            Perbarui Password
        </h1>
        <p style="color: #6b7280; font-size: 13px; font-weight: 500; margin: 0 0 28px;">
            Password minimal 8 karakter
        </p>

        <form action="{{ route('mahasiswa.password.update') }}" method="POST" id="confirmPasswordForm" style="width: 100%; text-align: left;">
            @csrf

            <div class="field-group">
                <label for="password">Password Baru</label>
                <div class="field-wrap">
                    <input type="password" name="password" id="password" required minlength="8">
                    <button type="button" class="toggle-eye" data-target="password">
                        <i class="fa-solid fa-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="field-group" style="margin-bottom: 8px;">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="field-wrap">
                    <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8">
                    <button type="button" class="toggle-eye" data-target="password_confirmation">
                        <i class="fa-solid fa-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="strength-row">
                <div class="strength-bar">
                    <span class="seg seg-1"></span>
                    <span class="seg seg-2"></span>
                    <span class="seg seg-3"></span>
                </div>
                <span class="strength-label" id="strengthLabel">waiting..</span>
            </div>

            <button type="submit" class="btn-green" style="width: 100%; margin-top: 16px;">Simpan Password Baru</button>
        </form>

    </div>
</div>
@endsection

@push('styles')
<style>
    .app-screen .field-wrap input {
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        color: #1f2937;
    }
    .app-screen label {
        color: #1f2937;
    }

    .strength-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 4px 0 4px;
    }
    .strength-bar {
        display: flex;
        gap: 4px;
        flex: 1;
    }
    .strength-bar .seg {
        height: 5px;
        flex: 1;
        border-radius: 4px;
        background-color: #e5e7eb;
    }
    .strength-bar .seg-1.active { background-color: #1B5E35; }
    .strength-bar .seg-2.active { background-color: #1B5E35; }
    .strength-bar .seg-3.active { background-color: #F2C744; }
    .strength-label {
        font-size: 12px;
        font-weight: 600;
        color: #9ca3af;
        white-space: nowrap;
    }

    .btn-green {
        background-color: #1B5E35;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        padding: 14px 0;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color .15s ease;
    }
    .btn-green:hover {
        background-color: #164a2a;
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
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });

    var passwordInput   = document.getElementById('password');
    var seg1             = document.querySelector('.seg-1');
    var seg2             = document.querySelector('.seg-2');
    var seg3             = document.querySelector('.seg-3');
    var strengthLabel    = document.getElementById('strengthLabel');

    passwordInput.addEventListener('input', function () {
        var val = this.value;
        seg1.classList.remove('active');
        seg2.classList.remove('active');
        seg3.classList.remove('active');

        if (val.length === 0) {
            strengthLabel.textContent = 'waiting..';
            return;
        }
        if (val.length < 8) {
            seg1.classList.add('active');
            strengthLabel.textContent = 'lemah';
        } else if (val.length < 12) {
            seg1.classList.add('active');
            seg2.classList.add('active');
            strengthLabel.textContent = 'cukup';
        } else {
            seg1.classList.add('active');
            seg2.classList.add('active');
            seg3.classList.add('active');
            strengthLabel.textContent = 'kuat';
        }
    });
</script>
@endpush