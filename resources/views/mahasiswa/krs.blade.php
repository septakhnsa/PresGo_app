@extends('layouts.mahasiswa')

@section('title', 'KRS - Kartu Rencana Studi - PresGo')

@push('styles')
<style>
/* ─── Reset body untuk scroll normal ─── */
html, body {
    height: auto !important;
    overflow: auto !important;
    min-height: 100vh;
}

/* ─── Wrapper utama halaman KRS ─── */
.krs-wrap {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    width: 100%;
    background: #F5F5F5;
    position: relative;
}

/* ══ AppBar Tosca ══ */
.krs-appbar {
    background: #1B5E35;
    padding: 48px 20px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    position: sticky;
    top: 0;
    z-index: 50;
    box-shadow: 0 2px 12px rgba(0, 150, 136, 0.3);
    flex-shrink: 0;
}
.krs-appbar-back {
    color: #fff;
    font-size: 20px;
    text-decoration: none;
    line-height: 1;
    flex-shrink: 0;
    padding: 4px;
    transition: opacity 0.15s;
}
.krs-appbar-back:hover { opacity: 0.75; }
.krs-appbar-title {
    font-size: 20px;
    font-weight: 800;
    color: #fff;
    letter-spacing: 0.2px;
}

/* ══ Loading State ══ */
#state-loading {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
}
.krs-spinner {
    width: 48px;
    height: 48px;
    border: 5px solid #EBF0EC;
    border-top-color: #1B5E35;
    border-radius: 50%;
    animation: krs-spin 0.9s linear infinite;
}
@keyframes krs-spin { to { transform: rotate(360deg); } }

/* ══ Pending State ══ */
#state-pending {
    flex: 1;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 40px 28px;
    min-height: 70vh;
}
.krs-pending-card {
    background: #fff;
    border-radius: 24px;
    padding: 40px 28px;
    text-align: center;
    width: 100%;
    max-width: 380px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
}
.krs-pending-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #EBF0EC 0%, #C5D6CC 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    animation: krs-pulse 2.5s ease-in-out infinite;
}
@keyframes krs-pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(0, 150, 136, 0.3); }
    50%       { box-shadow: 0 0 0 16px rgba(0, 150, 136, 0); }
}
.krs-pending-icon i { font-size: 46px; color: #1B5E35; }
.krs-pending-title {
    font-size: 20px;
    font-weight: 800;
    color: #212121;
    margin-bottom: 10px;
    line-height: 1.3;
}
.krs-pending-subtitle {
    font-size: 13.5px;
    color: #9E9E9E;
    font-weight: 500;
    line-height: 1.6;
    margin-bottom: 28px;
}
.krs-pending-dots {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.krs-pending-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #1B5E35;
    animation: krs-bounce 1.4s ease-in-out infinite;
}
.krs-pending-dots span:nth-child(2) { animation-delay: 0.2s; }
.krs-pending-dots span:nth-child(3) { animation-delay: 0.4s; }
@keyframes krs-bounce {
    0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
    40%            { transform: scale(1.0); opacity: 1; }
}

/* ══ Normal State ══ */
#state-normal {
    display: none;
    flex-direction: column;
    flex: 1;
}
.krs-list-header { padding: 20px 20px 8px; }
.krs-list-title { font-size: 15px; font-weight: 800; color: #212121; margin-bottom: 4px; }
.krs-list-subtitle { font-size: 12.5px; color: #9E9E9E; font-weight: 500; }

.krs-sks-bar {
    margin: 0 20px 16px;
    background: #EBF0EC;
    border-radius: 12px;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.krs-sks-bar-label { font-size: 12.5px; color: #1B5E35; font-weight: 700; }
.krs-sks-bar-value { font-size: 14px; font-weight: 800; color: #1B5E35; }

.krs-list { padding: 0 20px 110px; flex: 1; }

.krs-matkul-card {
    background: #fff;
    border-radius: 20px;
    padding: 16px 18px;
    margin-bottom: 12px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.07);
    border: 2px solid transparent;
    display: flex;
    align-items: center;
    gap: 14px;
    cursor: pointer;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.1s ease;
}
.krs-matkul-card:active { transform: scale(0.985); }
.krs-matkul-card.selected {
    border-color: #1B5E35;
    box-shadow: 0 4px 18px rgba(0, 150, 136, 0.2);
    background: #F7FBF8;
}
.krs-matkul-card input[type="checkbox"] { display: none; }

.krs-checkbox {
    width: 24px;
    height: 24px;
    min-width: 24px;
    border-radius: 8px;
    border: 2.5px solid #E0E0E0;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.krs-matkul-card.selected .krs-checkbox {
    background: #1B5E35;
    border-color: #1B5E35;
}
.krs-checkbox i {
    font-size: 13px;
    color: #fff;
    opacity: 0;
    transform: scale(0.5);
    transition: all 0.15s ease;
}
.krs-matkul-card.selected .krs-checkbox i {
    opacity: 1;
    transform: scale(1);
}

.krs-matkul-info { flex: 1; min-width: 0; }
.krs-matkul-name {
    font-size: 14.5px;
    font-weight: 700;
    color: #212121;
    margin-bottom: 6px;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.krs-sks-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #EBF0EC;
    color: #1B5E35;
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
}

/* ══ Submit Button ══ */
.krs-submit-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 16px 20px;
    background: linear-gradient(to top, #F5F5F5 60%, transparent);
    z-index: 40;
    display: none;
}
.krs-submit-btn {
    width: 100%;
    padding: 16px;
    background: #FFD54F;
    color: #1B5E35;
    border: none;
    border-radius: 16px;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    font-family: 'Plus Jakarta Sans', sans-serif;
    letter-spacing: 0.3px;
    box-shadow: 0 6px 20px rgba(255, 213, 79, 0.45);
    transition: transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.krs-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(255, 213, 79, 0.55); }
.krs-submit-btn:active { transform: scale(0.97); }
.krs-submit-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

/* ══ Snackbar ══ */
.krs-snackbar {
    position: fixed;
    bottom: 90px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    background: #43A047;
    color: #fff;
    font-size: 13.5px;
    font-weight: 700;
    padding: 13px 20px;
    border-radius: 14px;
    white-space: nowrap;
    z-index: 999;
    opacity: 0;
    transition: all 0.35s ease;
    pointer-events: none;
    box-shadow: 0 6px 24px rgba(67, 160, 71, 0.4);
    display: flex;
    align-items: center;
    gap: 8px;
}
.krs-snackbar.krs-snackbar--show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

/* ══ Empty State ══ */
.krs-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
    color: #9E9E9E;
}
.krs-empty i { font-size: 48px; margin-bottom: 16px; color: #BDBDBD; }
.krs-empty p { font-size: 14px; font-weight: 600; line-height: 1.5; }
</style>
@endpush

@section('content')
@php
    $user = Auth::user();
@endphp

<div class="krs-wrap" id="krsWrap">

    {{-- ══ APPBAR ══ --}}
    <div class="krs-appbar" style="justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <span class="krs-appbar-title">Kartu Rencana Studi</span>
        </div>
        <form action="{{ route('mahasiswa.logout') }}" method="POST" id="krsLogoutForm" style="margin: 0;">
            @csrf
            <button type="submit" class="krs-appbar-back" aria-label="Keluar" style="background: none; border: none; cursor: pointer;">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        </form>
    </div>

    {{-- ══ STATE: LOADING ══ --}}
    <div id="state-loading">
        <div class="krs-spinner"></div>
    </div>

    {{-- ══ STATE: PENDING (Menunggu Persetujuan Admin) ══ --}}
    <div id="state-pending">
        <div class="krs-pending-card">
            <div class="krs-pending-icon">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="krs-pending-title">Menunggu persetujuan admin</div>
            <div class="krs-pending-subtitle">
                KRS Anda sedang dalam proses peninjauan.<br>
                Kami akan memberitahu Anda segera setelah disetujui.
            </div>
            <div class="krs-pending-dots">
                <span></span><span></span><span></span>
            </div>
        </div>
    </div>

    {{-- ══ STATE: NORMAL (Pilih Mata Kuliah) ══ --}}
    <div id="state-normal">

        <div class="krs-list-header">
            <div class="krs-list-title">Pilih Mata Kuliah</div>
            <div class="krs-list-subtitle">Centang mata kuliah yang ingin kamu ambil semester ini.</div>
        </div>

        {{-- Counter SKS --}}
        <div class="krs-sks-bar">
            <span class="krs-sks-bar-label">
                <i class="fa-solid fa-layer-group" style="margin-right: 6px;"></i>Total SKS Dipilih
            </span>
            <span class="krs-sks-bar-value" id="totalSks">0 SKS</span>
        </div>

        {{-- Daftar Mata Kuliah --}}
        <div class="krs-list" id="matkulList">
            @if($jadwals->isEmpty())
                <div class="krs-empty">
                    <i class="fa-solid fa-book-open"></i>
                    <p>Belum ada mata kuliah yang tersedia.<br>Hubungi admin untuk informasi lebih lanjut.</p>
                </div>
            @else
                @foreach($jadwals as $jadwal)
                    @if($jadwal->mataKuliah)
                    <div class="krs-matkul-card"
                         id="card-{{ $jadwal->id }}"
                         data-jadwal-id="{{ $jadwal->id }}"
                         data-sks="{{ $jadwal->mataKuliah->sks ?? 0 }}"
                         onclick="toggleCard(this)">

                        <input type="checkbox"
                               id="chk-{{ $jadwal->id }}"
                               name="jadwal_ids[]"
                               value="{{ $jadwal->id }}"
                               data-sks="{{ $jadwal->mataKuliah->sks ?? 0 }}">

                        <div class="krs-checkbox">
                            <i class="fa-solid fa-check"></i>
                        </div>

                        <div class="krs-matkul-info">
                            <div class="krs-matkul-name">{{ $jadwal->mataKuliah->nama_mk }}</div>
                            <span class="krs-sks-badge">
                                <i class="fa-solid fa-star" style="font-size: 9px;"></i>
                                {{ $jadwal->mataKuliah->sks ?? 0 }} SKS
                            </span>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>

    {{-- ══ TOMBOL SIMPAN KRS (floating) ══ --}}
    <div class="krs-submit-bar" id="submitBar">
        <button class="krs-submit-btn" id="submitBtn" onclick="submitKrs()">
            <i class="fa-solid fa-floppy-disk"></i>
            Simpan KRS
        </button>
    </div>

    {{-- ══ SNACKBAR ══ --}}
    <div class="krs-snackbar" id="krsSnackbar">
        <i class="fa-solid fa-circle-check"></i>
        <span id="krsSnackbarMsg">Berhasil!</span>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ════════════════════════════════════════════════════
//  CONFIG & STATE
// ════════════════════════════════════════════════════
const CSRF_TOKEN    = '{{ csrf_token() }}';
const SUBMIT_URL    = '{{ route("mahasiswa.krs.submit") }}';
const POLL_URL      = '{{ route("mahasiswa.krs.poll") }}';
const HOME_URL      = '{{ route("mahasiswa.home") }}';
const isPendingInit = {{ $hasPending ? 'true' : 'false' }};

let pollTimer    = null;
let isSubmitting = false;
let snackTimer   = null;

// ════════════════════════════════════════════════════
//  INIT
// ════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    // Sembunyikan loading, tampilkan state yang tepat
    hideEl('state-loading');
    if (isPendingInit) {
        showStatePending();
    } else {
        showStateNormal();
    }
});

// ════════════════════════════════════════════════════
//  STATE MANAGEMENT
// ════════════════════════════════════════════════════
function hideEl(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = 'none';
}

function showStatePending() {
    hideEl('state-loading');
    hideEl('state-normal');
    document.getElementById('submitBar').style.display = 'none';

    const el = document.getElementById('state-pending');
    el.style.display         = 'flex';
    el.style.flex            = '1';
    el.style.alignItems      = 'center';
    el.style.justifyContent  = 'center';

    startPolling();
}

function showStateNormal() {
    hideEl('state-loading');
    hideEl('state-pending');
    stopPolling();

    const el = document.getElementById('state-normal');
    el.style.display       = 'flex';
    el.style.flexDirection = 'column';
    el.style.flex          = '1';

    document.getElementById('submitBar').style.display = 'block';
    updateSksCounter();
}

// ════════════════════════════════════════════════════
//  TOGGLE CARD (pilih / batalkan matkul)
// ════════════════════════════════════════════════════
function toggleCard(card) {
    const chk        = card.querySelector('input[type="checkbox"]');
    const isSelected = card.classList.toggle('selected');
    chk.checked      = isSelected;
    updateSksCounter();
}

// ════════════════════════════════════════════════════
//  SKS COUNTER
// ════════════════════════════════════════════════════
function updateSksCounter() {
    const boxes = document.querySelectorAll('#matkulList input[type="checkbox"]:checked');
    let total = 0;
    boxes.forEach(chk => { total += parseInt(chk.dataset.sks || 0); });
    document.getElementById('totalSks').textContent = total + ' SKS';

    const btn = document.getElementById('submitBtn');
    if (btn) btn.disabled = (boxes.length === 0);
}

// ════════════════════════════════════════════════════
//  SUBMIT KRS
// ════════════════════════════════════════════════════
function submitKrs() {
    if (isSubmitting) return;

    const boxes = document.querySelectorAll('#matkulList input[type="checkbox"]:checked');
    if (boxes.length === 0) {
        showSnackbar('Pilih minimal 1 mata kuliah terlebih dahulu.', '#E53935');
        return;
    }

    const jadwalIds = [];
    boxes.forEach(chk => jadwalIds.push(parseInt(chk.value)));

    isSubmitting = true;
    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
    }

    fetch(SUBMIT_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ jadwal_ids: jadwalIds })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showStatePending();
        } else {
            showSnackbar('Terjadi kesalahan. Coba lagi.', '#E53935');
        }
    })
    .catch(() => {
        showSnackbar('Gagal terhubung ke server.', '#E53935');
    })
    .finally(() => {
        isSubmitting = false;
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan KRS';
        }
    });
}

// ════════════════════════════════════════════════════
//  POLLING (cek krs_completed setiap 3 detik)
// ════════════════════════════════════════════════════
function startPolling() {
    stopPolling();
    pollTimer = setInterval(pollKrsStatus, 3000);
}

function stopPolling() {
    if (pollTimer) {
        clearInterval(pollTimer);
        pollTimer = null;
    }
}

function pollKrsStatus() {
    fetch(POLL_URL, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.krs_completed === '1' || data.krs_completed === 1) {
            stopPolling();
            showSnackbar('Admin telah menyetujui KRS Anda!', '#43A047');
            setTimeout(() => { window.location.href = HOME_URL; }, 2200);
        }
    })
    .catch(() => {
        // Abaikan error polling — coba lagi di interval berikutnya
    });
}

// ════════════════════════════════════════════════════
//  SNACKBAR / TOAST
// ════════════════════════════════════════════════════
function showSnackbar(message, color) {
    const bar = document.getElementById('krsSnackbar');
    const msg = document.getElementById('krsSnackbarMsg');
    if (!bar || !msg) return;
    if (color) bar.style.background = color;
    msg.textContent = message;
    bar.classList.add('krs-snackbar--show');
    if (snackTimer) clearTimeout(snackTimer);
    snackTimer = setTimeout(() => {
        bar.classList.remove('krs-snackbar--show');
    }, 3500);
}

window.addEventListener('beforeunload', stopPolling);
</script>
@endpush
