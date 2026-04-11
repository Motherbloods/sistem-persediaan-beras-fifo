@extends('layouts.app')
@section('title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('page-title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-truck text-primary"></i> {{ isset($supplier) ? 'Edit' : 'Tambah' }} Supplier</h5>
                    <a href="{{ route('supplier.index') }}" class="btn btn-sm btn-outline-secondary"><i
                            class="bi bi-arrow-left me-1"></i>Kembali</a>
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="{{ isset($supplier) ? route('supplier.update', $supplier) : route('supplier.store') }}">
                        @csrf
                        @if (isset($supplier))
                            @method('PUT')
                        @endif
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Kode Supplier <span class="text-danger">*</span></label>
                                <input type="text" name="kode_supplier"
                                    class="form-control @error('kode_supplier') is-invalid @enderror" placeholder="SUP-001"
                                    value="{{ old('kode_supplier', $supplier->kode_supplier ?? '') }}" required
                                    maxlength="20">
                                @error('kode_supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                                <input type="text" name="nama_supplier"
                                    class="form-control @error('nama_supplier') is-invalid @enderror"
                                    value="{{ old('nama_supplier', $supplier->nama_supplier ?? '') }}" required>
                                @error('nama_supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">PIC (Person In Charge)</label>
                                <input type="text" name="pic" class="form-control"
                                    value="{{ old('pic', $supplier->pic ?? '') }}" placeholder="Nama kontak">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="telepon" class="form-control"
                                    value="{{ old('telepon', $supplier->telepon ?? '') }}" placeholder="08xx-xxxx-xxxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $supplier->email ?? '') }}" placeholder="email@supplier.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap supplier">{{ old('alamat', $supplier->alamat ?? '') }}</textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                        value="1"
                                        {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active" style="font-size:13.5px;">Supplier
                                        aktif</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary"><i
                                    class="bi bi-save me-2"></i>{{ isset($supplier) ? 'Perbarui' : 'Simpan' }}</button>
                            <a href="{{ route('supplier.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
