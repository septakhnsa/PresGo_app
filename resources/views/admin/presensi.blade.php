@extends('layouts.admin')

@section('title', 'Riwayat Presensi')

@section('styles')
<style>
    .photo-preview {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        border: 1.5px solid var(--text-dark);
        object-fit: cover;
        box-shadow: -2px 2px 0px var(--text-dark);
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .photo-preview:hover {
        transform: scale(1.1);
    }

    .method-badge {
        background-color: #E0F2FE;
        color: #0369A1;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 800;
        border: 1px solid #0369A1;
    }

    .location-badge {
        background-color: #FEF3C7;
        color: #B45309;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 800;
        border: 1px solid #B45309;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
</style>
@endsection

@section('content')
<div class="presgo-card">
    <div class="card-title" style="justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-list-check"></i>
            <span>Log Riwayat Kehadiran Mahasiswa</span>
        </div>
        <span class="admin-badge" style="font-size: 12px;">Total: {{ $presensis->count() }} Kehadiran</span>
    </div>

    @if($presensis->isEmpty())
        <div style="text-align: center; padding: 60px 0; color: var(--text-muted);">
            <i class="fa-solid fa-folder-open" style="font-size: 50px; margin-bottom: 16px; color: var(--text-muted);"></i>
            <p style="font-weight: 700; font-size: 15px;">Belum ada riwayat presensi yang tercatat.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table class="presgo-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Waktu Presensi</th>
                        <th>Metode</th>
                        <th>Validasi GPS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($presensis as $presensi)
                    <tr>
                        <td style="text-align: center; width: 70px;">
                            @if($presensi->foto_wajah)
                                <img src="{{ asset('storage/' . $presensi->foto_wajah) }}" class="photo-preview" alt="Foto Absen">
                            @else
                                <div style="font-size: 11px; color: var(--text-muted);">No Photo</div>
                            @endif
                        </td>
                        <td>
                            <div>
                                <strong style="color: var(--tosca);">{{ $presensi->user->name ?? '-' }}</strong><br>
                                <span style="font-size: 11px; color: var(--text-muted);">{{ $presensi->user->nim ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $presensi->jadwal->mataKuliah->nama_mk ?? '-' }}</strong><br>
                                <span style="font-size: 11px; color: var(--text-muted);">Ruang {{ $presensi->jadwal->ruangan ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong style="color: var(--text-dark);">
                                    {{ $presensi->tanggal instanceof \Carbon\Carbon ? $presensi->tanggal->format('d M Y') : $presensi->tanggal }}
                                </strong><br>
                                <span style="font-size: 11px; color: var(--text-muted);">Jam {{ substr($presensi->jam_masuk, 0, 5) }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="method-badge">
                                <i class="fa-solid fa-face-smile"></i> Face & GPS
                            </span>
                        </td>
                        <td>
                            @if($presensi->latitude && $presensi->longitude)
                            <a href="https://www.google.com/maps?q={{ $presensi->latitude }},{{ $presensi->longitude }}" target="_blank" class="location-badge" style="text-decoration: none;">
                                <i class="fa-solid fa-location-crosshairs"></i> Lihat Lokasi
                            </a>
                            @else
                            <span class="location-badge" style="background-color: #FEE2E2; color: #991B1B; border-color: #991B1B;">
                                <i class="fa-solid fa-triangle-exclamation"></i> Tanpa GPS
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
@endsection
