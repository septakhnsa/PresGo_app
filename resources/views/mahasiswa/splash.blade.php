@extends('layouts.mahasiswa')

@section('title', 'PresGo')

@push('styles')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body, html {
        height: 100%;
        overflow: hidden;
    }

    .splash-screen {
        position: fixed;
        inset: 0;
        background-color: #1B5E35;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        text-decoration: none;
    }

    .splash-content {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        text-decoration: none;
    }

    .splash-logo-wrap {
        position: relative;
        width: 116px;
        height: 116px;
    }

    .splash-logo-wrap::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 100vw;
        height: 80px;
        background-color: #ede5c9;
        opacity: 1.50;
        z-index: -1;
    }

    .splash-logo-box {
        width: 116px;
        height: 116px;
        border-radius: 28px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 1;
    }

    .splash-logo-box span {
        font-family: 'Palatino Linotype', Palatino, 'Book Antiqua', Georgia, serif;
        font-size: 80px;
        font-style: italic;
        font-weight: 700;
        color: #1B5E35;
        line-height: 1;
        display: block;
        padding-bottom: 6px;
        -webkit-text-stroke: 1px #1B5E35;
    }

    .splash-text-wrap {
        margin-top: 28px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .splash-title {
        color: #ffffff;
        font-size: 31px;
        font-weight: 800;
        letter-spacing: 0.6px;
        line-height: 1;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .splash-tagline {
        color: #E7E2CE;
        font-size: 13.5px;
        font-weight: 600;
        letter-spacing: 0.3px;
        line-height: 1;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
</style>
@endpush

@section('content')
<a href="{{ route('mahasiswa.login') }}" class="splash-screen">
    <div class="splash-content">
        <div class="splash-logo-wrap">
            <div class="splash-logo-box">
                <span>P</span>
            </div>
        </div>
        <div class="splash-text-wrap">
            <p class="splash-title">PresGo</p>
            <p class="splash-tagline">Hadir Tepat, Pasti Tercatat</p>
        </div>
    </div>
</a>
@endsection

@push('scripts')
<script>
    setTimeout(function () {
        window.location.href = "{{ route('mahasiswa.login') }}";
    }, 8000);
</script>
@endpush