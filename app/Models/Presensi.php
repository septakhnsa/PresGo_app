<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'tanggal',
        'jam_masuk',
        'status_wajah',
        'foto_wajah',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'tanggal'   => 'date',
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    /**
     * Mahasiswa yang melakukan presensi ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Jadwal kuliah yang diikuti.
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalKuliah::class, 'jadwal_id');
    }
}
