<?php

namespace App\Http\Controllers;

use App\Models\JenisBeras;
use App\Models\StokKeluar;
use App\Services\FifoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokKeluarController extends Controller
{
    public function __construct(protected FifoService $fifoService)
    {
    }

    public function index(Request $request)
    {
        $query = StokKeluar::with(['jenisBeras', 'user'])->latest();

        if ($request->filled('jenis_beras_id')) {
            $query->where('jenis_beras_id', $request->jenis_beras_id);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_keluar', [$request->dari, $request->sampai]);
        }

        if ($request->filled('search')) {
            $query->where('no_transaksi', 'like', '%' . $request->search . '%');
        }

        $data = $query->paginate(15)->withQueryString();
        $jenisBeras = JenisBeras::active()->orderBy('nama_beras')->get();

        return view('stok-keluar.index', compact('data', 'jenisBeras'));
    }

    public function create()
    {
        $jenisBeras = JenisBeras::active()->orderBy('nama_beras')->get()
            ->map(function ($b) {
                $b->stok_tersedia = $this->fifoService->totalStok($b->id);
                return $b;
            });

        return view('stok-keluar.create', compact('jenisBeras'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_beras_id' => 'required|exists:jenis_beras,id',
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal_keluar' => 'required|date|before_or_equal:today',
            'tujuan_distribusi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'jumlah.min' => 'Jumlah harus lebih dari 0.',
            'tanggal_keluar.before_or_equal' => 'Tanggal keluar tidak boleh melebihi hari ini.',
        ]);

        // Cek stok sebelum proses
        if (!$this->fifoService->stokCukup($validated['jenis_beras_id'], $validated['jumlah'])) {
            $stokTersedia = $this->fifoService->totalStok($validated['jenis_beras_id']);

            return back()->withInput()->withErrors([
                'jumlah' => "Stok tidak mencukupi. Stok tersedia: {$stokTersedia}.",
            ]);
        }

        DB::transaction(function () use ($validated) {
            // 1. Kurangi antrian FIFO
            $this->fifoService->prosesKeluar($validated['jenis_beras_id'], $validated['jumlah']);

            // 2. Catat transaksi keluar
            $validated['user_id'] = Auth::id();
            $validated['no_transaksi'] = StokKeluar::generateNoTransaksi();

            StokKeluar::create($validated);
        });

        return redirect()->route('stok-keluar.index')
            ->with('success', 'Stok keluar berhasil dicatat. FIFO diproses otomatis.');
    }

    public function show(StokKeluar $stokKeluar)
    {
        $stokKeluar->load(['jenisBeras', 'user']);

        return view('stok-keluar.show', compact('stokKeluar'));
    }
}