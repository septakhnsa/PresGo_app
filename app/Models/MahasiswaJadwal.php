<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\JadwalKuliah;

class MahasiswaJadwal extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa_jadwal';

    protected $fillable = [
        'user_id',
        'jadwal_id'
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

public function jadwal()
{
    return $this->belongsTo(JadwalKuliah::class, 'jadwal_id');
}
}