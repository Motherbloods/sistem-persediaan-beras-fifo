<?php

namespace App\Http\Controllers;

use App\Models\JenisBeras;
use App\Models\StokMasuk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = StokMasuk::with(['jenisBeras', 'supplier', 'user'])->latest();

        // Filter pencarian
        if ($request->filled('jenis_beras_id')) {
            $query->where('jenis_beras_id', $request->jenis_beras_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_masuk', [$request->dari, $request->sampai]);
        }

        if ($request->filled('search')) {
            $query->where('no_transaksi', 'like', '%' . $request->search . '%');
        }

        $data = $query->paginate(15)->withQueryString();
        $jenisBeras = JenisBeras::active()->orderBy('nama_beras')->get();
        $suppliers = Supplier::active()->orderBy('nama_supplier')->get();

        return view('stok-masuk.index', compact('data', 'jenisBeras', 'suppliers'));
    }

    public function create()
    {
        $jenisBeras = JenisBeras::active()->orderBy('nama_beras')->get();
        $suppliers = Supplier::active()->orderBy('nama_supplier')->get();

        return view('stok-masuk.create', compact('jenisBeras', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_beras_id' => 'required|exists:jenis_beras,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah' => 'required|numeric|min:0.01',
            'harga_beli' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date|before_or_equal:today',
            'tanggal_kadaluarsa' => 'nullable|date|after:tanggal_masuk',
            'no_surat_jalan' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'jumlah.min' => 'Jumlah harus lebih dari 0.',
            'tanggal_masuk.before_or_equal' => 'Tanggal masuk tidak boleh melebihi hari ini.',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus setelah tanggal masuk.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['no_transaksi'] = StokMasuk::generateNoTransaksi();

        StokMasuk::create($validated);

        return redirect()->route('stok-masuk.index')
            ->with('success', "Stok masuk {$validated['no_transaksi']} berhasil dicatat.");
    }

    public function show(StokMasuk $stokMasuk)
    {
        $stokMasuk->load(['jenisBeras', 'supplier', 'user', 'fifoQueue']);

        return view('stok-masuk.show', compact('stokMasuk'));
    }
}