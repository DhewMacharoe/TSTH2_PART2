<?php

namespace App\Services;

use App\Models\Barang;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WarehouseExportService
{
    public function exportCsv(): StreamedResponse
    {
        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Nama Barang', 'Merk', 'Kategori', 'Lokasi Blok', 'Total Stok']);

            Barang::with('batches')->chunk(100, function($barangs) use($handle) {
                foreach ($barangs as $b) {
                    fputcsv($handle, [
                        '#B' . str_pad($b->id, 3, '0', STR_PAD_LEFT),
                        $b->nama_barang, $b->merk, $b->kategori, $b->lokasi_blok, $b->batches->sum('stok')
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="Laporan_Warehouse_TSTH2.csv"');

        return $response;
    }
}
