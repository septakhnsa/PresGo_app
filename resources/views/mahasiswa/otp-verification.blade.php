@extends('layouts.mahasiswa')

@section('title', 'Verifikasi OTP - PresGo')

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

        {{-- Icon lingkaran: envelope --}}
        <div style="
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background-color: #DCEEE1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        ">
            <i class="fa-solid fa-envelope" style="font-size: 32px; color: #1B5E35;"></i>
        </div>

        <h1 style="color: #1B5E35; font-size: 19px; font-weight: 800; margin: 0 0 8px;">
            Verifikasi OTP
        </h1>
        <p style="color: #6b7280; font-size: 13px; font-weight: 500; line-height: 1.5; margin: 0 0 4px;">
            Silahkan Cek Email Anda.<br>
            Kode OTP 5 digit telah dikirim ke
        </p>
        <p style="color: #1B5E35; font-size: 13px; font-weight: 700; margin: 0 0 24px;">
            {{ $maskedEmail ?? 'stu***@mhs.kampus.ac.id' }}
        </p>

        <form action="{{ route('mahasiswa.otp.verify') }}" method="POST" id="otpForm" style="width: 100%;">
            @csrf

            <div class="otp-row">
                <input type="text" name="otp_1" maxlength="1" inputmode="numeric" class="otp-box" autofocus>
                <input type="text" name="otp_2" maxlength="1" inputmode="numeric" class="otp-box">
                <input type="text" name="otp_3" maxlength="1" inputmode="numeric" class="otp-box">
                <input type="text" name="otp_4" maxlength="1" inputmode="numeric" class="otp-box">
                <input type="text" name="otp_5" maxlength="1" inputmode="numeric" class="otp-box">
            </div>

            <button type="submit" class="btn-green" style="width: 100%; margin-top: 28px;">Kirim Kode OTP</button>
        </form>

        <p style="color: #6b7280; font-size: 13px; font-weight: 500; margin-top: 20px;">
            Belum terima kode?
            <a href="{{ route('mahasiswa.otp.resend') }}" style="color: #DC2626; font-weight: 700; text-decoration: none;">Kirim ulang</a>
        </p>

    </div>
</div>
@endsection

@push('styles')
<style>
    .otp-row {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .otp-box {
        width: 48px;
        height: 56px;
        text-align: center;
        font-size: 22px;
        font-weight: 800;
        color: #1B5E35;
        border: 1.5px solid #1B5E35;
        border-radius: 10px;
        background-color: #ffffff;
        outline: none;
    }
    .otp-box:focus {
        border-color: #164a2a;
        box-shadow: 0 0 0 2px rgba(27, 94, 53, 0.15);
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
    });
</script>
@endpush