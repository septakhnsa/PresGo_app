<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PengingatPresensiNotification extends Notification
{
    use Queueable;

    public $jadwal;

    public function __construct($jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toDatabase($notifiable)
    {
        $namaMk = $this->jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah';
        return [
            'judul' => 'Pengingat Presensi',
            'pesan' => "Kelas {$namaMk} dimulai <span class=\"nt-highlight\">15 Menit</span> lagi",
            'is_reminder_aktif' => true,
            'jadwal_id' => $this->jadwal->id,
            'waktu' => now()->format('H:i')
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $namaMk = $this->jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah';
        return (new WebPushMessage)
            ->title('Pengingat Presensi')
            ->icon('/logo.png')
            ->body("Kelas {$namaMk} dimulai 15 menit lagi.")
            ->action('Presensi Sekarang', 'presensi_action')
            ->data(['url' => route('mahasiswa.presensi.camera', ['jadwal_id' => $this->jadwal->id])]);
    }
}
