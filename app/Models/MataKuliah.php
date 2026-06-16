<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
    ];

    /**
     * Jadwal-jadwal yang menggunakan mata kuliah ini.
     */
    public function jadwals()
    {
        return $this->hasMany(JadwalKuliah::class, 'mata_kuliah_id');
    }
}
