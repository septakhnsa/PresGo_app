@extends('layouts.mahasiswa')

@section('title', 'Notifikasi - PresGo')

@push('styles')
<style>
    /* ── root overrides ── */
    html, body { height: 100%; overflow: hidden; }

    /* ════════════════════════════════════════
       WRAPPER — fills app-screen
    ════════════════════════════════════════ */
    .nt-wrap {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
        background: #7A9E7E;   /* pastel green canvas from Figma */
        overflow: hidden;
    }

    /* ════════════════════════════════════════
       TOP GREEN HEADER
    ════════════════════════════════════════ */
    .nt-header {
        background: #1B5E35;
        padding: 40px 20px 18px;
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        z-index: 10;
    }
    .nt-header-left h1 {
        color: #fff;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.2;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .nt-header-left h1 .bell-icon {
        color: #FFD54F;
        font-size: 18px;
    }
    .nt-header-date {
        color: #9FC7A8;
        font-size: 12px;
        font-weight: 500;
        margin-top: 5px;
    }

    /* Three-dot menu */
    .nt-menu-btn {
        background: none;
        border: none;
        color: rgba(255,255,255,0.85);
        font-size: 22px;
        cursor: pointer;
        padding: 0 4px;
        line-height: 1;
        position: relative;
    }
    .nt-menu-btn:hover { color: #fff; }

    /* Dropdown menu */
    .nt-dropdown {
        display: none;
        position: absolute;
        top: 70px;
        right: 16px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.18);
        min-width: 180px;
        z-index: 200;
        overflow: hidden;
    }
    .nt-dropdown.open { display: block; }
    .nt-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        font-family: inherit;
        transition: background 0.15s;
    }
    .nt-dropdown-item:hover { background: #F3F4F6; }
    .nt-dropdown-item i { width: 18px; color: #1B5E35; }
    .nt-dropdown-item.danger { color: #DC2626; }
    .nt-dropdown-item.danger i { color: #DC2626; }
    .nt-dropdown-separator {
        height: 1px;
        background: #E5E7EB;
        margin: 0;
    }

    /* ════════════════════════════════════════
       SCROLLABLE BODY
    ════════════════════════════════════════ */
    .nt-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px 14px 0;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .nt-body::-webkit-scrollbar { display: none; }

    /* ════════════════════════════════════════
       NOTIFICATION CARD
    ════════════════════════════════════════ */
    .nt-card {
        background: #fff;
        border-radius: 18px;
        padding: 14px 14px 14px 14px;
        margin-bottom: 14px;
        display: flex;
        gap: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.10);
        transition: transform 0.2s ease, opacity 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .nt-card:active { transform: scale(0.985); }
    .nt-card.dismissing {
        transform: translateX(100%);
        opacity: 0;
        transition: transform 0.35s ease, opacity 0.35s ease;
    }
    .nt-card.unread {
        border-left: 4px solid #1B5E35;
    }

    /* Unread dot indicator */
    .nt-unread-dot {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #1B5E35;
    }

    /* Green circle P logo */
    .nt-icon {
        width: 44px;
        height: 44px;
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
    }

    /* Content */
    .nt-content { flex: 1; min-width: 0; }
    .nt-meta {
        font-size: 11px;
        font-weight: 700;
        color: #9CA3AF;
        margin-bottom: 3px;
    }
    .nt-meta strong { color: #374151; }
    .nt-title {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 5px;
    }
    .nt-message {
        font-size: 12.5px;
        color: #6B7280;
        line-height: 1.55;
        font-weight: 500;
    }
    .nt-message .nt-highlight {
        color: #DC2626;
        font-weight: 700;
    }

    /* Action buttons */
    .nt-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }
    .nt-btn-primary {
        flex: 1;
        padding: 10px 0;
        background: #1B5E35;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: background 0.15s, transform 0.1s;
    }
    .nt-btn-primary:hover { background: #14532D; }
    .nt-btn-primary:active { transform: scale(0.97); }
    .nt-btn-secondary {
        flex: 1;
        padding: 10px 0;
        background: transparent;
        color: #6B7280;
        border: 1.5px solid #D1D5DB;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: background 0.15s, color 0.15s;
    }
    .nt-btn-secondary:hover {
        background: #F3F4F6;
        color: #374151;
    }
    .nt-btn-secondary:active { transform: scale(0.97); }

    /* Empty state */
    .nt-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        gap: 14px;
        text-align: center;
    }
    .nt-empty i { font-size: 48px; color: rgba(255,255,255,0.5); }
    .nt-empty p { color: rgba(255,255,255,0.7); font-size: 14px; font-weight: 600; }

    /* Toast feedback */
    .nt-toast {
        position: fixed;
        bottom: 90px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: #111827;
        color: #fff;
        padding: 10px 20px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 600;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s, transform 0.3s;
        z-index: 300;
        white-space: nowrap;
    }
    .nt-toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    /* ════════════════════════════════════════
       BOTTOM GREEN BAR + FAB
    ════════════════════════════════════════ */
    .nt-bottom-bar {
        background: #1B5E35;
        height: 60px;
        flex-shrink: 0;
        position: relative;
        z-index: 10;
        border-radius: 22px 22px 0 0;
        box-shadow: 0 -2px 12px rgba(0,0,0,0.15);
    }
    /* Logout/back FAB matches Figma — red-ringed circle with arrow-right-from-bracket */
    .nt-fab {
        position: absolute;
        top: -28px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 0 0 3px #DC2626, 0 6px 18px rgba(0,0,0,0.20);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #DC2626;
        font-size: 22px;
        text-decoration: none;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .nt-fab:hover {
        transform: translateX(-50%) scale(1.07);
        box-shadow: 0 0 0 3px #DC2626, 0 8px 24px rgba(0,0,0,0.25);
    }
    .nt-fab:active {
        transform: translateX(-50%) scale(0.95);
    }
</style>
@endpush

@section('content')
@php
    \Carbon\Carbon::setLocale('id');
    $todayLabel = now()->translatedFormat('l, d F Y');

    // Mengambil notifikasi dari database
    $notifikasiList = Auth::user()->notifications;
@endphp

<div class="nt-wrap" id="ntWrap">

    {{-- ── TOP HEADER ── --}}
    <div class="nt-header">
        <div class="nt-header-left">
            <h1>Notifikasi <i class="fa-solid fa-bell bell-icon"></i></h1>
            <div class="nt-header-date">{{ $todayLabel }}</div>
        </div>
        <button type="button" class="nt-menu-btn" id="ntMenuBtn" aria-label="Menu">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
    </div>

    {{-- ── DROPDOWN MENU ── --}}
    <div class="nt-dropdown" id="ntDropdown">
        <button class="nt-dropdown-item" id="btnMarkAllRead">
            <i class="fa-solid fa-check-double"></i> Tandai semua dibaca
        </button>
        <div class="nt-dropdown-separator"></div>
        <button class="nt-dropdown-item" id="btnClearAll">
            <i class="fa-solid fa-trash-can"></i> Hapus semua notifikasi
        </button>
        <div class="nt-dropdown-separator"></div>
        <button class="nt-dropdown-item danger" id="btnCloseDropdown">
            <i class="fa-solid fa-xmark"></i> Tutup
        </button>
    </div>

    {{-- ── NOTIFICATION LIST ── --}}
    <div class="nt-body" id="ntBody">
        {{-- Dynamic 15-Minute Reminder Notification --}}
        @if(isset($notifJadwal) && $notifJadwal)
            <div class="nt-card unread" id="card-reminder-{{ $notifJadwal['id'] }}" data-id="reminder-{{ $notifJadwal['id'] }}">
                <div class="nt-unread-dot"></div>
                <div class="nt-icon" style="background: #FFD54F; color: #1B5E35;"><i class="fa-solid fa-bell"></i></div>
                <div class="nt-content">
                    <div class="nt-meta"><strong>PresGo</strong> &bull; Baru saja</div>
                    <div class="nt-title">Pengingat Presensi</div>
                    <div class="nt-message">
                        Kelas <span class="nt-highlight">{{ $notifJadwal['mata_kuliah'] }}</span> bersama dosen <span class="nt-highlight">{{ $notifJadwal['dosen'] }}</span> akan segera dimulai! Jangan lupa melakukan presensi di ruangan {{ $notifJadwal['ruangan'] }}.
                    </div>
                    <div class="nt-actions">
                        <a href="{{ route('mahasiswa.presensi.camera', ['jadwal_id' => $notifJadwal['id']]) }}"
                           class="nt-btn-primary">
                            Presensi Sekarang
                        </a>
                        <button type="button" class="nt-btn-secondary btn-abaikan" data-card-id="reminder-{{ $notifJadwal['id'] }}">
                            Abaikan
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @forelse ($notifikasiList as $notif)
            @php
                $data = $notif->data;
                $isUnread = $notif->unread();
            @endphp
            <div class="nt-card {{ $isUnread ? 'unread' : '' }}"
                 id="card-{{ $notif->id }}"
                 data-id="{{ $notif->id }}">

                {{-- Unread dot --}}
                @if ($isUnread)
                    <div class="nt-unread-dot"></div>
                @endif

                {{-- P Logo --}}
                <div class="nt-icon">P</div>

                {{-- Content --}}
                <div class="nt-content">
                    <div class="nt-meta"><strong>PresGo</strong> &bull; {{ $data['waktu'] ?? $notif->created_at->diffForHumans() }}</div>
                    <div class="nt-title">{{ $data['judul'] ?? 'Notifikasi' }}</div>
                    <div class="nt-message">{!! $data['pesan'] ?? '' !!}</div>

                    @if (isset($data['is_reminder_aktif']) && $data['is_reminder_aktif'])
                        <div class="nt-actions">
                            <a href="{{ route('mahasiswa.presensi.camera', ['jadwal_id' => $data['jadwal_id'] ?? '']) }}"
                               class="nt-btn-primary"
                               id="btnPresensi-{{ $notif->id }}">
                                Presensi Sekarang
                            </a>
                            <button type="button"
                                    class="nt-btn-secondary btn-abaikan"
                                    data-card-id="{{ $notif->id }}"
                                    id="btnAbaikan-{{ $notif->id }}">
                                Abaikan
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            @if(!isset($notifJadwal) || !$notifJadwal)
                <div class="nt-empty" id="ntEmpty">
                    <i class="fa-regular fa-bell-slash"></i>
                    <p>Tidak ada notifikasi saat ini.</p>
                </div>
            @endif
        @endforelse

        {{-- spacer agar konten tidak ketutup FAB --}}
        <div style="height: 90px;"></div>
    </div>

    {{-- ── BOTTOM BAR + FAB ── --}}
    <div class="nt-bottom-bar">
        <a href="{{ route('mahasiswa.home') }}"
           class="nt-fab"
           aria-label="Kembali ke Beranda">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a>
    </div>

    {{-- Toast feedback --}}
    <div class="nt-toast" id="ntToast"></div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    /* ──────────────────────────────────────────
       HELPERS
    ────────────────────────────────────────── */
    function showToast(msg) {
        const t = document.getElementById('ntToast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2500);
    }

    function removeCard(cardId) {
        const card = document.getElementById('card-' + cardId);
        if (!card) return;
        card.classList.add('dismissing');
        card.addEventListener('transitionend', () => {
            card.remove();
            checkEmpty();
        }, { once: true });
    }

    function checkEmpty() {
        const cards = document.querySelectorAll('.nt-card');
        const emptyEl = document.getElementById('ntEmpty');
        if (cards.length === 0) {
            if (!emptyEl) {
                const empty = document.createElement('div');
                empty.id = 'ntEmpty';
                empty.className = 'nt-empty';
                empty.innerHTML = `
                    <i class="fa-regular fa-bell-slash"></i>
                    <p>Tidak ada notifikasi saat ini.</p>
                `;
                document.getElementById('ntBody').prepend(empty);
            }
        }
    }

    /* ──────────────────────────────────────────
       TOMBOL ABAIKAN — dismiss individual card
    ────────────────────────────────────────── */
    document.querySelectorAll('.btn-abaikan').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const cardId = this.dataset.cardId;
            removeCard(cardId);
            showToast('Notifikasi diabaikan.');
            fetch('{{ route("mahasiswa.notifikasi.read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: cardId })
            });
        });
    });

    /* ──────────────────────────────────────────
       TIGA-TITIK MENU
    ────────────────────────────────────────── */
    const menuBtn      = document.getElementById('ntMenuBtn');
    const dropdown     = document.getElementById('ntDropdown');
    const btnClose     = document.getElementById('btnCloseDropdown');
    const btnMarkAll   = document.getElementById('btnMarkAllRead');
    const btnClearAll  = document.getElementById('btnClearAll');

    function openDropdown()  { dropdown.classList.add('open'); }
    function closeDropdown() { dropdown.classList.remove('open'); }

    menuBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.contains('open') ? closeDropdown() : openDropdown();
    });

    btnClose.addEventListener('click', closeDropdown);

    // Close when clicking outside
    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target) && e.target !== menuBtn) {
            closeDropdown();
        }
    });

    /* ── Tandai semua dibaca ── */
    btnMarkAll.addEventListener('click', function () {
        document.querySelectorAll('.nt-card.unread').forEach(function (card) {
            card.classList.remove('unread');
            const dot = card.querySelector('.nt-unread-dot');
            if (dot) dot.remove();
        });
        closeDropdown();
        showToast('Semua notifikasi ditandai dibaca.');
        fetch('{{ route("mahasiswa.notifikasi.read-all") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
    });

    /* ── Hapus semua ── */
    btnClearAll.addEventListener('click', function () {
        const cards = document.querySelectorAll('.nt-card');
        cards.forEach(function (card, i) {
            setTimeout(() => {
                card.classList.add('dismissing');
                card.addEventListener('transitionend', () => {
                    card.remove();
                    checkEmpty();
                }, { once: true });
            }, i * 80);
        });
        closeDropdown();
        showToast('Semua notifikasi dihapus.');
        fetch('{{ route("mahasiswa.notifikasi.delete-all") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
    });

    /* ──────────────────────────────────────────
       Tap on a read card → mark as read visually
    ────────────────────────────────────────── */
    document.querySelectorAll('.nt-card').forEach(function (card) {
        card.addEventListener('click', function (e) {
            // Don't fire when clicking buttons/links inside card
            if (e.target.closest('a, button')) return;
            if (card.classList.contains('unread')) {
                card.classList.remove('unread');
                const dot = card.querySelector('.nt-unread-dot');
                if (dot) dot.remove();
                
                const cardId = card.dataset.id;
                fetch('{{ route("mahasiswa.notifikasi.read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: cardId })
                });
            }
        });
    });
})();
</script>
@endpush