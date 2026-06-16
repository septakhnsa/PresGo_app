<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataKuliah = [
            [
                'kode_mk'  => 'MK#2',
                'nama_mk'  => 'Metodologi Penelitian',
                'sks'      => 2,
            ],
            [
                'kode_mk'  => 'MK#5',
                'nama_mk'  => 'Komputasi Awan',
                'sks'      => 4,
            ],
            [
                'kode_mk'  => 'MK#67',
                'nama_mk'  => 'Rekayasa Perangkat Lunak',
                'sks'      => 3,
            ],
            [
                'kode_mk'  => 'MK#29',
                'nama_mk'  => 'Mobile Programming Lanjut',
                'sks'      => 4,
            ],
            [
                'kode_mk'  => 'MK#41',
                'nama_mk'  => 'Kecerdasan Buatan',
                'sks'      => 3,
            ],
            [
                'kode_mk'  => 'MK#51',
                'nama_mk'  => 'Web Programming Lanjut',
                'sks'      => 4,
            ],
        ];

        foreach ($mataKuliah as $mk) {
            DB::table('mata_kuliah')->updateOrInsert(
                ['kode_mk' => $mk['kode_mk']],
                array_merge($mk, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
