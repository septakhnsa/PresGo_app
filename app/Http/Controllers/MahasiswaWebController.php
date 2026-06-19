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
        // If a mahasiswa is already logged in, skip straight to their home.
        if (Auth::check() && Auth::user()->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.home');
        }

        return view('mahasiswa.splash');
    }

    /**
     * Show Login Form
     */
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.home');
        }

        // Prefill NIM/email if previously remembered via "Ingat NIM" checkbox.
        $rememberedLogin = request()->cookie('presgo_remembered_login');

        return view('mahasiswa.login', compact('rememberedLogin'));
    }

    /**
     * Handle Login (NIM or Email + Password)
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

        if ($user->role !== 'mahasiswa') {
            return back()
                ->withErrors(['login' => 'Akun ini bukan akun mahasiswa. Silakan gunakan halaman login admin.'])
                ->withInput($request->only('login'));
        }

        Auth::login($user, $request->boolean('ingat_nim'));
        $request->session()->regenerate();

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
     * (Not part of the supplied Figma frames — added so the login flow
     * has somewhere to land. Replace with the real student dashboard later.)
     */
    public function home()
    {
        return view('mahasiswa.home');
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
}
