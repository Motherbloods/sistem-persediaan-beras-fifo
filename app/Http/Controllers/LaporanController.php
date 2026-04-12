<?php

namespace App\Http\Controllers;

use App\Models\JenisBeras;
use App\Models\StokKeluar;
use App\Models\StokMasuk;
use App\Services\FifoService;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(protected FifoService $fifoService)
    {
    }

    public function masuk(Request $request)
    {
        $query = StokMasuk::with(['jenisBeras', 'supplier', 'user']);
        $query = $this->applyFilter($query, $request, 'tanggal_masuk');

        $data = $query->orderBy('tanggal_masuk', 'desc')->paginate(20)->withQueryString();
        $jenisBeras = JenisBeras::active()->orderBy('nama_beras')->get();
        $totalJumlah = $query->sum('jumlah');

        return view('laporan.masuk', compact('data', 'jenisBeras', 'totalJumlah'));
    }

    public function exportMasuk(Request $request)
    {
        $query = StokMasuk::with(['jenisBeras', 'supplier', 'user']);
        $query = $this->applyFilter($query, $request, 'tanggal_masuk');

        $data = $query->orderBy('tanggal_masuk', 'desc')->get();
        $totalJumlah = $data->sum('jumlah');
        $periode = $this->labelPeriode($request);

        return view('laporan.pdf.masuk', compact('data', 'totalJumlah', 'periode'));
    }

    public function keluar(Request $request)
    {
        $query = StokKeluar::with(['jenisBeras', 'user']);
        $query = $this->applyFilter($query, $request, 'tanggal_keluar');

        $data = $query->orderBy('tanggal_keluar', 'desc')->paginate(20)->withQueryString();
        $jenisBeras = JenisBeras::active()->orderBy('nama_beras')->get();
        $totalJumlah = $query->sum('jumlah');

        return view('laporan.keluar', compact('data', 'jenisBeras', 'totalJumlah'));
    }

    public function exportKeluar(Request $request)
    {
        $query = StokKeluar::with(['jenisBeras', 'user']);
        $query = $this->applyFilter($query, $request, 'tanggal_keluar');

        $data = $query->orderBy('tanggal_keluar', 'desc')->get();
        $totalJumlah = $data->sum('jumlah');
        $periode = $this->labelPeriode($request);

        return view('laporan.pdf.keluar', compact('data', 'totalJumlah', 'periode'));
    }

    public function persediaan()
    {
        $data = JenisBeras::active()->orderBy('nama_beras')->get()
            ->map(function ($beras) {
                $beras->stok_tersedia = $this->fifoService->totalStok($beras->id);
                $beras->antrian_fifo = $this->fifoService->detailAntrian($beras->id);

                return $beras;
            });

        return view('laporan.persediaan', compact('data'));
    }

    public function exportPersediaan()
    {
        $data = JenisBeras::active()->orderBy('nama_beras')->get()
            ->map(function ($beras) {
                $beras->stok_tersedia = $this->fifoService->totalStok($beras->id);

                return $beras;
            });

        return view('laporan.pdf.persediaan', [
            'data' => $data,
            'periode' => 'Per ' . now()->translatedFormat('d F Y'),
        ]);
    }

    private function applyFilter($query, Request $request, string $kolom)
    {
        if ($request->filled('jenis_beras_id')) {
            $query->where('jenis_beras_id', $request->jenis_beras_id);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween($kolom, [$request->dari, $request->sampai]);
        } elseif ($request->filled('dari')) {
            $query->whereDate($kolom, '>=', $request->dari);
        } elseif ($request->filled('sampai')) {
            $query->whereDate($kolom, '<=', $request->sampai);
        }

        return $query;
    }

    private function labelPeriode(Request $request): string
    {
        if ($request->filled('dari') && $request->filled('sampai')) {
            return $request->dari . ' s/d ' . $request->sampai;
        }

        return 'Semua periode';
    }
}