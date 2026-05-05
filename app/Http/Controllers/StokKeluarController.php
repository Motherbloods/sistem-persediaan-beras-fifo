<?php

namespace App\Http\Controllers;

use App\Models\FifoQueue;
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

        if (!$this->fifoService->stokCukup($validated['jenis_beras_id'], $validated['jumlah'])) {
            $stokTersedia = $this->fifoService->totalStok($validated['jenis_beras_id']);
            return back()->withInput()->withErrors([
                'jumlah' => "Stok tidak mencukupi. Stok tersedia: {$stokTersedia}.",
            ]);
        }

        DB::transaction(function () use ($validated) {
            $this->fifoService->prosesKeluar($validated['jenis_beras_id'], $validated['jumlah']);

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

    public function edit(StokKeluar $stokKeluar)
    {
        $jenisBeras = JenisBeras::active()->orderBy('nama_beras')->get()
            ->map(function ($b) use ($stokKeluar) {
                $stokSaatIni = $this->fifoService->totalStok($b->id);

                $b->stok_tersedia = $b->id === $stokKeluar->jenis_beras_id
                    ? $stokSaatIni + $stokKeluar->jumlah
                    : $stokSaatIni;

                return $b;
            });

        return view('stok-keluar.edit', compact('stokKeluar', 'jenisBeras'));
    }

    public function update(Request $request, StokKeluar $stokKeluar)
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

        DB::transaction(function () use ($validated, $stokKeluar) {
            $jenisBerasLama = $stokKeluar->jenis_beras_id;
            $jumlahLama = (float) $stokKeluar->jumlah;
            $jenisBeraBaru = (int) $validated['jenis_beras_id'];
            $jumlahBaru = (float) $validated['jumlah'];

            // 1. Kembalikan stok lama ke fifo_queues
            $this->kembalikanStok($jenisBerasLama, $jumlahLama);

            // 2. Cek apakah stok baru mencukupi
            $stokTersedia = $this->fifoService->totalStok($jenisBeraBaru);

            if ($stokTersedia < $jumlahBaru) {
                throw new \Exception("Stok tidak mencukupi. Tersedia: {$stokTersedia}.");
            }

            // 3. Proses stok keluar baru dengan FIFO
            $this->fifoService->prosesKeluar($jenisBeraBaru, $jumlahBaru);

            // 4. Simpan perubahan data transaksi
            $stokKeluar->update([
                'jenis_beras_id' => $jenisBeraBaru,
                'jumlah' => $jumlahBaru,
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'tujuan_distribusi' => $validated['tujuan_distribusi'],
                'keterangan' => $validated['keterangan'],
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('stok-keluar.index')
            ->with('success', 'Stok keluar berhasil diperbarui.');
    }

    public function destroy(StokKeluar $stokKeluar)
    {
        DB::transaction(function () use ($stokKeluar) {
            // Kembalikan stok ke fifo_queues sebelum data dihapus
            $this->kembalikanStok($stokKeluar->jenis_beras_id, (float) $stokKeluar->jumlah);

            $stokKeluar->delete();
        });

        return redirect()->route('stok-keluar.index')
            ->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan otomatis.');
    }

    private function kembalikanStok(int $jenisBerasId, float $jumlahDikembalikan): void
    {
        $antrian = FifoQueue::where('jenis_beras_id', $jenisBerasId)
            ->orderBy('tanggal_masuk', 'desc')
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->get();

        $sisaKembali = $jumlahDikembalikan;

        foreach ($antrian as $batch) {
            if ($sisaKembali <= 0)
                break;

            // Berapa ruang yang masih tersedia di batch ini
            $ruang = (float) $batch->jumlah_awal - (float) $batch->jumlah_tersisa;

            if ($ruang <= 0)
                continue; // batch sudah penuh, lewati

            $isiKembali = min($ruang, $sisaKembali);
            $tersisaBaru = (float) $batch->jumlah_tersisa + $isiKembali;

            $batch->update([
                'jumlah_tersisa' => $tersisaBaru,
                'status' => 'tersedia',
            ]);

            $sisaKembali -= $isiKembali;
        }
    }
}