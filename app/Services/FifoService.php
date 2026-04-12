<?php

namespace App\Services;

use App\Models\FifoQueue;
use Illuminate\Support\Facades\DB;

class FifoService
{
    public function stokCukup(int $jenisBerasId, float $jumlahDiminta): bool
    {
        $totalTersedia = FifoQueue::where('jenis_beras_id', $jenisBerasId)
            ->tersedia()
            ->sum('jumlah_tersisa');

        return (float) $totalTersedia >= $jumlahDiminta;
    }

    public function totalStok(int $jenisBerasId): float
    {
        return (float) FifoQueue::where('jenis_beras_id', $jenisBerasId)
            ->tersedia()
            ->sum('jumlah_tersisa');
    }

    /**
     * Proses pengurangan stok menggunakan metode FIFO.
     *
     * Algoritma:
     *  1. Ambil semua batch dengan status 'tersedia' untuk jenis beras,
     *     diurutkan dari tanggal masuk paling lama (ASC).
     *  2. Kurangi jumlah_tersisa dari batch tertua hingga jumlah terpenuhi.
     *  3. Jika jumlah_tersisa batch = 0, ubah status menjadi 'habis'.
     * 
     */
    public function prosesKeluar(int $jenisBerasId, float $jumlahKeluar): void
    {
        DB::transaction(function () use ($jenisBerasId, $jumlahKeluar) {

            if (!$this->stokCukup($jenisBerasId, $jumlahKeluar)) {
                throw new \Exception('Stok tidak mencukupi untuk pengeluaran sejumlah ' . $jumlahKeluar . '.');
            }

            $antrian = FifoQueue::where('jenis_beras_id', $jenisBerasId)
                ->tersedia()
                ->urutFifo()
                ->lockForUpdate()
                ->get();

            $sisaAmbil = $jumlahKeluar;

            foreach ($antrian as $batch) {
                if ($sisaAmbil <= 0) {
                    break;
                }

                if ((float) $batch->jumlah_tersisa <= $sisaAmbil) {
                    $sisaAmbil -= (float) $batch->jumlah_tersisa;
                    $batch->update([
                        'jumlah_tersisa' => 0,
                        'status' => 'habis',
                    ]);
                } else {
                    $batch->update([
                        'jumlah_tersisa' => $batch->jumlah_tersisa - $sisaAmbil,
                    ]);
                    $sisaAmbil = 0;
                }
            }
        });
    }

    public function detailAntrian(int $jenisBerasId): \Illuminate\Support\Collection
    {
        return FifoQueue::with('stokMasuk.supplier')
            ->where('jenis_beras_id', $jenisBerasId)
            ->tersedia()
            ->urutFifo()
            ->get();
    }
}