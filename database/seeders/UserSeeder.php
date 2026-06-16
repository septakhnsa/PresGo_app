<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nim' => 'ADM001',
            'name' => 'Administrator',
            'email' => 'admin@presgo.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'nim' => 'STI202303519',
            'name' => 'Anissa Balqis',
            'email' => 'annisabalqisbalqis79@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'mahasiswa',
        ]);

        User::create([
            'nim' => 'STI202303520',
            'name' => 'Aina Nuratia',
            'email' => 'ainanuratia@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'mahasiswa',
        ]);

        User::create([
            'nim' => 'STI202303524',
            'name' => 'Dela Nur Asia',
            'email' => 'Nadela283@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'mahasiswa',
        ]);

        User::create([
            'nim' => 'STI202303686',
            'name' => 'Septa Khoerun Nisa',
            'email' => 'septakhnsa@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'mahasiswa',
        ]);
    }
}