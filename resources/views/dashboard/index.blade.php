@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan persediaan beras hari ini')

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

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#EFF6FF; color:#2563EB;">
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
                <div class="stat-icon" style="background:#F0FDF4; color:#059669;">
                    <i class="bi bi-arrow-down-circle-fill"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Masuk Bulan Ini</div>
                    <div class="stat-value">{{ number_format($totalMasukBulanIni, 0, ',', '.') }} <small
                            style="font-size:13px;color:#64748B;">kg</small></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#FFF7ED; color:#EA580C;">
                    <i class="bi bi-arrow-up-circle-fill"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Keluar Bulan Ini</div>
                    <div class="stat-value">{{ number_format($totalKeluarBulanIni, 0, ',', '.') }} <small
                            style="font-size:13px;color:#64748B;">kg</small></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#FEF2F2; color:#DC2626;">
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
                    <h5><i class="bi bi-bar-chart-fill text-primary"></i> Stok Masuk vs Keluar (6 Bulan)</h5>
                </div>
                <div class="card-body">
                    <canvas id="grafikStok" height="240"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h5><i class="bi bi-clock-history text-primary"></i> Transaksi Terbaru</h5>
                    <a href="{{ route('stok-masuk.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @forelse($transaksiTerbaru as $t)
                        <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom: 1px solid #F1F5F9;">
                            <div
                                style="width:36px;height:36px;background:#F0FDF4;border-radius:8px;display:grid;place-items:center;color:#059669;font-size:16px;flex-shrink:0;">
                                <i class="bi bi-arrow-down-circle"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div
                                    style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $t->jenisBeras->nama_beras }}
                                </div>
                                <div style="font-size:12px;color:#64748B;">{{ $t->tanggal_masuk->format('d M Y') }} &bull;
                                    {{ $t->supplier->nama_supplier }}</div>
                            </div>
                            <div style="font-size:13px;font-weight:600;color:#059669;white-space:nowrap;">
                                +{{ number_format($t->jumlah, 0, ',', '.') }} kg
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4" style="color:#94A3B8;font-size:13px;">
                            <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                            Belum ada transaksi
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('grafikStok').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($grafik['bulan']) !!},
                datasets: [{
                        label: 'Masuk (kg)',
                        data: {!! json_encode($grafik['masuk']) !!},
                        backgroundColor: 'rgba(37,99,235,.8)',
                        borderRadius: 6,
                        borderSkipped: false,
                    },
                    {
                        label: 'Keluar (kg)',
                        data: {!! json_encode($grafik['keluar']) !!},
                        backgroundColor: 'rgba(234,88,12,.75)',
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
