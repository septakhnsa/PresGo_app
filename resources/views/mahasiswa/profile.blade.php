@extends('layouts.mahasiswa')

@section('title', 'Profile - PresGo')

@push('styles')
<style>
    html, body { height: 100%; overflow: hidden; }

    .pf-wrap {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
        background: #f1f5f9;
        overflow: hidden;
    }

    .pf-header {
        background: #1B5E35;
        padding: 40px 20px 24px;
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        z-index: 10;
    }
    .pf-header h1 {
        color: #fff;
        font-size: 22px;
        font-weight: 800;
        margin: 0;
    }
    .pf-header-inner {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .pf-tabs {
        display: flex;
        background: #1B5E35;
        justify-content: center;
        gap: 0;
        flex-shrink: 0;
        border-bottom: 2px solid #2d7a4a;
    }
    .pf-tab {
        flex: 1;
        text-align: center;
        padding: 12px 0;
        color: #a3c4b0;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: color 0.2s;
    }
    .pf-tab.active {
        color: #FFD54F;
        border-bottom: 3px solid #FFD54F;
    }

    .pf-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px 16px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .pf-body::-webkit-scrollbar { display: none; }

    .pf-avatar-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        box-shadow: 0 2px 12px #0000000f;
    }
    .pf-avatar-img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 4px solid #1B5E35;
        object-fit: cover;
        box-shadow: 0 4px 16px #1b5e3540;
    }
    .pf-avatar-name {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        text-align: center;
    }
    .pf-avatar-nim {
        font-size: 13px;
        color: #6B7280;
        font-weight: 600;
    }
    .pf-role-badge {
        background: #D1FAE5;
        color: #065F46;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 999px;
        border: 1.5px solid #6EE7B7;
    }

    .pf-info-card {
        background: #fff;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 12px #0000000f;
    }
    .pf-info-title {
        font-size: 13px;
        font-weight: 800;
        color: #1B5E35;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pf-info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid #F3F4F6;
        gap: 12px;
    }
    .pf-info-row:last-child { border-bottom: none; }
    .pf-info-label {
        font-size: 12px;
        color: #9CA3AF;
        font-weight: 600;
        flex-shrink: 0;
        min-width: 90px;
    }
    .pf-info-value {
        font-size: 13px;
        color: #111827;
        font-weight: 700;
        text-align: right;
        word-break: break-word;
    }

    .pf-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 16px;
    }
    .pf-btn {
        width: 100%;
        padding: 14px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border: none;
        text-decoration: none;
        transition: all 0.15s;
        box-sizing: border-box;
    }
    .pf-btn-primary {
        background: #1B5E35;
        color: #fff;
        box-shadow: 0 4px 14px #1b5e354d;
    }
    .pf-btn-primary:hover { background: #14532D; }
    .pf-btn-danger {
        background: #FEF2F2;
        color: #DC2626;
        border: 1.5px solid #FECACA;
    }
    .pf-btn-danger:hover { background: #FEE2E2; }

    .pf-bottom-bar {
        background: #1B5E35;
        height: 58px;
        flex-shrink: 0;
        position: relative;
        z-index: 10;
        border-radius: 22px 22px 0 0;
        box-shadow: 0 -2px 12px #0000001f;
    }
    .pf-fab {
        position: absolute;
        top: -28px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #1B5E35;
        border: 4px solid #fff;
        box-shadow: 0 4px 14px #00000033;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 22px;
        text-decoration: none;
        transition: transform 0.15s;
    }
    .pf-fab:hover { transform: translateX(-50%) scale(1.07); }

    @media (min-width: 768px) {
        .pf-header {
            padding-left: 24px;
            padding-right: 24px;
        }
        .pf-header-inner {
            max-width: 900px;
            margin: 0 auto;
        }
        .pf-tabs {
            justify-content: center;
        }
        .pf-tab {
            flex: 0 1 auto;
            min-width: 120px;
        }
        .pf-body {
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
            padding: 24px 24px 0;
        }
        .pf-bottom-bar {
            display: none;
        }
        .pf-fab {
            position: fixed;
            top: auto;
            bottom: 32px;
            right: 32px;
            left: auto;
            transform: none;
        }
        .pf-fab:hover { transform: scale(1.08); }
    }

    .status-verified   { color: #065F46; }
    .status-unverified { color: #DC2626; }
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

    {{-- HEADER --}}
    <div class="pf-header">
        <div class="pf-header-inner">
            <h1>Profile</h1>
            <a href="{{ route('mahasiswa.notifikasi') }}" style="color:#ffffffcc; font-size:20px; text-decoration:none;">
                <i class="fa-solid fa-bell"></i>
            </a>
        </div>
    </div>

    {{-- TABS --}}
    <div class="pf-tabs">
        <span class="pf-tab active">Profile</span>
        <a href="{{ route('mahasiswa.home') }}" class="pf-tab">Home</a>
        <a href="{{ route('mahasiswa.history') }}" class="pf-tab">History</a>
    </div>

    {{-- BODY --}}
    <div class="pf-body">

        <div class="pf-avatar-card">
            <img src="{{ $avatarUrl }}" class="pf-avatar-img" alt="Foto Profil">
            <div class="pf-avatar-name">{{ $userName }}</div>
            <div class="pf-avatar-nim">{{ $userNim }}</div>
            <div class="pf-role-badge"><i class="fa-solid fa-graduation-cap"></i> Mahasiswa</div>
        </div>

        <div class="pf-info-card">
            <div class="pf-info-title">
                <i class="fa-solid fa-id-card"></i> Informasi Akun
            </div>
            <div class="pf-info-row">
                <span class="pf-info-label">Nama Lengkap</span>
                <span class="pf-info-value">{{ $userName }}</span>
            </div>
            <div class="pf-info-row">
                <span class="pf-info-label">NIM</span>
                <span class="pf-info-value">{{ $userNim }}</span>
            </div>
            <div class="pf-info-row">
                <span class="pf-info-label">Email</span>
                <span class="pf-info-value">{{ $userEmail }}</span>
            </div>
            <div class="pf-info-row">
                <span class="pf-info-label">Program Studi</span>
                <span class="pf-info-value">Teknik Informatika</span>
            </div>
            <div class="pf-info-row">
                <span class="pf-info-label">Institusi</span>
                <span class="pf-info-value">STMIK Widya Utama</span>
            </div>
            <div class="pf-info-row">
                <span class="pf-info-label">Status</span>
               <span class="pf-info-value {{ $user->nim ? 'status-verified' : 'status-unverified' }}">
                    {{ $user->nim ? 'Terverifikasi' : 'Belum Diverifikasi' }}
                </span>
            </div>
        </div>

        <div class="pf-actions">
            <a href="{{ route('mahasiswa.home') }}" class="pf-btn pf-btn-primary">
                <i class="fa-solid fa-house"></i> Kembali ke Beranda
            </a>
            <form action="{{ route('mahasiswa.logout') }}" method="POST" id="logoutForm">
                @csrf
                <button type="submit" class="pf-btn pf-btn-danger" style="width:100%;">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Akun
                </button>
            </form>
        </div>

        <div style="height: 80px;"></div>
    </div>

    {{-- BOTTOM BAR --}}
    <div class="pf-bottom-bar">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="pf-fab" aria-label="Keluar">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>

</div>
@endsection