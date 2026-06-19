<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showForgotPassword()
    {
        return view('mahasiswa.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        // TODO: validasi email, generate & kirim OTP
        return redirect()->route('mahasiswa.otp');
    }

    public function showOtp()
    {
        return view('mahasiswa.otp-verification');
    }

    public function verifyOtp(Request $request)
    {
        // TODO: cek kode OTP yang diinput user
        return redirect()->route('mahasiswa.password.reset');
    }

    public function resendOtp(Request $request)
    {
        // TODO: kirim ulang OTP
        return redirect()->back();
    }

    public function showResetPassword()
    {
        return view('mahasiswa.confirm-new-password');
    }

    public function updatePassword(Request $request)
    {
        // TODO: validasi & simpan password baru
        return redirect()->route('mahasiswa.password.verified');
    }

    public function showPasswordVerified()
    {
        return view('mahasiswa.password-verify');
    }
}