<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JadwalKuliah;
use App\Models\Presensi;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AdminWebController extends Controller
{
    /**
     * Show standard web login form
     */
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle standard web login request
     */
    public function loginWeb(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
            return back()->withErrors([
                'email' => 'Hanya administrator yang diijinkan masuk.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ]);
    }
    /**
     * Auto login using API Sanctum Token from Flutter
     */
    public function autoLogin(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect('/')->with('error', 'Token tidak ditemukan');
        }

        // Cari token di personal_access_tokens
        $tokenModel = PersonalAccessToken::findToken($token);

        if (!$tokenModel) {
            return redirect('/')->with('error', 'Token tidak valid');
        }

        $user = $tokenModel->tokenable;

        if ($user && $user->role === 'admin') {
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        return redirect('/')->with('error', 'Akses ditolak');
    }

    /**
     * Web Dashboard
     */
    public function dashboard()
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalMataKuliah = MataKuliah::count();
        $totalJadwal = JadwalKuliah::count();
        
        // Presensi hari ini
        $presensiHariIni = Presensi::whereDate('tanggal', today())
            ->with(['user', 'jadwal.mataKuliah'])
            ->orderBy('created_at', 'desc')
            ->get();
        $totalHadir = $presensiHariIni->count();
        
        // Jadwal aktif hari ini
        $hariIni = $this->getHariString(now()->dayOfWeek);
        $jadwalHariIni = JadwalKuliah::where('hari', $hariIni)->with('mataKuliah')->get();

        return view('admin.dashboard', compact(
            'totalMahasiswa',
            'totalMataKuliah',
            'totalJadwal',
            'totalHadir',
            'presensiHariIni',
            'jadwalHariIni'
        ));
    }

    /**
     * API for dashboard real-time polling
     */
    public function dashboardApi()
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalMataKuliah = MataKuliah::count();
        $totalJadwal = JadwalKuliah::count();

        $presensiHariIni = Presensi::whereDate('tanggal', today())
            ->with(['user', 'jadwal.mataKuliah'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalHadir = $presensiHariIni->count();

        return response()->json([
            'totalMahasiswa' => $totalMahasiswa,
            'totalMataKuliah' => $totalMataKuliah,
            'totalJadwal' => $totalJadwal,
            'totalHadir' => $totalHadir,
            'feed' => $presensiHariIni->map(function($presensi) {
                return [
                    'nama' => $presensi->user->name ?? '-',
                    'nim' => $presensi->user->nim ?? '-',
                    'mata_kuliah' => $presensi->jadwal->mataKuliah->nama_mk ?? '-',
                    'jam' => substr($presensi->jam_masuk, 0, 5),
                    'lat' => $presensi->latitude,
                    'lng' => $presensi->longitude,
                ];
            })
        ]);
    }

    /**
     * List Jadwal
     */
    public function jadwal()
    {
        $jadwals = JadwalKuliah::with('mataKuliah')->orderBy('hari')->get();
        return view('admin.jadwal', compact('jadwals'));
    }

    /**
     * List Mahasiswa
     */
    public function mahasiswa()
    {
        $mahasiswa = User::where('role', 'mahasiswa')->get();
        return view('admin.mahasiswa', compact('mahasiswa'));
    }

    /**
     * Verify Mahasiswa (Set NIM)
     */
    public function verifyMahasiswa(Request $request, $id)
    {
        $request->validate([
            'nim' => 'required|string|unique:users,nim',
        ]);

        $user = User::findOrFail($id);
        $user->nim = $request->nim;
        $user->save();

        return redirect()->back()->with('success', 'NIM berhasil ditetapkan. Mahasiswa telah diverifikasi.');
    }

    /**
     * List Riwayat Presensi
     */
    public function presensi()
    {
        $presensis = Presensi::with(['user', 'jadwal.mataKuliah'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->get();
        return view('admin.presensi', compact('presensis'));
    }

    /**
     * Logout Web Session
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    private function getHariString($dayOfWeek)
    {
        $days = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        return $days[$dayOfWeek] ?? 'Senin';
    }
    public function krsPending()
{
    $response = file_get_contents(url('/api/admin/krs/pending'));
    $data = json_decode($response, true);

    $krs = $data['data'] ?? [];

    return view('admin.krs_pending', compact('krs'));
}
}
