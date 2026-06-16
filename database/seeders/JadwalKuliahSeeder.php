<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID mata kuliah berdasarkan kode_mk
        $mkMap = DB::table('mata_kuliah')
            ->pluck('id', 'kode_mk');

        $jadwal = [
            [
                'mata_kuliah_id' => $mkMap['MK#2'],
                'hari'           => 'Senin',
                'jam_mulai'      => '08:30:00',
                'jam_selesai'    => '09:30:00',
                'ruangan'        => 'Kampus Berkoh - Ruang 2.1',
                'status'         => 'aktif',
            ],
            [
                'mata_kuliah_id' => $mkMap['MK#5'],
                'hari'           => 'Senin',
                'jam_mulai'      => '11:00:00',
                'jam_selesai'    => '13:00:00',
                'ruangan'        => 'Kampus Berkoh - Ruang 2.1',
                'status'         => 'aktif',
            ],
            [
                'mata_kuliah_id' => $mkMap['MK#67'],
                'hari'           => 'Selasa',
                'jam_mulai'      => '08:30:00',
                'jam_selesai'    => '09:30:00',
                'ruangan'        => 'Kampus Suwatio - Ruang 1.2',
                'status'         => 'aktif',
            ],
            [
                'mata_kuliah_id' => $mkMap['MK#29'],
                'hari'           => 'Rabu',
                'jam_mulai'      => '10:00:00',
                'jam_selesai'    => '12:00:00',
                'ruangan'        => 'Kampus Berkoh - Ruang 2.3',
                'status'         => 'aktif',
            ],
            [
                'mata_kuliah_id' => $mkMap['MK#41'],
                'hari'           => 'Kamis',
                'jam_mulai'      => '09:30:00',
                'jam_selesai'    => '11:00:00',
                'ruangan'        => 'Kampus Berkoh - Ruang 2.3',
                'status'         => 'aktif',
            ],
            [
                'mata_kuliah_id' => $mkMap['MK#51'],
                'hari'           => 'Jumat',
                'jam_mulai'      => '09:30:00',
                'jam_selesai'    => '11:30:00',
                'ruangan'        => 'Kampus Berkoh - Lab 2',
                'status'         => 'aktif',
            ],
        ];

        foreach ($jadwal as $item) {
            DB::table('jadwal_kuliah')->updateOrInsert(
                [
                    'mata_kuliah_id' => $item['mata_kuliah_id'],
                    'hari'           => $item['hari'],
                ],
                array_merge($item, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
