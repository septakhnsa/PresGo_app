@extends('layouts.mahasiswa')

@section('title', 'Notifikasi - PresGo')

@php
    \Carbon\Carbon::setLocale('id');
    $todayLabel = now()->translatedFormat('l, d F Y');

    // Contoh struktur data notifikasi — ganti dengan data asli dari controller
    $notifikasiList = $notifikasiList ?? [
        [
            'judul'      => 'Pengingat Presensi',
            'waktu'      => 'Baru saja',
            'pesan'      => 'Kelas Mobile Programming dimulai 15 Menit lagi',
            'is_reminder_aktif' => true,
        ],
        [
            'judul'      => 'Pengingat Presensi',
            'waktu'      => '13.00',
            'pesan'      => 'Presensi Berhasil! Mobile Programming tercatat hadir',
            'is_reminder_aktif' => false,
        ],
        [
            'judul'      => 'Pengingat Presensi',
            'waktu'      => '13.00',
            'pesan'      => 'Presensi Berhasil! Mobile Programming tercatat hadir',
            'is_reminder_aktif' => false,
        ],
    ];
@endphp

@push('styles')
<style>
    .nt-screen {
        background-color: #1B5E35;
        min-height: 100%;
        display: flex;
        flex-direction: column;
    }

    .nt-header {
        padding: 22px 22px 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-shrink: 0;
    }
    .nt-header h1 {
        color: #fff;
        font-size: 19px;
        font-weight: 800;
        margin: 0;
    }
    .nt-header p {
        color: #b9cdbd;
        font-size: 12px;
        font-weight: 500;
        margin: 3px 0 0;
    }
    .nt-menu-btn {
        background: none;
        border: none;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
    }

    .nt-body {
        flex: 1;
        padding: 4px 18px 24px;
        overflow-y: auto;
    }

    .nt-card {
        background-color: #F7F5EC;
        border-radius: 16px;
        padding: 14px 16px;
        margin-bottom: 14px;
        display: flex;
        gap: 12px;
    }
    .nt-card.unread {
        background-color: #ffffff;
    }

    .nt-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: #1B5E35;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Palatino Linotype', Palatino, 'Book Antiqua', Georgia, serif;
        font-style: italic;
        font-weight: 700;
        color: #fff;
        font-size: 18px;
    }

    .nt-content { flex: 1; }
    .nt-meta {
        font-size: 11px;
        color: #6b7280;
        font-weight: 600;
        margin: 0 0 2px;
    }
    .nt-title {
        font-size: 14px;
        font-weight: 800;
        color: #1f2937;
        margin: 0 0 4px;
    }
    .nt-message {
        font-size: 12.5px;
        color: #6b7280;
        line-height: 1.5;
        margin: 0;
    }

    .nt-actions {
        display: flex;
        gap: 10px;
        margin-top: 12px;
    }
    .nt-btn {
        flex: 1;
        text-align: center;
        font-size: 12.5px;
        font-weight: 700;
        padding: 9px 0;
        border-radius: 10px;
        text-decoration: none;
        cursor: pointer;
        border: none;
    }
    .nt-btn-primary {
        background-color: #1B5E35;
        color: #ffffff;
    }
    .nt-btn-secondary {
        background-color: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .nt-bottom-bar {
        background-color: #1B5E35;
        height: 78px;
        flex-shrink: 0;
        position: relative;
    }
    .nt-back-btn {
        position: absolute;
        top: -32px;
        left: 50%;
        transform: translateX(-50%);
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0,0,0,0.3);
        text-decoration: none;
    }
    .nt-back-btn i {
        font-size: 22px;
        color: #DC2626;
    }
</style>
@endpush

@section('content')
<div class="nt-screen">

    <div class="nt-header">
        <div>
            <h1>Notifikasi <i class="fa-solid fa-bell" style="font-size:15px;"></i></h1>
            <p>{{ $todayLabel }}</p>
        </div>
        <button type="button" class="nt-menu-btn"><i class="fa-solid fa-ellipsis"></i></button>
    </div>

    <div class="nt-body">
        @foreach ($notifikasiList as $i => $notif)
            <div class="nt-card {{ $i === 0 ? 'unread' : '' }}">
                <div class="nt-icon">P</div>
                <div class="nt-content">
                    <p class="nt-meta">PresGo &bull; {{ $notif['waktu'] }}</p>
                    <p class="nt-title">{{ $notif['judul'] }}</p>
                    <p class="nt-message">{{ $notif['pesan'] }}</p>

                    @if ($notif['is_reminder_aktif'])
                        <div class="nt-actions">
                            <a href="{{ route('mahasiswa.presensi.camera') }}" class="nt-btn nt-btn-primary">Presensi Sekarang</a>
                            <button type="button" class="nt-btn nt-btn-secondary">Abaikan</button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="nt-bottom-bar">
        <a href="{{ route('mahasiswa.home') }}" class="nt-back-btn" aria-label="Kembali ke Home">
            <i class="fa-solid fa-house"></i>
        </a>
    </div>

</div>
@endsection