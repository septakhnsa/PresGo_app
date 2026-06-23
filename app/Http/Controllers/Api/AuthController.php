<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // ─────────────────────────────
    // LOGIN
    // ─────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('nim', $request->login)
            ->orWhere('email', $request->login)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau NIM tidak ditemukan'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }

        $token = $user->createToken('flutter-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nim' => $user->nim,
                'role' => $user->role,
                'krs_completed' => $user->krs_completed,
                'created_at' => $user->created_at ? $user->created_at->toDateString() : '2000-01-01',
            ]
        ]);
    }

    // ─────────────────────────────
    // REGISTER
    // ─────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
            'nim' => null, // Tandai sebagai Pending
            'krs_completed' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil. Menunggu verifikasi admin.',
            'user'    => $user
        ], 201);
    }

    // ─────────────────────────────
    // FORGOT PASSWORD (KIRIM OTP)
    // ─────────────────────────────
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // cek user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan'
            ], 404);
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
            Mail::raw("Kode OTP kamu adalah: $otp_code (berlaku 5 menit)", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('OTP Reset Password');
            });

            return response()->json([
                'success' => true,
                'message' => 'OTP berhasil dikirim ke email'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ─────────────────────────────
    // VERIFY OTP
    // ─────────────────────────────
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required'
        ]);

        $otp = DB::table('otp_codes')
            ->where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->where('is_used', 0)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP salah atau sudah expired'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP valid'
        ]);
    }

    // ─────────────────────────────
    // RESET PASSWORD
    // ─────────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required',
            'password' => 'required|min:8'
        ]);

        $otp = DB::table('otp_codes')
            ->where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->where('is_used', 0)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP tidak valid'
            ], 400);
        }

        // update password user
        User::where('email', $request->email)->update([
            'password' => bcrypt($request->password)
        ]);

        // tandai OTP sudah dipakai
        DB::table('otp_codes')
            ->where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->update([
                'is_used' => 1
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah'
        ]);
    }
}