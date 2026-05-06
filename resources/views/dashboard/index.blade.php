@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan persediaan beras')

@push('styles')
    <style>
        .welcome-bar {
            background: linear-gradient(135deg, #1E3A8A, #2563EB);
            border-radius: 12px;
            padding: 22px 28px;
            color: #fff;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .welcome-bar h4 {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 4px;
        }

        .welcome-bar p {
            margin: 0;
            opacity: .75;
            font-size: 13.5px;
        }

        .welcome-icon {
            font-size: 42px;
            opacity: .25;
        }

        .alert-menipis {
            border: none;
            background: #FEF9C3;
            border-left: 4px solid #D97706;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            color: #92400E;
        }

        .nav-bulan {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 8px 14px;
            margin-bottom: 20px;
            justify-content: space-between;
        }

        .nav-bulan-label {
            font-size: 15px;
            font-weight: 600;
            color: #1E293B;
            min-width: 140px;
            text-align: center;
        }

        .nav-bulan-badge {
            font-size: 11px;
            background: #DBEAFE;
            color: #1D4ED8;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500;
        }

        .btn-nav {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid #E2E8F0;
            background: #F8FAFC;
            color: #475569;
            display: grid;
            place-items: center;
            text-decoration: none;
            font-size: 14px;
            transition: all .15s;
            flex-shrink: 0;
        }

        .btn-nav:hover {
            background: #EFF6FF;
            border-color: #2563EB;
            color: #2563EB;
        }

        .btn-nav.disabled {
            opacity: .35;
            pointer-events: none;
            cursor: not-allowed;
        }

        .riwayat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-bottom: 1px solid #F1F5F9;
            transition: background .1s;
        }

        .riwayat-item:last-child {
            border-bottom: none;
        }

        .riwayat-item:hover {
            background: #F8FAFC;
        }

        .riwayat-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .riwayat-empty {
            text-align: center;
            padding: 32px 16px;
            color: #94A3B8;
            font-size: 13px;
        }
    </style>
@endpush

@section('content')

    <div class="welcome-bar">
        <div>
            <h4>Selamat datang, {{ auth()->user()->name }} 👋</h4>
            <p>{{ now()->translatedFormat('l, d F Y') }} &mdash; Data persediaan diperbarui secara langsung.</p>
        </div>
    </div>

    @if ($berasMenipis->count() > 0)
        <div class="alert-menipis mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>{{ $berasMenipis->count() }} jenis beras</strong> membutuhkan perhatian:
            {{ $berasMenipis->pluck('nama_beras')->join(', ') }}.
            <a href="{{ route('monitoring') }}" class="fw-semibold ms-1" style="color:#92400E;">Lihat monitoring →</a>
        </div>
    @endif

    <div class="nav-bulan">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('dashboard', ['bulan' => $bulanSebelum]) }}" class="btn-nav" title="Bulan sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </a>
            <div class="nav-bulan-label">
                {{ $bulanAktif->translatedFormat('F Y') }}
            </div>
            <a href="{{ $isBuilanIni ? '#' : route('dashboard', ['bulan' => $bulanSesudah]) }}"
                class="btn-nav {{ $isBuilanIni ? 'disabled' : '' }}"
                title="{{ $isBuilanIni ? 'Ini bulan terkini' : 'Bulan sesudahnya' }}">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if (!$isBuilanIni)
                <span class="nav-bulan-badge">
                    <i class="bi bi-clock-history me-1"></i>Melihat riwayat
                </span>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary"
                    style="font-size:12px;padding:4px 10px;">
                    Kembali ke bulan ini
                </a>
            @else
                <span style="font-size:12px;color:#64748B;">
                    <i class="bi bi-circle-fill me-1" style="font-size:8px;color:#059669;"></i>Bulan berjalan
                </span>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#EFF6FF;color:#2563EB;">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Jenis Beras</div>
                    <div class="stat-value">{{ $totalJenisBeras }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#F0FDF4;color:#059669;">
                    <i class="bi bi-arrow-down-circle-fill"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">
                        Masuk — {{ $bulanAktif->translatedFormat('M Y') }}
                    </div>
                    <div class="stat-value">
                        {{ number_format($totalMasukBulan, 0, ',', '.') }}
                        <small style="font-size:13px;color:#64748B;">kg</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#FFF7ED;color:#EA580C;">
                    <i class="bi bi-arrow-up-circle-fill"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">
                        Keluar — {{ $bulanAktif->translatedFormat('M Y') }}
                    </div>
                    <div class="stat-value">
                        {{ number_format($totalKeluarBulan, 0, ',', '.') }}
                        <small style="font-size:13px;color:#64748B;">kg</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#FEF2F2;color:#DC2626;">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Stok Menipis</div>
                    <div class="stat-value">{{ $berasMenipis->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-bar-chart-fill text-primary"></i>
                        Stok Masuk vs Keluar
                    </h5>
                    <span style="font-size:12px;color:#94A3B8;">
                        6 bulan s/d {{ $bulanAktif->translatedFormat('M Y') }}
                    </span>
                </div>
                <div class="card-body">
                    <canvas id="grafikStok" height="240"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-clock-history text-primary"></i>
                        Riwayat {{ $bulanAktif->translatedFormat('F Y') }}
                    </h5>
                    <div class="d-flex gap-1">
                        <a href="{{ route('stok-masuk.index') }}" class="btn btn-sm btn-outline-primary"
                            style="font-size:11px;padding:3px 8px;">
                            <i class="bi bi-arrow-down-circle me-1"></i>Masuk
                        </a>
                        <a href="{{ route('stok-keluar.index') }}" class="btn btn-sm btn-outline-danger"
                            style="font-size:11px;padding:3px 8px;">
                            <i class="bi bi-arrow-up-circle me-1"></i>Keluar
                        </a>
                    </div>
                </div>
                <div class="card-body p-0" style="overflow-y:auto;max-height:340px;">
                    @forelse($riwayat as $item)
                        <div class="riwayat-item">
                            <div class="riwayat-icon"
                                style="background:{{ $item['tipe'] === 'masuk' ? '#F0FDF4' : '#FEF2F2' }};
                                color:{{ $item['tipe'] === 'masuk' ? '#059669' : '#DC2626' }};">
                                <i class="bi bi-arrow-{{ $item['tipe'] === 'masuk' ? 'down' : 'up' }}-circle"></i>
                            </div>

                            <div style="flex:1;min-width:0;">
                                <div
                                    style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $item['label'] }}
                                </div>
                                <div
                                    style="font-size:11.5px;color:#94A3B8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $item['tanggal']->format('d M') }}
                                    &bull;
                                    {{ $item['sub'] }}
                                </div>
                            </div>

                            <div
                                style="font-size:13px;font-weight:600;white-space:nowrap;
                                color:{{ $item['tipe'] === 'masuk' ? '#059669' : '#DC2626' }};">
                                {{ $item['tipe'] === 'masuk' ? '+' : '-' }}{{ number_format($item['jumlah'], 0, ',', '.') }}
                                kg
                            </div>
                        </div>
                    @empty
                        <div class="riwayat-empty">
                            <i class="bi bi-calendar-x"
                                style="font-size:28px;display:block;margin-bottom:8px;opacity:.4;"></i>
                            Tidak ada transaksi di<br>{{ $bulanAktif->translatedFormat('F Y') }}
                        </div>
                    @endforelse
                </div>

                @if ($riwayat->count() > 0)
                    <div class="card-body border-top py-2">
                        <div class="d-flex justify-content-between" style="font-size:12.5px;">
                            <span style="color:#059669;font-weight:500;">
                                <i class="bi bi-arrow-down-circle me-1"></i>
                                Masuk: {{ number_format($totalMasukBulan, 0, ',', '.') }} kg
                            </span>
                            <span style="color:#DC2626;font-weight:500;">
                                <i class="bi bi-arrow-up-circle me-1"></i>
                                Keluar: {{ number_format($totalKeluarBulan, 0, ',', '.') }} kg
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('grafikStok').getContext('2d');

        const bulanLabels = {!! json_encode($grafik['bulan']) !!};
        const dataMasuk = {!! json_encode($grafik['masuk']) !!};
        const dataKeluar = {!! json_encode($grafik['keluar']) !!};
        const bulanAktifIdx = bulanLabels.length - 1;
        const warnaMasuk = bulanLabels.map((_, i) =>
            i === bulanAktifIdx ? 'rgba(37,99,235,1)' : 'rgba(37,99,235,.4)');
        const warnaKeluar = bulanLabels.map((_, i) =>
            i === bulanAktifIdx ? 'rgba(234,88,12,1)' : 'rgba(234,88,12,.4)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: bulanLabels,
                datasets: [{
                        label: 'Masuk (kg)',
                        data: dataMasuk,
                        backgroundColor: warnaMasuk,
                        borderRadius: 6,
                        borderSkipped: false,
                    },
                    {
                        label: 'Keluar (kg)',
                        data: dataKeluar,
                        backgroundColor: warnaKeluar,
                        borderRadius: 6,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'DM Sans',
                                size: 12
                            },
                            boxWidth: 12
                        }
                    },
                    tooltip: {
                        bodyFont: {
                            family: 'DM Sans'
                        },
                        titleFont: {
                            family: 'DM Sans'
                        },
                        callbacks: {
                            title: (items) => {
                                const label = items[0].label;
                                return items[0].dataIndex === bulanAktifIdx ?
                                    label + ' ★ (bulan dipilih)' :
                                    label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'DM Sans',
                                size: 12
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: '#F1F5F9'
                        },
                        ticks: {
                            font: {
                                family: 'DM Sans',
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
