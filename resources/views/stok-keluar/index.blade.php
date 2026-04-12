@extends('layouts.app')

@section('title', 'Stok Keluar')
@section('page-title', 'Stok Keluar')
@section('page-subtitle', 'Daftar distribusi beras dari gudang')

@section('topbar-actions')
    <a href="{{ route('stok-keluar.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Catat Stok Keluar
    </a>
@endsection

@section('content')

    <div class="card mb-4">
        <div class="card-body">
            @include('partials._filter', ['route' => 'stok-keluar.index', 'jenisBeras' => $jenisBeras])
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-arrow-up-circle text-danger"></i> Data Stok Keluar</h5>
            <span class="badge bg-light text-secondary fw-normal" style="font-size:12px;">{{ $data->total() }}
                transaksi</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Jenis Beras</th>
                            <th>Jumlah</th>
                            <th>Tujuan Distribusi</th>
                            <th>Tgl Keluar</th>
                            <th>Dicatat Oleh</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                            <tr>
                                <td><span class="mono">{{ $item->no_transaksi }}</span></td>
                                <td>
                                    <div style="font-weight:500;">{{ $item->jenisBeras->nama_beras }}</div>
                                    <div style="font-size:11.5px;color:#94A3B8;">{{ $item->jenisBeras->kode_beras }}</div>
                                </td>
                                <td>
                                    <span style="font-weight:600;color:#DC2626;">
                                        {{ number_format($item->jumlah, 2, ',', '.') }}
                                    </span>
                                    <span style="color:#94A3B8;font-size:12px;"> {{ $item->jenisBeras->satuan }}</span>
                                </td>
                                <td>{{ $item->tujuan_distribusi ?? '-' }}</td>
                                <td>{{ $item->tanggal_keluar->format('d M Y') }}</td>
                                <td style="font-size:12.5px;color:#64748B;">{{ $item->user->name }}</td>
                                <td>
                                    <a href="{{ route('stok-keluar.show', $item) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5" style="color:#94A3B8;">
                                    <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                                    Belum ada data stok keluar
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($data->hasPages())
            <div class="card-body border-top pt-3">{{ $data->links() }}</div>
        @endif
    </div>

@endsection
