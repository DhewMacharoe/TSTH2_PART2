<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangBatch;

class GudangBatchController extends Controller
{
    /**
     * Tambah batch baru (bisa multi item dalam satu batch).
     */
    public function addBatch(Request $permintaan, $id)
    {
        $permintaan->validate([
            'tanggal_kedatangan'   => 'required|date',
            'tanggal_kadaluarsa'   => 'nullable|date|after_or_equal:tanggal_kedatangan',
            'stok_jumlah'          => 'required|array',
            'stok_jumlah.*'        => 'required|integer|min:1',
            'stok_satuan'          => 'required|array',
            'stok_satuan.*'        => 'required|string',
            'stok_kondisi'         => 'required|array',
            'stok_kondisi.*'       => 'required|string',
        ]);

        $barang  = Barang::findOrFail($id);
        $batchNo = BarangBatch::nextBatchNo($barang->id);

        $stokDetail = [];
        $totalStok  = 0;

        $stokJumlah  = $permintaan->input('stok_jumlah', []);
        $stokSatuan  = $permintaan->input('stok_satuan', []);
        $stokKondisi = $permintaan->input('stok_kondisi', []);

        for ($i = 0; $i < count($stokJumlah); $i++) {
            $stokDetail[] = [
                'kondisi' => $stokKondisi[$i] ?? '-',
                'satuan'  => $stokSatuan[$i] ?? '-',
                'stok'    => (int) $stokJumlah[$i]
            ];
            $totalStok += (int) $stokJumlah[$i];
        }

        BarangBatch::create([
            'barang_id'          => $barang->id,
            'batch_no'           => $batchNo,
            'tanggal_kedatangan' => $permintaan->tanggal_kedatangan,
            'tanggal_kadaluarsa' => $permintaan->tanggal_kadaluarsa,
            'stok'               => $totalStok,
            'stok_detail'        => $stokDetail,
        ]);

        return redirect()->route('gudang.show', $barang->id)
            ->with('success', 'Batch ' . $batchNo . ' berhasil ditambahkan!');
    }

    /**
     * Tambah item baru ke batch yang sudah ada.
     */
    public function addItemToBatch(Request $permintaan, $batchNo)
    {
        $barang = Barang::findOrFail($permintaan->barang_id);

        $permintaan->validate([
            'barang_id'          => 'required|exists:barangs,id',
            'stok'               => 'required|integer|min:0',
            'satuan'             => 'required|string|max:50',
            'kondisi'            => 'required|string|max:100',
            'tanggal_kedatangan' => 'required|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_kedatangan',
        ]);

        $existingBatch = BarangBatch::where('barang_id', $barang->id)
            ->where('batch_no', $batchNo)
            ->first();

        if (!$existingBatch) {
            return back()->with('error', 'Batch tidak ditemukan.');
        }

        BarangBatch::create([
            'barang_id'          => $barang->id,
            'batch_no'           => $batchNo,
            'tanggal_kedatangan' => $existingBatch->tanggal_kedatangan,
            'tanggal_kadaluarsa' => $permintaan->tanggal_kadaluarsa,
            'stok'               => $permintaan->stok,
            'stok_detail'        => [
                [
                    'kondisi' => $permintaan->kondisi,
                    'satuan'  => $permintaan->satuan,
                    'stok'    => (int) $permintaan->stok
                ]
            ]
        ]);

        return back()->with('success', 'Item berhasil ditambahkan ke batch ' . $batchNo . '!');
    }

    public function destroyBatch($batch_id)
    {
        $batch = BarangBatch::findOrFail($batch_id);
        $batch->delete();
        return back()->with('success', 'Item batch berhasil dihapus!');
    }

    public function destroyBatchItem($itemId)
    {
        $item = BarangBatch::findOrFail($itemId);
        $item->delete();
        return back()->with('success', 'Item batch berhasil dihapus!');
    }
}
