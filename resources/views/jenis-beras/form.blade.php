@extends('layouts.app')
@section('title', isset($jenisBeras) ? 'Edit Jenis Beras' : 'Tambah Jenis Beras')
@section('page-title', isset($jenisBeras) ? 'Edit Jenis Beras' : 'Tambah Jenis Beras')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-tags text-primary"></i> {{ isset($jenisBeras) ? 'Edit' : 'Tambah' }} Jenis Beras</h5>
                    <a href="{{ route('jenis-beras.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $isEdit = isset($jenisBeras);
                    @endphp

                    <form method="POST"
                        action="{{ $isEdit ? route('jenis-beras.update', $jenisBeras->id) : route('jenis-beras.store') }}">

                        @csrf

                        @if ($isEdit)
                            @method('PUT')
                        @endif
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Kode Beras <span class="text-danger">*</span></label>
                                <input type="text" name="kode_beras"
                                    class="form-control @error('kode_beras') is-invalid @enderror" placeholder="BR-001"
                                    value="{{ old('kode_beras', $jenisBeras->kode_beras ?? '') }}" required maxlength="20">
                                @error('kode_beras')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Nama Beras <span class="text-danger">*</span></label>
                                <input type="text" name="nama_beras"
                                    class="form-control @error('nama_beras') is-invalid @enderror"
                                    placeholder="Beras IR64 Premium"
                                    value="{{ old('nama_beras', $jenisBeras->nama_beras ?? '') }}" required>
                                @error('nama_beras')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                    @foreach (['kg', 'sak', 'karung', 'ton', 'gram'] as $s)
                                        <option value="{{ $s }}"
                                            {{ old('satuan', $jenisBeras->satuan ?? 'kg') === $s ? 'selected' : '' }}>
                                            {{ $s }}</option>
                                    @endforeach
                                </select>
                                @error('satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                                <input type="number" name="stok_minimum"
                                    class="form-control @error('stok_minimum') is-invalid @enderror" placeholder="500"
                                    step="0.01" min="0"
                                    value="{{ old('stok_minimum', $jenisBeras->stok_minimum ?? '') }}" required>
                                <div class="form-text">Batas notifikasi menipis</div>
                                @error('stok_minimum')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Harga Referensi/Satuan</label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                        style="background:#F8FAFC;border-color:#E2E8F0;font-size:13px;">Rp</span>
                                    <input type="number" name="harga_per_satuan"
                                        class="form-control @error('harga_per_satuan') is-invalid @enderror" placeholder="0"
                                        step="100" min="0"
                                        value="{{ old('harga_per_satuan', $jenisBeras->harga_per_satuan ?? 0) }}">
                                </div>
                                @error('harga_per_satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="2" placeholder="Keterangan produk beras (opsional)">{{ old('deskripsi', $jenisBeras->deskripsi ?? '') }}</textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                        value="1"
                                        {{ old('is_active', $jenisBeras->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active" style="font-size:13.5px;">Jenis beras
                                        aktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>{{ isset($jenisBeras) ? 'Perbarui' : 'Simpan' }}
                            </button>
                            <a href="{{ route('jenis-beras.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
