@extends('layouts.app')

@section('title', 'Detail Stok Keluar')
@section('page-title', 'Detail Stok Keluar')
@section('page-subtitle', $stokKeluar->no_transaksi)

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-file-earmark-text text-danger"></i> Informasi Distribusi</h5>
                    <a href="{{ route('stok-keluar.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-borderless" style="font-size:13.5px;">
                        <tr>
                            <td style="width:180px;color:#64748B;font-weight:500;">No. Transaksi</td>
                            <td><span class="mono">{{ $stokKeluar->no_transaksi }}</span></td>
                        </tr>
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Jenis Beras</td>
                            <td>{{ $stokKeluar->jenisBeras->nama_beras }}
                                <span
                                    style="color:#94A3B8;font-size:12px;">({{ $stokKeluar->jenisBeras->kode_beras }})</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Jumlah Keluar</td>
                            <td style="font-weight:600;color:#DC2626;font-size:15px;">
                                {{ number_format($stokKeluar->jumlah, 2, ',', '.') }} {{ $stokKeluar->jenisBeras->satuan }}
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Tanggal Keluar</td>
                            <td>{{ $stokKeluar->tanggal_keluar->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Tujuan Distribusi</td>
                            <td>{{ $stokKeluar->tujuan_distribusi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Dicatat Oleh</td>
                            <td>{{ $stokKeluar->user->name }}</td>
                        </tr>
                        @if ($stokKeluar->keterangan)
                            <tr>
                                <td style="color:#64748B;font-weight:500;">Keterangan</td>
                                <td>{{ $stokKeluar->keterangan }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Dicatat Pada</td>
                            <td style="color:#94A3B8;font-size:12.5px;">{{ $stokKeluar->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    </table>

                    <div class="alert mt-3 py-2"
                        style="font-size:12.5px;background:#FFF7ED;border:1px solid #FDE68A;border-radius:8px;color:#92400E;">
                        <i class="bi bi-lightning-charge-fill me-1"></i>
                        Pengurangan stok dilakukan otomatis menggunakan metode <strong>FIFO</strong>.
                        Batch yang paling lama masuk diambil terlebih dahulu.
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
