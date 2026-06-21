<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $response = redirect()->route('mahasiswa.home');

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
            'password' => 'required|string|min:8|confirmed',
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
        return view('mahasiswa.dashboard-presensi', compact('user'));
    }

    /**
     * Notifikasi
     */
    public function notifikasi()
    {
        $user = Auth::user();
        return view('mahasiswa.Notification', compact('user'));
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
        if (!$jadwalId) {
            $jadwal = \App\Models\JadwalKuliah::first();
            if ($jadwal) {
                $jadwalId = $jadwal->id;
            }
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
}