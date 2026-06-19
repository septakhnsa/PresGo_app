@extends('layouts.mahasiswa')

@section('title', 'Password Diperbarui - PresGo')

@section('content')
<div class="app-screen" style="background-color: #ffffff;">

    <div style="background-color: #1B5E35; height: 64px; flex-shrink: 0;"></div>

    <div style="padding: 64px 28px 32px; flex: 1; text-align: center; display: flex; flex-direction: column; align-items: center;">

        {{-- Icon lingkaran: checkmark sukses --}}
        <div style="
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #DCEEE1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
        ">
            <div style="
                width: 64px;
                height: 64px;
                border-radius: 50%;
                background-color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <i class="fa-solid fa-check" style="font-size: 28px; color: #1B5E35;"></i>
            </div>
        </div>

        <h1 style="color: #1B5E35; font-size: 19px; font-weight: 800; margin: 0 0 8px;">
            Password Diperbarui!
        </h1>
        <p style="color: #6b7280; font-size: 13px; font-weight: 500; line-height: 1.5; margin: 0 0 24px;">
            Password anda telah berhasil diperbarui.<br>
            Silahkan login dengan password baru.
        </p>

        <div style="
            background-color: #DCEEE1;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 32px;
            width: 100%;
            box-sizing: border-box;
        ">
            <p style="color: #1B5E35; font-size: 12.5px; font-weight: 600; line-height: 1.5; margin: 0;">
                Notifikasi Perubahan Password telah dikirim ke email anda.
            </p>
        </div>

        <a href="{{ route('mahasiswa.login') }}" class="btn-green" style="width: 100%; display: block; box-sizing: border-box; text-decoration: none;">
            Kembali ke Login
        </a>

    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-green {
        background-color: #1B5E35;
        color: #ffffff !important;
        border: none;
        border-radius: 10px;
        padding: 14px 0;
        font-size: 14px;
        font-weight: 700;
        text-align: center;
        transition: background-color .15s ease;
    }
    .btn-green:hover {
        background-color: #164a2a;
    }
</style>
@endpush