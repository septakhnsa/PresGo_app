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
        background: #f3f4f6;
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
                <a href="{{ route('mahasiswa.dashboard-presensi') }}" class="h-lihat-btn" style="text-decoration: none;">Lihat Absensi &rsaquo;</a>
            </div>
        </div>


        {{-- Welcome Modal --}}
        <div class="h-modal-overlay" id="welcomeModal">
            <div class="h-modal-card">
                <div class="h-modal-topbar">
                    <span class="h-modal-topbar-date">{{ $dayLabel }}</span>
                    @if($totalJadwal == 0)
                        <span class="h-modal-topbar-badge" style="background:#F3F4F6; color:#4B5563;">Tidak ada jadwal</span>
                    @elseif($totalAbsen > 0)
                        <span class="h-modal-topbar-badge" style="background:#D1FAE5; color:#065F46;">{{ $totalAbsen }}/{{ $totalJadwal }} telah absen</span>
                    @else
                        <span class="h-modal-topbar-badge">Belum absen</span>
                    @endif
                </div>
                <div class="h-modal-body">
                    <div class="h-modal-greeting">{{ $greeting }}</div>
                    <img src="{{ $avatarUrl }}" class="h-modal-avatar" alt="Foto Profil">
                    <div class="h-modal-nim">{{ $userNim }}</div>
                    <div class="h-modal-name">{{ $userName }}</div>
                    @if(Auth::user()->nim === null)
                        <div class="h-modal-desc" style="color: #DC2626; font-weight: 700; margin-top: 10px; background: #FEE2E2; padding: 10px; border-radius: 8px;">
                            <i class="fa-solid fa-clock-rotate-left" style="margin-right:4px;"></i> PENDING VERIFIKASI<br>
                            <span style="font-size: 11.5px; font-weight: 500; color: #991B1B;">Data Anda telah terdaftar dan diterima. Harap menunggu admin memverifikasi dan menginputkan NIM Anda agar bisa melanjutkan.</span>
                        </div>
                        <form action="{{ route('mahasiswa.logout') }}" method="POST" style="margin-top: 18px;">
                            @csrf
                            <button type="submit" class="h-modal-dismiss" style="margin-top: 0;">
                                Saya Mengerti
                            </button>
                        </form>
                    @else
                        <div class="h-modal-desc">
                            Mahasiswa Semester 6 · Program Studi Teknik Informatika<br>
                            STMIK Widya Utama
                        </div>
                        <button class="h-modal-dismiss" onclick="document.getElementById('welcomeModal').style.display='none'">
                            Dismiss
                        </button>
                    @endif
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

    // Titik Acuan Kampus STMIK Widya Utama (Berkoh)
    const campusLatLng = [-7.442651, 109.260515];
    const campusIcon = L.divIcon({
        html: '<div style="width:24px;height:24px;background:#DC2626;border:3px solid #fff;border-radius:50%;box-shadow:0 0 8px rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:bold;">K</div>',
        className: '', iconSize: [24,24], iconAnchor: [12,12]
    });
    L.marker(campusLatLng, {icon: campusIcon}).addTo(map).bindPopup('<b>STMIK Widya Utama</b><br>Jl. Sunan Kalijaga, Berkoh');

    let _marker;
    let _polyline;

    if ('geolocation' in navigator) {
        navigator.geolocation.watchPosition(pos => {
            const { latitude: lat, longitude: lng } = pos.coords;
            const userLatLng = [lat, lng];
            
            // Marker User
            const userIcon = L.divIcon({
                html: '<div style="width:18px;height:18px;background:#1D4ED8;border:3px solid #fff;border-radius:50%;box-shadow:0 0 8px rgba(0,0,0,.3)"></div>',
                className: '', iconSize: [18,18], iconAnchor: [9,9]
            });

            if (!_marker) {
                _marker = L.marker(userLatLng, {icon: userIcon}).addTo(map);
            } else {
                _marker.setLatLng(userLatLng);
            }

            // Line Connection User ke Kampus
            if (!_polyline) {
                _polyline = L.polyline([userLatLng, campusLatLng], {
                    color: '#1B5E35',
                    weight: 4,
                    opacity: 0.8,
                    dashArray: '10, 10',
                    lineJoin: 'round'
                }).addTo(map);
            } else {
                _polyline.setLatLngs([userLatLng, campusLatLng]);
            }
            
            // Fit bounds agar kedua titik (user & kampus) terlihat di map
            const bounds = L.latLngBounds([userLatLng, campusLatLng]);
            map.fitBounds(bounds, { padding: [50, 50] });

        }, () => {}, { enableHighAccuracy: true, maximumAge: 0 });
    }

    // Close welcome after 500ms on first load (or let user dismiss)
    // document.getElementById('welcomeModal') stays open until user taps Dismiss

    // ── PUSH NOTIFICATION & COUNTDOWN LOGIC ──
    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    function subscribeUserToPush() {
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.register('/sw.js').then(function(reg) {
                console.log('Service Worker Registered');
                reg.pushManager.getSubscription().then(function(sub) {
                    if (sub === null) {
                        reg.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: urlBase64ToUint8Array('{{ env("VAPID_PUBLIC_KEY") }}')
                        }).then(function(newSub) {
                            // Send subscription to backend
                            fetch('{{ route("mahasiswa.push.subscribe") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(newSub)
                            });
                        }).catch(function(e) {
                            if (Notification.permission === 'denied') {
                                console.warn('Permission for notifications was denied');
                            } else {
                                console.error('Unable to subscribe to push', e);
                            }
                        });
                    }
                });
            });
        }
    }

    // Request permission on load
    if (Notification.permission === 'default') {
        Notification.requestPermission().then(function(permission) {
            if (permission === 'granted') {
                subscribeUserToPush();
            }
        });
    } else if (Notification.permission === 'granted') {
        subscribeUserToPush();
    }
</script>
@endpush
