@extends('layouts.app')
@section('title', 'Supplier') @section('page-title', 'Supplier') @section('page-subtitle', 'Master data pemasok beras')
@section('topbar-actions')
    <a href="{{ route('supplier.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah
        Supplier</a>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-truck text-primary"></i> Daftar Supplier</h5>
            <span class="badge bg-light text-secondary fw-normal" style="font-size:12px;">{{ $data->total() }} supplier</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Supplier</th>
                            <th>PIC</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                            <tr>
                                <td><span class="mono">{{ $item->kode_supplier }}</span></td>
                                <td style="font-weight:500;">{{ $item->nama_supplier }}</td>
                                <td>{{ $item->pic ?? '-' }}</td>
                                <td>{{ $item->telepon ?? '-' }}</td>
                                <td>{{ $item->email ?? '-' }}</td>
                                <td><span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}"
                                        style="font-size:11px;">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('supplier.edit', $item) }}"
                                            class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('supplier.destroy', $item) }}" method="POST"
                                            onsubmit="return confirm('Hapus supplier ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4" style="color:#94A3B8;">Belum ada data supplier
                                </td>
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
