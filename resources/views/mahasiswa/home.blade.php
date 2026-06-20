@extends('layouts.mahasiswa')

@section('title', 'Beranda - PresGo')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    /* ─── override layout body for map page ─── */
    html, body { height: 100%; overflow: hidden; }

    /* ─── Home Screen fills entire app-screen ─── */
    .home-wrap {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
        overflow: hidden;
        background: #f1f5f9;
    }

    /* ══════════════════════════════
       TOP GREEN HEADER
    ══════════════════════════════ */
    .h-header {
        background: #1B5E35;
        padding: 25px 24px 0;   /* 36px = fake status bar */
        flex-shrink: 0;
        z-index: 20;
        position: relative;
    }
    .h-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .h-name {
        color: #fff;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 2px;
    }
    .h-nim {
        color: #B9CDBD;
        font-size: 12.5px;
        font-weight: 500;
    }
    .h-status-icons {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #fff;
        font-size: 13px;
        padding-top: 4px;
    }
    .h-notif-btn {
        color: #fff;
        font-size: 20px;
        text-decoration: none;
        margin-left: 4px;
    }

    /* Tab bar */
    .h-tabs {
        display: flex;
        justify-content: center;
        gap: 32px;
    }
    .h-tab {
        color: #8DB89B;
        font-size: 13.5px;
        font-weight: 700;
        padding: 10px 0 14px;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        text-align: center;
        cursor: pointer;
    }
    .h-tab.active {
        color: #fff;
        border-bottom-color: #fff;
    }

    /* ══════════════════════════════
       MAP (fills remaining space)
    ══════════════════════════════ */
    .h-map-area {
        flex: 1;
        position: relative;
        overflow: hidden;
    }
    #map {
        position: absolute;
        inset: 0;
        z-index: 1;
    }

    /* ══════════════════════════════
       YELLOW ABSENSI CARD (floating on map)
    ══════════════════════════════ */
    .h-absensi-card {
        position: absolute;
        top: 18px;
        left: 16px;
        right: 16px;
        background: #FFD54F;
        border-radius: 16px;
        padding: 13px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 10;
        box-shadow: 0 6px 20px rgba(0,0,0,0.14);
    }
    .h-absensi-left .h-ab-date {
        font-size: 13px;
        font-weight: 800;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 3px;
    }
    .h-absensi-left .h-ab-date i { color: #D97706; }
    .h-absensi-left .h-ab-univ {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
    }
    .h-absensi-right { text-align: right; }
    .h-absensi-right .h-ab-ask {
        font-size: 10.5px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 3px;
    }
    .h-lihat-btn {
        background: none;
        border: none;
        font-size: 13.5px;
        font-weight: 800;
        color: #DC2626;
        cursor: pointer;
        padding: 0;
        font-family: inherit;
    }

    /* ══════════════════════════════
       WELCOME MODAL
    ══════════════════════════════ */
    .h-modal-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 30;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .h-modal-card {
        background: #fff;
        border-radius: 24px;
        width: 100%;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }
    .h-modal-topbar {
        background: #FFD54F;
        padding: 14px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .h-modal-topbar-date {
        font-size: 13px;
        font-weight: 800;
        color: #1f2937;
    }
    .h-modal-topbar-badge {
        background: #FEE2E2;
        color: #DC2626;
        font-size: 10.5px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 8px;
    }
    .h-modal-body {
        padding: 22px 20px 24px;
        text-align: center;
    }
    .h-modal-greeting {
        font-family: 'Playfair Display', 'Georgia', serif;
        font-size: 22px;
        font-style: italic;
        font-weight: 700;
        color: #1B5E35;
        margin-bottom: 16px;
    }
    .h-modal-avatar {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        border: 3px solid #e5e7eb;
        display: block;
        margin: 0 auto 12px;
    }
    .h-modal-nim {
        font-size: 11.5px;
        font-weight: 700;
        color: #9CA3AF;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }
    .h-modal-name {
        font-size: 17px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 10px;
    }
    .h-modal-desc {
        font-size: 11.5px;
        color: #6B7280;
        line-height: 1.6;
        font-weight: 500;
    }
    .h-modal-dismiss {
        display: block;
        margin-top: 18px;
        font-size: 13.5px;
        font-weight: 800;
        color: #1B5E35;
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        width: 100%;
    }

    /* ══════════════════════════════
       DASHBOARD MODAL (slide-up overlay)
    ══════════════════════════════ */
    .dash-overlay {
        position: absolute;
        inset: 0;
        z-index: 40;
        display: none;
        flex-direction: column;
        background: #8FB090; /* pastel green body */
        overflow: hidden;
    }
    .dash-overlay.open { display: flex; }

    /* Dashboard top header */
    .dash-header {
        background: #1B5E35;
        padding: 36px 20px 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-radius: 0 0 28px 28px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        flex-shrink: 0;
    }
    .dash-header-name {
        color: #fff;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 2px;
    }
    .dash-header-nim {
        color: #B9CDBD;
        font-size: 12.5px;
        font-weight: 500;
    }
    .dash-header-right {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .dash-close-btn {
        background: none;
        border: none;
        color: #fff;
        font-size: 22px;
        cursor: pointer;
        line-height: 1;
    }
    .dash-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2.5px solid #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    /* Dashboard scrollable body */
    .dash-body {
        flex: 1;
        overflow-y: auto;
        padding: 18px 18px 0;
        -webkit-overflow-scrolling: touch;
    }
    /* Notification pill */
    .dash-notif-pill {
        background: #fff;
        border-radius: 999px;
        padding: 11px 12px 11px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: box-shadow 0.2s, background 0.2s;
        cursor: pointer;
    }
    .dash-notif-pill:hover {
        box-shadow: 0 4px 16px rgba(27,94,53,0.15);
        background: #F0FDF4;
    }
    /* Link wrapping icon + text — no default anchor styles */
    .dash-notif-pill-link {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        text-decoration: none;
        color: inherit;
        min-width: 0;
    }
    .dash-notif-pill-icon {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: #1B5E35;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 13px;
        flex-shrink: 0;
    }
    .dash-notif-pill-text {
        flex: 1;
        font-size: 11.5px;
        font-weight: 700;
        color: #374151;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .dash-notif-pill-text .dash-red { color: #DC2626; }
    .dash-notif-close {
        background: none; border: none; color: #DC2626;
        font-size: 16px; cursor: pointer; flex-shrink: 0;
        padding: 4px;
        line-height: 1;
        transition: transform 0.15s;
    }
    .dash-notif-close:hover { transform: scale(1.15); }

    /* Rekap card */
    .dash-rekap {
        background: #14532D;
        border-radius: 22px;
        padding: 18px;
        margin-bottom: 20px;
        box-shadow: -6px 6px 0 #000;
    }
    .dash-rekap-title {
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        text-align: center;
        margin-bottom: 14px;
    }
    .dash-rekap-row { display: flex; gap: 10px; }
    .dash-rekap-box {
        flex: 1;
        background: #fff;
        border-radius: 14px;
        padding: 14px 6px;
        text-align: center;
    }
    .dash-rekap-num { font-size: 20px; font-weight: 800; display: block; }
    .dash-rekap-lbl { font-size: 10.5px; font-weight: 700; display: block; margin-top: 2px; }
    .dash-rekap-box.k  .dash-rekap-num, .dash-rekap-box.k  .dash-rekap-lbl { color: #14532D; }
    .dash-rekap-box.h  .dash-rekap-num, .dash-rekap-box.h  .dash-rekap-lbl { color: #1D4ED8; }
    .dash-rekap-box.a  .dash-rekap-num, .dash-rekap-box.a  .dash-rekap-lbl { color: #DC2626; }

    /* Jadwal section */
    .dash-jadwal-title {
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 12px;
    }
    .dash-jadwal-card {
        background: #fff;
        border-radius: 16px;
        padding: 15px 16px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .dash-mk {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
    }
    .dash-jadwal-meta {
        font-size: 12.5px;
        font-weight: 600;
        color: #6B7280;
    }
    .dash-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 999px;
        white-space: nowrap;
    }
    .dash-pill.belum { background: #FEF3C7; color: #92400E; }
    .dash-pill.hadir { background: #D1FAE5; color: #065F46; }

    /* ══════════════════════════════
       BOTTOM GREEN BAR
    ══════════════════════════════ */
    .h-bottom-bar {
        background: #1B5E35;
        height: 58px;
        flex-shrink: 0;
        position: relative;
        z-index: 20;
    }
    .h-fab {
        position: absolute;
        top: -28px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #1B5E35;
        border: 4px solid #fff;
        box-shadow: 0 4px 14px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 24px;
        text-decoration: none;
        z-index: 21;
    }

    /* ══════════════════════════════
       RESPONSIVE DESKTOP ADJUSTMENTS
    ══════════════════════════════ */
    @media (min-width: 768px) {
        .h-header-row, .h-tabs {
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }
        .h-tabs {
            justify-content: center;
            gap: 32px;
        }
        .h-tab {
            flex: 0 1 auto;
        }
        .h-absensi-card {
            max-width: 400px;
            left: 32px;
            right: auto;
            top: 24px;
        }
        .h-modal-card {
            max-width: 400px;
        }
        .dash-overlay {
            right: 0;
            left: auto;
            width: 400px;
            border-left: 1px solid rgba(0,0,0,0.1);
        }
        .dash-header {
            border-radius: 0;
        }
        .h-bottom-bar {
            height: 0;
            background: transparent;
        }
        .dash-overlay .h-bottom-bar {
            height: 58px;
            background: #1B5E35;
        }
        .h-fab {
            top: auto;
            bottom: 32px;
            right: 32px;
            left: auto;
            transform: none;
        }
        .dash-overlay .h-fab {
            bottom: -28px;
            top: auto;
            left: 50%;
            right: auto;
            transform: translateX(-50%);
        }
    }
</style>
@endpush

@section('content')
@php
    \Carbon\Carbon::setLocale('id');
    $now       = now();
    $dateLabel = $now->translatedFormat('d M Y');
    $dayLabel  = $now->translatedFormat('l, d F Y');
    $hour      = $now->hour;
    if ($hour < 11)      $greeting = 'Selamat Pagi,';
    elseif ($hour < 15)  $greeting = 'Selamat Siang,';
    elseif ($hour < 18)  $greeting = 'Selamat Sore,';
    else                 $greeting = 'Selamat Malam,';
    $userName = Auth::user()->name;
    $userNim  = Auth::user()->nim ?? 'STI202303686';
    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=1B5E35&color=fff&bold=true&size=128';
@endphp

<div class="home-wrap">

    {{-- ── TOP GREEN HEADER ── --}}
    <div class="h-header">
        <div class="h-header-row">
            <div>
                <div class="h-name">{{ $userName }}</div>
                <div class="h-nim">{{ $userNim }}</div>
            </div>
            <div class="h-status-icons">

                <a href="{{ route('mahasiswa.notifikasi') }}" class="h-notif-btn">
                    <i class="fa-solid fa-bell"></i>
                </a>
            </div>
        </div>
        <div class="h-tabs">
            <a href="{{ route('mahasiswa.profile') }}" class="h-tab">Profile</a>
            <span class="h-tab active">Home</span>
            <a href="{{ route('mahasiswa.history') }}" class="h-tab">History</a>
        </div>
    </div>

    {{-- ── MAP AREA ── --}}
    <div class="h-map-area">
        <div id="map"></div>

        {{-- Yellow absensi floating card --}}
        <div class="h-absensi-card">
            <div class="h-absensi-left">
                <div class="h-ab-date">
                    <i class="fa-solid fa-star"></i>
                    {{ $dateLabel }}
                </div>
                <div class="h-ab-univ">STMIK Widya Utama</div>
            </div>
            <div class="h-absensi-right">
                <div class="h-ab-ask">Sudah cek absensi hari ini?</div>
                <button class="h-lihat-btn" onclick="openDashboard()">Lihat Absensi &rsaquo;</button>
            </div>
        </div>

        {{-- Welcome Modal --}}
        <div class="h-modal-overlay" id="welcomeModal">
            <div class="h-modal-card">
                <div class="h-modal-topbar">
                    <span class="h-modal-topbar-date">{{ $dayLabel }}</span>
                    <span class="h-modal-topbar-badge">Belum absen</span>
                </div>
                <div class="h-modal-body">
                    <div class="h-modal-greeting">{{ $greeting }}</div>
                    <img src="{{ $avatarUrl }}" class="h-modal-avatar" alt="Foto Profil">
                    <div class="h-modal-nim">{{ $userNim }}</div>
                    <div class="h-modal-name">{{ $userName }}</div>
                    <div class="h-modal-desc">
                        Mahasiswa Semester 6 · Program Studi Teknik Informatika<br>
                        STMIK Widya Utama
                    </div>
                    <button class="h-modal-dismiss" onclick="document.getElementById('welcomeModal').style.display='none'">
                        Dismiss
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── BOTTOM BAR ── --}}
    <div class="h-bottom-bar">
        <a href="{{ route('mahasiswa.presensi.camera') }}" class="h-fab">
            <i class="fa-solid fa-camera"></i>
        </a>
    </div>

    {{-- ══ DASHBOARD PRESENSI OVERLAY (muncul saat klik "Lihat Absensi") ══ --}}
    <div class="dash-overlay" id="dashOverlay">

        {{-- Dashboard header --}}
        <div class="dash-header">
            <div>
                <div class="dash-header-name">{{ $userName }}</div>
                <div class="dash-header-nim">{{ $userNim }}</div>
            </div>
            <div class="dash-header-right">
                <button class="dash-close-btn" onclick="closeDashboard()">
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <img src="{{ $avatarUrl }}" class="dash-avatar" alt="Avatar">
            </div>
        </div>

        {{-- Dashboard body --}}
        <div class="dash-body">

            {{-- Notif pill — klik teks → halaman Notifikasi | klik X → dismiss --}}
            <div class="dash-notif-pill" id="dashNotifPill">
                <a href="{{ route('mahasiswa.notifikasi') }}" class="dash-notif-pill-link">
                    <div class="dash-notif-pill-icon"><i class="fa-solid fa-bell"></i></div>
                    <div class="dash-notif-pill-text">
                        <strong style="color:#1B5E35;">PresGo - Baru saja</strong>
                        &nbsp;Pengingat Presensi | Mobile Programming
                        <span class="dash-red">15 Menit lagi &rsaquo;</span>
                    </div>
                </a>
                <button class="dash-notif-close"
                        onclick="event.stopPropagation(); document.getElementById('dashNotifPill').style.display='none'">
                    <i class="fa-regular fa-circle-xmark"></i>
                </button>
            </div>

            {{-- Rekap Kehadiran --}}
            <div class="dash-rekap">
                <div class="dash-rekap-title">Rekap Kehadiran Bulan ini</div>
                <div class="dash-rekap-row">
                    <div class="dash-rekap-box k">
                        <span class="dash-rekap-num">84%</span>
                        <span class="dash-rekap-lbl">Kehadiran</span>
                    </div>
                    <div class="dash-rekap-box h">
                        <span class="dash-rekap-num">17</span>
                        <span class="dash-rekap-lbl">Hadir</span>
                    </div>
                    <div class="dash-rekap-box a">
                        <span class="dash-rekap-num">2</span>
                        <span class="dash-rekap-lbl">Absen</span>
                    </div>
                </div>
            </div>

            {{-- Jadwal Hari Ini --}}
            <div class="dash-jadwal-title">Jadwal Hari Ini</div>

            <div class="dash-jadwal-card">
                <div>
                    <div class="dash-mk">Mobile Programming</div>
                    <div class="dash-jadwal-meta">10.00 – 12.00 &bull; KBR 2.3</div>
                </div>
                <span class="dash-pill belum">Belum</span>
            </div>

            <div class="dash-jadwal-card">
                <div>
                    <div class="dash-mk">Web Programming</div>
                    <div class="dash-jadwal-meta">09.30 – 11.30 &bull; KBR 2.3</div>
                </div>
                <span class="dash-pill hadir">Hadir</span>
            </div>

            <div style="height: 100px;"></div>{{-- spacer above bottom bar --}}
        </div>

        {{-- Dashboard bottom bar (same camera FAB style) --}}
        <div class="h-bottom-bar" style="border-radius: 22px 22px 0 0;">
            <a href="{{ route('mahasiswa.presensi.camera') }}" class="h-fab">
                <i class="fa-solid fa-camera"></i>
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    // ── MAP ──
    const map = L.map('map', { zoomControl: false, attributionControl: false })
                  .setView([-7.412, 109.25], 15);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png',
                { maxZoom: 19 }).addTo(map);
    let _marker;
    if ('geolocation' in navigator) {
        navigator.geolocation.watchPosition(pos => {
            const { latitude: lat, longitude: lng } = pos.coords;
            map.setView([lat, lng], 17);
            const icon = L.divIcon({
                html: '<div style="width:18px;height:18px;background:#1D4ED8;border:3px solid #fff;border-radius:50%;box-shadow:0 0 8px rgba(0,0,0,.3)"></div>',
                className: '', iconSize: [18,18], iconAnchor: [9,9]
            });
            if (!_marker) _marker = L.marker([lat,lng],{icon}).addTo(map);
            else _marker.setLatLng([lat,lng]);
        }, () => {}, { enableHighAccuracy: true, maximumAge: 0 });
    }

    // ── Dashboard open/close ──
    function openDashboard()  { document.getElementById('dashOverlay').classList.add('open'); }
    function closeDashboard() { document.getElementById('dashOverlay').classList.remove('open'); }

    // Close welcome after 500ms on first load (or let user dismiss)
    // document.getElementById('welcomeModal') stays open until user taps Dismiss
</script>
@endpush
