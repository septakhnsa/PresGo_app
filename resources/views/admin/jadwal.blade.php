@extends('layouts.admin')

@section('title', 'Jadwal Kuliah')

@section('styles')
<style>
    .day-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 11px;
        border: 1.5px solid var(--text-dark);
        box-shadow: -2px 2px 0px var(--text-dark);
    }

    .day-senin { background-color: #DBEAFE; color: #1E40AF; }
    .day-selasa { background-color: #FEE2E2; color: #991B1B; }
    .day-rabu { background-color: #FEF3C7; color: #92400E; }
    .day-kamis { background-color: #ECEFEE; color: #4B5563; }
    .day-jumat { background-color: var(--mint); color: var(--tosca); }
    .day-sabtu { background-color: #F3E8FF; color: #6B21A8; }
    .day-minggu { background-color: #F1F5F9; color: #1E293B; }
</style>
@endsection

@section('content')
<div class="presgo-card">
    <div class="card-title" style="justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-calendar-days"></i>
            <span>Jadwal Kuliah Terdaftar</span>
        </div>
        <span class="admin-badge" style="font-size: 12px;">Total: {{ $jadwals->count() }} Kelas</span>
    </div>

    @if($jadwals->isEmpty())
        <div style="text-align: center; padding: 60px 0; color: var(--text-muted);">
            <i class="fa-solid fa-calendar-xmark" style="font-size: 50px; margin-bottom: 16px; color: var(--text-muted);"></i>
            <p style="font-weight: 700; font-size: 15px;">Belum ada jadwal kuliah yang ditambahkan.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table class="presgo-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen Pengampu</th>
                        <th>Ruang</th>
                        <th>Waktu</th>
                        <th>SKS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $jadwal)
                    @php
                        $dayClass = 'day-' . strtolower($jadwal->hari);
                    @endphp
                    <tr>
                        <td>
                            <span class="day-badge {{ $dayClass }}">{{ $jadwal->hari }}</span>
                        </td>
                        <td>
                            <div>
                                <strong style="color: var(--tosca);">{{ $jadwal->mataKuliah->nama_mk }}</strong><br>
                                <span style="font-size: 11px; color: var(--text-muted);">{{ $jadwal->mataKuliah->kode_mk }}</span>
                            </div>
                        </td>
                        <td>{{ $jadwal->dosen }}</td>
                        <td><strong style="color: var(--text-dark);">{{ $jadwal->ruangan }}</strong></td>
                        <td>
                            <span style="background-color: var(--bg-light); padding: 4px 8px; border-radius: 6px; border: 1.5px solid var(--text-dark); font-size: 11px; font-weight: 800;">
                                {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                            </span>
                        </td>
                        <td>
                            <span style="background-color: var(--gold); color: var(--tosca); padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 900; border: 1px solid var(--text-dark);">
                                {{ $jadwal->mataKuliah->sks }} SKS
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
