<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showForgotPassword()
    {
        return view('mahasiswa.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar di sistem kami.'])->withInput();
        }

        // generate OTP
        $otp_code = rand(10000, 99999);

        // simpan OTP
        DB::table('otp_codes')->insert([
            'email' => $request->email,
            'otp_code' => $otp_code,
            'is_used' => 0,
            'expires_at' => now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            Mail::raw("Kode OTP pemulihan password kamu adalah: $otp_code (berlaku 5 menit)", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('OTP Reset Password PresGo');
            });

            // Simpan email di session untuk tahap selanjutnya
            session()->put('reset_email', $request->email);

            return redirect()->route('mahasiswa.otp')->with('success', 'Kode OTP telah dikirim ke email kamu.');

        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email OTP. Pastikan konfigurasi SMTP benar.'])->withInput();
        }
    }

    public function showOtp()
    {
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('mahasiswa.forgot-password')->withErrors(['email' => 'Sesi berakhir, silakan masukkan email kembali.']);
        }

        // Masking email: mhs***@kampus.ac.id
        $parts = explode("@", $email);
        $name = $parts[0];
        $domain = $parts[1];
        
        $maskedName = substr($name, 0, 3) . str_repeat('*', max(0, strlen($name) - 3));
        $maskedEmail = $maskedName . '@' . $domain;

        return view('mahasiswa.otp-verification', compact('maskedEmail'));
    }

    public function verifyOtp(Request $request)
    {
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('mahasiswa.forgot-password')->withErrors(['email' => 'Sesi berakhir.']);
        }

        // Gabungkan kode OTP dari 5 input (otp_1 sampai otp_5)
        $otp_code = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5;

        if (strlen($otp_code) < 5) {
            return back()->withErrors(['otp' => 'Silakan masukkan 5 digit kode OTP dengan lengkap.']);
        }

        $otp = DB::table('otp_codes')
            ->where('email', $email)
            ->where('otp_code', $otp_code)
            ->where('is_used', 0)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        // OTP valid, simpan ke session untuk tahap reset password
        session()->put('verified_otp', $otp_code);

        return redirect()->route('mahasiswa.password.reset')->with('success', 'OTP valid. Silakan masukkan password baru.');
    }

    public function resendOtp(Request $request)
    {
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('mahasiswa.forgot-password')->withErrors(['email' => 'Sesi berakhir.']);
        }

        // generate OTP baru
        $otp_code = rand(10000, 99999);

        // simpan OTP
        DB::table('otp_codes')->insert([
            'email' => $email,
            'otp_code' => $otp_code,
            'is_used' => 0,
            'expires_at' => now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            Mail::raw("Kode OTP pemulihan password BARU kamu adalah: $otp_code (berlaku 5 menit)", function ($message) use ($email) {
                $message->to($email)
                    ->subject('OTP Reset Password PresGo (Baru)');
            });

            return back()->with('success', 'Kode OTP baru telah dikirim ke email kamu.');

        } catch (\Exception $e) {
            return back()->withErrors(['otp' => 'Gagal mengirim ulang email OTP.']);
        }
    }

    public function showResetPassword()
    {
        if (!session('reset_email') || !session('verified_otp')) {
            return redirect()->route('mahasiswa.forgot-password')->withErrors(['email' => 'Sesi reset password tidak valid.']);
        }
        return view('mahasiswa.confirm-new-password');
    }

    public function updatePassword(Request $request)
    {
        $email = session('reset_email');
        $otp_code = session('verified_otp');

        if (!$email || !$otp_code) {
            return redirect()->route('mahasiswa.forgot-password')->withErrors(['email' => 'Sesi berakhir.']);
        }

        $request->validate([
            'password' => 'required|min:8|confirmed'
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // Cek lagi apakah OTP belum diganti (keamanan tambahan)
        $otp = DB::table('otp_codes')
            ->where('email', $email)
            ->where('otp_code', $otp_code)
            ->where('is_used', 0)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$otp) {
            return redirect()->route('mahasiswa.forgot-password')->withErrors(['email' => 'Sesi reset password tidak valid atau OTP sudah kadaluarsa.']);
        }

        // update password user
        User::where('email', $email)->update([
            'password' => Hash::make($request->password)
        ]);

        // tandai OTP sudah dipakai
        DB::table('otp_codes')
            ->where('email', $email)
            ->where('otp_code', $otp_code)
            ->update([
                'is_used' => 1
            ]);

        // bersihkan sesi
        session()->forget(['reset_email', 'verified_otp']);

        return redirect()->route('mahasiswa.password.verified');
    }

    public function showPasswordVerified()
    {
        return view('mahasiswa.password-verify');
    }
}