<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SiPadi CV Santri Abadi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>

<body>
    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    <nav id="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" class="brand-logo">
                <div class="brand-icon"><i class="bi bi-box-seam"></i></div>
                <div class="brand-text">
                    <strong>SiPadi</strong>
                    <span>CV Santri Abadi</span>
                </div>
            </a>
        </div>

        <div class="sidebar-nav">
            <div class="nav-label">Utama</div>
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('monitoring') }}" class="{{ request()->routeIs('monitoring') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Monitoring Stok
                    @php $jmlMenipis = \App\Models\JenisBeras::active()->get()->filter(fn($b) => $b->stok_saat_ini <= $b->stok_minimum && $b->stok_saat_ini >= 0)->count(); @endphp
                    @if ($jmlMenipis > 0)
                        <span class="nav-badge">{{ $jmlMenipis }}</span>
                    @endif
                </a>
            </div>

            <div class="nav-label">Transaksi</div>
            <div class="nav-item">
                <a href="#" class="{{ request()->routeIs('stok-masuk.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-down-circle"></i> Stok Masuk
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="{{ request()->routeIs('stok-keluar.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-up-circle"></i> Stok Keluar
                </a>
            </div>

            @if (auth()->user()->isAdmin())
                <div class="nav-label">Laporan</div>
                <div class="nav-item">
                    <a href="#" class="{{ request()->routeIs('laporan.masuk*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph"></i> Laporan Masuk
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="{{ request()->routeIs('laporan.keluar*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-arrow-up"></i> Laporan Keluar
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="{{ request()->routeIs('laporan.persediaan*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-data"></i> Laporan Persediaan
                    </a>
                </div>

                <div class="nav-label">Master Data</div>
                <div class="nav-item">
                    <a href="{{ route('jenis-beras.index') }}"
                        class="{{ request()->routeIs('jenis-beras.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Jenis Beras
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="{{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i> Supplier
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Pengguna
                    </a>
                </div>
            @endif

            <div class="nav-label">Akun</div>
            <div class="nav-item">
                <a href="#" class="{{ request()->routeIs('profil') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i> Profil Saya
                </a>
            </div>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="user-info">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ auth()->user()->role }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout" title="Keluar">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </nav>

    <header id="topbar">
        <button class="d-md-none btn btn-sm btn-outline-secondary me-2" onclick="openSidebar()">
            <i class="bi bi-list"></i>
        </button>

        <div class="topbar-title">
            @yield('page-title', 'Dashboard')
            @hasSection('page-subtitle')
                <small>@yield('page-subtitle')</small>
            @endif
        </div>

        <div class="topbar-actions">
            @yield('topbar-actions')

            @php $totalMenipis = \App\Models\JenisBeras::active()->get()->filter(fn($b) => $b->stok_saat_ini <= $b->stok_minimum)->count(); @endphp
            <button class="notif-btn" onclick="window.location='{{ route('monitoring') }}'">
                <i class="bi bi-bell"></i>
                @if ($totalMenipis > 0)
                    <span class="notif-dot"></span>
                @endif
            </button>
        </div>
    </header>

    <main id="main">
        <div class="page-content">

            @if (session('success'))
                <div class="alert alert-success alert-flash alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-flash alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle (mobile)
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('sidebar-overlay').classList.add('show');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('show');
        }

        // Auto dismiss flash alert setelah 4 detik
        setTimeout(() => {
            document.querySelectorAll('.alert-flash').forEach(el => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                bsAlert.close();
            });
        }, 4000);
    </script>

    @stack('scripts')
</body>

</html>
