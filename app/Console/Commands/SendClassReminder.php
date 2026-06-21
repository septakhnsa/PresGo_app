<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalKuliah;
use App\Notifications\PengingatPresensiNotification;
use Carbon\Carbon;

class SendClassReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-class-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notification 15 minutes before class starts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Carbon::setLocale('id');
        $hariIni = now()->translatedFormat('l');
        $waktuTarget = now()->addMinutes(15)->format('H:i'); // Waktu 15 menit dari sekarang (jam & menit)

        // Cari jadwal yang harinya sama dan jam mulai = 15 menit lagi
        // karena format jam_mulai bisa H:i:s, kita gunakan LIKE
        $jadwals = JadwalKuliah::where('hari', $hariIni)
            ->where('jam_mulai', 'LIKE', $waktuTarget . '%')
            ->with('mahasiswas')
            ->get();

        foreach ($jadwals as $jadwal) {
            foreach ($jadwal->mahasiswas as $mahasiswa) {
                // Jangan kirim kalau sudah presensi
                $sudahAbsen = \App\Models\Presensi::where('user_id', $mahasiswa->id)
                    ->where('jadwal_id', $jadwal->id)
                    ->where('tanggal', now()->toDateString())
                    ->exists();

                if (!$sudahAbsen) {
                    $mahasiswa->notify(new PengingatPresensiNotification($jadwal));
                }
            }
        }
        
        $this->info("Reminders sent for " . $jadwals->count() . " schedules.");
    }
}
