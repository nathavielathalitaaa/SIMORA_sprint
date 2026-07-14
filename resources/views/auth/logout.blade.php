<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluar | SIMORA SMK Telkom Sidoarjo</title>
    
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #F6F6F6 0%, #F5F5F7 40%, var(--color-primary) 100%);
            background-attachment: fixed;
            padding: 24px;
        }

        .logout-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 32px;
            padding: 48px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 10px 40px rgba(79, 101, 96, 0.1);
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-box {
            margin-bottom: 32px;
        }
        .logo-box img {
            height: 40px;
            width: auto;
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            background: var(--color-bg-light);
            color: var(--color-text);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: #111111;
            margin-bottom: 12px;
        }

        p {
            font-size: 14px;
            color: #6B7280;
            line-height: 1.6;
            margin-bottom: 36px;
            font-weight: 300;
        }

        .btn-signin {
            display: inline-block;
            width: 100%;
            background: var(--color-text);
            color: white;
            text-decoration: none;
            padding: 16px 24px;
            border-radius: 9999px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(230,33,41,0.10);
        }

        .btn-signin:hover {
            background: #E62129;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(230,33,41,0.20);
        }

        .footer-text {
            margin-top: 32px;
            font-size: 11px;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
    </style>
</head>
<body>

    <div class="logout-card">
        <div class="logo-box">
            <img src="{{ URL::to('assets/images/logo-sinergi.png') }}" alt="SIMORA SMK Telkom Sidoarjo">
        </div>

        <div class="icon-circle">
            <i data-lucide="log-out" style="width: 32px; height: 32px;"></i>
        </div>

        <h1>Telah Keluar</h1>
        <p>Terima kasih telah menggunakan Sinergi HRIS.<br>Anda telah keluar dari akun Anda dengan aman.</p>

        <a href="{{ route('login') }}" class="btn-signin">
            Kembali Ke Halaman Masuk
        </a>

        <div class="footer-text">
            SIMORA SMK Telkom Sidoarjo &middot; HRIS System
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
