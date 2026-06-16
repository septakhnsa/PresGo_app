<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kuliah';

    protected $fillable = [
        'mata_kuliah_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'status',
    ];

    /**
     * Mata kuliah yang dimiliki jadwal ini.
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Mahasiswa yang terdaftar di jadwal ini (pivot).
     */
    public function mahasiswas()
    {
        return $this->belongsToMany(User::class, 'mahasiswa_jadwal', 'jadwal_id', 'user_id');
    }

    /**
     * Presensi untuk jadwal ini.
     */
    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'jadwal_id');
    }
}
