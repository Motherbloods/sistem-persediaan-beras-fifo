<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #F8FAFC;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1E293B;
        }

        .container {
            text-align: center;
            max-width: 400px;
            padding: 24px;
        }

        .code {
            font-size: 80px;
            font-weight: 600;
            color: #E2E8F0;
            line-height: 1;
            margin-bottom: 12px;
        }

        .code span {
            color: #DC2626;
        }

        h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        p {
            font-size: 14px;
            color: #64748B;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        a {
            display: inline-block;
            background: #2563EB;
            color: #fff;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
        }

        a:hover {
            background: #1D4ED8;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="code"><span>4</span>03</div>
        <h1>Akses Ditolak</h1>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini. Hubungi administrator jika Anda merasa ini keliru.</p>
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}">
            ← Kembali
        </a>
    </div>
</body>

</html>
