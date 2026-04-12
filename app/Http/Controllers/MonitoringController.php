<?php

namespace App\Http\Controllers;

use App\Models\JenisBeras;
use App\Services\FifoService;

class MonitoringController extends Controller
{
    public function __construct(protected FifoService $fifoService)
    {
    }

    public function index()
    {
        $data = JenisBeras::active()
            ->orderBy('nama_beras')
            ->get()
            ->map(function ($beras) {
                $beras->stok_tersedia = $this->fifoService->totalStok($beras->id);
                $beras->antrian_fifo = $this->fifoService->detailAntrian($beras->id);

                return $beras;
            });

        $berasAman = $data->filter(fn($b) => $b->status_stok === 'aman');
        $berasMenipis = $data->filter(fn($b) => $b->status_stok === 'menipis');
        $berasHabis = $data->filter(fn($b) => $b->status_stok === 'habis');

        return view('monitoring.index', compact('data', 'berasAman', 'berasMenipis', 'berasHabis'));
    }
}