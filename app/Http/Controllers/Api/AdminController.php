<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalKuliah;
use App\Models\MataKuliah;
use App\Models\Presensi;
use App\Models\MahasiswaJadwal;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    // =========================================================================
    // GET /api/admin/dashboard
    // =========================================================================

    /**
     * Mengembalikan ringkasan data untuk halaman dashboard admin.
     */
    public function dashboard(): JsonResponse
    {
        try {
            $today = Carbon::today();
            $hariIni = $today->locale('id')->isoFormat('dddd'); // Nama hari dalam Bahasa Indonesia

            // Mapping nama hari lokal ke nilai enum di database
            $hariMap = [
                'Senin'  => 'Senin',
                'Selasa' => 'Selasa',
                'Rabu'   => 'Rabu',
                'Kamis'  => 'Kamis',
                'Jumat'  => 'Jumat',
                'Sabtu'  => 'Sabtu',
                'Minggu' => 'Minggu',
            ];

            $hariDb = $hariMap[$hariIni] ?? $hariIni;

            $totalMahasiswa = User::where('role', 'mahasiswa')->count();

            $totalMataKuliah = MataKuliah::count();

            $totalPresensiHariIni = Presensi::whereDate('tanggal', $today)->count();

            $jadwalAktif = JadwalKuliah::with('mataKuliah')
                ->where('status', 'aktif')
                ->where('hari', $hariDb)
                ->get()
                ->map(function ($jadwal) {
                    return [
                        'id'          => $jadwal->id,
                        'nama_mk'     => $jadwal->mataKuliah->nama_mk ?? '-',
                        'kode_mk'     => $jadwal->mataKuliah->kode_mk ?? '-',
                        'sks'         => $jadwal->mataKuliah->sks ?? 0,
                        'hari'        => $jadwal->hari,
                        'jam_mulai'   => $jadwal->jam_mulai,
                        'jam_selesai' => $jadwal->jam_selesai,
                        'ruangan'     => $jadwal->ruangan,
                    ];
                });

            return response()->json([
                'success' => true,
                'data'    => [
                    'total_mahasiswa'        => $totalMahasiswa,
                    'total_mata_kuliah'      => $totalMataKuliah,
                    'total_presensi_hari_ini' => $totalPresensiHariIni,
                    'jadwal_aktif'           => $jadwalAktif,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dashboard: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // GET /api/admin/jadwal
    // =========================================================================

    /**
     * Mengembalikan seluruh jadwal kuliah yang aktif beserta data mata kuliah.
     */
    public function jadwal(): JsonResponse
    {
        try {
            $jadwals = JadwalKuliah::with('mataKuliah')
                ->where('status', 'aktif')
                ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
                ->orderBy('jam_mulai')
                ->get()
                ->map(function ($jadwal) {
                    return [
                        'id'          => $jadwal->id,
                        'nama_mk'     => $jadwal->mataKuliah->nama_mk ?? '-',
                        'kode_mk'     => $jadwal->mataKuliah->kode_mk ?? '-',
                        'sks'         => $jadwal->mataKuliah->sks ?? 0,
                        'hari'        => $jadwal->hari,
                        'jam_mulai'   => $jadwal->jam_mulai,
                        'jam_selesai' => $jadwal->jam_selesai,
                        'ruangan'     => $jadwal->ruangan,
                        'status'      => $jadwal->status,
                    ];
                });

            return response()->json([
                'success' => true,
                'data'    => $jadwals,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data jadwal: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // GET /api/admin/presensi/{jadwal_id}
    // =========================================================================

    /**
     * Mengembalikan daftar presensi mahasiswa untuk jadwal tertentu hari ini.
     */
    public function presensi(int $jadwal_id): JsonResponse
    {
        try {
            // Validasi: pastikan jadwal ada
            $jadwal = JadwalKuliah::with('mataKuliah')->find($jadwal_id);

            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal dengan ID ' . $jadwal_id . ' tidak ditemukan.',
                ], 404);
            }

            $presensis = Presensi::with('user')
                ->where('jadwal_id', $jadwal_id)
                ->orderBy('tanggal', 'desc')
                ->orderBy('jam_masuk', 'asc')
                ->get()
                ->map(function ($presensi) {
                    return [
                        'id'             => $presensi->id,
                        'tanggal'        => $presensi->tanggal
                                            ? $presensi->tanggal->format('Y-m-d')
                                            : null,
                        'nama_mahasiswa' => $presensi->user->name ?? '-',
                        'nim'            => $presensi->user->nim ?? '-',
                        'jam_masuk'      => $presensi->jam_masuk,
                        'status_wajah'   => $presensi->status_wajah,
                        'foto_wajah'     => $presensi->foto_wajah,
                        'latitude'       => $presensi->latitude,
                        'longitude'      => $presensi->longitude,
                    ];
                });

            return response()->json([
                'success' => true,
                'data'    => [
                    'jadwal'   => [
                        'id'          => $jadwal->id,
                        'nama_mk'     => $jadwal->mataKuliah->nama_mk ?? '-',
                        'kode_mk'     => $jadwal->mataKuliah->kode_mk ?? '-',
                        'hari'        => $jadwal->hari,
                        'jam_mulai'   => $jadwal->jam_mulai,
                        'jam_selesai' => $jadwal->jam_selesai,
                        'ruangan'     => $jadwal->ruangan,
                    ],
                    'total_hadir' => $presensis->count(),
                    'presensis'   => $presensis,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data presensi: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function approveKrs($id): JsonResponse
{
    $krs = MahasiswaJadwal::findOrFail($id);

    $krs->update([
        'status' => 'approved'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'KRS berhasil di-approve'
    ]);
}
public function rejectKrs($id): JsonResponse
{
    $krs = MahasiswaJadwal::findOrFail($id);

    $krs->update([
        'status' => 'rejected'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'KRS ditolak'
    ]);
}
public function krsPending(): JsonResponse
{
    $data = MahasiswaJadwal::with(['user', 'jadwal.mataKuliah'])
        ->where('status', 'pending')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'mahasiswa' => $item->user->name ?? '-',
                'nim' => $item->user->nim ?? '-',
                'mata_kuliah' => $item->jadwal->mataKuliah->nama_mk ?? '-',
                'sks' => $item->jadwal->mataKuliah->sks ?? 0,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $data
    ]);
}
}
