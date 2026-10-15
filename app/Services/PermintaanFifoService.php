<?php

namespace App\Services;

use App\Models\PermintaanBarang;
use App\Models\SatuanKonversi;
use App\Models\BarangBatch;
use Carbon\Carbon;

class PermintaanFifoService
{
    public function handleVerifyPickup(PermintaanBarang $permintaan, ?string $fotoPath = null)
    {
        $barang    = $permintaan->barang;
        $remaining = $permintaan->jumlah;

        $batches = $barang->batches()
            ->where('stok', '>', 0)
            ->where(function($q) {
                $q->whereNull('tanggal_kadaluarsa')
                  ->orWhere('tanggal_kadaluarsa', '>=', Carbon::today());
            })
            ->orderByRaw("CASE WHEN tanggal_kadaluarsa IS NULL THEN 1 ELSE 0 END ASC")
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->orderBy('tanggal_kedatangan', 'asc')
            ->get();

        $permintaanedSatuan = strtolower($permintaan->satuan_diminta);

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;
            
            $available = $batch->getAvailableStokBySatuan($permintaanedSatuan);
            if ($available > 0) {
                $take = min($available, $remaining);
                $batch->decrementStokBySatuan($permintaanedSatuan, $take);
                $remaining -= $take;
            }
        }

        if ($remaining > 0) {
            return back()->with('error', 'Stok tidak mencukupi untuk memenuhi permintaan, termasuk setelah melakukan konversi. Kekurangan: ' . $remaining . ' ' . strtoupper($permintaanedSatuan));
        }

        $updateData = ['status' => PermintaanBarang::STATUS_BARANG_DIAMBIL];
        if ($fotoPath) {
            $updateData['foto_pengambilan'] = $fotoPath;
        }

        $permintaan->update($updateData);
        return redirect()->route('permintaan.index')
            ->with('success', 'Bukti pengambilan berhasil diunggah. Barang resmi diambil dan stok otomatis dipotong.');
    }

    public function handleVerifyReturn(PermintaanBarang $permintaan)
    {
        abort_if($permintaan->status !== PermintaanBarang::STATUS_VERIFIKASI_PENGEMBALIAN, 422, 'Status tidak valid.');

        if (!$permintaan->is_habis_pakai_saat_lapor && $permintaan->jumlah_dikembalikan > 0) {
            $barang = $permintaan->barang;
            $batch = $barang->batches()->orderBy('id', 'desc')->first();

            if ($batch) {
                $batch->incrementStokBySatuan(
                    strtolower($permintaan->satuan_diminta),
                    $permintaan->kondisi_pengembalian ?? 'Bekas Pakai',
                    $permintaan->jumlah_dikembalikan
                );
            } else {
                $newBatchNo = BarangBatch::nextBatchNo($barang->id);
                $barang->batches()->create([
                    'batch_no'           => $newBatchNo,
                    'tanggal_kedatangan' => now(),
                    'stok'               => $permintaan->jumlah_dikembalikan,
                    'stok_detail'        => [[
                        'kondisi' => $permintaan->kondisi_pengembalian ?? 'Bekas Pakai',
                        'satuan'  => strtolower($permintaan->satuan_diminta),
                        'stok'    => $permintaan->jumlah_dikembalikan,
                    ]],
                ]);
            }
            $permintaan->update(['status' => PermintaanBarang::STATUS_BARANG_DIKEMBALIKAN]);
            return redirect()->route('permintaan.index')->with('success', 'Pengembalian diverifikasi. Stok telah ditambahkan kembali.');
        } else {
            $permintaan->closeRequest();
            return redirect()->route('permintaan.index')->with('success', 'Laporan diverifikasi. Permintaan selesai (barang habis terpakai).');
        }
    }
}
