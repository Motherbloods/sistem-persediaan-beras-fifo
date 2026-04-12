@extends('layouts.app')

@section('title', 'Laporan Persediaan')
@section('page-title', 'Laporan Persediaan')
@section('page-subtitle', 'Kondisi stok semua jenis beras per ' . now()->translatedFormat('d F Y'))

@section('topbar-actions')
    <a href="{{ route('laporan.persediaan.export') }}" target="_blank" class="btn btn-sm btn-outline-danger">
        <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
    </a>
@endsection

@section('content')

    @php
        $totalAman = $data->filter(fn($b) => $b->status_stok === 'aman')->count();
        $totalMenipis = $data->filter(fn($b) => $b->status_stok === 'menipis')->count();
        $totalHabis = $data->filter(fn($b) => $b->status_stok === 'habis')->count();
        $totalStokKg = $data->sum('stok_tersedia');
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#EFF6FF;color:#2563EB;"><i class="bi bi-tags-fill"></i></div>
                <div class="stat-body">
                    <div class="stat-label">Total Jenis</div>
                    <div class="stat-value">{{ $data->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#F0FDF4;color:#059669;"><i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Stok Aman</div>
                    <div class="stat-value">{{ $totalAman }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#FFFBEB;color:#D97706;"><i
                        class="bi bi-exclamation-triangle-fill"></i></div>
                <div class="stat-body">
                    <div class="stat-label">Menipis</div>
                    <div class="stat-value">{{ $totalMenipis }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#F8FAFC;color:#64748B;"><i class="bi bi-stack"></i></div>
                <div class="stat-body">
                    <div class="stat-label">Total Stok</div>
                    <div class="stat-value" style="font-size:17px;">
                        {{ number_format($totalStokKg, 0, ',', '.') }}
                        <small style="font-size:12px;color:#64748B;">kg</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-clipboard-data text-primary"></i> Rekap Persediaan Beras</h5>
            <span style="font-size:12px;color:#64748B;">
                <i class="bi bi-clock me-1"></i>Per {{ now()->format('d M Y H:i') }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode</th>
                            <th>Nama Beras</th>
                            <th>Satuan</th>
                            <th>Stok Tersedia</th>
                            <th>Stok Minimum</th>
                            <th style="min-width:140px;">Kondisi Stok</th>
                            <th>Status</th>
                            <th>Batch Aktif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $i => $beras)
                            @php
                                $stok = $beras->stok_tersedia;
                                $min = $beras->stok_minimum;
                                $persen = $min > 0 ? min(($stok / ($min * 2)) * 100, 100) : ($stok > 0 ? 100 : 0);
                                $color =
                                    $beras->status_stok === 'aman'
                                        ? '#059669'
                                        : ($beras->status_stok === 'menipis'
                                            ? '#D97706'
                                            : '#DC2626');
                                $jmlBatch = $beras->antrian_fifo->count();
                            @endphp
                            <tr>
                                <td style="color:#94A3B8;font-size:12.5px;">{{ $i + 1 }}</td>
                                <td><span class="mono">{{ $beras->kode_beras }}</span></td>
                                <td style="font-weight:500;">{{ $beras->nama_beras }}</td>
                                <td style="color:#64748B;">{{ $beras->satuan }}</td>
                                <td>
                                    <span style="font-size:15px;font-weight:600;color:{{ $color }};">
                                        {{ number_format($stok, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td style="color:#64748B;font-size:13px;">{{ number_format($min, 2, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="stok-bar" style="flex:1;">
                                            <div class="stok-bar-fill"
                                                style="width:{{ $persen }}%;background:{{ $color }};"></div>
                                        </div>
                                        <span
                                            style="font-size:11.5px;color:#64748B;min-width:32px;">{{ number_format($persen, 0) }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($beras->status_stok === 'aman')
                                        <span class="badge badge-aman px-2 py-1" style="border-radius:5px;">Aman</span>
                                    @elseif($beras->status_stok === 'menipis')
                                        <span class="badge badge-menipis px-2 py-1"
                                            style="border-radius:5px;">Menipis</span>
                                    @else
                                        <span class="badge badge-habis px-2 py-1" style="border-radius:5px;">Habis</span>
                                    @endif
                                </td>
                                <td style="font-size:13px;color:#64748B;">
                                    {{ $jmlBatch > 0 ? $jmlBatch . ' batch' : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#F8FAFC;font-weight:600;">
                            <td colspan="4" style="text-align:right;font-size:13px;color:#64748B;padding:12px 14px;">
                                Total Keseluruhan:</td>
                            <td style="color:#1E293B;font-size:15px;">
                                {{ number_format($totalStokKg, 2, ',', '.') }} kg
                            </td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@endsection
