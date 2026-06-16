<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PresGo</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --tosca: #14532D;
            --mint: #DCFCE7;
            --gold: #FFD54F;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --border-width: 3px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--mint);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background-color: white;
            border: var(--border-width) solid var(--text-dark);
            border-radius: 24px;
            width: 100%;
            max-width: 420px;
            padding: 32px;
            box-shadow: -8px 8px 0px var(--text-dark);
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-box {
            background-color: var(--gold);
            color: var(--tosca);
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 900;
            border: 2.5px solid var(--text-dark);
            box-shadow: -4px 4px 0px var(--text-dark);
            margin-bottom: 12px;
        }

        .login-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--tosca);
        }

        .login-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            border: 2px solid var(--text-dark);
            border-radius: 12px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: var(--tosca);
            box-shadow: -3px 3px 0px var(--tosca);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--tosca);
            color: white;
            border: 2.5px solid var(--text-dark);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: -4px 4px 0px var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.1s ease;
            margin-top: 8px;
        }

        .login-btn:active {
            transform: translate(-3px, 3px);
            box-shadow: -1px 1px 0px var(--text-dark);
        }

        .error-alert {
            background-color: #FEE2E2;
            color: #B91C1C;
            border: 2px solid var(--text-dark);
            border-radius: 12px;
            padding: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <div class="logo-box">P</div>
            <h1>PresGo Admin</h1>
            <p>Sistem Presensi Mahasiswa Terintegrasi</p>
        </div>

        @if($errors->any())
            <div class="error-alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">E-mail Administrator</label>
                <input type="email" name="email" id="email" class="form-input" placeholder="admin@presgo.ac.id" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="login-btn">
                Masuk Ke Panel <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>
    </div>

</body>
</html>
