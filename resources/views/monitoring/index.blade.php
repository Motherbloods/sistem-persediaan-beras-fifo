@extends('layouts.app')

@section('title', 'Monitoring Stok')
@section('page-title', 'Monitoring Stok')
@section('page-subtitle', 'Kondisi persediaan beras secara langsung')

@section('topbar-actions')
    <span style="font-size:12px;color:#64748B;">
        <i class="bi bi-clock me-1"></i>Diperbarui: {{ now()->format('H:i:s') }}
    </span>
@endsection

@section('content')

    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:#F0FDF4;color:#059669;"><i class="bi bi-check-circle-fill"></i></div>
                <div class="stat-body">
                    <div class="stat-label">Stok Aman</div>
                    <div class="stat-value">{{ $berasAman->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:#FFFBEB;color:#D97706;"><i
                        class="bi bi-exclamation-triangle-fill"></i></div>
                <div class="stat-body">
                    <div class="stat-label">Menipis</div>
                    <div class="stat-value">{{ $berasMenipis->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:#FEF2F2;color:#DC2626;"><i class="bi bi-x-circle-fill"></i></div>
                <div class="stat-body">
                    <div class="stat-label">Habis</div>
                    <div class="stat-value">{{ $berasHabis->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    @if ($berasMenipis->count() > 0)
        <div class="alert d-flex gap-2 mb-4"
            style="background:#FEF9C3;border:1px solid #FCD34D;border-radius:10px;color:#92400E;font-size:13.5px;">
            <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
            <div>
                <strong>Perhatian!</strong> {{ $berasMenipis->count() }} jenis beras stoknya di bawah batas minimum:
                <strong>{{ $berasMenipis->pluck('nama_beras')->join(', ') }}</strong>.
                Segera lakukan pemesanan ke supplier.
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-table text-primary"></i> Status Stok Semua Jenis Beras</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Beras</th>
                            <th>Stok Tersedia</th>
                            <th>Minimum</th>
                            <th style="min-width:160px;">Persentase</th>
                            <th>Status</th>
                            <th>Antrian FIFO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $beras)
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
                            @endphp
                            <tr>
                                <td><span class="mono">{{ $beras->kode_beras }}</span></td>
                                <td style="font-weight:500;">{{ $beras->nama_beras }}</td>
                                <td>
                                    <span style="font-size:15px;font-weight:600;color:{{ $color }};">
                                        {{ number_format($stok, 2, ',', '.') }}
                                    </span>
                                    <span style="color:#94A3B8;font-size:12px;"> {{ $beras->satuan }}</span>
                                </td>
                                <td style="color:#64748B;font-size:13px;">{{ number_format($min, 2, ',', '.') }}
                                    {{ $beras->satuan }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="stok-bar" style="flex:1;">
                                            <div class="stok-bar-fill"
                                                style="width:{{ $persen }}%;background:{{ $color }};"></div>
                                        </div>
                                        <span
                                            style="font-size:12px;color:#64748B;min-width:36px;">{{ number_format($persen, 0) }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($beras->status_stok === 'aman')
                                        <span class="badge badge-aman px-3 py-1" style="border-radius:6px;">Aman</span>
                                    @elseif($beras->status_stok === 'menipis')
                                        <span class="badge badge-menipis px-3 py-1"
                                            style="border-radius:6px;">Menipis</span>
                                    @else
                                        <span class="badge badge-habis px-3 py-1" style="border-radius:6px;">Habis</span>
                                    @endif
                                </td>
                                <td>
                                    @php $jmlBatch = $beras->antrian_fifo->count(); @endphp
                                    @if ($jmlBatch > 0)
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="toggleAntrian({{ $beras->id }})" style="font-size:12px;">
                                            <i class="bi bi-layers me-1"></i>{{ $jmlBatch }} batch
                                        </button>
                                    @else
                                        <span style="font-size:12px;color:#94A3B8;">-</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Baris detail antrian FIFO (collapsible) --}}
                            @if ($beras->antrian_fifo->count() > 0)
                                <tr id="antrian-{{ $beras->id }}" style="display:none;background:#F8FAFC;">
                                    <td colspan="7" style="padding:0 16px 16px;">
                                        <div
                                            style="font-size:12.5px;color:#64748B;margin-bottom:8px;padding-top:12px;font-weight:500;">
                                            <i class="bi bi-layers me-1"></i>Antrian FIFO — {{ $beras->nama_beras }}
                                            (batch tertua diambil pertama)
                                        </div>
                                        <table class="table table-sm mb-0"
                                            style="background:#fff;border-radius:8px;overflow:hidden;border:1px solid #E2E8F0;">
                                            <thead style="background:#F1F5F9;">
                                                <tr>
                                                    <th style="font-size:11px;">Urutan</th>
                                                    <th style="font-size:11px;">Tgl Masuk</th>
                                                    <th style="font-size:11px;">Supplier</th>
                                                    <th style="font-size:11px;">Stok Awal</th>
                                                    <th style="font-size:11px;">Sisa</th>
                                                    <th style="font-size:11px;">Terpakai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($beras->antrian_fifo as $i => $q)
                                                    <tr>
                                                        <td>
                                                            @if ($i === 0)
                                                                <span
                                                                    style="background:#DBEAFE;color:#1D4ED8;font-size:10.5px;padding:2px 8px;border-radius:4px;font-weight:600;">
                                                                    Berikutnya
                                                                </span>
                                                            @else
                                                                <span
                                                                    style="font-size:12px;color:#94A3B8;">#{{ $i + 1 }}</span>
                                                            @endif
                                                        </td>
                                                        <td style="font-size:12.5px;">
                                                            {{ $q->tanggal_masuk->format('d M Y') }}</td>
                                                        <td style="font-size:12.5px;">
                                                            {{ $q->stokMasuk->supplier->nama_supplier ?? '-' }}</td>
                                                        <td style="font-size:12.5px;">
                                                            {{ number_format($q->jumlah_awal, 2, ',', '.') }}</td>
                                                        <td style="font-size:12.5px;font-weight:600;color:#059669;">
                                                            {{ number_format($q->jumlah_tersisa, 2, ',', '.') }}</td>
                                                        <td style="font-size:12.5px;color:#DC2626;">
                                                            {{ number_format($q->jumlah_awal - $q->jumlah_tersisa, 2, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4" style="color:#94A3B8;">Belum ada data jenis
                                    beras</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function toggleAntrian(id) {
            const row = document.getElementById('antrian-' + id);
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
@endpush
