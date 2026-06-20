@extends('layouts.mahasiswa')

@section('title', 'Profile - PresGo')

@push('styles')
<style>
html, body {
    height: auto !important;
    min-height: auto !important;
}

    .pf-wrap {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        width: 100%;
        background: #ffffff;
    }

    /* ===== TOPBAR (header + tabs, sticky di atas) ===== */
    .pf-topbar {
        position: sticky;
        top: 0;
        z-index: 20;
    }

    .pf-header {
        background: #1B5E35;
        padding: 14px 20px 0;
        display: flex;
        justify-content: flex-end;
    }
    .pf-header a {
        color: #ffffffcc;
        font-size: 18px;
        text-decoration: none;
    }

    .pf-tabs {
        display: flex;
        background: #1B5E35;
        justify-content: center;
        gap: 0;
        padding: 0 20px;
    }
    .pf-tab {
        flex: 1;
        text-align: center;
        padding: 10px 0 14px;
        color: #a3c4b0;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: color 0.2s;
    }
    .pf-tab.active {
        color: #ffffff;
        border-bottom: 3px solid #FFD54F;
    }

    /* ===== BODY (mengalir normal, ikut scroll halaman) ===== */
    .pf-id-panel {
        background: #FFD54F;
        padding: 18px 10px 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        border-radius: 0 0 65px 65px;
    }
    .pf-avatar-img {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        border: 3px solid #ffffff;
        object-fit: cover;
        box-shadow: 0 4px 14px #00000022;
        margin-bottom: 12px;
    }
    .pf-avatar-name {
        font-size: 19px;
        font-weight: 800;
        color: #1B3A24;
        margin-bottom: 2px;
    }
    .pf-avatar-nim {
        font-size: 12px;
        color: #4d3f12;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .pf-avatar-meta {
        font-size: 11.5px;
        color: #4d3f12;
        font-weight: 600;
        line-height: 1.5;
        opacity: 0.85;
        max-width: 260px;
    }

    .pf-content {
        padding: 22px 20px 0;
    }

    .pf-section { margin-bottom: 26px; }
    .pf-section-title {
        font-size: 13px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 12px;
    }
    .pf-section-divider {
        height: 2px;
        background: #1B5E35;
        border-radius: 2px;
        margin-top: 14px;
        opacity: 0.85;
    }

    .pf-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 7px 0;
        gap: 12px;
    }
    .pf-row-label {
        font-size: 12.5px;
        color: #6B7280;
        font-weight: 600;
        flex-shrink: 0;
        min-width: 110px;
    }
    .pf-row-value {
        font-size: 12.5px;
        color: #111827;
        font-weight: 700;
        text-align: right;
        word-break: break-word;
    }

    .status-verified   { color: #065F46; }
    .status-unverified { color: #DC2626; }

    /* ===== BOTTOM BAR + FAB (akhir konten, bukan fixed) ===== */
    .pf-bottom-bar {
        background: #1B5E35;
        height: 59px;
        margin-top: auto;
        position: relative;
        border-radius: 22px 22px 0 0;
    }
    .pf-fab {
        position: absolute;
        top: -26px;
        left: 50%;
        transform: translateX(-50%);
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: #DC2626;
        border: 4px solid #fff;
        box-shadow: 0 4px 14px #00000033;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        text-decoration: none;
        transition: transform 0.15s;
    }
    .pf-fab:hover { transform: translateX(-50%) scale(1.07); }
    .pf-content {
        padding: 22px 20px 32px;
    }

    /* ===== RESPONSIVE (tablet/desktop) ===== */
    @media (min-width: 768px) {
        .pf-tabs { padding: 0; justify-content: center; }
        .pf-tab { flex: 0 1 auto; min-width: 120px; }

        .pf-id-panel { padding-bottom: 32px; }

        .pf-content {
            max-width: 640px;
            margin: 0 auto;
            width: 100%;
            padding: 28px 24px 0;
        }
    }
</style>
@endpush

@section('content')
@php
    $user      = Auth::user();
    $userName  = $user->name;
    $userNim   = $user->nim ?? 'Belum Diverifikasi';
    $userEmail = $user->email;
    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=1B5E35&color=fff&bold=true&size=128';
@endphp

<div class="pf-wrap">

    {{-- TOPBAR: header + tabs, sticky --}}
    <div class="pf-topbar">
        <div class="pf-header">
            <a href="{{ route('mahasiswa.notifikasi') }}" aria-label="Notifikasi">
                <i class="fa-solid fa-bell"></i>
            </a>
        </div>
        <div class="pf-tabs">
            <span class="pf-tab active">Profile</span>
            <a href="{{ route('mahasiswa.home') }}" class="pf-tab">Home</a>
            <a href="{{ route('mahasiswa.history') }}" class="pf-tab">History</a>
        </div>
    </div>

    {{-- PANEL IDENTITAS --}}
    <div class="pf-id-panel">
        <img src="{{ $avatarUrl }}" class="pf-avatar-img" alt="Foto Profil">
        <div class="pf-avatar-name">{{ $userName }}</div>
        <div class="pf-avatar-nim">{{ $userNim }}</div>
        <div class="pf-avatar-meta">Mahasiswa Program Studi Teknik Informatika<br>STMIK Widya Utama</div>
    </div>

    <div class="pf-content">

        <div class="pf-section">
            <div class="pf-section-title">Informasi Akun</div>

            <div class="pf-row">
                <span class="pf-row-label">Nama Lengkap</span>
                <span class="pf-row-value">{{ $userName }}</span>
            </div>
            <div class="pf-row">
                <span class="pf-row-label">NIM</span>
                <span class="pf-row-value">{{ $userNim }}</span>
            </div>
            <div class="pf-row">
                <span class="pf-row-label">Email</span>
                <span class="pf-row-value">{{ $userEmail }}</span>
            </div>
            <div class="pf-row">
                <span class="pf-row-label">Program Studi</span>
                <span class="pf-row-value">Teknik Informatika</span>
            </div>
            <div class="pf-row">
                <span class="pf-row-label">Institusi</span>
                <span class="pf-row-value">STMIK Widya Utama</span>
            </div>
            <div class="pf-row">
                <span class="pf-row-label">Status</span>
                <span class="pf-row-value {{ $user->nim ? 'status-verified' : 'status-unverified' }}">
                    {{ $user->nim ? 'Terverifikasi' : 'Belum Diverifikasi' }}
                </span>
            </div>

            <div class="pf-section-divider"></div>
        </div>

        <form action="{{ route('mahasiswa.logout') }}" method="POST" id="logoutForm">
            @csrf
        </form>
    </div>

    {{-- BOTTOM BAR: bagian akhir konten, bukan fixed --}}
    <div class="pf-bottom-bar">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="pf-fab" aria-label="Keluar">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>

</div>
@endsection