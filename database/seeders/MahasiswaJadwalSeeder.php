<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaJadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mendaftarkan SEMUA mahasiswa ke SEMUA jadwal kuliah yang tersedia.
     */
    public function run(): void
    {
        // Ambil semua mahasiswa (role = 'mahasiswa')
        $mahasiswaIds = DB::table('users')
            ->where('role', 'mahasiswa')
            ->pluck('id');

        // Ambil semua jadwal kuliah yang aktif
        $jadwalIds = DB::table('jadwal_kuliah')
            ->where('status', 'aktif')
            ->pluck('id');

        foreach ($mahasiswaIds as $userId) {
            foreach ($jadwalIds as $jadwalId) {
                DB::table('mahasiswa_jadwal')->updateOrInsert(
                    [
                        'user_id'   => $userId,
                        'jadwal_id' => $jadwalId,
                    ],
                    [
                        'user_id'    => $userId,
                        'jadwal_id'  => $jadwalId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
