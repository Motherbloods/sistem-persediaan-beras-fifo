<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SiPadi CV Santri Abadi</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --brand: #2563EB;
            --brand-dark: #1D4ED8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #F1F5F9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 900px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .12);
        }

        /* Panel kiri — ilustrasi */
        .login-panel-left {
            flex: 1;
            background: linear-gradient(145deg, #1E3A8A 0%, #1D4ED8 50%, #2563EB 100%);
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-panel-left::before {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            border: 40px solid rgba(255, 255, 255, .06);
            top: -80px;
            right: -80px;
        }

        .login-panel-left::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 30px solid rgba(255, 255, 255, .04);
            bottom: -60px;
            left: -60px;
        }

        .panel-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .panel-brand-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, .15);
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 20px;
            color: #fff;
        }

        .panel-brand-text strong {
            display: block;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
        }

        .panel-brand-text span {
            color: rgba(255, 255, 255, .6);
            font-size: 12px;
        }

        .panel-body {
            position: relative;
            z-index: 1;
        }

        .panel-body h2 {
            color: #fff;
            font-size: 26px;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 12px;
        }

        .panel-body p {
            color: rgba(255, 255, 255, .65);
            font-size: 14px;
            line-height: 1.7;
        }

        .panel-features {
            list-style: none;
            padding: 0;
            margin: 20px 0 0;
        }

        .panel-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, .75);
            font-size: 13px;
            margin-bottom: 10px;
        }

        .panel-features li i {
            font-size: 15px;
            color: #93C5FD;
        }

        /* Panel kanan — form */
        .login-panel-right {
            width: 380px;
            background: #fff;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-panel-right h3 {
            font-size: 22px;
            font-weight: 600;
            color: #1E293B;
            margin-bottom: 4px;
        }

        .login-panel-right p.sub {
            font-size: 13.5px;
            color: #64748B;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 5px;
        }

        .form-control {
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
            outline: none;
        }

        .input-group-icon {
            position: relative;
        }

        .input-group-icon .form-control {
            padding-left: 38px;
        }

        .input-group-icon .icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 15px;
            pointer-events: none;
        }

        .btn-login {
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 11px;
            font-size: 14px;
            font-weight: 500;
            width: 100%;
            cursor: pointer;
            transition: background .15s, transform .1s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-login:hover {
            background: var(--brand-dark);
        }

        .btn-login:active {
            transform: scale(.99);
        }

        .form-check-label {
            font-size: 13px;
            color: #64748B;
        }

        @media (max-width: 650px) {
            .login-panel-left {
                display: none;
            }

            .login-panel-right {
                width: 100%;
                border-radius: 16px;
            }

            .login-wrapper {
                max-width: 420px;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">

        {{-- Panel kiri --}}
        <div class="login-panel-left">
            <div class="panel-brand">
                <div>
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 40px; height: 40px;">
                </div>
                <div class="panel-brand-text">
                    <strong>SiPadi</strong>
                    <span>Sistem Informasi Persediaan</span>
                </div>
            </div>

            <div class="panel-body">
                <h2>Kelola stok beras lebih terstruktur</h2>
                <p>Sistem informasi persediaan berbasis web dengan metode FIFO untuk CV Santri Abadi Indonesia.</p>
                <ul class="panel-features">
                    <li><i class="bi bi-check-circle-fill"></i> Pencatatan stok masuk & keluar otomatis</li>
                    <li><i class="bi bi-check-circle-fill"></i> Metode FIFO (First In, First Out)</li>
                    <li><i class="bi bi-check-circle-fill"></i> Notifikasi stok menipis real-time</li>
                    <li><i class="bi bi-check-circle-fill"></i> Laporan persediaan lengkap & PDF</li>
                </ul>
            </div>

            <div style="color: rgba(255,255,255,.35); font-size: 12px;">
                © {{ date('Y') }} CV Santri Abadi Indonesia
            </div>
        </div>

        {{-- Panel kanan --}}
        <div class="login-panel-right">
            <h3>Masuk ke sistem</h3>
            <p class="sub">Masukkan email dan password Anda untuk melanjutkan.</p>

            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3"
                    style="font-size:13px; border-radius:8px;">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group-icon">
                        <i class="bi bi-envelope icon"></i>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="nama@email.com" value="{{ old('email') }}" autofocus required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group-icon">
                        <i class="bi bi-lock icon"></i>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                            required>
                    </div>
                </div>

                <div class="mb-4 d-flex align-items-center justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
