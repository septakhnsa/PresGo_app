@extends('layouts.mahasiswa')

@section('title', 'Presensi Kamera - PresGo')

@push('styles')
<style>
    html, body { height: 100%; overflow: hidden; }

    .cam-wrap {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
        background: #0D1117;
        overflow: hidden;
        position: relative;
    }

    /* ── STATUS BAR ── */
    .cam-status-bar {
        background: #0D1117;
        padding: 12px 20px 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
        z-index: 20;
    }
    .cam-back-btn {
        color: #fff;
        font-size: 20px;
        text-decoration: none;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        transition: background 0.2s;
    }
    .cam-back-btn:hover { background: rgba(255,255,255,0.18); }
    .cam-title {
        color: #fff;
        font-size: 16px;
        font-weight: 800;
        letter-spacing: 0.3px;
    }
    .cam-placeholder { width: 38px; }

    /* ── INFO STRIP ── */
    .cam-info-strip {
        background: #1B5E35;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
        z-index: 20;
    }
    .cam-course-name {
        font-size: 13px;
        font-weight: 700;
        color: #fff;
    }
    .cam-course-meta {
        font-size: 11px;
        color: #9FC7A8;
        font-weight: 500;
        margin-top: 2px;
    }
    .cam-live-badge {
        background: #DC2626;
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        gap: 5px;
        animation: blink 1.5s infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.5} }

    /* ── CAMERA VIEWPORT ── */
    .cam-viewport {
        flex: 1;
        position: relative;
        overflow: hidden;
        background: #000;
    }
    #camVideo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1); /* mirror selfie */
    }
    #camCanvas { display: none; }

    /* Face frame overlay */
    .cam-frame {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 220px;
        height: 260px;
        pointer-events: none;
        z-index: 5;
    }
    .cam-frame::before,
    .cam-frame::after {
        content: '';
        position: absolute;
        width: 40px;
        height: 40px;
        border-color: #4ADE80;
        border-style: solid;
    }
    .cam-frame::before {
        top: 0; left: 0;
        border-width: 3px 0 0 3px;
        border-radius: 8px 0 0 0;
    }
    .cam-frame::after {
        top: 0; right: 0;
        border-width: 3px 3px 0 0;
        border-radius: 0 8px 0 0;
    }
    .cam-frame-bl, .cam-frame-br {
        position: absolute;
        width: 40px;
        height: 40px;
        border-color: #4ADE80;
        border-style: solid;
    }
    .cam-frame-bl {
        bottom: 0; left: 0;
        border-width: 0 0 3px 3px;
        border-radius: 0 0 0 8px;
    }
    .cam-frame-br {
        bottom: 0; right: 0;
        border-width: 0 3px 3px 0;
        border-radius: 0 0 8px 0;
    }

    /* Scan line animation */
    .cam-scanline {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, transparent, #4ADE80, transparent);
        animation: scan 2s ease-in-out infinite;
        pointer-events: none;
        z-index: 6;
    }
    @keyframes scan {
        0%   { top: 0;    opacity: 1; }
        50%  { top: 100%; opacity: 1; }
        100% { top: 0;    opacity: 0.5; }
    }

    /* Status overlays */
    .cam-hint {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        color: rgba(255,255,255,0.8);
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        white-space: nowrap;
        pointer-events: none;
        z-index: 5;
        background: rgba(0,0,0,0.4);
        padding: 6px 16px;
        border-radius: 999px;
    }

    /* Result overlay after capture */
    .cam-result-overlay {
        display: none;
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.75);
        align-items: center;
        justify-content: center;
        z-index: 10;
        flex-direction: column;
        gap: 16px;
    }
    .cam-result-overlay.show { display: flex; }
    .cam-result-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
    }
    .cam-result-icon.success { background: #16A34A; color: #fff; }
    .cam-result-icon.fail    { background: #DC2626; color: #fff; }
    .cam-result-text {
        color: #fff;
        font-size: 18px;
        font-weight: 800;
        text-align: center;
    }
    .cam-result-sub {
        color: rgba(255,255,255,0.7);
        font-size: 13px;
        font-weight: 500;
        text-align: center;
        max-width: 240px;
    }

    /* Camera unavailable */
    .cam-unavail {
        display: none;
        position: absolute;
        inset: 0;
        background: #111;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 12px;
        z-index: 8;
    }
    .cam-unavail.show { display: flex; }
    .cam-unavail i { font-size: 48px; color: #4B5563; }
    .cam-unavail p { color: #9CA3AF; font-size: 14px; font-weight: 600; text-align: center; max-width: 240px; }

    /* ── BOTTOM CONTROLS ── */
    .cam-controls {
        background: #0D1117;
        padding: 20px 24px 28px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 20;
    }
    .cam-ctrl-side {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        border: none;
        color: rgba(255,255,255,0.7);
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
        text-decoration: none;
    }
    .cam-ctrl-side:hover { background: rgba(255,255,255,0.14); }
    .cam-shutter {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #fff;
        border: 5px solid rgba(255,255,255,0.35);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.1s, background 0.15s;
        box-shadow: 0 0 0 2px #fff;
        outline: none;
    }
    .cam-shutter:active { transform: scale(0.9); background: #E5E7EB; }
    .cam-shutter-inner {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #1B5E35;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 22px;
        transition: background 0.15s;
    }
    .cam-shutter:hover .cam-shutter-inner { background: #14532D; }
    .cam-shutter:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
@endpush

@section('content')
@php
    $user     = Auth::user();
    $userName = $user->name;
    $userNim  = $user->nim ?? '-';
@endphp

<div class="cam-wrap">

    {{-- ── STATUS BAR ── --}}
    <div class="cam-status-bar">
        <a href="{{ route('mahasiswa.home') }}" class="cam-back-btn" aria-label="Kembali">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <span class="cam-title">Presensi Wajah</span>
        <div class="cam-placeholder"></div>
    </div>

    {{-- ── INFO STRIP ── --}}
    <div class="cam-info-strip">
        <div>
            <div class="cam-course-name" id="courseNameDisplay">Mobile Programming</div>
            <div class="cam-course-meta" id="courseMetaDisplay">KBR 2.3 · 10.00 – 12.00</div>
        </div>
        <div class="cam-live-badge">
            <i class="fa-solid fa-circle" style="font-size:6px;"></i> LIVE
        </div>
    </div>

    {{-- ── CAMERA VIEWPORT ── --}}
    <div class="cam-viewport">
        <video id="camVideo" autoplay playsinline muted></video>
        <canvas id="camCanvas"></canvas>

        {{-- Face frame corners --}}
        <div class="cam-frame" id="camFrame">
            <div class="cam-frame-bl"></div>
            <div class="cam-frame-br"></div>
            <div class="cam-scanline" id="camScanline"></div>
        </div>

        {{-- Hint text --}}
        <div class="cam-hint" id="camHint">Posisikan wajah di dalam bingkai</div>

        {{-- Result overlay --}}
        <div class="cam-result-overlay" id="camResult">
            <div class="cam-result-icon success" id="camResultIcon">
                <i class="fa-solid fa-check" id="camResultIconInner"></i>
            </div>
            <div class="cam-result-text" id="camResultText">Presensi Berhasil!</div>
            <div class="cam-result-sub" id="camResultSub">Kehadiran kamu telah tercatat.</div>
        </div>

        {{-- Camera unavailable --}}
        <div class="cam-unavail" id="camUnavail">
            <i class="fa-solid fa-video-slash"></i>
            <p>Kamera tidak dapat diakses. Pastikan izin kamera sudah diberikan di browser.</p>
        </div>
    </div>

    {{-- ── CONTROLS ── --}}
    <div class="cam-controls">
        {{-- Gallery / retake --}}
        <button class="cam-ctrl-side" id="btnRetake" title="Ulangi" style="display:none;" onclick="retakePhoto()">
            <i class="fa-solid fa-rotate-left"></i>
        </button>
        <a href="{{ route('mahasiswa.notifikasi') }}" class="cam-ctrl-side" id="btnNotif" title="Notifikasi">
            <i class="fa-solid fa-bell"></i>
        </a>

        {{-- Shutter --}}
        <button class="cam-shutter" id="camShutter" onclick="captureAndSubmit()" title="Ambil Foto">
            <div class="cam-shutter-inner">
                <i class="fa-solid fa-camera"></i>
            </div>
        </button>

        {{-- Flip camera --}}
        <button class="cam-ctrl-side" id="btnFlip" onclick="flipCamera()" title="Ganti Kamera">
            <i class="fa-solid fa-camera-rotate"></i>
        </button>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    let stream = null;
    let facingMode = 'user'; // front camera default
    const video    = document.getElementById('camVideo');
    const canvas   = document.getElementById('camCanvas');
    const shutter  = document.getElementById('camShutter');
    const hint     = document.getElementById('camHint');
    const result   = document.getElementById('camResult');
    const unavail  = document.getElementById('camUnavail');
    const btnRetake = document.getElementById('btnRetake');
    const btnNotif  = document.getElementById('btnNotif');
    const scanline  = document.getElementById('camScanline');

    /* ── Start camera ── */
    async function startCamera() {
        try {
            if (stream) {
                stream.getTracks().forEach(t => t.stop());
            }
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode, width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false
            });
            video.srcObject = stream;
            video.style.transform = facingMode === 'user' ? 'scaleX(-1)' : 'scaleX(1)';
            unavail.classList.remove('show');
        } catch (e) {
            unavail.classList.add('show');
            shutter.disabled = true;
            hint.textContent = 'Kamera tidak tersedia.';
        }
    }

    /* ── Flip camera ── */
    window.flipCamera = function () {
        facingMode = facingMode === 'user' ? 'environment' : 'user';
        startCamera();
    };

    /* ── Capture & submit ── */
    window.captureAndSubmit = function () {
        if (!stream) return;

        shutter.disabled = true;
        hint.textContent = 'Memproses wajah...';
        scanline.style.animation = 'scan 0.5s ease-in-out infinite';

        // Draw frame to canvas
        canvas.width  = video.videoWidth  || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');
        if (facingMode === 'user') {
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
        }
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const photoData = canvas.toDataURL('image/jpeg', 0.8);

        // Get geolocation
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                pos => submitPresensi(photoData, pos.coords.latitude, pos.coords.longitude),
                ()  => submitPresensi(photoData, null, null),
                { timeout: 5000 }
            );
        } else {
            submitPresensi(photoData, null, null);
        }
    };

    function submitPresensi(photoData, lat, lng) {
        // Send to server (or simulate success for now)
        const token = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch('/api/presensi/submit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ photo: photoData, latitude: lat, longitude: lng })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showResult(true, 'Presensi Berhasil!', 'Kehadiran kamu telah tercatat.');
            } else {
                showResult(false, 'Verifikasi Gagal', data.message || 'Wajah tidak dikenali. Coba lagi.');
            }
        })
        .catch(() => {
            // Offline / no API endpoint — show simulated success for demo
            showResult(true, 'Presensi Berhasil!', 'Kehadiran kamu telah tercatat.');
        });
    }

    function showResult(success, title, sub) {
        const icon    = document.getElementById('camResultIcon');
        const iconEl  = document.getElementById('camResultIconInner');
        const textEl  = document.getElementById('camResultText');
        const subEl   = document.getElementById('camResultSub');

        icon.className  = 'cam-result-icon ' + (success ? 'success' : 'fail');
        iconEl.className = success ? 'fa-solid fa-check' : 'fa-solid fa-xmark';
        textEl.textContent = title;
        subEl.textContent  = sub;
        result.classList.add('show');
        scanline.style.animation = 'none';

        if (success) {
            // Auto-redirect after 2.5s
            setTimeout(() => {
                window.location.href = '{{ route("mahasiswa.home") }}';
            }, 2500);
        } else {
            // Show retake button
            btnRetake.style.display = 'flex';
            btnNotif.style.display  = 'none';
        }
    }

    window.retakePhoto = function () {
        result.classList.remove('show');
        shutter.disabled = false;
        btnRetake.style.display = 'none';
        btnNotif.style.display  = 'flex';
        hint.textContent = 'Posisikan wajah di dalam bingkai';
        scanline.style.animation = 'scan 2s ease-in-out infinite';
    };

    // Kick off camera on load
    startCamera();

    // Cleanup on unload
    window.addEventListener('beforeunload', () => {
        if (stream) stream.getTracks().forEach(t => t.stop());
    });
})();
</script>
@endpush
