@extends('layouts.app')
@section('title', 'Jenis Beras')
@section('page-title', 'Jenis Beras')
@section('page-subtitle', 'Master data produk beras')
@section('topbar-actions')
    <a href="{{ route('jenis-beras.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Jenis Beras
    </a>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-tags text-primary"></i> Daftar Jenis Beras</h5>
            <span class="badge bg-light text-secondary fw-normal" style="font-size:12px;">{{ $data->total() }} jenis</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Beras</th>
                            <th>Satuan</th>
                            <th>Stok Saat Ini</th>
                            <th>Minimum</th>
                            <th>Harga/Kg</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                            <tr>
                                <td><span class="mono">{{ $item->kode_beras }}</span></td>
                                <td style="font-weight:500;">{{ $item->nama_beras }}</td>
                                <td>{{ $item->satuan }}</td>
                                <td>
                                    @php $stok = $item->stok_saat_ini; @endphp
                                    <span
                                        style="font-weight:600;color:{{ $item->status_stok === 'aman' ? '#059669' : ($item->status_stok === 'menipis' ? '#D97706' : '#DC2626') }};">
                                        {{ number_format($stok, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td style="color:#64748B;">{{ number_format($item->stok_minimum, 2, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->harga_per_satuan, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}"
                                        style="font-size:11px;">
                                        {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('jenis-beras.edit', $item) }}"
                                            class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('jenis-beras.destroy', $item) }}" method="POST"
                                            onsubmit="return confirm('Hapus jenis beras ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4" style="color:#94A3B8;">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($data->hasPages())
            <div class="card-body border-top pt-3">{{ $data->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
