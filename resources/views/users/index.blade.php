@extends('layouts.app')
@section('title', 'Pengguna') @section('page-title', 'Pengguna') @section('page-subtitle',
'Kelola akun pengguna
sistem')
@section('topbar-actions')
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Pengguna</a>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-people text-primary"></i> Daftar Pengguna</h5>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div
                                        style="width:32px;height:32px;background:{{ $item->isAdmin() ? '#EFF6FF' : '#F0FDF4' }};border-radius:50%;display:grid;place-items:center;font-size:12px;font-weight:600;color:{{ $item->isAdmin() ? '#2563EB' : '#059669' }};flex-shrink:0;">
                                        {{ strtoupper(substr($item->name, 0, 2)) }}
                                    </div>
                                    <span style="font-weight:500;">{{ $item->name }}</span>
                                    @if ($item->id === auth()->id())
                                        <span
                                            style="font-size:11px;background:#F1F5F9;color:#64748B;padding:2px 6px;border-radius:4px;">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td style="font-size:13px;color:#64748B;">{{ $item->email }}</td>
                            <td>
                                <span class="badge {{ $item->isAdmin() ? 'bg-primary' : 'bg-success' }}"
                                    style="font-size:11px;text-transform:capitalize;">
                                    {{ $item->role }}
                                </span>
                            </td>
                            <td style="font-size:12.5px;color:#94A3B8;">{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('users.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i
                                            class="bi bi-pencil"></i></a>
                                    @if ($item->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $item) }}" method="POST"
                                            onsubmit="return confirm('Hapus pengguna ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color:#94A3B8;">Belum ada pengguna</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($data->hasPages())
            <div class="card-body border-top pt-3">{{ $data->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
