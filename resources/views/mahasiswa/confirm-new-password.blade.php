@extends('layouts.mahasiswa')

@section('title', 'Perbarui Password - PresGo')

@section('content')
<div class="np-screen">

    {{-- Header hijau --}}
    <div class="np-header">
        <a href="{{ route('mahasiswa.forgot-password') }}" class="np-back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

    {{-- Kartu putih --}}
    <div class="np-card">

        {{-- Ikon gembok mencuat ke area hijau (oval/pill) --}}
        <div class="np-icon-pill">
            <svg width="40" height="48" viewBox="0 0 56 66" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Shackle -->
                <path d="M10 28V20C10 10.059 18.059 2 28 2C37.941 2 46 10.059 46 20V28"
                      stroke="#1A5E35" stroke-width="5" stroke-linecap="round" fill="none"/>
                <!-- Badan gembok -->
                <rect x="4" y="26" width="48" height="38" rx="7" fill="#1A5E35"/>
                <!-- Tanda tanya -->
                <text x="28" y="53" text-anchor="middle"
                      fill="white"
                      font-family="Plus Jakarta Sans, Arial, sans-serif"
                      font-size="22"
                      font-weight="900">?</text>
            </svg>
        </div>

        {{-- Scroll area --}}
        <div class="np-scroll">
            <div class="np-body">

                @if ($errors->any())
                    <div class="np-alert np-alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <h1 class="np-title">Perbarui Password</h1>
                <p class="np-subtitle">Password minimal 8 karakter</p>

                <form action="{{ route('mahasiswa.password.update') }}" method="POST" id="confirmPasswordForm" class="np-form">
                    @csrf

                    <div class="np-field-group">
                        <label for="password">Password Baru</label>
                        <div class="np-field-wrap">
                            <input type="password" name="password" id="password" required minlength="8" placeholder="">
                            <button type="button" class="np-eye" data-target="password">
                                <i class="fa-regular fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="np-field-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="np-field-wrap">
                            <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8" placeholder="">
                            <button type="button" class="np-eye" data-target="password_confirmation">
                                <i class="fa-regular fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Strength indicator --}}
                    <div class="np-strength-row">
                        <div class="np-strength-bar">
                            <span class="np-seg np-seg-1"></span>
                            <span class="np-seg np-seg-2"></span>
                            <span class="np-seg np-seg-3"></span>
                        </div>
                        <span class="np-strength-lbl" id="strengthLabel">waiting..</span>
                    </div>

                    <button type="submit" class="np-btn-submit">Simpan Password Baru</button>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
html, body { height: 100%; overflow: hidden; }

/* ── Layar penuh hijau ─────────────────────────── */
.np-screen {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    background: #1A5E35;
    position: relative;
    overflow: hidden;
}

/* ── Header hijau (±42% tinggi) ────────────────── */
.np-header {
    flex: 0 0 42%;
    display: flex;
    align-items: flex-start;
    padding: 40px 20px 0;
    flex-shrink: 0;
}

/* Tombol kembali */
.np-back-btn {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px; text-decoration: none;
    transition: background 0.2s;
}
.np-back-btn:hover { background: rgba(255,255,255,0.25); }

/* ── Kartu putih ────────────────────────────────── */
.np-card {
    flex: 1;
    background: #F1F5F9;
    border-radius: 28px 28px 0 0;
    overflow: visible;           /* biarkan ikon mencuat ke atas */
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    margin-top: -113px;
}

/* ── Ikon gembok — oval/pill mencuat ke area hijau ── */
.np-icon-pill {
    margin-top: -36px;
    margin-bottom: 12px;
    flex-shrink: 0;
    position: relative;
    z-index: 5;

    width: 108px; height: 103px;   /* oval pill */
    border-radius: 999px;
    background: #DCEEE1;
    border: 5px solid #fff;
    box-shadow: 0 4px 18px rgba(0,0,0,0.10);
    display: flex; align-items: center; justify-content: center;
}

/* ── Area scroll ────────────────────────────────── */
.np-scroll {
    flex: 1;
    width: 100%;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}

/* ── Isi konten ─────────────────────────────────── */
.np-body {
    padding: 0 24px 48px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 420px;
    margin: 0 auto;
}

/* ── Alert ──────────────────────────────────────── */
.np-alert {
    width: 100%;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 12.5px;
    font-weight: 600;
    margin-bottom: 16px;
    display: flex; align-items: flex-start; gap: 8px; text-align: left;
}
.np-alert-error { background: #FEE2E2; color: #DC2626; }

/* ── Teks ───────────────────────────────────────── */
.np-title {
    color: #1A5E35;
    font-size: 20px;
    font-weight: 800;
    margin: 0 0 6px;
}
.np-subtitle {
    color: #6B7280;
    font-size: 13px;
    font-weight: 500;
    margin: 0 0 24px;
}

/* ── Form ───────────────────────────────────────── */
.np-form { width: 100%; text-align: left; }

.np-field-group {
    margin-bottom: 16px;
}
.np-field-group label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 8px;
}
.np-field-wrap {
    position: relative;
}
.np-field-wrap input {
    width: 100%;
    padding: 14px 46px 14px 16px;
    border-radius: 12px;
    border: 1.5px solid #D1D5DB;
    background: #fff;
    font-family: inherit;
    font-size: 14px;
    color: #374151;
    outline: none;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.np-field-wrap input:focus {
    border-color: #1A5E35;
    box-shadow: 0 0 0 3px rgba(26,94,53,0.10);
}
.np-eye {
    position: absolute;
    right: 14px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    color: #9CA3AF; font-size: 16px;
    cursor: pointer; padding: 4px;
    line-height: 1;
}
.np-eye:hover { color: #6B7280; }

/* ── Strength bar ───────────────────────────────── */
.np-strength-row {
    display: flex; align-items: center; gap: 8px;
    margin: 6px 0 20px;
}
.np-strength-bar {
    display: flex; gap: 5px; flex: 1;
}
.np-seg {
    height: 5px; flex: 1;
    border-radius: 4px;
    transition: opacity 0.3s;
}
/* Default: always show color, dim when not "active" */
.np-seg-1 { background: #1A5E35; opacity: 0.25; }
.np-seg-2 { background: #1A5E35; opacity: 0.25; }
.np-seg-3 { background: #F2C744; opacity: 0.25; }

.np-seg-1.active { opacity: 1; }
.np-seg-2.active { opacity: 1; }
.np-seg-3.active { opacity: 1; }
.np-strength-lbl {
    font-size: 11px; font-weight: 700;
    color: #9CA3AF; white-space: nowrap;
}

/* ── Tombol submit ──────────────────────────────── */
.np-btn-submit {
    display: block; width: 100%;
    padding: 15px;
    background: #1A5E35; color: #fff;
    border: none; border-radius: 12px;
    font-size: 15px; font-weight: 800;
    cursor: pointer; font-family: inherit;
    letter-spacing: 0.3px;
    transition: background 0.2s, transform 0.1s;
}
.np-btn-submit:hover  { background: #154a2a; }
.np-btn-submit:active { transform: scale(0.98); }
</style>
@endpush

@push('scripts')
<script>
    // Toggle show/hide password
    document.querySelectorAll('.np-eye').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.target);
            var icon  = btn.querySelector('i');
            var isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash', !isPass);
            icon.classList.toggle('fa-eye',       isPass);
        });
    });

    // Password strength
    var pwdInput     = document.getElementById('password');
    var seg1         = document.querySelector('.np-seg-1');
    var seg2         = document.querySelector('.np-seg-2');
    var seg3         = document.querySelector('.np-seg-3');
    var strengthLbl  = document.getElementById('strengthLabel');

    pwdInput.addEventListener('input', function () {
        var v = this.value;
        seg1.classList.remove('active');
        seg2.classList.remove('active');
        seg3.classList.remove('active');

        if (v.length === 0) {
            strengthLbl.textContent = 'waiting..';
            strengthLbl.style.color = '#9CA3AF';
        } else if (v.length < 8) {
            seg1.classList.add('active');
            strengthLbl.textContent = 'Lemah';
            strengthLbl.style.color = '#9CA3AF';
        } else if (v.length < 12) {
            seg1.classList.add('active');
            seg2.classList.add('active');
            strengthLbl.textContent = 'Cukup';
            strengthLbl.style.color = '#1A5E35';
        } else {
            seg1.classList.add('active');
            seg2.classList.add('active');
            seg3.classList.add('active');
            strengthLbl.textContent = 'Kuat';
            strengthLbl.style.color = '#1A5E35';
        }
    });
</script>
@endpush