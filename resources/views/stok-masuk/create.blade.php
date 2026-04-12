@extends('layouts.app')

@section('title', 'Catat Stok Masuk')
@section('page-title', 'Catat Stok Masuk')
@section('page-subtitle', 'Input penerimaan beras baru ke gudang')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-arrow-down-circle text-primary"></i> Form Stok Masuk</h5>
                    <a href="{{ route('stok-masuk.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('stok-masuk.store') }}">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Jenis Beras <span class="text-danger">*</span></label>
                                <select name="jenis_beras_id"
                                    class="form-select @error('jenis_beras_id') is-invalid @enderror" required>
                                    <option value="">— Pilih jenis beras —</option>
                                    @foreach ($jenisBeras as $jb)
                                        <option value="{{ $jb->id }}"
                                            {{ old('jenis_beras_id') == $jb->id ? 'selected' : '' }}>
                                            {{ $jb->nama_beras }} ({{ $jb->kode_beras }})
                                        </option>
                                    @endforeach
                                </select>
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
                                            {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
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
                                    <input type="number" name="jumlah"
                                        class="form-control @error('jumlah') is-invalid @enderror" placeholder="0"
                                        step="0.01" min="0.01" value="{{ old('jumlah') }}" required>
                                    <span class="input-group-text"
                                        style="background:#F8FAFC;border-color:#E2E8F0;font-size:13px;">kg</span>
                                </div>
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
                                        class="form-control @error('harga_beli') is-invalid @enderror" placeholder="0"
                                        step="100" min="0" value="{{ old('harga_beli') }}" required>
                                </div>
                                @error('harga_beli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_masuk"
                                    class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                                @error('tanggal_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">No. Surat Jalan</label>
                                <input type="text" name="no_surat_jalan"
                                    class="form-control @error('no_surat_jalan') is-invalid @enderror"
                                    placeholder="Opsional" value="{{ old('no_surat_jalan') }}">
                                @error('no_surat_jalan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kadaluarsa</label>
                                <input type="date" name="tanggal_kadaluarsa"
                                    class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror"
                                    value="{{ old('tanggal_kadaluarsa') }}">
                                <div class="form-text">Opsional. Untuk kontrol kualitas batch.</div>
                                @error('tanggal_kadaluarsa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3"
                                    placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="alert alert-info d-flex gap-2 mt-4 py-2" style="font-size:13px;border-radius:8px;">
                            <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
                            <span>
                                Setelah disimpan, stok ini akan otomatis masuk ke antrian <strong>FIFO</strong>.
                                Distribusi berikutnya akan mengambil dari batch yang paling lama masuk terlebih dahulu.
                            </span>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Simpan Stok Masuk
                            </button>
                            <a href="{{ route('stok-masuk.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
