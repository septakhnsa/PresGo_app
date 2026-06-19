@extends('layouts.mahasiswa')

@section('title', 'Lupa Password - PresGo')

@section('content')
<div class="app-screen" style="background-color: #ffffff;">

    {{-- Header bar hijau tua, polos seperti pada mockup --}}
    <div style="background-color: #1B5E35; height: 64px; flex-shrink: 0;"></div>

    <div style="padding: 56px 28px 32px; flex: 1; text-align: center; display: flex; flex-direction: column; align-items: center;">

        @if (session('success'))
            <div class="alert alert-success" style="width: 100%; text-align: left;">
                <i class="fa-solid fa-circle-check" style="margin-top:1px;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

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
            <span style="
                position: absolute;
                font-size: 17px;
                font-weight: 800;
                color: #1B5E35;
                top: 26px;
            ">?</span>
        </div>

        <h1 style="color: #1B5E35; font-size: 19px; font-weight: 800; margin: 0 0 8px;">
            Lupa Password?
        </h1>
        <p style="color: #6b7280; font-size: 13px; font-weight: 500; line-height: 1.5; margin: 0 0 28px;">
            Masukkan email aktif anda.<br>
            Akan kami kirimkan kode OTP untuk verifikasi.
        </p>

        <form action="{{ route('mahasiswa.forgot-password.submit') }}" method="POST" id="forgotForm" style="width: 100%; text-align: left;">
            @csrf

            <div class="field-group">
                <label for="email" style="color: #1f2937;">Email</label>
                <div class="field-wrap">
                    <input type="email" name="email" id="email" placeholder="email@mhs.kampus.ac.id"
                        value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn-green" style="width: 100%;">Kirim Kode OTP</button>
        </form>

    </div>
</div>
@endsection

@push('styles')
<style>
    /* Input style versi terang, dipakai khusus untuk halaman forgot password
       supaya sesuai dengan tampilan mockup (background putih, border abu) */
    .app-screen .field-wrap input {
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        color: #1f2937;
    }
    .app-screen .field-wrap input::placeholder {
        color: #9ca3af;
    }
    .app-screen label {
        color: #1f2937;
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