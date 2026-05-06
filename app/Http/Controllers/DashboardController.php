<?php

namespace App\Http\Controllers;

use App\Models\JenisBeras;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use App\Services\FifoService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected FifoService $fifoService)
    {
    }

    public function index(Request $request)
    {
        $bulanParam = $request->get('bulan', now()->format('Y-m'));

        try {
            $bulanAktif = Carbon::createFromFormat('Y-m', $bulanParam)->startOfMonth();
        } catch (\Exception $e) {
            $bulanAktif = now()->startOfMonth();
        }

        $bulanSebelum = $bulanAktif->copy()->subMonth()->format('Y-m');
        $bulanSesudah = $bulanAktif->copy()->addMonth()->format('Y-m');
        $isBuilanIni = $bulanAktif->format('Y-m') === now()->format('Y-m');

        $totalJenisBeras = JenisBeras::active()->count();

        $totalMasukBulan = StokMasuk::whereMonth('tanggal_masuk', $bulanAktif->month)
            ->whereYear('tanggal_masuk', $bulanAktif->year)
            ->sum('jumlah');

        $totalKeluarBulan = StokKeluar::whereMonth('tanggal_keluar', $bulanAktif->month)
            ->whereYear('tanggal_keluar', $bulanAktif->year)
            ->sum('jumlah');

        $berasMenipis = JenisBeras::active()->get()
            ->filter(fn($b) => $b->stok_saat_ini <= $b->stok_minimum)
            ->values();

        $grafik = $this->dataGrafik($bulanAktif);

        $riwayatMasuk = StokMasuk::with(['jenisBeras', 'supplier', 'user'])
            ->whereMonth('tanggal_masuk', $bulanAktif->month)
            ->whereYear('tanggal_masuk', $bulanAktif->year)
            ->latest('tanggal_masuk')
            ->limit(8)
            ->get()
            ->map(fn($t) => [
                'tipe' => 'masuk',
                'tanggal' => $t->tanggal_masuk,
                'label' => $t->jenisBeras->nama_beras,
                'sub' => $t->supplier->nama_supplier,
                'jumlah' => $t->jumlah,
                'no' => $t->no_transaksi,
            ]);

        $riwayatKeluar = StokKeluar::with(['jenisBeras', 'user'])
            ->whereMonth('tanggal_keluar', $bulanAktif->month)
            ->whereYear('tanggal_keluar', $bulanAktif->year)
            ->latest('tanggal_keluar')
            ->limit(8)
            ->get()
            ->map(fn($t) => [
                'tipe' => 'keluar',
                'tanggal' => $t->tanggal_keluar,
                'label' => $t->jenisBeras->nama_beras,
                'sub' => $t->tujuan_distribusi ?? 'Tidak ada tujuan',
                'jumlah' => $t->jumlah,
                'no' => $t->no_transaksi,
            ]);

        $riwayat = $riwayatMasuk->concat($riwayatKeluar)
            ->sortByDesc('tanggal')
            ->take(10)
            ->values();

        return view('dashboard.index', compact(
            'totalJenisBeras',
            'totalMasukBulan',
            'totalKeluarBulan',
            'berasMenipis',
            'grafik',
            'riwayat',
            'bulanAktif',
            'bulanSebelum',
            'bulanSesudah',
            'isBuilanIni',
        ));
    }

    private function dataGrafik(Carbon $bulanAktif): array
    {
        $bulan = [];
        $masuk = [];
        $keluar = [];

        for ($i = 5; $i >= 0; $i--) {
            $tgl = $bulanAktif->copy()->subMonths($i);

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