@extends('layouts.mahasiswa')

@section('title', 'Dashboard Presensi - PresGo')

@php
    $userName  = $user->name ?? 'Septa Khoerun Nisa';
    $userNim   = $user->nim ?? 'STI202303686';
    $userPhoto = $user->photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=1B5E35&color=fff&size=128';

    $kehadiranPersen = $kehadiranPersen ?? 84;
    $totalHadir      = $totalHadir ?? 17;
    $totalAbsen      = $totalAbsen ?? 2;

    // Contoh struktur data jadwal — ganti dengan data asli dari controller
    $jadwalHariIni = $jadwalHariIni ?? [
        [
            'mata_kuliah' => 'Mobile Programming',
            'jam'         => '10.00 – 12.00',
            'ruangan'     => 'KBR 2.3',
            'status'      => 'Belum',
        ],
        [
            'mata_kuliah' => 'Web Programming',
            'jam'         => '09.30 – 11.30',
            'ruangan'     => 'KBR 2.3',
            'status'      => 'Hadir',
        ],
    ];
@endphp

@push('styles')
<style>
    .dp-screen {
        background-color: #1B5E35;
        min-height: 100%;
        padding-bottom: 90px;
    }

    .dp-header {
        padding: 20px 22px 18px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .dp-header h1 {
        color: #fff;
        font-size: 19px;
        font-weight: 800;
        margin: 0;
        line-height: 1.3;
    }
    .dp-header p {
        color: #b9cdbd;
        font-size: 12px;
        font-weight: 500;
        margin: 2px 0 0;
    }
    .dp-header-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dp-menu-btn {
        background: none;
        border: none;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
    }
    .dp-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.4);
    }

    .dp-body {
        padding: 0 20px;
    }

    /* Notifikasi reminder card */
    .dp-reminder {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 14px 16px;
        margin-bottom: 18px;
        position: relative;
    }
    .dp-reminder .dp-reminder-top {
        font-size: 11.5px;
        color: #6b7280;
        font-weight: 600;
        margin: 0 0 2px;
    }
    .dp-reminder .dp-reminder-title {
        font-size: 14px;
        font-weight: 800;
        color: #1f2937;
        margin: 0;
    }
    .dp-reminder .dp-reminder-sub {
        font-size: 12.5px;
        color: #6b7280;
        margin: 4px 0 0;
    }
    .dp-reminder .dp-reminder-sub a {
        color: #1B5E35;
        font-weight: 700;
        text-decoration: none;
    }
    .dp-reminder-close {
        position: absolute;
        top: 10px;
        right: 12px;
        background: none;
        border: none;
        color: #DC2626;
        font-size: 16px;
        cursor: pointer;
    }

    /* Rekap kehadiran */
    .dp-rekap-title {
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        text-align: center;
        margin: 0 0 12px;
    }
    .dp-rekap-row {
        display: flex;
        gap: 10px;
        margin-bottom: 26px;
    }
    .dp-rekap-box {
        flex: 1;
        border-radius: 14px;
        padding: 14px 8px;
        text-align: center;
    }
    .dp-rekap-box .dp-rekap-num {
        font-size: 19px;
        font-weight: 800;
        display: block;
    }
    .dp-rekap-box .dp-rekap-label {
        font-size: 11px;
        font-weight: 600;
        display: block;
        margin-top: 2px;
    }
    .dp-rekap-box.kehadiran { background-color: #ffffff; }
    .dp-rekap-box.kehadiran .dp-rekap-num,
    .dp-rekap-box.kehadiran .dp-rekap-label { color: #1B5E35; }

    .dp-rekap-box.hadir { background-color: #DCEAFE; }
    .dp-rekap-box.hadir .dp-rekap-num,
    .dp-rekap-box.hadir .dp-rekap-label { color: #1D4ED8; }

    .dp-rekap-box.absen { background-color: #FDDDE3; }
    .dp-rekap-box.absen .dp-rekap-num,
    .dp-rekap-box.absen .dp-rekap-label { color: #B91C1C; }

    /* Jadwal hari ini */
    .dp-jadwal-title {
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        margin: 0 0 12px;
    }
    .dp-jadwal-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 16px 18px;
        margin-bottom: 14px;
    }
    .dp-jadwal-card .dp-mk {
        font-size: 14.5px;
        font-weight: 800;
        color: #1f2937;
        margin: 0 0 8px;
    }
    .dp-jadwal-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dp-jadwal-info {
        font-size: 14px;
        font-weight: 700;
        color: #1B5E35;
    }
    .dp-jadwal-room {
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
        margin-left: 6px;
    }
    .dp-status-pill {
        font-size: 11.5px;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 999px;
        white-space: nowrap;
    }
    .dp-status-pill.belum { background-color: #FCE4C7; color: #B45309; }
    .dp-status-pill.hadir { background-color: #D5F2DE; color: #15803D; }

    .dp-bottom-bar {
        background-color: #1B5E35;
        height: 78px;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        max-width: 480px;
        margin: 0 auto;
    }
    .dp-camera-btn {
        position: absolute;
        top: -32px;
        left: 50%;
        transform: translateX(-50%);
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background-color: #ffffff;
        border: 4px solid #1B5E35;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0,0,0,0.3);
        text-decoration: none;
    }
    .dp-camera-btn i {
        font-size: 24px;
        color: #1B5E35;
    }
</style>
@endpush

@section('content')
<div class="dp-screen">

    <div class="dp-header">
        <div>
            <h1>{{ $userName }}</h1>
            <p>{{ $userNim }}</p>
        </div>
        <div class="dp-header-right">
            <button type="button" class="dp-menu-btn"><i class="fa-solid fa-ellipsis"></i></button>
            <img src="{{ $userPhoto }}" alt="{{ $userName }}" class="dp-avatar">
        </div>
    </div>

    <div class="dp-body">

        <div class="dp-reminder" id="dpReminder">
            <button type="button" class="dp-reminder-close" id="dpReminderClose">
                <i class="fa-solid fa-circle-xmark"></i>
            </button>
            <p class="dp-reminder-top">PresGo • Baru saja</p>
            <p class="dp-reminder-title">Pengingat Presensi</p>
            <p class="dp-reminder-sub">
                Mobile Programming 15 Menit lagi
                <a href="{{ route('mahasiswa.presensi.camera') }}">&rsaquo;</a>
            </p>
        </div>

        <p class="dp-rekap-title">Rekap Kehadiran Bulan ini</p>
        <div class="dp-rekap-row">
            <div class="dp-rekap-box kehadiran">
                <span class="dp-rekap-num">{{ $kehadiranPersen }}%</span>
                <span class="dp-rekap-label">Kehadiran</span>
            </div>
            <div class="dp-rekap-box hadir">
                <span class="dp-rekap-num">{{ $totalHadir }}</span>
                <span class="dp-rekap-label">Hadir</span>
            </div>
            <div class="dp-rekap-box absen">
                <span class="dp-rekap-num">{{ $totalAbsen }}</span>
                <span class="dp-rekap-label">Absen</span>
            </div>
        </div>

        <p class="dp-jadwal-title">Jadwal Hari Ini</p>

        @foreach ($jadwalHariIni as $jadwal)
            <div class="dp-jadwal-card">
                <p class="dp-mk">{{ $jadwal['mata_kuliah'] }}</p>
                <div class="dp-jadwal-row">
                    <div>
                        <span class="dp-jadwal-info">{{ $jadwal['jam'] }}</span>
                        <span class="dp-jadwal-room">&bull; {{ $jadwal['ruangan'] }}</span>
                    </div>
                    <span class="dp-status-pill {{ $jadwal['status'] === 'Hadir' ? 'hadir' : 'belum' }}">
                        {{ $jadwal['status'] }}
                    </span>
                </div>
            </div>
        @endforeach

    </div>

    <div class="dp-bottom-bar">
        <a href="{{ route('mahasiswa.presensi.camera') }}" class="dp-camera-btn" aria-label="Buka kamera presensi">
            <i class="fa-solid fa-camera"></i>
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.getElementById('dpReminderClose').addEventListener('click', function () {
        document.getElementById('dpReminder').style.display = 'none';
    });
</script>
@endpush