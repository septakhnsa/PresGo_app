<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PresenceLocation;

class PresenceLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PresenceLocation::create([
            'name' => 'Kampus utama (berkoh)',
            'latitude' => -7.439266,
            'longitude' => 109.266213100,
            'radius' => 100, // 100 meters
        ]);

        PresenceLocation::create([
            'name' => 'kampus suwatio',
            'latitude' => -7.445081,
            'longitude' => 109.254635,
            'radius' => 100, // 100 meters
        ]);
    }
}
