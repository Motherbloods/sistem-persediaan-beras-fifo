@extends('layouts.app')

@section('title', 'Laporan Stok Masuk')
@section('page-title', 'Laporan Stok Masuk')
@section('page-subtitle', 'Rekap seluruh penerimaan beras ke gudang')

@section('topbar-actions')
    <a href="{{ route('laporan.masuk.export', request()->query()) }}" target="_blank" class="btn btn-sm btn-outline-danger">
        <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
    </a>
@endsection

@section('content')

    <div class="card mb-4">
        <div class="card-body">
            @include('partials._filter', [
                'route' => 'laporan.masuk',
                'jenisBeras' => $jenisBeras,
            ])
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:#F0FDF4;color:#059669;">
                    <i class="bi bi-arrow-down-circle-fill"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Total Masuk</div>
                    <div class="stat-value">
                        {{ number_format($totalJumlah, 2, ',', '.') }}
                        <small style="font-size:13px;color:#64748B;">kg</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:#EFF6FF;color:#2563EB;">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Jumlah Transaksi</div>
                    <div class="stat-value">{{ $data->total() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:#FFF7ED;color:#EA580C;">
                    <i class="bi bi-calendar-range"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Periode</div>
                    <div class="stat-value" style="font-size:14px;font-weight:500;">
                        {{ request('dari') && request('sampai') ? request('dari') . ' — ' . request('sampai') : 'Semua' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-file-earmark-bar-graph text-primary"></i> Detail Laporan Stok Masuk</h5>
            <span class="badge bg-light text-secondary fw-normal" style="font-size:12px;">{{ $data->total() }}
                transaksi</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Transaksi</th>
                            <th>Jenis Beras</th>
                            <th>Supplier</th>
                            <th>Jumlah</th>
                            <th>Harga Beli/Kg</th>
                            <th>Total Nilai</th>
                            <th>Tgl Masuk</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $i => $item)
                            <tr>
                                <td style="color:#94A3B8;font-size:12.5px;">{{ $data->firstItem() + $i }}</td>
                                <td><span class="mono">{{ $item->no_transaksi }}</span></td>
                                <td>
                                    <div style="font-weight:500;">{{ $item->jenisBeras->nama_beras }}</div>
                                    <div style="font-size:11.5px;color:#94A3B8;">{{ $item->jenisBeras->kode_beras }}</div>
                                </td>
                                <td style="font-size:13px;">{{ $item->supplier->nama_supplier }}</td>
                                <td>
                                    <span style="font-weight:600;color:#059669;">
                                        {{ number_format($item->jumlah, 2, ',', '.') }}
                                    </span>
                                    <span style="color:#94A3B8;font-size:12px;"> {{ $item->jenisBeras->satuan }}</span>
                                </td>
                                <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td style="font-weight:500;">Rp
                                    {{ number_format($item->jumlah * $item->harga_beli, 0, ',', '.') }}</td>
                                <td>{{ $item->tanggal_masuk->format('d M Y') }}</td>
                                <td style="font-size:12.5px;color:#64748B;">{{ $item->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5" style="color:#94A3B8;">
                                    <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                                    Tidak ada data untuk filter ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($data->count() > 0)
                        <tfoot>
                            <tr style="background:#F8FAFC;font-weight:600;">
                                <td colspan="4" style="text-align:right;font-size:13px;color:#64748B;padding:12px 14px;">
                                    Total Halaman Ini:</td>
                                <td style="color:#059669;">
                                    {{ number_format($data->sum('jumlah'), 2, ',', '.') }} kg
                                </td>
                                <td></td>
                                <td style="color:#1E293B;">
                                    Rp {{ number_format($data->sum(fn($i) => $i->jumlah * $i->harga_beli), 0, ',', '.') }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @if ($data->hasPages())
            <div class="card-body border-top pt-3">{{ $data->links() }}</div>
        @endif
    </div>

@endsection
