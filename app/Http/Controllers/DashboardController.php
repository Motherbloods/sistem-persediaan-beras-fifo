<?php

namespace App\Http\Controllers;

use App\Models\JenisBeras;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use App\Services\FifoService;

class DashboardController extends Controller
{
    public function __construct(protected FifoService $fifoService)
    {
    }

    public function index()
    {
        $totalJenisBeras = JenisBeras::active()->count();
        $totalMasukBulanIni = StokMasuk::whereMonth('tanggal_masuk', now()->month)
            ->whereYear('tanggal_masuk', now()->year)
            ->sum('jumlah');
        $totalKeluarBulanIni = StokKeluar::whereMonth('tanggal_keluar', now()->month)
            ->whereYear('tanggal_keluar', now()->year)
            ->sum('jumlah');

        $berasMenipis = JenisBeras::active()->get()
            ->filter(fn($b) => $b->stok_saat_ini <= $b->stok_minimum)
            ->values();

        $grafik = $this->dataGrafik();

        $transaksiTerbaru = StokMasuk::with(['jenisBeras', 'supplier', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalJenisBeras',
            'totalMasukBulanIni',
            'totalKeluarBulanIni',
            'berasMenipis',
            'grafik',
            'transaksiTerbaru',
        ));
    }

    private function dataGrafik(): array
    {
        $bulan = [];
        $masuk = [];
        $keluar = [];

        for ($i = 5; $i >= 0; $i--) {
            $tgl = now()->subMonths($i);

            $bulan[] = $tgl->translatedFormat('M Y');

            $masuk[] = (float) StokMasuk::whereMonth('tanggal_masuk', $tgl->month)
                ->whereYear('tanggal_masuk', $tgl->year)
                ->sum('jumlah');

            $keluar[] = (float) StokKeluar::whereMonth('tanggal_keluar', $tgl->month)
                ->whereYear('tanggal_keluar', $tgl->year)
                ->sum('jumlah');
        }

        return compact('bulan', 'masuk', 'keluar');
    }
}