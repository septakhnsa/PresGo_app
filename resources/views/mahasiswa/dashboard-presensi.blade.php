@extends('layouts.mahasiswa')

@section('title', 'Dashboard Presensi - PresGo')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    html, body {
        height: 100%;
        overflow: hidden;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    /* ════════════════════════════════
       WRAPPER
    ════════════════════════════════ */
    .dp-wrap {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
        background: #f4f0d7;
        overflow: hidden;
    }

    /* ════════════════════════════════
       HEADER — elegan, bersih
    ════════════════════════════════ */
    .dp-header {
        background: #1B5E35;
        padding: 32px 20px 20px;
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 10;
    }
    .dp-header::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: rgba(255,255,255,0.08);
    }
    .dp-header-name {
        color: #fff;
        font-size: 17px;
        font-weight: 700;
        letter-spacing: -0.2px;
        margin-bottom: 3px;
    }
    .dp-header-nim {
        color: rgba(255,255,255,0.50);
        font-size: 12px;
        font-weight: 500;
        letter-spacing: 0.4px;
    }
    .dp-header-right {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .dp-menu-link {
        color: rgba(255,255,255,0.75);
        font-size: 18px;
        text-decoration: none;
        transition: color 0.2s;
    }
    .dp-menu-link:hover { color: #fff; }
    .dp-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.25);
        object-fit: cover;
    }

    /* ════════════════════════════════
       SCROLLABLE BODY
    ════════════════════════════════ */
    .dp-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px 14px 0;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .dp-body::-webkit-scrollbar { display: none; }

    /* ── Notif Pill ── */
    .dp-notif-pill {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .dp-notif-icon {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: #1B5E35;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 12px; flex-shrink: 0;
    }
    .dp-notif-text {
        flex: 1;
        font-size: 11px;
        font-weight: 500;
        color: #374151;
        line-height: 1.5;
    }
    .dp-notif-text strong { color: #1B5E35; font-weight: 700; }
    .dp-notif-red { color: #B91C1C; font-weight: 600; }
    .dp-notif-close {
        background: none; border: none; color: #9CA3AF;
        font-size: 15px; cursor: pointer; flex-shrink: 0;
        transition: color 0.15s;
    }
    .dp-notif-close:hover { color: #DC2626; }

    /* ════════════════════════════════
       REKAP KEHADIRAN — institutional
    ════════════════════════════════ */
    .dp-rekap {
        background: #1B5E35;
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 16px;
    }
    .dp-rekap-title {
        color: rgba(255,255,255,0.65);
        font-size: 11px;
        font-weight: 600;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 14px;
    }
    .dp-rekap-row {
        display: flex;
        gap: 8px;
    }
    .dp-rekap-box {
        flex: 1;
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 10px;
        padding: 12px 6px;
        text-align: center;
    }
    .dp-rekap-num {
        font-size: 22px;
        font-weight: 700;
        display: block;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }
    .dp-rekap-lbl {
        font-size: 10px;
        font-weight: 500;
        display: block;
        margin-top: 4px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .dp-rekap-box.kehadiran .dp-rekap-num { color: #6EE7B7; }
    .dp-rekap-box.kehadiran .dp-rekap-lbl { color: rgba(255,255,255,0.45); }
    .dp-rekap-box.hadir    .dp-rekap-num { color: #93C5FD; }
    .dp-rekap-box.hadir    .dp-rekap-lbl { color: rgba(255,255,255,0.45); }
    .dp-rekap-box.absen    .dp-rekap-num { color: #FCA5A5; }
    .dp-rekap-box.absen    .dp-rekap-lbl { color: rgba(255,255,255,0.45); }

    /* ════════════════════════════════
       JADWAL HARI INI
    ════════════════════════════════ */
    .dp-jadwal-title {
        color: #374151;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 10px;
        padding-left: 2px;
    }
    .dp-jadwal-card {
        background: #fff;
        border-radius: 12px;
        padding: 0;
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        border: 1px solid #E5E7EB;
        border-left: 3px solid #1B5E35;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    /* Baris atas card jadwal */
    .dp-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 13px 13px;
        gap: 10px;
    }
    .dp-card-top a.dp-card-info {
        text-decoration: none;
        color: inherit;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
    }
    .dp-mk {
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        letter-spacing: -0.1px;
    }
    .dp-jadwal-meta {
        font-size: 11.5px;
        font-weight: 500;
        color: #6B7280;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
    }
    .dp-jadwal-meta i { opacity: 0.55; font-size: 10.5px; }
    
    .dp-card-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .dp-status-pill {
        font-size: 10px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
        white-space: nowrap;
        letter-spacing: 0.2px;
    }
    .dp-status-pill.belum {
        background: #FEF9C3;
        color: #854D0E;
        border: 1px solid #FDE68A;
    }
    .dp-status-pill.hadir {
        background: #DCFCE7;
        color: #166534;
        border: 1px solid #BBF7D0;
    }

    /* Photo trigger button */
    .dp-photo-trigger-btn {
        width: 32px;
        height: 32px;
        background: #F9FAFB;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        font-size: 13px;
        color: #4B5563;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }
    .dp-photo-trigger-btn:hover {
        background: #F0FDF4;
        border-color: #A7F3D0;
        color: #1B5E35;
    }

    /* ════════════════════════════════
       GPS TRACKING PANEL — data table style
    ════════════════════════════════ */
    .dp-gps-panel {
        background: #F9FAFB;
        border-top: 1px solid #E5E7EB;
        padding: 11px 13px 12px;
        position: relative;
    }
    .dp-gps-panel::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, #1B5E35, #4ADE80, #1B5E35);
        background-size: 200% 100%;
        animation: gpsShimmer 3s linear infinite;
    }
    @keyframes gpsShimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    /* Header GPS */
    .dp-gps-header {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 9px;
    }
    .dp-gps-badge {
        display: flex;
        align-items: center;
        gap: 4px;
        background: #1B5E35;
        color: #6EE7B7;
        font-size: 9px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 4px;
        letter-spacing: 0.8px;
        text-transform: uppercase;
    }
    .dp-gps-dot {
        width: 5px;
        height: 5px;
        background: #4ADE80;
        border-radius: 50%;
        animation: gpsPulse 2s ease-in-out infinite;
        flex-shrink: 0;
    }
    @keyframes gpsPulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.3; }
    }
    .dp-gps-subtitle {
        font-size: 10px;
        font-weight: 500;
        color: #9CA3AF;
        flex: 1;
        letter-spacing: 0.2px;
    }
    /* Grid info */
    .dp-gps-grid {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }
    .dp-gps-row {
        display: flex;
        align-items: flex-start;
        gap: 9px;
    }
    .dp-gps-icon {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: #F0FDF4;
        border: 1px solid #BBF7D0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1B5E35;
        font-size: 11px;
        flex-shrink: 0;
    }
    .dp-gps-icon.gray {
        background: #F3F4F6;
        border-color: #E5E7EB;
        color: #9CA3AF;
    }
    .dp-gps-info { flex: 1; min-width: 0; }
    .dp-gps-info-label {
        font-size: 9px;
        font-weight: 600;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        margin-bottom: 2px;
    }
    .dp-gps-info-value {
        font-size: 11.5px;
        font-weight: 600;
        color: #1F2937;
        line-height: 1.4;
        word-break: break-word;
    }
    .dp-gps-coords {
        font-size: 9.5px;
        font-weight: 500;
        color: #9CA3AF;
        font-family: 'Courier New', monospace;
        margin-top: 2px;
        letter-spacing: 0.3px;
    }
    .dp-gps-loading {
        font-size: 10.5px;
        color: #9CA3AF;
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .dp-gps-loading i {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    /* ════════════════════════════════
       BOTTOM BAR + FAB
    ════════════════════════════════ */
    .dp-bottom-bar {
        background: #1B5E35;
        height: 56px;
        flex-shrink: 0;
        position: relative;
        z-index: 10;
    }
    .dp-fab {
        position: absolute;
        top: -24px;
        left: 50%;
        transform: translateX(-50%);
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #1B5E35;
        border: 3px solid #8FB090;
        box-shadow: 0 4px 16px rgba(26,61,43,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        text-decoration: none;
        z-index: 11;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .dp-fab:hover {
        transform: translateX(-50%) scale(1.06);
        box-shadow: 0 6px 20px rgba(26,61,43,0.45);
    }

    /* ════════════════════════════════
       MODAL PENGINGAT — clean, elegan
    ════════════════════════════════ */
    .dp-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 999;
        padding: 20px;
    }
    .dp-modal-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 24px 60px rgba(0,0,0,0.18);
        width: 100%;
        max-width: 340px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: dpModalPop 0.28s ease-out;
    }
    @keyframes dpModalPop {
        from { transform: scale(0.95) translateY(12px); opacity: 0; }
        to   { transform: scale(1) translateY(0); opacity: 1; }
    }
    .dp-modal-header {
        background: #1B5E35;
        padding: 22px 24px;
        text-align: center;
    }
    .dp-modal-bell-wrap {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.20);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }
    .dp-modal-bell-wrap i {
        color: #6EE7B7;
        font-size: 20px;
    }
    .dp-modal-title {
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.2px;
    }
    .dp-modal-subtitle {
        color: rgba(255,255,255,0.5);
        font-size: 11.5px;
        font-weight: 500;
        margin-top: 4px;
        letter-spacing: 0.2px;
    }
    .dp-modal-body {
        padding: 20px 20px 22px;
        text-align: center;
    }
    .dp-modal-text {
        color: #4B5563;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.7;
        margin-bottom: 20px;
    }
    .dp-modal-highlight {
        color: #1B5E35;
        font-weight: 700;
    }
    .dp-modal-btn-confirm {
        display: block;
        background: #1B5E35;
        color: #fff;
        padding: 13px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13.5px;
        text-decoration: none;
        border: none;
        transition: background 0.2s;
        margin-bottom: 10px;
    }
    .dp-modal-btn-confirm:hover { background: #14532D; color: #fff; }
    .dp-modal-btn-cancel {
        display: block;
        width: 100%;
        background: #F9FAFB;
        color: #6B7280;
        padding: 13px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13.5px;
        border: 1px solid #E5E7EB;
        cursor: pointer;
        transition: background 0.2s;
    }
    .dp-modal-btn-cancel:hover { background: #F3F4F6; }

    /* ════════════════════════════════
       PHOTO PREVIEW MODAL
    ════════════════════════════════ */
    .dp-photo-modal {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 24px;
    }
    .dp-photo-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 20px 50px rgba(0,0,0,0.20);
        width: 100%;
        max-width: 340px;
        padding: 20px;
        text-align: center;
        animation: dpModalPop 0.28s ease-out;
    }
    .dp-photo-title {
        font-weight: 700;
        font-size: 15px;
        margin-bottom: 14px;
        color: #1B5E35;
        letter-spacing: -0.1px;
    }
    .dp-photo-img {
        width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
    }
    .dp-photo-close-btn {
        margin-top: 16px;
        width: 100%;
        background: #1B5E35;
        color: #fff;
        font-weight: 600;
        font-size: 13.5px;
        padding: 13px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .dp-photo-close-btn:hover { background: #14532D; }
</style>

@endpush


@section('content')
@php
    $userName  = $user->name ?? 'Septa Khoerun Nisa';
    $userNim   = $user->nim  ?? 'STI202303686';
    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=1B5E35&color=fff&bold=true&size=128';

    $kehadiranPersen = $kehadiranPersen ?? 84;
    $totalHadir      = $totalHadir ?? 17;
    $totalAbsen      = $totalAbsen ?? 2;

    $jadwalHariIni = $jadwalHariIni ?? [
        ['mata_kuliah' => 'Mobile Programming', 'jam' => '10.00 – 12.00', 'ruangan' => 'KBR 2.3', 'status' => 'Belum'],
        ['mata_kuliah' => 'Web Programming',    'jam' => '09.30 – 11.30', 'ruangan' => 'KBR 2.3', 'status' => 'Hadir'],
    ];
@endphp

<div class="dp-wrap">

    {{-- ── TOP GREEN HEADER ── --}}
    <div class="dp-header">
        <div style="display: flex; align-items: center; gap: 14px;">
            <a href="{{ route('mahasiswa.home') }}" style="color: #fff; font-size: 20px; text-decoration: none;">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <div>
                <div class="dp-header-name">{{ $userName }}</div>
                <div class="dp-header-nim">{{ $userNim }}</div>
            </div>
        </div>
        <div class="dp-header-right">
            <a href="{{ route('mahasiswa.notifikasi') }}" class="dp-menu-link">
                <i class="fa-solid fa-bell"></i>
            </a>
            <img src="{{ $avatarUrl }}" class="dp-avatar" alt="{{ $userName }}">
        </div>
    </div>

    {{-- ── SCROLLABLE BODY ── --}}
    <div class="dp-body">

        {{-- Notification pill --}}
        @if (isset($notifJadwal) && $notifJadwal)
        <div class="dp-notif-pill" id="dpNotifPill" data-href="{{ route('mahasiswa.notifikasi') }}" style="cursor: pointer;">
            <div class="dp-notif-icon"><i class="fa-solid fa-bell"></i></div>
            <div class="dp-notif-text">
                <strong>PresGo - Baru saja</strong>
                &nbsp;Pengingat Presensi | {{ $notifJadwal['mata_kuliah'] }}
                <span class="dp-notif-red">15 Menit lagi &rsaquo;</span>
            </div>
            <button type="button" class="dp-notif-close" id="dpNotifClose">
                <i class="fa-regular fa-circle-xmark"></i>
            </button>
        </div>
        @endif

        {{-- Rekap Kehadiran --}}
        <div class="dp-rekap">
            <div class="dp-rekap-title">Rekap Kehadiran Bulan ini</div>
            <div class="dp-rekap-row">
                <div class="dp-rekap-box kehadiran">
                    <span class="dp-rekap-num">{{ $kehadiranPersen }}%</span>
                    <span class="dp-rekap-lbl">Kehadiran</span>
                </div>
                <div class="dp-rekap-box hadir">
                    <span class="dp-rekap-num">{{ $totalHadir }}</span>
                    <span class="dp-rekap-lbl">Hadir</span>
                </div>
                <div class="dp-rekap-box absen">
                    <span class="dp-rekap-num">{{ $totalAbsen }}</span>
                    <span class="dp-rekap-lbl">Absen</span>
                </div>
            </div>
        </div>

        {{-- Jadwal Hari Ini --}}
        <div class="dp-jadwal-title">Jadwal Hari Ini</div>

        @foreach ($jadwalHariIni as $jadwal)
            <div class="dp-jadwal-card">

                {{-- ── Baris utama jadwal ── --}}
                <div class="dp-card-top">
                    <a href="{{ $jadwal['status'] === 'Hadir' ? '#' : route('mahasiswa.presensi.camera', ['jadwal_id' => $jadwal['id']]) }}" class="dp-card-info">
                        <div class="dp-mk">{{ $jadwal['mata_kuliah'] }}</div>
                        <div class="dp-jadwal-meta">
                            <i class="fa-solid fa-user-tie"></i>
                            <span>{{ $jadwal['dosen'] }}</span>
                        </div>
                        <div class="dp-jadwal-meta">
                            <i class="fa-regular fa-clock"></i>
                            <span>{{ $jadwal['jam'] }}</span>
                            <span style="color:#D1D5DB;">&bull;</span>
                            <span>{{ $jadwal['ruangan'] }}</span>
                        </div>
                    </a>
                    <div class="dp-card-actions">
                        <span class="dp-status-pill {{ $jadwal['status'] === 'Hadir' ? 'hadir' : 'belum' }}">
                            {{ $jadwal['status'] }}
                        </span>
                        @if ($jadwal['status'] === 'Hadir' && isset($jadwal['foto_wajah']) && $jadwal['foto_wajah'])
                            <button type="button"
                                    class="dp-photo-trigger-btn"
                                    data-photo-url="{{ asset('storage/' . $jadwal['foto_wajah']) }}"
                                    data-mk-name="{{ $jadwal['mata_kuliah'] }}">
                                <i class="fa-solid fa-image"></i>
                            </button>
                        @endif
                    </div>
                </div>

                {{-- ── GPS Panel (hanya jika Hadir) ── --}}
                @if ($jadwal['status'] === 'Hadir')
                <div class="dp-gps-panel">

                    {{-- Header --}}
                    <div class="dp-gps-header">
                        <div class="dp-gps-badge">
                            <span class="dp-gps-dot"></span>
                            GPS TRACKING
                        </div>
                        <span class="dp-gps-subtitle">Data Tercatat Otomatis</span>
                    </div>

                    {{-- Grid info --}}
                    <div class="dp-gps-grid">

                        {{-- Waktu Submit --}}
                        <div class="dp-gps-row">
                            <div class="dp-gps-icon">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                            <div class="dp-gps-info">
                                <div class="dp-gps-info-label">Waktu Submit</div>
                                <div class="dp-gps-info-value">
                                    @if ($jadwal['waktu_submit'])
                                        {{ $jadwal['waktu_submit'] }}
                                    @else
                                        <span style="color:#9CA3AF;font-weight:600;">Tidak tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Lokasi GPS --}}
                        @if ($jadwal['latitude'] && $jadwal['longitude'])
                        <div class="dp-gps-row"
                             id="gps-row-{{ $jadwal['id'] }}"
                             data-gps-lat="{{ $jadwal['latitude'] }}"
                             data-gps-lng="{{ $jadwal['longitude'] }}">
                            <div class="dp-gps-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="dp-gps-info">
                                <div class="dp-gps-info-label">Lokasi Presensi</div>
                                <div class="dp-gps-info-value" id="gps-address-{{ $jadwal['id'] }}">
                                    <span class="dp-gps-loading">
                                        <i class="fa-solid fa-spinner"></i> Memuat alamat...
                                    </span>
                                </div>
                                <div class="dp-gps-coords">
                                    {{ number_format($jadwal['latitude'], 6) }},
                                    {{ number_format($jadwal['longitude'], 6) }}
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="dp-gps-row">
                            <div class="dp-gps-icon gray">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="dp-gps-info">
                                <div class="dp-gps-info-label">Lokasi Presensi</div>
                                <div class="dp-gps-info-value" style="color:#9CA3AF;font-weight:600;">GPS tidak tersedia</div>
                            </div>
                        </div>
                        @endif

                    </div>{{-- /dp-gps-grid --}}
                </div>{{-- /dp-gps-panel --}}
                @endif

            </div>{{-- /dp-jadwal-card --}}

        @endforeach

        <div style="height: 90px;"></div>
    </div>

    {{-- ── BOTTOM BAR + FAB ── --}}
    <div class="dp-bottom-bar">
        <a href="{{ route('mahasiswa.presensi.camera') }}" class="dp-fab" aria-label="Presensi">
            <i class="fa-solid fa-camera"></i>
        </a>
    </div>

</div>

<!-- Modal Pengingat Presensi -->
@if (isset($notifJadwal) && $notifJadwal)
<div id="reminderModal" class="dp-modal-overlay">
    <div class="dp-modal-card">
        <div class="dp-modal-header">
            <div class="dp-modal-bell-wrap">
                <i class="fa-solid fa-bell animate-bounce"></i>
            </div>
            <h3 class="dp-modal-title">Pengingat Presensi</h3>
            <p class="dp-modal-subtitle">Kelas akan segera dimulai!</p>
        </div>
        <div class="dp-modal-body">
            <p class="dp-modal-text">
                Kamu memiliki jadwal kuliah <span class="dp-modal-highlight">{{ $notifJadwal['mata_kuliah'] }}</span> bersama dosen <span class="dp-modal-highlight">{{ $notifJadwal['dosen'] }}</span> pada jam <span class="dp-modal-highlight">{{ $notifJadwal['jam'] }}</span> di ruangan <span class="dp-modal-highlight">{{ $notifJadwal['ruangan'] }}</span>.
            </p>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a href="{{ route('mahasiswa.presensi.camera', ['jadwal_id' => $notifJadwal['id']]) }}" class="dp-modal-btn-confirm">
                    Presensi Sekarang
                </a>
                <button type="button" onclick="closeReminderModal()" class="dp-modal-btn-cancel">
                    Abaikan
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal Preview Foto Presensi -->
<div id="photoPreviewModal" class="dp-photo-modal">
    <div class="dp-photo-card">
        <div class="dp-photo-title" id="photoModalTitle">Hasil Presensi</div>
        <img id="photoModalImg" src="" class="dp-photo-img" alt="Foto Presensi">
        <button type="button" onclick="closePhotoModal()" class="dp-photo-close-btn">
            Tutup Preview
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openReminderModal() {
        const modal = document.getElementById('reminderModal');
        if (modal) {
            modal.style.setProperty('display', 'flex', 'important');
        }
    }

    function closeReminderModal() {
        const modal = document.getElementById('reminderModal');
        if (modal) {
            modal.style.setProperty('display', 'none', 'important');
        }
    }

    // Tampilkan modal foto presensi
    function showPhotoModal(url, mkName) {
        document.getElementById('photoModalImg').src = url;
        document.getElementById('photoModalTitle').innerText = 'Foto Presensi: ' + mkName;
        const modal = document.getElementById('photoPreviewModal');
        if (modal) {
            modal.style.setProperty('display', 'flex', 'important');
        }
    }

    // Tutup modal foto presensi
    function closePhotoModal() {
        const modal = document.getElementById('photoPreviewModal');
        if (modal) {
            modal.style.setProperty('display', 'none', 'important');
        }
    }

    // Notification pill: klik untuk buka halaman notifikasi
    document.addEventListener('DOMContentLoaded', () => {
        const notifPill = document.getElementById('dpNotifPill');
        if (notifPill) {
            notifPill.addEventListener('click', function () {
                window.location.href = this.dataset.href;
            });
        }

        const notifClose = document.getElementById('dpNotifClose');
        if (notifClose) {
            notifClose.addEventListener('click', function (e) {
                e.stopPropagation();
                const pill = document.getElementById('dpNotifPill');
                if (pill) {
                    pill.style.display = 'none';
                }
            });
        }

        // Tombol preview foto presensi di setiap kartu jadwal
        document.querySelectorAll('.dp-photo-trigger-btn').forEach((btn) => {
            btn.addEventListener('click', function () {
                showPhotoModal(this.dataset.photoUrl, this.dataset.mkName);
            });
        });

        // ── REVERSE GEOCODING via Nominatim (OpenStreetMap) ──
        // Untuk setiap GPS row yang punya koordinat, fetch nama alamat
        document.querySelectorAll('[data-gps-lat][data-gps-lng]').forEach(async (row) => {
            const lat = row.dataset.gpsLat;
            const lng = row.dataset.gpsLng;
            // Ambil jadwal id dari id element "gps-row-{id}"
            const rowId = row.id.replace('gps-row-', '');
            const addressEl = document.getElementById('gps-address-' + rowId);
            if (!addressEl) return;

            try {
                const resp = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=id`,
                    { headers: { 'Accept': 'application/json' } }
                );
                if (!resp.ok) throw new Error('Nominatim error');
                const data = await resp.json();

                // Susun nama alamat yang informatif
                const addr = data.address || {};
                const parts = [
                    addr.road || addr.pedestrian || addr.footway,
                    addr.neighbourhood || addr.suburb || addr.village,
                    addr.city || addr.town || addr.county,
                    addr.state
                ].filter(Boolean);

                const displayName = parts.length > 0
                    ? parts.join(', ')
                    : (data.display_name ? data.display_name.split(',').slice(0,3).join(',') : 'Alamat tidak dikenali');

                addressEl.innerHTML = `<span style="color:#111827;">${displayName}</span>`;
            } catch (e) {
                addressEl.innerHTML = `<span style="color:#DC2626;font-size:11px;"><i class="fa-solid fa-triangle-exclamation"></i> Gagal memuat alamat</span>`;
            }
        });

        // Tampilkan modal pengingat presensi otomatis saat halaman selesai dimuat
        openReminderModal();
    });
</script>
@endpush