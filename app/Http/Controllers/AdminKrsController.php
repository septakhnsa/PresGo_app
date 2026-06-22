<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KrsRequest;
use App\Models\MahasiswaJadwal;

class AdminKrsController extends Controller
{
    /**
     * Tampilkan daftar mahasiswa yang butuh persetujuan KRS.
     */
    public function index()
    {
        // Ambil mahasiswa yang punya request KRS berstatus 'pending'
        $pendingUsers = User::whereHas('krsRequests', function ($query) {
            $query->where('status', 'pending');
        })
        ->with(['krsRequests' => function ($query) {
            $query->where('status', 'pending');
        }, 'krsRequests.jadwal.mataKuliah'])
        ->get();

        return view('admin.krs.pending', compact('pendingUsers'));
    }

    /**
     * Proses persetujuan KRS untuk mahasiswa tertentu.
     */
    public function approve($id)
    {
        // 1. Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // 2. Ubah krs_completed jadi 1
        $user->krs_completed = 1;
        $user->save();

        // 3. Ambil semua krs_request yang masih pending untuk user ini
        $krsRequests = KrsRequest::where('user_id', $id)
            ->where('status', 'pending')
            ->get();

        foreach ($krsRequests as $krs) {
            // Update status di krs_requests menjadi 'approved'
            $krs->status = 'approved';
            $krs->save();

            // Masukkan ke tabel mahasiswa_jadwal sebagai jadwal resmi
            MahasiswaJadwal::updateOrCreate([
                'user_id' => $krs->user_id,
                'jadwal_id' => $krs->jadwal_id,
            ]);
        }

        return redirect()->back()->with('success', 'KRS Mahasiswa ' . $user->name . ' berhasil disetujui.');
    }
}
