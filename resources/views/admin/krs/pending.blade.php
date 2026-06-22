@extends('layouts.admin')

@section('title', 'Approval KRS Mahasiswa')

@section('content')
<div class="presgo-card">
    <div class="card-title">
        <i class="fa-solid fa-list-check"></i>
        <span>Daftar Persetujuan KRS Mahasiswa</span>
    </div>

    @if(session('success'))
        <div style="background-color: #D1FAE5; color: #065F46; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; border: 1px solid #34D399;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if($pendingUsers->isEmpty())
        <div style="text-align: center; padding: 40px 0; color: var(--text-muted);">
            <i class="fa-solid fa-check-double" style="font-size: 40px; margin-bottom: 12px; color: var(--tosca);"></i>
            <p style="font-weight: 600; font-size: 14px;">Tidak ada pengajuan KRS yang butuh persetujuan saat ini.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table class="presgo-table">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Daftar Mata Kuliah</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                        <tr>
                            <td style="font-weight: 600; color: var(--text-muted);">{{ $user->nim }}</td>
                            <td>
                                <strong style="color: var(--tosca);">{{ $user->name }}</strong>
                            </td>
                            <td>
                                <ul style="margin: 0; padding-left: 16px; font-size: 13px; color: var(--text-dark);">
                                    @foreach($user->krsRequests as $krs)
                                        <li>{{ $krs->jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah Tidak Ditemukan' }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="text-align: center;">
                                <form action="{{ route('admin.krs.approve', $user->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" style="background-color: #10B981; color: white; border: 2px solid #047857; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: -2px 2px 0px #047857; transition: all 0.2s ease;">
                                        <i class="fa-solid fa-check"></i> Setujui KRS
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
