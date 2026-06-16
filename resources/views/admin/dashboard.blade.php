@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
<style>
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border: var(--border-width) solid var(--text-dark);
        border-radius: 20px;
        padding: 20px;
        box-shadow: -5px 5px 0px var(--text-dark);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s ease;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        border: 2px solid var(--text-dark);
        box-shadow: -2px 2px 0px var(--text-dark);
    }

    .stat-info h3 {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-dark);
    }

    .stat-info p {
        font-size: 11px;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Colors for stats icon background */
    .color-1 { background-color: var(--mint); color: var(--tosca); }
    .color-2 { background-color: #DBEAFE; color: #1D4ED8; }
    .color-3 { background-color: #FEE2E2; color: #DC2626; }
    .color-4 { background-color: #FEF3C7; color: #D97706; }

    /* Dashboard Layout Columns */
    .dashboard-columns {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 24px;
    }



    /* Live Badge */
    .badge-live {
        background-color: #EF4444;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { opacity: 0.6; }
        50% { opacity: 1; }
        100% { opacity: 0.6; }
    }

    /* List class */
    .class-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px;
        border: 2px solid var(--text-dark);
        border-radius: 12px;
        margin-bottom: 10px;
        background-color: white;
    }

    .class-info h4 {
        font-size: 14px;
        font-weight: 700;
    }

    .class-info p {
        font-size: 11px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .time-badge {
        background-color: var(--mint);
        color: var(--tosca);
        font-size: 11px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid var(--tosca);
    }

    /* Responsive adjustments */
    @media (max-width: 900px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 16px;
        }

        .stat-card {
            padding: 10px 8px;
            gap: 8px;
            border-radius: 12px;
            box-shadow: -3px 3px 0px var(--text-dark);
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            font-size: 14px;
            border-radius: 8px;
            box-shadow: -1px 1px 0px var(--text-dark);
            flex-shrink: 0;
        }

        .stat-info h3 {
            font-size: 16px;
            line-height: 1.2;
        }

        .stat-info p {
            font-size: 9px;
            letter-spacing: 0.2px;
        }

        .class-item {
            padding: 8px;
            margin-bottom: 8px;
        }

        .class-info h4 {
            font-size: 12px;
        }

        .class-info p {
            font-size: 10px;
        }

        .time-badge {
            font-size: 10px;
            padding: 2px 6px;
        }

        .dashboard-columns {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }
</style>
@endsection

@section('content')
<!-- Stats Row -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon color-1">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <div class="stat-info">
            <h3 id="stat-mahasiswa">{{ $totalMahasiswa }}</h3>
            <p>Mahasiswa</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon color-2">
            <i class="fa-solid fa-book"></i>
        </div>
        <div class="stat-info">
            <h3 id="stat-matakuliah">{{ $totalMataKuliah }}</h3>
            <p>Mata Kuliah</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon color-4">
            <i class="fa-solid fa-calendar-week"></i>
        </div>
        <div class="stat-info">
            <h3 id="stat-kelas">{{ $totalJadwal }}</h3>
            <p>Total Kelas</p>
        </div>
    </div>
    <div class="stat-card" style="background-color: var(--gold); border-color: var(--text-dark);">
        <div class="stat-icon color-3" style="background-color: white;">
            <i class="fa-solid fa-check-double"></i>
        </div>
        <div class="stat-info">
            <h3 id="stat-hadir">{{ $totalHadir }}</h3>
            <p style="color: var(--tosca);">Hadir Hari Ini</p>
        </div>
    </div>
</div>

<div class="dashboard-columns">
    <!-- Left Column: Live attendance stream -->
    <div class="presgo-card">
        <div class="card-title">
            <i class="fa-solid fa-wave-square"></i>
            <span>Log Kehadiran Mahasiswa</span>
            <span class="badge-live"><i class="fa-solid fa-circle"></i> Live Feed</span>
        </div>
        
        <div id="attendance-feed-container">
            @if($presensiHariIni->isEmpty())
                <div style="text-align: center; padding: 40px 0; color: var(--text-muted);">
                    <i class="fa-solid fa-clipboard-question" style="font-size: 40px; margin-bottom: 12px; color: var(--tosca);"></i>
                    <p style="font-weight: 600; font-size: 13px;">Belum ada absensi masuk hari ini.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table class="presgo-table">
                        <thead>
                            <tr>
                                <th>Mahasiswa</th>
                                <th>Mata Kuliah</th>
                                <th>Jam Absen</th>
                                <th>Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody id="feed-table-body">
                            @foreach($presensiHariIni as $presensi)
                            <tr>
                                <td>
                                    <div>
                                        <strong style="color: var(--tosca);">{{ $presensi->user->name ?? '-' }}</strong><br>
                                        <span style="font-size: 11px; color: var(--text-muted);">{{ $presensi->user->nim ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>{{ $presensi->jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                                <td><span class="time-badge">{{ substr($presensi->jam_masuk, 0, 5) }}</span></td>
                                <td>
                                    @if($presensi->latitude && $presensi->longitude)
                                    <a href="https://www.google.com/maps?q={{ $presensi->latitude }},{{ $presensi->longitude }}" target="_blank" style="background-color: #D1FAE5; color: #065F46; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-location-dot"></i> Maps
                                    </a>
                                    @else
                                    <span style="background-color: #FEE2E2; color: #991B1B; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-shield-halved"></i> Valid
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Classes Active Today -->
    <div class="presgo-card">
        <div class="card-title">
            <i class="fa-solid fa-clock"></i>
            <span>Jadwal Kuliah Hari Ini</span>
        </div>

        @if($jadwalHariIni->isEmpty())
            <div style="text-align: center; padding: 40px 0; color: var(--text-muted);">
                <i class="fa-solid fa-calendar-xmark" style="font-size: 40px; margin-bottom: 12px; color: var(--text-muted);"></i>
                <p style="font-weight: 600; font-size: 13px;">Tidak ada jadwal aktif untuk hari ini.</p>
            </div>
        @else
            @foreach($jadwalHariIni as $jadwal)
            <div class="class-item">
                <div class="class-info">
                    <h4>{{ $jadwal->mataKuliah->nama_mk }}</h4>
                    <p>{{ $jadwal->ruangan }} | Dosen: {{ $jadwal->dosen }}</p>
                </div>
                <span class="time-badge">{{ substr($jadwal->jam_mulai, 0, 5) }}</span>
            </div>
            @endforeach
        @endif
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Polling setiap 5 detik untuk memperbarui dashboard secara real-time
        setInterval(fetchRealtimeFeed, 5000);

        function fetchRealtimeFeed() {
            fetch("{{ route('admin.dashboard.api') }}")
                .then(response => response.json())
                .then(data => {
                    // Update stats
                    document.getElementById("stat-mahasiswa").innerText = data.totalMahasiswa;
                    document.getElementById("stat-matakuliah").innerText = data.totalMataKuliah;
                    document.getElementById("stat-kelas").innerText = data.totalJadwal;
                    document.getElementById("stat-hadir").innerText = data.totalHadir;

                    const container = document.getElementById("attendance-feed-container");
                    
                    if (data.feed.length === 0) {
                        container.innerHTML = `
                            <div style="text-align: center; padding: 40px 0; color: var(--text-muted);">
                                <i class="fa-solid fa-clipboard-question" style="font-size: 40px; margin-bottom: 12px; color: var(--tosca);"></i>
                                <p style="font-weight: 600; font-size: 13px;">Belum ada absensi masuk hari ini.</p>
                            </div>
                        `;
                    } else {
                        let html = `
                            <div style="overflow-x: auto;">
                                <table class="presgo-table">
                                    <thead>
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <th>Mata Kuliah</th>
                                            <th>Jam Absen</th>
                                            <th>Verifikasi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="feed-table-body">
                        `;

                        data.feed.forEach(presensi => {
                            let verifikasiHtml = '';
                            if (presensi.lat && presensi.lng) {
                                verifikasiHtml = `
                                    <a href="https://www.google.com/maps?q=${presensi.lat},${presensi.lng}" target="_blank" style="background-color: #D1FAE5; color: #065F46; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-location-dot"></i> Maps
                                    </a>
                                `;
                            } else {
                                verifikasiHtml = `
                                    <span style="background-color: #D1FAE5; color: #065F46; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-shield-halved"></i> Valid
                                    </span>
                                `;
                            }

                            html += `
                                <tr>
                                    <td>
                                        <div>
                                            <strong style="color: var(--tosca);">${presensi.nama}</strong><br>
                                            <span style="font-size: 11px; color: var(--text-muted);">${presensi.nim}</span>
                                        </div>
                                    </td>
                                    <td>${presensi.mata_kuliah}</td>
                                    <td><span class="time-badge">${presensi.jam}</span></td>
                                    <td>${verifikasiHtml}</td>
                                </tr>
                            `;
                        });

                        html += `
                                    </tbody>
                                </table>
                            </div>
                        `;
                        container.innerHTML = html;
                    }
                })
                .catch(error => console.error("Error fetching live feed:", error));
        }
    });
</script>
@endsection
