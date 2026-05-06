@extends('layouts.app')

@section('title', 'Edit Stok Masuk')
@section('page-title', 'Edit Stok Masuk')
@section('page-subtitle', $stokMasuk->no_transaksi)

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if ($sudahTerpakai > 0)
                <div class="alert d-flex gap-2 mb-4 py-2"
                    style="background:#FEF9C3;border:1px solid #FCD34D;border-radius:8px;font-size:13px;color:#92400E;">
                    <i class="bi bi-exclamation-triangle-fill mt-1 shrink-0"></i>
                    <div>
                        <strong>Perhatian!</strong> Sebanyak
                        <strong>{{ number_format($sudahTerpakai, 2, ',', '.') }}
                            {{ $stokMasuk->jenisBeras->satuan }}</strong>
                        dari batch ini sudah dikeluarkan. Jumlah yang bisa diubah
                        <strong>minimal {{ number_format($sudahTerpakai, 2, ',', '.') }}
                            {{ $stokMasuk->jenisBeras->satuan }}</strong>
                        (tidak boleh kurang dari yang sudah terpakai).
                    </div>
                </div>
            @else
                <div class="alert d-flex gap-2 mb-4 py-2"
                    style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;font-size:13px;color:#166534;">
                    <i class="bi bi-check-circle-fill mt-1 shrink-0"></i>
                    <div>
                        Batch ini belum ada yang dikeluarkan — semua field bebas diubah termasuk jumlahnya.
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-pencil-square text-primary"></i> Form Edit Stok Masuk</h5>
                    <a href="{{ route('stok-masuk.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('stok-masuk.update', $stokMasuk) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Jenis Beras <span class="text-danger">*</span></label>
                                <select name="jenis_beras_id"
                                    class="form-select @error('jenis_beras_id') is-invalid @enderror"
                                    {{ $sudahTerpakai > 0 ? 'disabled' : '' }} required>
                                    <option value="">— Pilih jenis beras —</option>
                                    @foreach ($jenisBeras as $jb)
                                        <option value="{{ $jb->id }}"
                                            {{ old('jenis_beras_id', $stokMasuk->jenis_beras_id) == $jb->id ? 'selected' : '' }}>
                                            {{ $jb->nama_beras }} ({{ $jb->kode_beras }})
                                        </option>
                                    @endforeach
                                </select>
                                @if ($sudahTerpakai > 0)
                                    <input type="hidden" name="jenis_beras_id" value="{{ $stokMasuk->jenis_beras_id }}">
                                    <div class="form-text text-warning">
                                        <i class="bi bi-lock-fill"></i> Tidak bisa diubah karena stok sudah sebagian
                                        dikeluarkan.
                                    </div>
                                @endif
                                @error('jenis_beras_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Supplier <span class="text-danger">*</span></label>
                                <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror"
                                    required>
                                    <option value="">— Pilih supplier —</option>
                                    @foreach ($suppliers as $s)
                                        <option value="{{ $s->id }}"
                                            {{ old('supplier_id', $stokMasuk->supplier_id) == $s->id ? 'selected' : '' }}>
                                            {{ $s->nama_supplier }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="jumlah" id="jumlah"
                                        class="form-control @error('jumlah') is-invalid @enderror" step="0.01"
                                        min="{{ max(0.01, $sudahTerpakai) }}"
                                        value="{{ old('jumlah', $stokMasuk->jumlah) }}" required>
                                    <span class="input-group-text"
                                        style="background:#F8FAFC;border-color:#E2E8F0;font-size:13px;">
                                        {{ $stokMasuk->jenisBeras->satuan }}
                                    </span>
                                </div>
                                @if ($sudahTerpakai > 0)
                                    <div class="form-text text-warning">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        Minimal: {{ number_format($sudahTerpakai, 2, ',', '.') }}
                                        {{ $stokMasuk->jenisBeras->satuan }}
                                    </div>
                                @endif
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Harga Beli/Kg <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                        style="background:#F8FAFC;border-color:#E2E8F0;font-size:13px;">Rp</span>
                                    <input type="number" name="harga_beli"
                                        class="form-control @error('harga_beli') is-invalid @enderror" step="100"
                                        min="0" value="{{ old('harga_beli', $stokMasuk->harga_beli) }}" required>
                                </div>
                                @error('harga_beli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_masuk"
                                    class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}"
                                    value="{{ old('tanggal_masuk', $stokMasuk->tanggal_masuk->format('Y-m-d')) }}"
                                    required>
                                @error('tanggal_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">No. Surat Jalan</label>
                                <input type="text" name="no_surat_jalan"
                                    class="form-control @error('no_surat_jalan') is-invalid @enderror"
                                    placeholder="Opsional" value="{{ old('no_surat_jalan', $stokMasuk->no_surat_jalan) }}">
                                @error('no_surat_jalan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kadaluarsa</label>
                                <input type="date" name="tanggal_kadaluarsa"
                                    class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror"
                                    value="{{ old('tanggal_kadaluarsa', $stokMasuk->tanggal_kadaluarsa?->format('Y-m-d')) }}">
                                <div class="form-text">Opsional. Untuk kontrol kualitas batch.</div>
                                @error('tanggal_kadaluarsa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $stokMasuk->keterangan) }}</textarea>
                            </div>

                        </div>

                        <div class="alert alert-info d-flex gap-2 mt-4 py-2" style="font-size:13px;border-radius:8px;">
                            <i class="bi bi-info-circle-fill mt-1 shrink-0"></i>
                            <div>
                                Perubahan jumlah akan otomatis menyinkronkan antrian FIFO batch ini.
                                <strong>Jumlah tersisa</strong> di antrian akan disesuaikan
                                dengan rumus: <em>jumlah baru − sudah terpakai</em>.
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('stok-masuk.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>

                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="bi bi-layers text-warning"></i> Status Batch FIFO Saat Ini</h5>
                </div>
                <div class="card-body">
                    @if ($stokMasuk->fifoQueue)
                        @php
                            $fifo = $stokMasuk->fifoQueue;
                            $persen = $fifo->jumlah_awal > 0 ? ($fifo->jumlah_tersisa / $fifo->jumlah_awal) * 100 : 0;
                        @endphp
                        <div class="row g-3">
                            <div class="col-4 text-center">
                                <div style="font-size:11px;color:#64748B;margin-bottom:4px;">Jumlah Awal</div>
                                <div style="font-size:18px;font-weight:600;color:#1E293B;">
                                    {{ number_format($fifo->jumlah_awal, 2, ',', '.') }}
                                    <span
                                        style="font-size:12px;font-weight:400;color:#94A3B8;">{{ $stokMasuk->jenisBeras->satuan }}</span>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div style="font-size:11px;color:#64748B;margin-bottom:4px;">Sudah Terpakai</div>
                                <div style="font-size:18px;font-weight:600;color:#DC2626;">
                                    {{ number_format($sudahTerpakai, 2, ',', '.') }}
                                    <span
                                        style="font-size:12px;font-weight:400;color:#94A3B8;">{{ $stokMasuk->jenisBeras->satuan }}</span>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div style="font-size:11px;color:#64748B;margin-bottom:4px;">Sisa Tersedia</div>
                                <div style="font-size:18px;font-weight:600;color:#059669;">
                                    {{ number_format($fifo->jumlah_tersisa, 2, ',', '.') }}
                                    <span
                                        style="font-size:12px;font-weight:400;color:#94A3B8;">{{ $stokMasuk->jenisBeras->satuan }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="stok-bar mt-3">
                            <div class="stok-bar-fill"
                                style="width:{{ $persen }}%;background:{{ $persen > 50 ? '#059669' : ($persen > 20 ? '#D97706' : '#DC2626') }};">
                            </div>
                        </div>
                        <div class="text-end mt-1" style="font-size:12px;color:#94A3B8;">
                            Sisa {{ number_format($persen, 1) }}% dari jumlah awal
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

@endsection
