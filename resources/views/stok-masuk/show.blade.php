@extends('layouts.app')

@section('title', 'Detail Stok Masuk')
@section('page-title', 'Detail Stok Masuk')
@section('page-subtitle', $stokMasuk->no_transaksi)

@section('content')

    <div class="row g-3">

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-file-earmark-text text-primary"></i> Informasi Transaksi</h5>
                    <a href="{{ route('stok-masuk.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-borderless" style="font-size:13.5px;">
                        <tr>
                            <td style="width:180px;color:#64748B;font-weight:500;">No. Transaksi</td>
                            <td><span class="mono">{{ $stokMasuk->no_transaksi }}</span></td>
                        </tr>
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Jenis Beras</td>
                            <td>
                                {{ $stokMasuk->jenisBeras->nama_beras }}
                                <span
                                    style="color:#94A3B8;font-size:12px;">({{ $stokMasuk->jenisBeras->kode_beras }})</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Supplier</td>
                            <td>{{ $stokMasuk->supplier->nama_supplier }}</td>
                        </tr>
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Jumlah Masuk</td>
                            <td style="font-weight:600;color:#059669;font-size:15px;">
                                {{ number_format($stokMasuk->jumlah, 2, ',', '.') }} {{ $stokMasuk->jenisBeras->satuan }}
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Harga Beli/Kg</td>
                            <td>Rp {{ number_format($stokMasuk->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Total Nilai</td>
                            <td>Rp {{ number_format($stokMasuk->jumlah * $stokMasuk->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Tanggal Masuk</td>
                            <td>{{ $stokMasuk->tanggal_masuk->format('d F Y') }}</td>
                        </tr>
                        @if ($stokMasuk->tanggal_kadaluarsa)
                            <tr>
                                <td style="color:#64748B;font-weight:500;">Tgl Kadaluarsa</td>
                                <td>{{ $stokMasuk->tanggal_kadaluarsa->format('d F Y') }}</td>
                            </tr>
                        @endif
                        @if ($stokMasuk->no_surat_jalan)
                            <tr>
                                <td style="color:#64748B;font-weight:500;">No. Surat Jalan</td>
                                <td>{{ $stokMasuk->no_surat_jalan }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Dicatat Oleh</td>
                            <td>{{ $stokMasuk->user->name }}</td>
                        </tr>
                        @if ($stokMasuk->keterangan)
                            <tr>
                                <td style="color:#64748B;font-weight:500;">Keterangan</td>
                                <td>{{ $stokMasuk->keterangan }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="color:#64748B;font-weight:500;">Dicatat Pada</td>
                            <td style="color:#94A3B8;font-size:12.5px;">{{ $stokMasuk->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-layers text-warning"></i> Status Antrian FIFO</h5>
                </div>
                <div class="card-body">
                    @if ($stokMasuk->fifoQueue)
                        @php
                            $fifo = $stokMasuk->fifoQueue;
                            $persen = $fifo->jumlah_awal > 0 ? ($fifo->jumlah_tersisa / $fifo->jumlah_awal) * 100 : 0;
                        @endphp

                        <div class="d-flex justify-content-between mb-2" style="font-size:13px;">
                            <span style="color:#64748B;">Sisa Stok Batch</span>
                            <span style="font-weight:600;">
                                {{ number_format($fifo->jumlah_tersisa, 2, ',', '.') }} /
                                {{ number_format($fifo->jumlah_awal, 2, ',', '.') }}
                                <span style="color:#94A3B8;font-weight:400;font-size:12px;">kg</span>
                            </span>
                        </div>

                        <div class="stok-bar mb-3">
                            <div class="stok-bar-fill"
                                style="width:{{ $persen }}%;background:{{ $persen > 50 ? '#059669' : ($persen > 20 ? '#D97706' : '#DC2626') }};">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size:12px;color:#64748B;">Status</span>
                            @if ($fifo->status === 'tersedia')
                                <span class="badge badge-aman px-3 py-1" style="border-radius:6px;">Tersedia</span>
                            @else
                                <span class="badge badge-habis px-3 py-1" style="border-radius:6px;">Habis Digunakan</span>
                            @endif
                        </div>

                        <hr style="border-color:#F1F5F9;margin:16px 0;">

                        <div style="font-size:12.5px;color:#64748B;">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Sudah terpakai</span>
                                <span style="color:#1E293B;font-weight:500;">
                                    {{ number_format($fifo->jumlah_awal - $fifo->jumlah_tersisa, 2, ',', '.') }} kg
                                    ({{ number_format(100 - $persen, 1) }}%)
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tgl masuk antrian</span>
                                <span
                                    style="color:#1E293B;font-weight:500;">{{ $fifo->tanggal_masuk->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="alert mt-3 py-2"
                            style="font-size:12px;background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;color:#166534;">
                            <i class="bi bi-info-circle me-1"></i>
                            Batch ini akan dikeluarkan <strong>lebih dulu</strong> dibanding batch yang masuk setelah
                            tanggal ini (FIFO).
                        </div>
                    @else
                        <div class="text-center py-3" style="color:#94A3B8;font-size:13px;">
                            <i class="bi bi-question-circle" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                            Data antrian FIFO tidak ditemukan
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection
