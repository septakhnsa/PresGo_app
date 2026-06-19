

@extends('layouts.mahasiswa')

@section('title', 'Beranda - PresGo')

@section('content')
<div class="app-screen" style="align-items: center; justify-content: center; text-align: center; padding: 40px 28px;">
    <div class="logo-circle">
        <span>P</span>
    </div>

    <h1 style="color: #fff; font-size: 20px; font-weight: 800; margin-top: 22px;">
        Halo, {{ Auth::user()->name }} 👋
    </h1>

    @if (Auth::user()->nim)
        <span style="display:inline-block; margin-top: 10px; background: rgba(255,213,79,0.18); color: var(--gold); border: 1px solid rgba(255,213,79,0.5); padding: 5px 14px; border-radius: 999px; font-size: 12px; font-weight: 700;">
            <i class="fa-solid fa-circle-check"></i> Terverifikasi &middot; NIM {{ Auth::user()->nim }}
        </span>
    @else
        <span style="display:inline-block; margin-top: 10px; background: rgba(229,72,72,0.15); color: #FFD4D4; border: 1px solid rgba(229,72,72,0.5); padding: 5px 14px; border-radius: 999px; font-size: 12px; font-weight: 700;">
            <i class="fa-solid fa-hourglass-half"></i> Menunggu verifikasi NIM oleh admin
        </span>
    @endif

    <p style="color: #E7E2CE; font-size: 13.5px; font-weight: 500; margin-top: 22px; line-height: 1.6;">
        Gunakan aplikasi mobile PresGo untuk melakukan presensi kuliah, lihat jadwal, dan riwayat kehadiranmu.
    </p>

    <form action="{{ route('mahasiswa.logout') }}" method="POST" style="margin-top: 28px; width: 100%;">
        @csrf
        <button type="submit" class="btn-gold" style="width: 100%;">Logout</button>
    </form>
</div>
@endsection
