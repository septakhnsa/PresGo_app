@extends('layouts.mahasiswa')

@section('title', 'Dashboard Presensi - PresGo')

@push('styles')
<style>
    html, body { height: 100%; overflow: hidden; }

    /* ── Dashboard page fills entire app-screen ── */
    .dp-wrap {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
        background: #8FB090;   /* pastel green from Figma */
        overflow: hidden;
    }

    /* ══════════════════════════
       TOP GREEN HEADER
    ══════════════════════════ */
    .dp-header {
        background: #1B5E35;
        padding: 36px 20px 22px;   /* 36px = fake status bar */
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-radius: 0 0 28px 28px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        position: relative;
        z-index: 10;
    }
    .dp-header-name {
        color: #fff;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 2px;
    }
    .dp-header-nim {
        color: #B9CDBD;
        font-size: 12.5px;
        font-weight: 500;
    }
    .dp-header-right {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .dp-menu-link {
        color: #fff;
        font-size: 20px;
        text-decoration: none;
    }
    .dp-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2.5px solid #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        object-fit: cover;
    }

    /* ══════════════════════════
       SCROLLABLE BODY
    ══════════════════════════ */
    .dp-body {
        flex: 1;
        overflow-y: auto;
        padding: 18px 16px 0;
        -webkit-overflow-scrolling: touch;
        margin-top: -6px;   /* let content peek slightly under header curve */
    }

    /* Notification pill */
    .dp-notif-pill {
        background: #fff;
        border-radius: 999px;
        padding: 11px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    }
    .dp-notif-icon {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: #1B5E35;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 13px; flex-shrink: 0;
    }
    .dp-notif-text {
        flex: 1;
        font-size: 11px;
        font-weight: 700;
        color: #374151;
        line-height: 1.4;
    }
    .dp-notif-text strong { color: #1B5E35; }
    .dp-notif-red { color: #DC2626; }
    .dp-notif-close {
        background: none; border: none; color: #DC2626;
        font-size: 16px; cursor: pointer; flex-shrink: 0;
    }

    /* ══════════════════════════
       REKAP KEHADIRAN BOX
    ══════════════════════════ */
    .dp-rekap {
        background: #14532D;
        border-radius: 22px;
        padding: 18px 18px 20px;
        margin-bottom: 20px;
        box-shadow: -6px 6px 0 #000;
    }
    .dp-rekap-title {
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        text-align: center;
        margin-bottom: 16px;
    }
    .dp-rekap-row {
        display: flex;
        gap: 10px;
    }
    .dp-rekap-box {
        flex: 1;
        background: #fff;
        border-radius: 14px;
        padding: 14px 6px;
        text-align: center;
    }
    .dp-rekap-num {
        font-size: 20px;
        font-weight: 800;
        display: block;
        line-height: 1.2;
    }
    .dp-rekap-lbl {
        font-size: 10.5px;
        font-weight: 700;
        display: block;
        margin-top: 3px;
    }
    .dp-rekap-box.kehadiran .dp-rekap-num,
    .dp-rekap-box.kehadiran .dp-rekap-lbl { color: #14532D; }
    .dp-rekap-box.hadir .dp-rekap-num,
    .dp-rekap-box.hadir .dp-rekap-lbl     { color: #1D4ED8; }
    .dp-rekap-box.absen .dp-rekap-num,
    .dp-rekap-box.absen .dp-rekap-lbl     { color: #DC2626; }

    /* ══════════════════════════
       JADWAL HARI INI
    ══════════════════════════ */
    .dp-jadwal-title {
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 12px;
    }
    .dp-jadwal-card {
        background: #fff;
        border-radius: 16px;
        padding: 15px 16px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .dp-mk {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
    }
    .dp-jadwal-meta {
        font-size: 12.5px;
        font-weight: 600;
        color: #6B7280;
    }
    .dp-status-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 999px;
        white-space: nowrap;
    }
    .dp-status-pill.belum { background: #FEF3C7; color: #92400E; }
    .dp-status-pill.hadir { background: #D1FAE5; color: #065F46; }

    /* Photo trigger button on jadwal card */
    .dp-photo-trigger-btn {
        padding: 8px 10px;
        background: #E6F4EA;
        border-radius: 10px;
        border: 2px solid #1B5E35;
        font-size: 15px;
        color: #1B5E35;
        cursor: pointer;
        flex-shrink: 0;
        box-shadow: -2px 2px 0 0 #1B5E35;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ══════════════════════════
       BOTTOM GREEN BAR + FAB
    ══════════════════════════ */
    .dp-bottom-bar {
        background: #1B5E35;
        height: 58px;
        flex-shrink: 0;
        position: relative;
        z-index: 10;
        border-radius: 22px 22px 0 0;
    }
    .dp-fab {
        position: absolute;
        top: -28px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #1B5E35;
        border: 4px solid #fff;
        box-shadow: 0 4px 14px rgba(0,0,0,0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 24px;
        text-decoration: none;
        z-index: 11;
    }

    /* Modal Pengingat Presensi (Brutalist style) */
    .dp-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 999;
        padding: 16px;
    }
    .dp-modal-card {
        background: #fff;
        border-radius: 28px;
        border: 4px solid #14532D;
        box-shadow: -8px 8px 0 0 #14532D;
        width: 100%;
        max-width: 360px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: dpModalPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes dpModalPop {
        from { transform: scale(0.9) translateY(20px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }
    .dp-modal-header {
        background: #14532D;
        padding: 24px;
        text-align: center;
    }
    .dp-modal-bell-wrap {
        width: 56px;
        height: 56px;
        background: #FFD54F;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        border: 2px solid #14532D;
        box-shadow: -2px 2px 0 0 #000;
    }
    .dp-modal-bell-wrap i {
        color: #14532D;
        font-size: 20px;
    }
    .dp-modal-title {
        color: #fff;
        font-size: 18px;
        font-weight: 800;
        margin: 0;
    }
    .dp-modal-subtitle {
        color: #B9CDBD;
        font-size: 12px;
        font-weight: 700;
        margin-top: 4px;
    }
    .dp-modal-body {
        padding: 24px;
        text-align: center;
    }
    .dp-modal-text {
        color: #374151;
        font-size: 13.5px;
        font-weight: 700;
        line-height: 1.6;
        margin-bottom: 24px;
    }
    .dp-modal-highlight {
        color: #14532D;
        font-weight: 900;
    }
    .dp-modal-btn-confirm {
        display: block;
        background: #1B5E35;
        color: #FFD54F;
        padding: 14px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 14px;
        text-decoration: none;
        border: 2px solid #1B5E35;
        box-shadow: -4px 4px 0 0 #000;
        transition: transform 0.1s, box-shadow 0.1s;
        margin-bottom: 12px;
    }
    .dp-modal-btn-confirm:active {
        transform: translate(-2px, 2px);
        box-shadow: -2px 2px 0 0 #000;
    }
    .dp-modal-btn-cancel {
        display: block;
        width: 100%;
        background: #F3F4F6;
        color: #4B5563;
        padding: 14px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 14px;
        border: 2px solid #D1D5DB;
        cursor: pointer;
        transition: background 0.2s;
    }
    .dp-modal-btn-cancel:hover {
        background: #E5E7EB;
    }

    /* Photo Preview Modal styling */
    .dp-photo-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 24px;
    }
    .dp-photo-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.25);
        width: 100%;
        max-width: 360px;
        padding: 24px;
        text-align: center;
        animation: dpModalPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .dp-photo-title {
        font-weight: 800;
        font-size: 17px;
        margin-bottom: 16px;
        color: #1B5E35;
    }
    .dp-photo-img {
        width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: cover;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .dp-photo-close-btn {
        margin-top: 20px;
        width: 100%;
        background: #1B5E35;
        color: #fff;
        font-weight: 700;
        padding: 14px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }
    .dp-photo-close-btn:hover {
        background: #14532D;
    }
    .dp-photo-close-btn:active {
        transform: scale(0.98);
    }
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
            <div class="dp-jadwal-card" style="display: flex; align-items: center; justify-content: space-between;">
                <a href="{{ $jadwal['status'] === 'Hadir' ? '#' : route('mahasiswa.presensi.camera', ['jadwal_id' => $jadwal['id']]) }}" style="text-decoration: none; flex: 1; display: flex; justify-content: space-between; align-items: center; color: inherit; margin-right: 8px;">
                    <div>
                        <div class="dp-mk">{{ $jadwal['mata_kuliah'] }}</div>
                        <div class="dp-jadwal-meta">{{ $jadwal['jam'] }} &bull; {{ $jadwal['ruangan'] }}</div>
                    </div>
                    <span class="dp-status-pill {{ $jadwal['status'] === 'Hadir' ? 'hadir' : 'belum' }}">
                        {{ $jadwal['status'] }}
                    </span>
                </a>
                @if ($jadwal['status'] === 'Hadir' && isset($jadwal['foto_wajah']) && $jadwal['foto_wajah'])
                    <button
                        type="button"
                        class="dp-photo-trigger-btn"
                        data-photo-url="{{ asset('storage/' . $jadwal['foto_wajah']) }}"
                        data-mk-name="{{ $jadwal['mata_kuliah'] }}"
                    >
                        <i class="fa-solid fa-image"></i>
                    </button>
                @endif
            </div>
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
                Kamu memiliki jadwal kuliah <span class="dp-modal-highlight">{{ $notifJadwal['mata_kuliah'] }}</span> pada jam <span class="dp-modal-highlight">{{ $notifJadwal['jam'] }}</span> di ruangan <span class="dp-modal-highlight">{{ $notifJadwal['ruangan'] }}</span>.
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

        // Tampilkan modal pengingat presensi otomatis saat halaman selesai dimuat
        openReminderModal();
    });
</script>
@endpush