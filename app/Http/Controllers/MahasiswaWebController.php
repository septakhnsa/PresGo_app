<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PresensiBerhasilNotification;

class MahasiswaWebController extends Controller
{
    /**
     * Splash Screen (entry point of the web app)
     */
    public function splash()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if (Auth::user()->role === 'mahasiswa') {
                if (Auth::user()->krs_completed == 0) {
                    return redirect()->route('mahasiswa.krs');
                }
                return redirect()->route('mahasiswa.home');
            }
        }

        return view('mahasiswa.splash');
    }

    /**
     * Show Login Form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if (Auth::user()->role === 'mahasiswa') {
                if (Auth::user()->krs_completed == 0) {
                    return redirect()->route('mahasiswa.krs');
                }
                return redirect()->route('mahasiswa.home');
            }
        }

        // Prefill NIM/email if previously remembered via "Ingat NIM" checkbox.
        $rememberedLogin = request()->cookie('presgo_remembered_login');

        return view('mahasiswa.login', compact('rememberedLogin'));
    }

    /**
     * Handle Login (NIM atau Email + Password) — untuk role mahasiswa & admin
     */
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required'    => 'NIM atau Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('nim', $request->login)
            ->orWhere('email', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['login' => 'NIM/Email atau password yang kamu masukkan salah.'])
                ->withInput($request->only('login'));
        }

        Auth::login($user, $request->boolean('ingat_nim'));
        $request->session()->regenerate();

        // Admin langsung diarahkan ke dashboard admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role !== 'mahasiswa') {
            Auth::logout();
            return back()
                ->withErrors(['login' => 'Akun ini tidak memiliki akses ke halaman ini.'])
                ->withInput($request->only('login'));
        }

        if ($user->krs_completed == 0) {
            $response = redirect()->route('mahasiswa.krs');
        } else {
            $response = redirect()->route('mahasiswa.home');
        }

        if ($request->boolean('ingat_nim')) {
            $response->withCookie(cookie('presgo_remembered_login', $request->login, 60 * 24 * 30));
        } else {
            $response->withCookie(cookie()->forget('presgo_remembered_login'));
        }

        return $response;
    }

    /**
     * Show Register Form
     */
    public function showRegisterForm()
    {
        if (Auth::check() && Auth::user()->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.home');
        }

        return view('mahasiswa.register');
    }

    /**
     * Handle Register
     *
     * Note: the Figma design includes a "NIM" field on this screen, but NIM
     * assignment in this app is owned by the admin verification flow
     * (see AdminWebController::verifyMahasiswa). To keep that workflow intact,
     * the NIM input here is informational only and is not written to the
     * database — new accounts are always created with nim = null ("Pending")
     * until an admin verifies them from the admin panel.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa',
            'nim'      => null, // Pending admin verification
        ]);

        return redirect()->route('mahasiswa.login')
            ->with('success', 'Pendaftaran berhasil! Akun kamu akan diverifikasi oleh admin (NIM akan ditambahkan oleh admin). Silakan login.');
    }

    /**
     * Simple placeholder home page after login.
     */
    public function home()
    {
        \Carbon\Carbon::setLocale('id');
        $hariIni = now()->translatedFormat('l');
        $user = Auth::user();

        $jadwalHariIni = $user->jadwals()->where('hari', $hariIni)->with('mataKuliah')->get();
        
        // Mode Demo: Jika jadwal personal kosong (misal akun baru), ambil semua jadwal global hari ini
        if ($jadwalHariIni->isEmpty()) {
            $jadwalHariIni = \App\Models\JadwalKuliah::where('hari', $hariIni)->with('mataKuliah')->get();
        }

        // Mode Demo Lanjutan: Jika hari ini memang libur (Misal Minggu), selalu tampilkan hari Senin
        if ($jadwalHariIni->isEmpty()) {
            $jadwalHariIni = \App\Models\JadwalKuliah::where('hari', 'Senin')->with('mataKuliah')->get();
        }

        $totalJadwal = $jadwalHariIni->count();
        $totalAbsen = 0;
        $nextJadwal = null;

        foreach ($jadwalHariIni as $jadwal) {
            $absen = \App\Models\Presensi::where('user_id', $user->id)
                        ->where('jadwal_id', $jadwal->id)
                        ->where('tanggal', now()->toDateString())
                        ->first();
            
            if ($absen) {
                $jadwal->sudah_absen = true;
                $jadwal->foto_wajah = $absen->foto_wajah;
                $totalAbsen++;
            } else {
                $jadwal->sudah_absen = false;
                $jadwal->foto_wajah = null;
                if (!$nextJadwal) {
                    $nextJadwal = $jadwal;
                }
            }
        }

        return view('mahasiswa.home', compact('jadwalHariIni', 'totalJadwal', 'totalAbsen', 'nextJadwal'));
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('splash');
    }

    /**
     * Dashboard Presensi (Rekap)
     */
    public function dashboardPresensi()
    {
        $user = Auth::user();
        
        \Carbon\Carbon::setLocale('id');
        $hariIni = now()->translatedFormat('l'); // e.g. "Senin", "Selasa", dll.

        // 1. Ambil jadwal hari ini
        $jadwalHariIni = $user->jadwals()->where('hari', $hariIni)->with('mataKuliah')->get();
        
        // Mode Demo: Jika jadwal personal kosong (misal akun baru), ambil semua jadwal global hari ini
        if ($jadwalHariIni->isEmpty()) {
            $jadwalHariIni = \App\Models\JadwalKuliah::where('hari', $hariIni)->with('mataKuliah')->get();
        }

        // Mode Demo Lanjutan: Jika hari ini memang libur (Misal Sabtu/Minggu), selalu tampilkan hari Senin
        $isFallbackMonday = false;
        if ($jadwalHariIni->isEmpty()) {
            $jadwalHariIni = \App\Models\JadwalKuliah::where('hari', 'Senin')->with('mataKuliah')->get();
            $isFallbackMonday = true;
        }

        // 2. Map ke format array untuk tampilan di blade
        $mappedJadwal = [];
        foreach ($jadwalHariIni as $j) {
            $absen = \App\Models\Presensi::where('user_id', $user->id)
                ->where('jadwal_id', $j->id)
                ->where('tanggal', now()->toDateString())
                ->first();

            $status = $absen ? 'Hadir' : 'Belum';
            $fotoWajah = $absen ? $absen->foto_wajah : null;

            $jamMulai = substr($j->jam_mulai, 0, 5);
            $jamSelesai = substr($j->jam_selesai, 0, 5);

            $mappedJadwal[] = [
                'id' => $j->id,
                'mata_kuliah' => $j->mataKuliah->nama_mk ?? 'Mata Kuliah',
                'dosen' => $j->dosen ?? 'Dosen Pengampu',
                'jam' => "{$jamMulai} – {$jamSelesai}",
                'ruangan' => $j->ruangan,
                'status' => $status,
                'foto_wajah' => $fotoWajah,
                'jam_mulai_raw' => $j->jam_mulai,
                'jam_selesai_raw' => $j->jam_selesai,
            ];
        }

        // 3. Hitung Rekap Kehadiran bulan ini secara dinamis dari tabel presensi
        $totalHadir = \App\Models\Presensi::where('user_id', $user->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        $totalAbsen = 0; // default 0 jika tidak ada pencatatan absen manual
        $totalSesi = $totalHadir + $totalAbsen;
        $kehadiranPersen = $totalSesi > 0 ? round(($totalHadir / $totalSesi) * 100) : 0;

        // 4. Cek apakah ada jadwal yang sedang/akan masuk dalam rentang 15 menit
        $notifJadwal = null;
        $now = now();

        foreach ($mappedJadwal as $mj) {
            if ($mj['status'] === 'Belum') {
                if ($isFallbackMonday) {
                    // Mode Demo: Selalu tampilkan kelas pertama dari Senin yang belum absen
                    $notifJadwal = $mj;
                    break;
                } else {
                    // Mode Real-Time asli
                    try {
                        $jamMulaiCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $mj['jam_mulai_raw']);
                        $jamSelesaiCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $mj['jam_selesai_raw']);
                        
                        // Rentang: 15 menit sebelum mulai sampai jam selesai kuliah
                        $reminderStart = $jamMulaiCarbon->copy()->subMinutes(15);
                        
                        if ($now->between($reminderStart, $jamSelesaiCarbon)) {
                            $notifJadwal = $mj;
                            break;
                        }
                    } catch (\Exception $e) {
                        // Skip jika format jam tidak sesuai
                    }
                }
            }
        }

        return view('mahasiswa.dashboard-presensi', [
            'user' => $user,
            'jadwalHariIni' => $mappedJadwal,
            'totalHadir' => $totalHadir,
            'totalAbsen' => $totalAbsen,
            'kehadiranPersen' => $kehadiranPersen,
            'notifJadwal' => $notifJadwal
        ]);
    }

    /**
     * Notifikasi
     */
    public function notifikasi()
    {
        $user = Auth::user();
        
        // Hapus notifikasi "Presensi Berhasil" jika data presensi aslinya sudah tidak ada di DB
        $hasPresensi = \App\Models\Presensi::where('user_id', $user->id)->exists();
        if (!$hasPresensi) {
            $user->notifications()
                ->where('type', 'App\Notifications\PresensiBerhasilNotification')
                ->delete();
        }
        
        // Cek apakah ada jadwal yang sedang/akan masuk dalam rentang 15 menit
        \Carbon\Carbon::setLocale('id');
        $hariIni = now()->translatedFormat('l');
        $jadwalHariIni = $user->jadwals()->where('hari', $hariIni)->with('mataKuliah')->get();
        if ($jadwalHariIni->isEmpty()) {
            $jadwalHariIni = \App\Models\JadwalKuliah::where('hari', $hariIni)->with('mataKuliah')->get();
        }
        $isFallbackMonday = false;
        if ($jadwalHariIni->isEmpty()) {
            $jadwalHariIni = \App\Models\JadwalKuliah::where('hari', 'Senin')->with('mataKuliah')->get();
            $isFallbackMonday = true;
        }

        $notifJadwal = null;
        $now = now();

        foreach ($jadwalHariIni as $j) {
            $absen = \App\Models\Presensi::where('user_id', $user->id)
                ->where('jadwal_id', $j->id)
                ->where('tanggal', now()->toDateString())
                ->first();
            
            if (!$absen) { // Belum absen
                if ($isFallbackMonday) {
                    $notifJadwal = [
                        'id' => $j->id,
                        'mata_kuliah' => $j->mataKuliah->nama_mk ?? 'Mata Kuliah',
                        'dosen' => $j->dosen ?? 'Dosen Pengampu',
                        'jam' => substr($j->jam_mulai, 0, 5) . ' – ' . substr($j->jam_selesai, 0, 5),
                        'ruangan' => $j->ruangan
                    ];
                    break;
                } else {
                    try {
                        $jamMulaiCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_mulai);
                        $jamSelesaiCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $j->jam_selesai);
                        $reminderStart = $jamMulaiCarbon->copy()->subMinutes(15);
                        
                        if ($now->between($reminderStart, $jamSelesaiCarbon)) {
                            $notifJadwal = [
                                'id' => $j->id,
                                'mata_kuliah' => $j->mataKuliah->nama_mk ?? 'Mata Kuliah',
                                'dosen' => $j->dosen ?? 'Dosen Pengampu',
                                'jam' => substr($j->jam_mulai, 0, 5) . ' – ' . substr($j->jam_selesai, 0, 5),
                                'ruangan' => $j->ruangan
                            ];
                            break;
                        }
                    } catch (\Exception $e) {}
                }
            }
        }

        return view('mahasiswa.Notification', compact('user', 'notifJadwal'));
    }

    /**
     * Camera Presensi
     */
    public function camera(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');
        $jadwal = null;
        if ($jadwalId) {
            $jadwal = \App\Models\JadwalKuliah::with('mataKuliah')->find($jadwalId);
        }
        return view('mahasiswa.camera', compact('jadwal', 'jadwalId'));
    }

    /**
     * Submit Presensi Web
     */
    public function submitPresensi(Request $request)
    {
        $request->validate([
            'photo' => 'required|string', // base64
        ]);

        $photoData = $request->input('photo');
        $photoData = str_replace('data:image/jpeg;base64,', '', $photoData);
        $photoData = str_replace(' ', '+', $photoData);
        $imageName = 'attendance_' . Auth::id() . '_' . time() . '.jpeg';
        
        \Illuminate\Support\Facades\Storage::disk('public')->put('attendances/' . $imageName, base64_decode($photoData));

        $jadwalId = $request->input('jadwal_id');
        $jadwal = null;
        
        if (!$jadwalId) {
            $jadwal = \App\Models\JadwalKuliah::with('mataKuliah')->first();
            if ($jadwal) {
                $jadwalId = $jadwal->id;
            }
        } else {
            $jadwal = \App\Models\JadwalKuliah::with('mataKuliah')->find($jadwalId);
        }

        \App\Models\Presensi::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'jadwal_id' => $jadwalId,
                'tanggal' => now()->toDateString(),
            ],
            [
                'jam_masuk' => now()->toTimeString(),
                'status_wajah' => 'valid',
                'foto_wajah' => 'attendances/' . $imageName,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        // Kirim notifikasi realtime & background ke user
        if ($jadwal) {
            Auth::user()->notify(new PresensiBerhasilNotification($jadwal));
        }

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat!',
        ]);
    }

    /**
     * Profile
     */
    public function profile()
    {
        return view('mahasiswa.profile');
    }

    /**
     * History
     */
    public function history()
    {
        return view('mahasiswa.history');
    }

    /**
     * Push Subscription
     */
    public function pushSubscribe(Request $request)
    {
        $request->validate([
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = Auth::user();
        $user->updatePushSubscription($endpoint, $key, $token);
        
        return response()->json(['success' => true], 200);
    }

    /**
     * Mark Notification as Read
     */
    public function markAsRead(Request $request)
    {
        $id = $request->input('id');
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    }

    /**
     * Mark All Notifications as Read
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    /**
     * Delete All Notifications
     */
    public function deleteAllNotifications()
    {
        Auth::user()->notifications()->delete();
        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // KRS (Kartu Rencana Studi) — Mahasiswa
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Tampilkan halaman KRS mahasiswa.
     * State ditentukan dari status krs_completed dan krs_requests user.
     */
    public function krsPage()
    {
        $user = Auth::user();

        // Jika sudah approved (krs_completed == 1), redirect ke beranda
        if ($user->krs_completed == 1) {
            return redirect()->route('mahasiswa.home');
        }

        // Ambil semua jadwal beserta mata kuliah
        $jadwals = \App\Models\JadwalKuliah::with('mataKuliah')->get();

        // Cek apakah user sudah punya KRS pending
        $hasPending = \App\Models\KrsRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        return view('mahasiswa.krs', compact('jadwals', 'hasPending'));
    }

    /**
     * Submit KRS mahasiswa (simpan jadwal yang dipilih sebagai pending).
     */
    public function submitKrs(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'jadwal_ids'   => 'required|array|min:1',
            'jadwal_ids.*' => 'exists:jadwal_kuliah,id',
        ], [
            'jadwal_ids.required' => 'Pilih minimal 1 mata kuliah.',
            'jadwal_ids.min'      => 'Pilih minimal 1 mata kuliah.',
        ]);

        $user = Auth::user();

        // Hapus KRS pending lama (jika ada) agar bisa submit ulang
        \App\Models\KrsRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->delete();

        foreach ($request->jadwal_ids as $jadwalId) {
            \App\Models\KrsRequest::create([
                'user_id'  => $user->id,
                'jadwal_id' => $jadwalId,
                'status'   => 'pending',
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Polling endpoint: cek apakah KRS user sudah di-approve (krs_completed == 1).
     */
    public function pollKrsStatus()
    {
        $user = Auth::user();
        return response()->json([
            'krs_completed' => (string) $user->fresh()->krs_completed,
        ]);
    }
}