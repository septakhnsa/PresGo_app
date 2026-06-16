<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Get schedules for the authenticated user.
     */
    public function getMyJadwal(Request $request)
    {
        $user = $request->user();
        
        // Jika user belum diverifikasi (NIM masih null), jangan kembalikan jadwal apapun.
        if (is_null($user->nim)) {
            return response()->json([
                'status' => 'success',
                'data' => []
            ], 200);
        }

        // Fetch schedules associated with the user, including the MataKuliah details.
        $jadwals = $user->jadwals()->with('mataKuliah')->get();

        // Fallback: Jika mahasiswa sudah terverifikasi tapi belum di-assign jadwal spesifik,
        // tampilkan semua jadwal aktif dari database agar data presensi tetap sinkron.
        if ($jadwals->isEmpty()) {
            $jadwals = \App\Models\JadwalKuliah::with('mataKuliah')->where('status', 'aktif')->get();
        }

        // Map them to match the structure the Flutter app expects
        $formattedJadwals = $jadwals->map(function ($jadwal) use ($user) {
            // Check if there is an active attendance record for this user, this schedule, and today
            $absen = \App\Models\Presensi::where('user_id', $user->id)
                ->where('jadwal_id', $jadwal->id)
                ->whereDate('tanggal', today())
                ->first();

            return [
                'id'         => (string) $jadwal->id,
                'mataKuliah' => $jadwal->mataKuliah ? $jadwal->mataKuliah->nama_mk : 'Unknown',
                'kode'       => $jadwal->mataKuliah ? $jadwal->mataKuliah->kode_mk : '-',
                'dosen'      => $jadwal->dosen ?? 'Dosen Pengampu',
                'ruangan'    => $jadwal->ruangan ?? '-',
                'hari'       => $jadwal->hari,
                'jamMulai'   => substr($jadwal->jam_mulai, 0, 5),
                'jamSelesai' => substr($jadwal->jam_selesai, 0, 5),
                'status'     => $absen ? 'Sudah Absen' : 'Belum Absen',
                'foto'       => $absen ? $absen->foto_wajah : null,
                'jamAbsen'   => $absen ? substr($absen->jam_masuk, 0, 5) : null,
                'tanggalAbsen' => $absen ? $absen->tanggal : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formattedJadwals
        ], 200);
    }

    /**
     * Submit attendance for a specific class schedule.
     */
    public function submitPresensi(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_kuliah,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:10240', // Max 10MB
        ]);

        $user = $request->user();
        $today = now()->toDateString();
        $currentTime = now()->toTimeString();

        // Check if user already submitted attendance today for this schedule
        $existing = \App\Models\Presensi::where('user_id', $user->id)
            ->where('jadwal_id', $request->jadwal_id)
            ->where('tanggal', $today)
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah melakukan presensi hari ini untuk mata kuliah ini.'
            ], 422);
        }

        // Store photo in public disk
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('presensi_photos', 'public');
        }

        // Create presensi record
        $presensi = \App\Models\Presensi::create([
            'user_id' => $user->id,
            'jadwal_id' => $request->jadwal_id,
            'tanggal' => $today,
            'jam_masuk' => $currentTime,
            'status_wajah' => 'valid', // Default verified face status
            'foto_wajah' => $photoPath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Presensi berhasil disimpan.',
            'data' => $presensi
        ], 201);
    }
}
