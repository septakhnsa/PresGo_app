@extends('layouts.mahasiswa')

@section('title', 'Riwayat Presensi - PresGo')

@push('styles')
<style>
    html, body { height: 100%; overflow: hidden; }

    /* ════════════════════════════════════
       WRAPPER
    ════════════════════════════════════ */
    .hs-wrap {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
        background: #7A9E7E;   /* pastel green canvas — sama dengan notifikasi */
        overflow: hidden;
    }

    /* ── HEADER ── */
    .hs-header {
        background: #1B5E35;
        padding: 30px 23px 0;
        flex-shrink: 0;
        position: relative;
        z-index: 10;
    }
    .hs-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    .hs-header h1 {
        color: #fff;
        font-size: 22px;
        font-weight: 800;
        margin: 0;
    }
    .hs-header-sub {
        color: #9FC7A8;
        font-size: 12px;
        font-weight: 500;
        margin-top: 4px;
    }

    /* ── TABS ── */
    .hs-tabs {
        display: flex;
        background: #1B5E35;
        justify-content: center;
        gap: 0;
        border-bottom: 2px solid rgba(255,255,255,0.12);
    }
    .hs-tab {
        flex: 1;
        text-align: center;
        padding: 12px 0;
        color: rgba(255,255,255,0.65);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: color 0.2s;
    }
    .hs-tab.active {
        color: #FFD54F;
        border-bottom: 3px solid #FFD54F;
    }

    /* ── SUMMARY STRIP ── */
    .hs-summary {
        background: #14532D;
        display: flex;
        justify-content: space-around;
        padding: 14px 16px;
        flex-shrink: 0;
        gap: 8px;
    }
    .hs-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
    }
    .hs-stat-num {
        font-size: 22px;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }
    .hs-stat-lbl {
        font-size: 10px;
        font-weight: 700;
        color: #9FC7A8;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .hs-stat-divider {
        width: 1px;
        background: rgba(255,255,255,0.15);
        align-self: stretch;
    }

    /* ── BODY ── */
    .hs-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px 14px 0;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .hs-body::-webkit-scrollbar { display: none; }

    /* ── FILTER CHIP 3D ── */
    .hs-filter-row {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        overflow-x: auto;
        scrollbar-width: none;
        padding-bottom: 2px;
    }
    .hs-filter-row::-webkit-scrollbar { display: none; }
    .hs-filter-chip {
        padding: 7px 22px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        font-family: inherit;
        white-space: nowrap;
        transition: all 0.18s;
        background: rgba(255,255,255,0.28);
        color: rgba(255,255,255,0.92);
        box-shadow:
            0 4px 0 rgba(0,0,0,0.22),
            0 6px 16px rgba(0,0,0,0.14),
            0 1px 0 rgba(255,255,255,0.30) inset;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    .hs-filter-chip.active {
        background: #1B5E35;
        color: #FFD54F;
        box-shadow:
            0 4px 0 #0d3d1f,
            0 6px 20px rgba(27,94,53,0.55),
            0 1px 0 rgba(255,255,255,0.18) inset;
    }
    .hs-filter-chip:active {
        transform: translateY(4px);
        box-shadow:
            0 0 0 rgba(0,0,0,0),
            0 2px 6px rgba(0,0,0,0.12);
    }

    /* ── MONTH LABEL ── */
    .hs-month-label {
        font-size: 11px;
        font-weight: 800;
        color: rgba(255,255,255,0.82);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 16px 0 8px;
        padding-left: 2px;
    }

    /* ════════════════════════════════════
       HISTORY CARD — 3D, selaras notifikasi
    ════════════════════════════════════ */
    .hs-card {
        background: #fff;
        border-radius: 18px;
        padding: 14px 14px;
        margin-bottom: 14px;
        display: flex;
        gap: 12px;
        align-items: center;
        /* Layered 3D shadow */
        box-shadow:
            0 5px 0 #b8d0bb,
            0 8px 24px rgba(0,0,0,0.18),
            0 1px 4px rgba(0,0,0,0.08);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        position: relative;
        overflow: hidden;
        border-left: 4px solid #1B5E35;
    }
    .hs-card:active {
        transform: translateY(4px) scale(0.985);
        box-shadow:
            0 1px 0 #b8d0bb,
            0 3px 10px rgba(0,0,0,0.14);
    }
    .hs-card:hover {
        transform: translateY(-2px);
        box-shadow:
            0 7px 0 #b8d0bb,
            0 12px 32px rgba(0,0,0,0.20),
            0 1px 4px rgba(0,0,0,0.08);
    }
    .hs-card.absen-card {
        border-left-color: #DC2626;
        box-shadow:
            0 5px 0 #f5c5c5,
            0 8px 24px rgba(0,0,0,0.18),
            0 1px 4px rgba(0,0,0,0.08);
    }
    .hs-card.absen-card:hover {
        box-shadow:
            0 7px 0 #f5c5c5,
            0 12px 32px rgba(0,0,0,0.20);
    }
    .hs-card.absen-card:active {
        box-shadow:
            0 1px 0 #f5c5c5,
            0 3px 10px rgba(0,0,0,0.14);
    }

    /* ── ICON CIRCLE — identik nt-icon notifikasi ── */
    .hs-card-icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #1B5E35;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Playfair Display', 'Georgia', serif;
        font-style: italic;
        font-weight: 800;
        color: #fff;
        font-size: 22px;
        line-height: 1;
        box-shadow:
            0 3px 10px rgba(27,94,53,0.40),
            0 2px 0 rgba(255,255,255,0.15) inset;
    }
    .hs-card-icon.absen {
        background: #DC2626;
        box-shadow:
            0 3px 10px rgba(220,38,38,0.40),
            0 2px 0 rgba(255,255,255,0.15) inset;
    }

    /* ── CARD CONTENT ── */
    .hs-card-content { flex: 1; min-width: 0; }
    .hs-card-meta-top {
        font-size: 11px;
        font-weight: 700;
        color: #9CA3AF;
        margin-bottom: 3px;
    }
    .hs-card-meta-top strong { color: #374151; }
    .hs-card-mk {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .hs-card-detail {
        font-size: 12px;
        color: #6B7280;
        font-weight: 600;
        line-height: 1.55;
    }
    .hs-card-detail i { color: #1B5E35; margin-right: 3px; }

    /* ── STATUS PILL ── */
    .hs-status-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 6px 13px;
        border-radius: 999px;
        white-space: nowrap;
        flex-shrink: 0;
        align-self: flex-start;
        box-shadow: 0 2px 6px rgba(0,0,0,0.10);
    }
    .hs-status-pill.hadir {
        background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
        color: #065F46;
        box-shadow: 0 2px 6px rgba(16,185,129,0.28);
    }
    .hs-status-pill.absen {
        background: linear-gradient(135deg, #FEE2E2, #FECACA);
        color: #DC2626;
        box-shadow: 0 2px 6px rgba(220,38,38,0.22);
    }
    .hs-status-pill.izin {
        background: linear-gradient(135deg, #FEF3C7, #FDE68A);
        color: #92400E;
        box-shadow: 0 2px 6px rgba(245,158,11,0.22);
    }

    /* ── EMPTY STATE ── */
    .hs-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        gap: 12px;
        text-align: center;
    }
    .hs-empty i { font-size: 48px; color: rgba(255,255,255,0.5); }
    .hs-empty p { color: rgba(255,255,255,0.78); font-size: 14px; font-weight: 600; }

    /* ── BOTTOM BAR ── */
    .hs-bottom-bar {
        background: #1B5E35;
        height: 58px;
        flex-shrink: 0;
        position: relative;
        z-index: 10;
        border-radius: 22px 22px 0 0;
        box-shadow: 0 -2px 12px rgba(0,0,0,0.12);
    }
    .hs-fab {
        position: absolute;
        top: -28px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #1B5E35;
        border: 4px solid #fff;
        box-shadow: 0 4px 14px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 22px;
        text-decoration: none;
        transition: transform 0.15s;
    }
    .hs-fab:hover { transform: translateX(-50%) scale(1.07); }

    /* ══════════════════════════════
       RESPONSIVE DESKTOP
    ══════════════════════════════ */
    @media (min-width: 768px) {
        .hs-header-row {
            max-width: 960px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }
        .hs-tabs { gap: 0; justify-content: center; }
        .hs-tab { flex: 0 1 auto; min-width: 120px; }
        .hs-summary {
            padding-left: max(16px, calc((100% - 960px) / 2));
            padding-right: max(16px, calc((100% - 960px) / 2));
        }
        .hs-body {
            max-width: 960px;
            margin: 0 auto;
            width: 100%;
            padding: 24px 24px 0;
        }
        .hs-bottom-bar { display: none; }
        .hs-fab {
            position: fixed;
            top: auto;
            bottom: 32px;
            right: 32px;
            left: auto;
            transform: none;
        }
        .hs-fab:hover { transform: scale(1.08); }
    }
</style>
@endpush

@section('content')
@php
    \Carbon\Carbon::setLocale('id');
    $user = Auth::user();
    $userName = $user->name;
    $userNim  = $user->nim ?? '-';

    // Ambil riwayat presensi dari DB
    try {
        $presensiList = \App\Models\Presensi::where('user_id', $user->id)
            ->with('jadwal.mataKuliah')
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_masuk')
            ->limit(50)
            ->get();

        $totalHadir = $presensiList->where('status_wajah', 'verified')->count()
                    ?: $presensiList->count();
        $totalAbsen = 0;
    } catch (\Exception $e) {
        $presensiList = collect();
        $totalHadir   = 0;
        $totalAbsen   = 0;
    }

    $dummy = [];
    $dummyHadir = 0;
    $dummyAbsen = 0;

    $dispHadir = $presensiList->isEmpty() ? $dummyHadir : $totalHadir;
    $dispAbsen = $presensiList->isEmpty() ? $dummyAbsen : $totalAbsen;
    $dispTotal = $dispHadir + $dispAbsen;
    $dispPersen = $dispTotal > 0 ? round(($dispHadir / $dispTotal) * 100) : 0;
@endphp

<div class="hs-wrap">

    {{-- ── HEADER ── --}}
    <div class="hs-header">
        <div class="hs-header-row">
            <div>
                <h1>Riwayat Presensi</h1>
                <div class="hs-header-sub">{{ $userName }} &bull; {{ $userNim }}</div>
            </div>
            <a href="{{ route('mahasiswa.notifikasi') }}" style="color:rgba(255,255,255,0.8); font-size:20px; text-decoration:none; margin-top:4px;">
                <i class="fa-solid fa-bell"></i>
            </a>
        </div>

        {{-- Tabs --}}
        <div class="hs-tabs">
            <a href="{{ route('mahasiswa.profile') }}" class="hs-tab">Profile</a>
            <a href="{{ route('mahasiswa.home') }}" class="hs-tab">Home</a>
            <span class="hs-tab active">History</span>
        </div>
    </div>

    {{-- ── SUMMARY ── --}}
    <div class="hs-summary">
        <div class="hs-stat">
            <span class="hs-stat-num" style="color:#4ADE80;">{{ $dispHadir }}</span>
            <span class="hs-stat-lbl">Hadir</span>
        </div>
        <div class="hs-stat-divider"></div>
        <div class="hs-stat">
            <span class="hs-stat-num" style="color:#F87171;">{{ $dispAbsen }}</span>
            <span class="hs-stat-lbl">Absen</span>
        </div>
        <div class="hs-stat-divider"></div>
        <div class="hs-stat">
            <span class="hs-stat-num">{{ $dispTotal }}</span>
            <span class="hs-stat-lbl">Total</span>
        </div>
        <div class="hs-stat-divider"></div>
        <div class="hs-stat">
            <span class="hs-stat-num" style="color:#FFD54F;">{{ $dispPersen }}%</span>
            <span class="hs-stat-lbl">Kehadiran</span>
        </div>
    </div>

    {{-- ── BODY ── --}}
    <div class="hs-body">

        {{-- Filter chip --}}
        <div class="hs-filter-row">
            <button class="hs-filter-chip active" data-filter="semua">Semua</button>
        </div>

        {{-- List from DB --}}
        @if ($presensiList->isNotEmpty())
            @php $lastMonth = ''; @endphp
            @foreach ($presensiList as $p)
                @php
                    $monthLabel = \Carbon\Carbon::parse($p->tanggal)->translatedFormat('F Y');
                    $dayNum     = \Carbon\Carbon::parse($p->tanggal)->format('d');
                    $monthShort = \Carbon\Carbon::parse($p->tanggal)->translatedFormat('M');
                    $jamLabel   = $p->jam_masuk ? substr($p->jam_masuk, 0, 5) : '-';
                    $mkName     = optional(optional($p->jadwal)->mataKuliah)->nama_mk ?? 'Mata Kuliah';
                    $ruangan    = optional($p->jadwal)->ruangan ?? '-';
                    $status     = $p->status_wajah === 'verified' ? 'hadir' : 'hadir';
                @endphp

                @if ($monthLabel !== $lastMonth)
                    <div class="hs-month-label">{{ $monthLabel }}</div>
                    @php $lastMonth = $monthLabel; @endphp
                @endif

                <div class="hs-card {{ $status === 'absen' ? 'absen-card' : '' }}" data-status="{{ $status }}">
                    {{-- Icon circle — seperti nt-icon di notifikasi --}}
                    <div class="hs-card-icon {{ $status === 'absen' ? 'absen' : '' }}">P</div>

                    {{-- Content --}}
                    <div class="hs-card-content">
                        <div class="hs-card-meta-top">
                            <strong>PresGo</strong> &bull; {{ $dayNum }} {{ $monthShort }}
                        </div>
                        <div class="hs-card-mk">{{ $mkName }}</div>
                        <div class="hs-card-detail">
                            <i class="fa-solid fa-location-dot"></i>{{ $ruangan }}
                            &nbsp;&bull;&nbsp;
                            <i class="fa-regular fa-clock"></i>{{ $jamLabel }}
                        </div>
                    </div>

                    {{-- Status pill --}}
                    <span class="hs-status-pill {{ $status }}">{{ ucfirst($status) }}</span>
                </div>
            @endforeach

        @elseif (!empty($dummy))
            {{-- Dummy data --}}
            @php $lastMonth = ''; @endphp
            @foreach ($dummy as $d)
                @php
                    $monthLabel = \Carbon\Carbon::parse($d['tanggal'])->translatedFormat('F Y');
                    $dayNum     = \Carbon\Carbon::parse($d['tanggal'])->format('d');
                    $monthShort = \Carbon\Carbon::parse($d['tanggal'])->translatedFormat('M');
                @endphp

                @if ($monthLabel !== $lastMonth)
                    <div class="hs-month-label">{{ $monthLabel }}</div>
                    @php $lastMonth = $monthLabel; @endphp
                @endif

                <div class="hs-card {{ $d['status'] === 'absen' ? 'absen-card' : '' }}" data-status="{{ $d['status'] }}">
                    <div class="hs-card-icon {{ $d['status'] === 'absen' ? 'absen' : '' }}">P</div>
                    <div class="hs-card-content">
                        <div class="hs-card-meta-top">
                            <strong>PresGo</strong> &bull; {{ $dayNum }} {{ $monthShort }}
                        </div>
                        <div class="hs-card-mk">{{ $d['mk'] }}</div>
                        <div class="hs-card-detail">
                            <i class="fa-solid fa-location-dot"></i>{{ $d['ruangan'] }}
                            &nbsp;&bull;&nbsp;
                            <i class="fa-regular fa-clock"></i>{{ $d['jam'] }}
                        </div>
                    </div>
                    <span class="hs-status-pill {{ $d['status'] }}">{{ ucfirst($d['status']) }}</span>
                </div>
            @endforeach

        @else
            <div class="hs-empty">
                <i class="fa-regular fa-calendar-xmark"></i>
                <p>Belum ada riwayat presensi.</p>
            </div>
        @endif

        <div style="height: 90px;"></div>
    </div>

    {{-- ── BOTTOM BAR ── --}}
    <div class="hs-bottom-bar">
        <a href="{{ route('mahasiswa.presensi.camera') }}" class="hs-fab" aria-label="Presensi">
            <i class="fa-solid fa-camera"></i>
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Filter chips
document.querySelectorAll('.hs-filter-chip').forEach(function(chip) {
    chip.addEventListener('click', function() {
        document.querySelectorAll('.hs-filter-chip').forEach(c => c.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        document.querySelectorAll('.hs-card').forEach(function(card) {
            if (filter === 'semua' || card.dataset.status === filter) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
