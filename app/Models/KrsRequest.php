<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KrsRequest extends Model
{
    protected $table = 'krs_requests';

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'status'
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalKuliah::class, 'jadwal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}