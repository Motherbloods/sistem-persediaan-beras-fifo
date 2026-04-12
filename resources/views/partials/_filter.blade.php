<form method="GET" action="{{ route($route) }}" class="row g-2 align-items-end">
    @if (!empty($jenisBeras))
        <div class="col-sm-6 col-md-3">
            <label class="form-label">Jenis Beras</label>
            <select name="jenis_beras_id" class="form-select form-select-sm">
                <option value="">Semua Jenis</option>
                @foreach ($jenisBeras as $jb)
                    <option value="{{ $jb->id }}" {{ request('jenis_beras_id') == $jb->id ? 'selected' : '' }}>
                        {{ $jb->nama_beras }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    @if (!empty($suppliers) && !empty($showSupplier))
        <div class="col-sm-6 col-md-3">
            <label class="form-label">Supplier</label>
            <select name="supplier_id" class="form-select form-select-sm">
                <option value="">Semua Supplier</option>
                @foreach ($suppliers as $s)
                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->nama_supplier }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="col-sm-6 col-md-2">
        <label class="form-label">Dari Tanggal</label>
        <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
    </div>

    <div class="col-sm-6 col-md-2">
        <label class="form-label">Sampai</label>
        <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
    </div>

    <div class="col-sm-6 col-md-2">
        <label class="form-label">No. Transaksi</label>
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari..."
            value="{{ request('search') }}">
    </div>

    <div class="col-auto d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-search me-1"></i>Filter
        </button>
        <a href="{{ route($route) }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </div>
</form>
