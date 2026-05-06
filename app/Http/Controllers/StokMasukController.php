<?php

namespace App\Http\Controllers;

use App\Models\FifoQueue;
use App\Models\JenisBeras;
use App\Models\StokMasuk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = StokMasuk::with(['jenisBeras', 'supplier', 'user'])->latest();

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

        // FifoQueue dibuat otomatis via model boot
        StokMasuk::create($validated);

        return redirect()->route('stok-masuk.index')
            ->with('success', "Stok masuk {$validated['no_transaksi']} berhasil dicatat.");
    }

    public function show(StokMasuk $stokMasuk)
    {
        $stokMasuk->load(['jenisBeras', 'supplier', 'user', 'fifoQueue']);

        return view('stok-masuk.show', compact('stokMasuk'));
    }

    public function edit(StokMasuk $stokMasuk)
    {
        $fifo = $stokMasuk->fifoQueue;
        $sudahTerpakai = $fifo ? (float) $fifo->jumlah_awal - (float) $fifo->jumlah_tersisa : 0;
        $bisaDiubahMax = $fifo ? (float) $fifo->jumlah_tersisa : 0; // sisa yang belum terpakai

        $jenisBeras = JenisBeras::active()->orderBy('nama_beras')->get();
        $suppliers = Supplier::active()->orderBy('nama_supplier')->get();

        return view('stok-masuk.edit', compact(
            'stokMasuk',
            'jenisBeras',
            'suppliers',
            'sudahTerpakai',
            'bisaDiubahMax'
        ));
    }

    public function update(Request $request, StokMasuk $stokMasuk)
    {
        $fifo = $stokMasuk->fifoQueue;
        $sudahTerpakai = $fifo ? (float) $fifo->jumlah_awal - (float) $fifo->jumlah_tersisa : 0;

        $validated = $request->validate([
            'jenis_beras_id' => 'required|exists:jenis_beras,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah' => [
                'required',
                'numeric',
                'min:' . max(0.01, $sudahTerpakai),
            ],
            'harga_beli' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date|before_or_equal:today',
            'tanggal_kadaluarsa' => 'nullable|date|after:tanggal_masuk',
            'no_surat_jalan' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'jumlah.min' => "Jumlah tidak boleh kurang dari {$sudahTerpakai} karena sebagian stok sudah dikeluarkan.",
        ]);

        DB::transaction(function () use ($validated, $stokMasuk, $fifo, $sudahTerpakai) {
            $jumlahBaru = (float) $validated['jumlah'];

            // Update data transaksi
            $stokMasuk->update([
                'jenis_beras_id' => $validated['jenis_beras_id'],
                'supplier_id' => $validated['supplier_id'],
                'jumlah' => $jumlahBaru,
                'harga_beli' => $validated['harga_beli'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'tanggal_kadaluarsa' => $validated['tanggal_kadaluarsa'],
                'no_surat_jalan' => $validated['no_surat_jalan'],
                'keterangan' => $validated['keterangan'],
                'user_id' => Auth::id(),
            ]);

            // Sinkronisasi fifo_queues mengikuti jumlah baru
            if ($fifo) {
                $sisaBaru = $jumlahBaru - $sudahTerpakai;
                $fifo->update([
                    'jenis_beras_id' => $validated['jenis_beras_id'],
                    'jumlah_awal' => $jumlahBaru,
                    'jumlah_tersisa' => max(0, $sisaBaru),
                    'tanggal_masuk' => $validated['tanggal_masuk'],
                    'status' => $sisaBaru > 0 ? 'tersedia' : 'habis',
                ]);
            }
        });

        return redirect()->route('stok-masuk.index')
            ->with('success', 'Stok masuk berhasil diperbarui.');
    }

    public function destroy(StokMasuk $stokMasuk)
    {
        $fifo = $stokMasuk->fifoQueue;
        $sudahTerpakai = $fifo ? (float) $fifo->jumlah_awal - (float) $fifo->jumlah_tersisa : 0;

        // Tidak boleh hapus jika stok batch ini sudah sebagian keluar
        if ($sudahTerpakai > 0) {
            return back()->with(
                'error',
                "Tidak dapat menghapus. Sebanyak {$sudahTerpakai} {$stokMasuk->jenisBeras->satuan} dari batch ini sudah dikeluarkan."
            );
        }

        DB::transaction(function () use ($stokMasuk, $fifo) {
            // Hapus antrian FIFO dulu, baru transaksinya
            if ($fifo) {
                $fifo->delete();
            }
            $stokMasuk->delete();
        });

        return redirect()->route('stok-masuk.index')
            ->with('success', 'Transaksi stok masuk berhasil dihapus.');
    }
}