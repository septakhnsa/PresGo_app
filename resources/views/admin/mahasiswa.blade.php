@extends('layouts.admin')

@section('title', 'Daftar Mahasiswa')

@section('styles')
<style>
    .mhs-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid var(--text-dark);
        object-fit: cover;
        box-shadow: -2px 2px 0px var(--text-dark);
        background-color: var(--mint);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 800;
        border: 1.5px solid var(--text-dark);
    }

    .status-registered { background-color: var(--mint); color: var(--tosca); }
    .status-pending { background-color: #FEE2E2; color: #DC2626; }
</style>
@endsection

@section('content')
<div class="presgo-card">
    <div class="card-title" style="justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>Daftar Mahasiswa Terdaftar</span>
        </div>
        <span class="admin-badge" style="font-size: 12px;">Total: {{ $mahasiswa->count() }} Orang</span>
    </div>

    @if($mahasiswa->isEmpty())
        <div style="text-align: center; padding: 60px 0; color: var(--text-muted);">
            <i class="fa-solid fa-users-slash" style="font-size: 50px; margin-bottom: 16px; color: var(--text-muted);"></i>
            <p style="font-weight: 700; font-size: 15px;">Belum ada data mahasiswa.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table class="presgo-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Verifikasi Wajah</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswa as $mhs)
                    <tr>
                        <td style="text-align: center; width: 60px;">
                            @if($mhs->face_photo_path)
                                <img src="{{ asset('storage/' . $mhs->face_photo_path) }}" class="mhs-avatar" alt="Avatar">
                            @else
                                <div class="mhs-avatar" style="display: flex; align-items: center; justify-content: center; font-size: 16px; color: var(--tosca); font-weight: 800;">
                                    {{ substr($mhs->name, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($mhs->nim)
                                <strong style="color: var(--text-dark);">{{ $mhs->nim }}</strong>
                            @else
                                <span class="status-badge status-pending" style="font-size: 11px;">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td style="color: var(--tosca);">{{ $mhs->name }}</td>
                        <td>{{ $mhs->email }}</td>
                        <td>
                            @if($mhs->face_embedding)
                                <span class="status-badge status-registered">
                                    <i class="fa-solid fa-face-smile"></i> Terdaftar
                                </span>
                            @else
                                <span class="status-badge status-pending">
                                    <i class="fa-solid fa-circle-exclamation"></i> Belum Set
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if(!$mhs->nim)
                                <form action="{{ route('admin.mahasiswa.verify', $mhs->id) }}" method="POST" style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                    @csrf
                                    <input type="text" name="nim" placeholder="Input NIM" required style="padding: 6px 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 12px; width: 120px; outline: none;">
                                    <button type="submit" style="padding: 6px 12px; background: var(--tosca); color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-check"></i> Verifikasi
                                    </button>
                                </form>
                            @else
                                <span style="color: var(--text-muted); font-size: 12px;"><i class="fa-solid fa-check-circle" style="color: var(--green);"></i> Terverifikasi</span>
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
