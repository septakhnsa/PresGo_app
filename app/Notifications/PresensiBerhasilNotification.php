<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PresensiBerhasilNotification extends Notification
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
            'judul' => 'Presensi Berhasil!',
            'pesan' => "Presensi Berhasil!<br>{$namaMk} tercatat hadir.",
            'is_reminder_aktif' => false,
            'waktu' => now()->format('H:i')
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $namaMk = $this->jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah';
        return (new WebPushMessage)
            ->title('Presensi Berhasil!')
            ->icon('/logo.png')
            ->body("Kamu tercatat hadir untuk kelas {$namaMk}.")
            ->action('Lihat Riwayat', 'history_action');
    }
}
