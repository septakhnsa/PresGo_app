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
                ->whereDate('tanggal', today('Asia/Jakarta'))
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
        $today = now('Asia/Jakarta')->toDateString();
        $currentTime = now('Asia/Jakarta')->toTimeString();

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


    /**
 * Get attendance recap for the authenticated user.
 */
public function getRekapKehadiran(Request $request)
{
    $user = $request->user();
    $bulan = $request->query('bulan', now()->month);
    $tahun = $request->query('tahun', now()->year);

    $hadir = \App\Models\Presensi::where('user_id', $user->id)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->count();

    // Total = jumlah jadwal unik yang sudah lewat bulan ini
    // (setiap hari ada jadwal = 1 kesempatan presensi)
    $jadwals = $user->jadwals()->get();
    if ($jadwals->isEmpty()) {
        $jadwals = \App\Models\JadwalKuliah::where('status', 'aktif')->get();
    }

    $hariJadwal = $jadwals->pluck('hari')->unique()->values();

    // Hitung berapa hari dalam bulan ini yang hari-nya ada jadwal & sudah lewat
    $total = 0;
    $daysInMonth = now()->setMonth($bulan)->setYear($tahun)->daysInMonth;
    $today = now()->toDateString();
    $userCreatedAt = $user->created_at ? $user->created_at->toDateString() : '2000-01-01';

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = \Carbon\Carbon::create($tahun, $bulan, $day);
        
        // Jangan hitung jika tanggal berada di masa depan
        if ($date->toDateString() > $today) break;
        
        // Jangan hitung jika tanggal sebelum akun user dibuat
        if ($date->toDateString() < $userCreatedAt) continue;

        $hariIndo = match($date->dayOfWeek) {
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa',
            3 => 'Rabu',   4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
        };

        $total += $jadwals->where('hari', $hariIndo)->count();
    }

    $absen = max(0, $total - $hadir);
    $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;

    return response()->json([
        'hadir'      => $hadir,
        'absen'      => $absen,
        'total'      => $total,
        'persentase' => $persen,
    ]);
}

    /**
     * Get all attendance history for the authenticated user.
     */
    public function getMyHistory(Request $request)
    {
        $user = $request->user();
        
        $presensis = \App\Models\Presensi::with(['jadwalKuliah.mataKuliah'])
            ->where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->get();

        $formattedHistory = $presensis->map(function ($p) {
            return [
                'id' => (string) $p->id,
                'jadwal_id' => (string) $p->jadwal_id,
                'tanggal' => $p->tanggal,
                'jam_masuk' => substr($p->jam_masuk, 0, 5),
                'foto' => $p->foto_wajah,
                'status_wajah' => $p->status_wajah,
                'mataKuliah' => $p->jadwalKuliah && $p->jadwalKuliah->mataKuliah ? $p->jadwalKuliah->mataKuliah->nama_mk : 'Unknown',
                'kode' => $p->jadwalKuliah && $p->jadwalKuliah->mataKuliah ? $p->jadwalKuliah->mataKuliah->kode_mk : '-',
                'dosen' => $p->jadwalKuliah ? $p->jadwalKuliah->dosen : 'Dosen',
                'ruangan' => $p->jadwalKuliah ? $p->jadwalKuliah->ruangan : '-',
                'jamMulai' => $p->jadwalKuliah ? substr($p->jadwalKuliah->jam_mulai, 0, 5) : '00:00',
                'jamSelesai' => $p->jadwalKuliah ? substr($p->jadwalKuliah->jam_selesai, 0, 5) : '00:00',
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formattedHistory
        ], 200);
    }
}
