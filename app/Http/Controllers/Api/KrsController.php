<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KrsRequest;
use App\Models\MahasiswaJadwal;
use Illuminate\Support\Facades\DB;

class KrsController extends Controller
{
    // =========================================================
    // USER: AJUKAN KRS (PENDING)
    // =========================================================
    public function store(Request $request)
{
    $request->validate([
        'jadwal_ids' => 'required|array'
    ]);

    $user = $request->user();

    foreach ($request->jadwal_ids as $jadwal_id) {
        \App\Models\KrsRequest::create([
            'user_id' => $user->id,
            'jadwal_id' => $jadwal_id,
            'status' => 'pending'
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'KRS masuk pending'
    ]);
}

    // =========================================================
    // ADMIN: LIHAT KRS PENDING
    // =========================================================
    public function pending()
{
    $data = DB::table('krs_requests')
        ->join('users', 'krs_requests.user_id', '=', 'users.id')
        ->join('jadwal_kuliah', 'krs_requests.jadwal_id', '=', 'jadwal_kuliah.id')
        ->join('mata_kuliah', 'jadwal_kuliah.mata_kuliah_id', '=', 'mata_kuliah.id')
        ->select(
            'krs_requests.id',
            'users.name as mahasiswa',
            'mata_kuliah.nama_mk',
            'mata_kuliah.sks',
            'krs_requests.status'
        )
        ->where('krs_requests.status', 'pending')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $data
    ]);
}

    // =========================================================
    // ADMIN: APPROVE KRS
    // =========================================================
    public function approve($id)
{
    $krs = KrsRequest::findOrFail($id);

    // update status request
    $krs->status = 'approved';
    $krs->save();

    $user = $krs->user;
    $user->krs_completed = 1;
    $user->save();

    // masuk ke tabel final mahasiswa_jadwal
    MahasiswaJadwal::updateOrCreate([
        'user_id' => $krs->user_id,
        'jadwal_id' => $krs->jadwal_id,
    ], [
        'status' => 'approved'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'KRS berhasil disetujui & masuk jadwal mahasiswa'
    ]);
}

    // =========================================================
    // ADMIN: REJECT KRS
    // =========================================================
    public function reject($id)
    {
        $krs = KrsRequest::findOrFail($id);

        $krs->status = 'rejected';
        $krs->save();

        return response()->json([
            'success' => true,
            'message' => 'KRS ditolak'
        ]);
    }
    public function matakuliah()
{
    return response()->json([
        'status' => 'success',
        'data' => \App\Models\MataKuliah::select('id', 'nama_mk', 'sks')->get()
    ]);
}
}