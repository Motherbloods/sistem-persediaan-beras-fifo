@extends('layouts.app')

@section('title', 'Stok Masuk')
@section('page-title', 'Stok Masuk')
@section('page-subtitle', 'Daftar penerimaan beras ke gudang')

@section('topbar-actions')
    <a href="{{ route('stok-masuk.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Catat Stok Masuk
    </a>
@endsection

@section('content')

    <div class="card mb-4">
        <div class="card-body">
            @include('partials._filter', [
                'route' => 'stok-masuk.index',
                'jenisBeras' => $jenisBeras,
                'suppliers' => $suppliers,
                'showSupplier' => true,
            ])
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-arrow-down-circle text-primary"></i> Data Stok Masuk</h5>
            <span class="badge bg-light text-secondary fw-normal" style="font-size:12px;">
                {{ $data->total() }} transaksi
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Jenis Beras</th>
                            <th>Supplier</th>
                            <th>Jumlah</th>
                            <th>Harga Beli/Kg</th>
                            <th>Tgl Masuk</th>
                            <th>Dicatat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                            @php
                                $fifo = $item->fifoQueue;
                                $sudahTerpakai = $fifo ? (float) $fifo->jumlah_awal - (float) $fifo->jumlah_tersisa : 0;
                            @endphp
                            <tr>
                                <td><span class="mono">{{ $item->no_transaksi }}</span></td>
                                <td>
                                    <div style="font-weight:500;">{{ $item->jenisBeras->nama_beras }}</div>
                                    <div style="font-size:11.5px;color:#94A3B8;">{{ $item->jenisBeras->kode_beras }}</div>
                                </td>
                                <td>{{ $item->supplier->nama_supplier }}</td>
                                <td>
                                    <span style="font-weight:600;color:#059669;">
                                        {{ number_format($item->jumlah, 2, ',', '.') }}
                                    </span>
                                    <span style="color:#94A3B8;font-size:12px;"> {{ $item->jenisBeras->satuan }}</span>
                                </td>
                                <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td>{{ $item->tanggal_masuk->format('d M Y') }}</td>
                                <td style="font-size:12.5px;color:#64748B;">{{ $item->user->name }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('stok-masuk.show', $item) }}"
                                            class="btn btn-sm btn-outline-secondary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('stok-masuk.edit', $item) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        @if ($sudahTerpakai > 0)
                                            <button class="btn btn-sm btn-secondary" disabled
                                                title="{{ $item->stok_keluar_count . ' ' . $item->jenisBeras->satuan }} dari batch ini sudah dikeluarkan.">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('stok-masuk.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus transaksi {{ $item->no_transaksi }}? Antrian FIFO batch ini juga akan dihapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5" style="color:#94A3B8;">
                                    <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                                    Belum ada data stok masuk
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($data->hasPages())
            <div class="card-body border-top pt-3">
                {{ $data->links() }}
            </div>
        @endif
    </div>

@endsection
