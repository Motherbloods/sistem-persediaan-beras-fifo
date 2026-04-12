@extends('layouts.app')

@section('title', 'Catat Stok Keluar')
@section('page-title', 'Catat Stok Keluar')
@section('page-subtitle', 'Input distribusi beras dari gudang (FIFO otomatis)')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-arrow-up-circle text-danger"></i> Form Stok Keluar</h5>
                    <a href="{{ route('stok-keluar.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('stok-keluar.store') }}">
                        @csrf

                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label">Jenis Beras <span class="text-danger">*</span></label>
                                <select name="jenis_beras_id" id="jenis_beras_id"
                                    class="form-select @error('jenis_beras_id') is-invalid @enderror"
                                    onchange="updateStokInfo(this)" required>
                                    <option value="">— Pilih jenis beras —</option>
                                    @foreach ($jenisBeras as $jb)
                                        <option value="{{ $jb->id }}" data-stok="{{ $jb->stok_tersedia }}"
                                            data-satuan="{{ $jb->satuan }}" data-minimum="{{ $jb->stok_minimum }}"
                                            {{ old('jenis_beras_id') == $jb->id ? 'selected' : '' }}>
                                            {{ $jb->nama_beras }} ({{ $jb->kode_beras }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_beras_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div id="stok-info" class="mt-2 d-none">
                                    <div class="d-flex align-items-center justify-content-between p-3"
                                        style="background:#F8FAFC;border-radius:8px;border:1px solid #E2E8F0;">
                                        <div>
                                            <div style="font-size:12px;color:#64748B;">Stok tersedia saat ini</div>
                                            <div style="font-size:18px;font-weight:600;color:#1E293B;" id="stok-nilai">0
                                            </div>
                                        </div>
                                        <div id="stok-status-badge"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jumlah Keluar <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="jumlah" id="jumlah"
                                        class="form-control @error('jumlah') is-invalid @enderror" placeholder="0"
                                        step="0.01" min="0.01" value="{{ old('jumlah') }}" oninput="cekJumlah(this)"
                                        required>
                                    <span class="input-group-text" id="satuan-label"
                                        style="background:#F8FAFC;border-color:#E2E8F0;font-size:13px;">kg</span>
                                </div>
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="jumlah-warning" class="d-none mt-1" style="font-size:12px;color:#DC2626;">
                                    <i class="bi bi-exclamation-circle"></i> Jumlah melebihi stok tersedia!
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_keluar"
                                    class="form-control @error('tanggal_keluar') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}" value="{{ old('tanggal_keluar', date('Y-m-d')) }}" required>
                                @error('tanggal_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Tujuan Distribusi</label>
                                <input type="text" name="tujuan_distribusi"
                                    class="form-control @error('tujuan_distribusi') is-invalid @enderror"
                                    placeholder="Nama toko / agen / tujuan pengiriman"
                                    value="{{ old('tujuan_distribusi') }}">
                                @error('tujuan_distribusi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            </div>

                        </div>

                        <div class="alert alert-warning d-flex gap-2 mt-4 py-2" style="font-size:13px;border-radius:8px;">
                            <i class="bi bi-lightning-charge-fill mt-1 flex-shrink-0"></i>
                            <span>
                                Sistem akan otomatis mengambil dari <strong>batch yang paling lama masuk</strong> terlebih
                                dahulu sesuai metode FIFO.
                                Anda tidak perlu memilih batch secara manual.
                            </span>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-danger" id="btn-submit">
                                <i class="bi bi-save me-2"></i>Simpan Stok Keluar
                            </button>
                            <a href="{{ route('stok-keluar.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let stokTersedia = 0;

        function updateStokInfo(sel) {
            const opt = sel.options[sel.selectedIndex];
            const stok = parseFloat(opt.dataset.stok || 0);
            const satuan = opt.dataset.satuan || 'kg';
            const minimum = parseFloat(opt.dataset.minimum || 0);

            stokTersedia = stok;

            document.getElementById('satuan-label').textContent = satuan;
            document.getElementById('stok-nilai').textContent =
                stok.toLocaleString('id-ID', {
                    minimumFractionDigits: 2
                }) + ' ' + satuan;

            let badgeHtml = '';
            if (stok <= 0) {
                badgeHtml =
                    '<span class="badge" style="background:#FEE2E2;color:#991B1B;font-size:12px;padding:6px 10px;border-radius:6px;font-weight:500;">Habis</span>';
            } else if (stok <= minimum) {
                badgeHtml =
                    '<span class="badge" style="background:#FEF9C3;color:#92400E;font-size:12px;padding:6px 10px;border-radius:6px;font-weight:500;">Menipis</span>';
            } else {
                badgeHtml =
                    '<span class="badge" style="background:#DCFCE7;color:#166534;font-size:12px;padding:6px 10px;border-radius:6px;font-weight:500;">Aman</span>';
            }

            document.getElementById('stok-status-badge').innerHTML = badgeHtml;
            document.getElementById('stok-info').classList.remove('d-none');

            cekJumlah(document.getElementById('jumlah'));
        }

        function cekJumlah(input) {
            const jumlah = parseFloat(input.value || 0);
            const warning = document.getElementById('jumlah-warning');
            const btn = document.getElementById('btn-submit');

            if (jumlah > 0 && jumlah > stokTersedia && stokTersedia > 0) {
                warning.classList.remove('d-none');
            } else {
                warning.classList.add('d-none');
            }
        }
    </script>
@endpush
