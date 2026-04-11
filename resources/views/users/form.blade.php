@extends('layouts.app')
@section('title', isset($user) && !request()->routeIs('profil') ? 'Edit Pengguna' : (request()->routeIs('profil') ?
    'Profil Saya' : 'Tambah Pengguna'))
@section('page-title', isset($user) && !request()->routeIs('profil') ? 'Edit Pengguna' : (request()->routeIs('profil') ?
    'Profil Saya' : 'Tambah Pengguna'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-person-circle text-primary"></i>
                        @if (request()->routeIs('profil'))
                            Profil Saya
                        @elseif(isset($user))
                            Edit Pengguna
                        @else
                            Tambah Pengguna
                        @endif
                    </h5>
                    @if (!request()->routeIs('profil'))
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary"><i
                                class="bi bi-arrow-left me-1"></i>Kembali</a>
                    @endif
                </div>
                <div class="card-body">
                    @php
                        $isProfilPage = request()->routeIs('profil');
                        $formAction = $isProfilPage
                            ? route('profil.update')
                            : (isset($user)
                                ? route('users.update', $user)
                                : route('users.store'));
                        $formMethod = $isProfilPage || isset($user) ? 'PUT' : 'POST';
                    @endphp

                    <form method="POST" action="{{ $formAction }}">
                        @csrf
                        @if ($formMethod === 'PUT')
                            @method('PUT')
                        @endif

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if (!$isProfilPage)
                                <div class="col-12">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="admin"
                                            {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                        <option value="gudang"
                                            {{ old('role', $user->role ?? '') === 'gudang' ? 'selected' : '' }}>Petugas
                                            Gudang</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="col-12">
                                <hr style="border-color:#F1F5F9;">
                                <label class="form-label">
                                    {{ $isProfilPage ? 'Password Lama' : 'Password' }}
                                    @if (!isset($user))
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                @if ($isProfilPage)
                                    <input type="password" name="password_lama"
                                        class="form-control @error('password_lama') is-invalid @enderror"
                                        placeholder="Masukkan password lama">
                                    @error('password_lama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @else
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="{{ isset($user) ? 'Kosongkan jika tidak ingin mengubah' : 'Minimal 8 karakter' }}"
                                        {{ !isset($user) ? 'required' : '' }}>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>

                            @if ($isProfilPage)
                                <div class="col-12">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="password_baru"
                                        class="form-control @error('password_baru') is-invalid @enderror"
                                        placeholder="Minimal 8 karakter">
                                    @error('password_baru')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_baru_confirmation" class="form-control"
                                        placeholder="Ulangi password baru">
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i
                                    class="bi bi-save me-2"></i>{{ $isProfilPage ? 'Perbarui Profil' : (isset($user) ? 'Perbarui' : 'Simpan') }}
                            </button>
                            @if (!$isProfilPage)
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
