<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'presence_location_id',
        'date',
        'time_in',
        'time_out',
        'lat_in',
        'long_in',
        'lat_out',
        'long_out',
        'photo_in',
        'photo_out',
        'status',
        'notes',
        'is_verified_in',
        'is_verified_out',
    ];

    protected $casts = [
        'date' => 'date',
        'is_verified_in' => 'boolean',
        'is_verified_out' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function presenceLocation()
    {
        return $this->belongsTo(PresenceLocation::class);
    }
}
