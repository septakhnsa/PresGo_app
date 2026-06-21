@extends('layouts.mahasiswa')

@section('title', 'Dashboard Presensi - PresGo')

@push('styles')
<style>
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
        <div>
            <div class="dp-header-name">{{ $userName }}</div>
            <div class="dp-header-nim">{{ $userNim }}</div>
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
        <div class="dp-notif-pill" id="dpNotifPill" style="cursor: pointer;" onclick="openReminderModal()">
            <div class="dp-notif-icon"><i class="fa-solid fa-bell"></i></div>
            <div class="dp-notif-text">
                <strong>PresGo - Baru saja</strong>
                &nbsp;Pengingat Presensi | {{ $notifJadwal['mata_kuliah'] }}
                <span class="dp-notif-red">15 Menit lagi &rsaquo;</span>
            </div>
            <button class="dp-notif-close" onclick="event.stopPropagation(); document.getElementById('dpNotifPill').style.display='none'">
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
                <div>
                    <div class="dp-mk">{{ $jadwal['mata_kuliah'] }}</div>
                    <div class="dp-jadwal-meta">{{ $jadwal['jam'] }} &bull; {{ $jadwal['ruangan'] }}</div>
                </div>
                <span class="dp-status-pill {{ $jadwal['status'] === 'Hadir' ? 'hadir' : 'belum' }}">
                    {{ $jadwal['status'] }}
                </span>
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
<div id="reminderModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[30px] border-4 border-[#14532D] shadow-[-8px_8px_0_0_#14532D] w-full max-w-sm overflow-hidden flex flex-col">
        <div class="bg-[#14532D] p-6 text-center relative">
            <div class="w-14 h-14 bg-[#FFD54F] rounded-full flex items-center justify-center mx-auto mb-3 border-2 border-[#14532D] shadow-[-2px_2px_0_0_#000]">
                <i class="fa-solid fa-bell text-[#14532D] text-xl animate-bounce"></i>
            </div>
            <h3 class="text-white font-black text-lg">Pengingat Presensi</h3>
            <p class="text-[#B9CDBD] text-xs font-bold mt-1">Kelas akan segera dimulai!</p>
        </div>
        <div class="p-6 text-center">
            <p class="text-gray-700 font-bold text-sm leading-relaxed mb-6">
                Kamu memiliki jadwal kuliah <span class="text-[#14532D] font-black">{{ $notifJadwal['mata_kuliah'] }}</span> pada jam <span class="text-[#14532D] font-black">{{ $notifJadwal['jam'] }}</span> di ruangan <span class="text-[#14532D] font-black">{{ $notifJadwal['ruangan'] }}</span>.
            </p>
            <div class="flex flex-col gap-3">
                <a href="{{ route('mahasiswa.presensi.camera', ['jadwal_id' => $notifJadwal['id']]) }}" 
                   class="bg-[#1B5E35] text-[#FFD54F] py-3 rounded-xl font-black text-sm border-2 border-[#1B5E35] shadow-[-4px_4px_0_0_#000] text-center hover:bg-[#14532D] transition-all transform active:translate-y-1 active:shadow-[0_0_0_0_#000] block">
                    Presensi Sekarang
                </a>
                <button onclick="closeReminderModal()" 
                        class="bg-gray-100 text-gray-700 py-3 rounded-xl font-black text-sm border-2 border-gray-300 hover:bg-gray-200 transition-colors">
                    Abaikan
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

<script>
    function openReminderModal() {
        const modal = document.getElementById('reminderModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.setProperty('display', 'flex', 'important');
        }
    }

    function closeReminderModal() {
        const modal = document.getElementById('reminderModal');
        if (modal) {
            modal.style.setProperty('display', 'none', 'important');
        }
    }
</script>
@endpush