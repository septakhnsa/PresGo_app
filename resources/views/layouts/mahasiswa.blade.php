<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PresGo')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,800;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --green: #1E4A35;
            --green-dark: #15331F;
            --green-soft: #2A5C42;
            --cream: #F2ECD8;
            --gold: #FFD54F;
            --gold-dark: #E6B800;
            --text-dark: #14241B;
            --text-muted: #6B7A70;
            --red: #E54848;
            --field-bg: #F4F6F4;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #D4E0D5; /* light green desktop bg */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Phone-like canvas — fills full viewport on real mobile, centered frame on desktop */
        .app-screen {
            position: relative;
            width: 100%;
            height: 100vh;
            background-color: #f1f5f9;
            border-radius: 0;
            overflow: hidden;
            box-shadow: none;
            display: flex;
            flex-direction: column;
            /* children that use height:100% will fill this */
        }

        /* Any direct children page wrappers fill the screen */
        .app-screen > * {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        @media (min-width: 481px) {
            body { padding: 0; }
            .app-screen {
                height: 100vh;
                max-height: none;
            }
        }

        .stripe {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 230px;
            background-color: var(--cream);
        }

        .logo-circle {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.18);
        }

        .logo-circle span {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 44px;
            color: var(--green);
        }

        .logo-circle.sm { width: 76px; height: 76px; }
        .logo-circle.sm span { font-size: 34px; }

        h1, h2 { font-family: 'Plus Jakarta Sans', sans-serif; }

        .field-group { margin-bottom: 18px; text-align: left; }

        .field-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }

        .field-wrap { position: relative; }

        .field-wrap input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: none;
            background-color: var(--field-bg);
            font-family: inherit;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            outline: none;
        }

        .field-wrap input::placeholder { color: #9AA39C; }

        .field-wrap input:focus {
            box-shadow: 0 0 0 2px var(--gold);
        }

        .toggle-eye {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9AA39C;
            cursor: pointer;
            font-size: 14px;
            padding: 4px;
        }

        .btn-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-gold {
            flex: 1;
            padding: 15px;
            background-color: var(--gold);
            color: var(--text-dark);
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: transform 0.1s ease, background-color 0.15s ease;
        }

        .btn-gold:hover { background-color: var(--gold-dark); }
        .btn-gold:active { transform: scale(0.98); }

        .btn-finger {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 50%;
            background-color: var(--green-soft);
            border: 2px solid var(--gold);
            color: #fff;
            font-size: 19px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }

        .btn-finger:hover { background-color: var(--gold); color: var(--text-dark); }

        .link-muted {
            color: #E7E2CE;
            text-decoration: none;
            font-weight: 600;
        }
        .link-muted:hover { text-decoration: underline; }

        .link-gold {
            color: var(--gold);
            font-weight: 800;
            text-decoration: none;
        }
        .link-gold:hover { text-decoration: underline; }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #E7E2CE;
            font-weight: 600;
        }
        .checkbox-row input { width: 16px; height: 16px; accent-color: var(--gold); }

        .alert {
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            text-align: left;
        }
        .alert-error {
            background-color: rgba(229, 72, 72, 0.15);
            color: #FFD4D4;
            border: 1px solid rgba(229, 72, 72, 0.5);
        }
        .alert-success {
            background-color: rgba(255, 213, 79, 0.18);
            color: var(--gold);
            border: 1px solid rgba(255, 213, 79, 0.5);
        }

        /* Touch ID / fingerprint modal */
        .modal-overlay {
            position: absolute;
            inset: 0;
            background-color: rgba(10, 20, 14, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 32px;
            z-index: 50;
        }
        .modal-overlay.is-open { display: flex; }

        .modal-card {
            background-color: rgba(245, 245, 245, 0.97);
            border-radius: 18px;
            padding: 28px 24px;
            width: 100%;
            max-width: 300px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .modal-card i.fa-fingerprint {
            font-size: 40px;
            color: var(--red);
            margin-bottom: 14px;
        }

        .modal-card h3 {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .modal-card p {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 18px;
        }

        .modal-card .modal-note {
            font-size: 11px;
            color: var(--red);
            margin-bottom: 14px;
            min-height: 14px;
        }

        .modal-cancel {
            background: none;
            border: none;
            font-size: 13px;
            font-weight: 700;
            color: var(--green);
            cursor: pointer;
            padding: 6px 12px;
        }

        /* On real mobile, remove frame styling — fill full viewport */
        @media (max-width: 480px) {
            body { padding: 0; align-items: stretch; }
            .app-screen {
                border-radius: 0;
                height: 100vh;
                max-height: 100vh;
                box-shadow: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    @yield('content')

    @stack('scripts')
</body>
</html>
