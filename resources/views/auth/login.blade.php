<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SIMORA</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            overflow: hidden;
            background: #F5F5F7;
        }

        /* ── KIRI ── */
        .left-panel {
            flex: 1.2;
            background: #E62129;
            position: relative;
            overflow: hidden;
            padding: 60px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left-panel .brand {
            position: absolute;
            top: 40px;
            left: 40px;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .left-panel .title {
            font-size: 64px;
            font-weight: 700;
            line-height: 1.1;
            position: relative;
            z-index: 10;
        }

        /* Floating Logos Container */
        .floating-logos {
            position: absolute;
            top: 50%;
            left: 55%;
            transform: translateY(-20%);
            width: 350px;
            height: 350px;
            z-index: 5;
        }

        .logo-circle {
            position: absolute;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .logo-circle img {
            width: 75%;
            height: 75%;
            object-fit: contain;
        }

        .logo-mpk {
            width: 120px;
            height: 120px;
            top: 0;
            right: 20px;
        }

        .logo-osis {
            width: 120px;
            height: 120px;
            bottom: 40px;
            left: -20px;
        }

        .logo-ambalan {
            width: 80px;
            height: 80px;
            bottom: -20px;
            right: 60px;
        }

        /* Decorative dots and arcs */
        .dot-1 {
            position: absolute;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            top: 40%;
            right: -10px;
        }

        .dot-2 {
            position: absolute;
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            bottom: -80px;
            left: 80px;
        }

        .bottom-arc {
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            border: 45px solid white;
            box-sizing: border-box;
        }

        /* ── KANAN ── */
        .right-panel {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F5F5F7;
            overflow: hidden;
        }

        .top-arc {
            position: absolute;
            top: -120px;
            right: -120px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            border: 60px solid #E62129;
            box-sizing: border-box;
            z-index: 0;
        }

        .login-card {
            background: #FFFFFF;
            border-radius: 28px;
            padding: 50px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            position: relative;
            z-index: 10;
        }

        .login-card h2 {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #000;
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #111;
            margin-bottom: 8px;
        }

        .hv-input {
            width: 100%;
            background: #E5E7EB;
            border: none;
            border-radius: 9999px;
            padding: 14px 20px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #111;
            outline: none;
            transition: box-shadow 0.2s;
        }
        .hv-input:focus {
            box-shadow: 0 0 0 2px #E62129;
        }

        .btn-login {
            width: 100%;
            background: #E62129;
            color: white;
            border: none;
            border-radius: 9999px;
            padding: 14px 24px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 12px;
        }
        .btn-login:hover { background: #C91A20; }
        .btn-login:active { transform: scale(0.99); }

        /* Error */
        .error-msg {
            background: #fee2e2;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13px;
            color: #991b1b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
                overflow: auto;
            }
            .left-panel {
                flex: none;
                min-height: 350px;
                padding: 40px 32px;
            }
            .left-panel .title { font-size: 48px; }
            .floating-logos { transform: scale(0.7) translateY(-30%); left: 40%; }
            .right-panel {
                flex: none;
                padding: 40px 20px;
            }
            .top-arc { display: none; }
        }
    </style>
</head>
<body>

    {{-- PANEL KIRI --}}
    <div class="left-panel">
        <div class="brand">SIMORA</div>
        
        <div class="title">
            Selamat<br>Datang
        </div>

        <div class="floating-logos">
            <div class="logo-circle logo-mpk">
                <img src="{{ asset('assets/images/MPK.jpg') }}" alt="MPK">
            </div>
            <div class="logo-circle logo-osis">
                <img src="{{ asset('assets/images/OSIS.jpg') }}" alt="OSIS">
            </div>
            <div class="logo-circle logo-ambalan">
                <img src="{{ asset('assets/images/ambalan.jpg') }}" alt="Ambalan">
            </div>
            <div class="dot-1"></div>
            <div class="dot-2"></div>
        </div>

        <div class="bottom-arc"></div>
    </div>

    {{-- PANEL KANAN --}}
    <div class="right-panel">
        <div class="top-arc"></div>

        <div class="login-card">
            <h2>Masuk</h2>

            @if(session('session_expired'))
            <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; border-radius: 0 12px 12px 0; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i data-lucide="clock" style="width:16px;height:16px;color:#92400E;flex-shrink:0;"></i>
                <p style="font-family:'Poppins',sans-serif; font-size:12px; color:#92400E; margin:0;">{{ session('session_expired') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="error-msg">
                <i data-lucide="alert-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                {{ $errors->first() }}
            </div>
            @endif

            @if(session('error'))
            <div class="error-msg">
                <i data-lucide="alert-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Username</label>
                    <input type="email" id="email" name="email" class="hv-input" value="{{ old('email') }}" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" class="hv-input" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn-login">
                    Masuk
                </button>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

