<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        
        // Calculate some stats for the dashboard
        $totalBarang = $barangs->sum('stok');
        $totalKategori = $barangs->pluck('kategori')->unique()->count();

        // Data for Chart.js
        $barangsByCategory = $barangs->groupBy('kategori')->map->sum('stok');
        $barangsByBlock = $barangs->groupBy('lokasi_blok')->map->sum('stok');

        return view('warehouse', compact('barangs', 'totalBarang', 'totalKategori', 'barangsByCategory', 'barangsByBlock'));
    }

    public function create()
    {
        $existingBarangs = Barang::orderBy('nama_barang')->get();
        return view('warehouse_create', compact('existingBarangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('barangs')->where(function ($query) use ($request) {
                    if (empty($request->merk)) {
                        return $query->whereNull('merk');
                    }
                    return $query->where('merk', $request->merk);
                })
            ],
            'merk' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:100',
            'lokasi_blok' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_kedatangan' => 'required|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_kedatangan',
        ], [
            'nama_barang.unique' => 'Barang dengan nama ini sudah terdaftar di sistem. Silakan cari barang tersebut dan gunakan fitur "Restock" untuk menambah stok.'
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('barangs', 'public');
        }

        $barangData = $validated;
        unset($barangData['tanggal_kedatangan'], $barangData['tanggal_kadaluarsa']);
        $barangData['status'] = $barangData['stok'] > 50 ? 'Tersedia' : ($barangData['stok'] > 0 ? 'Stok Kritis' : 'Habis');

        $barang = Barang::create($barangData);

        if ($request->tanggal_kedatangan) {
            \App\Models\BarangBatch::create([
                'barang_id' => $barang->id,
                'tanggal_kedatangan' => $request->tanggal_kedatangan,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
                'stok' => $barang->stok
            ]);
        }

        return redirect()->route('warehouse')->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('warehouse_edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_barang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('barangs')->where(function ($query) use ($request) {
                    if (empty($request->merk)) {
                        return $query->whereNull('merk');
                    }
                    return $query->where('merk', $request->merk);
                })->ignore($id)
            ],
            'merk' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:100',
            'lokasi_blok' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama_barang.unique' => 'Barang dengan nama ini sudah terdaftar di sistem.'
        ]);

        $barang = Barang::findOrFail($id);

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('barangs', 'public');
        }

        $validated['status'] = $validated['stok'] > 50 ? 'Tersedia' : ($validated['stok'] > 0 ? 'Stok Kritis' : 'Habis');

        $barang->update($validated);

        return redirect()->route('warehouse')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        
        // Delete image
        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }
        
        $barang->delete();

        return redirect()->route('warehouse')->with('success', 'Data barang berhasil dihapus!');
    }

    public function addBatch(Request $request, $id)
    {
        $request->validate([
            'tanggal_kedatangan' => 'required|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_kedatangan',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barang = Barang::findOrFail($id);
        
        \App\Models\BarangBatch::create([
            'barang_id' => $barang->id,
            'tanggal_kedatangan' => $request->tanggal_kedatangan,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'stok' => $request->jumlah
        ]);

        $barang->stok += $request->jumlah;
        $barang->status = $barang->stok > 50 ? 'Tersedia' : 'Stok Kritis';
        $barang->save();

        return redirect()->route('warehouse')->with('success', 'Batch baru berhasil ditambahkan!');
    }

    public function show($id)
    {
        $barang = Barang::with(['batches' => function($query) {
            $query->orderBy('tanggal_kedatangan', 'asc');
        }])->findOrFail($id);
        
        return view('warehouse_show', compact('barang'));
    }

    public function destroyBatch($batch_id)
    {
        $batch = \App\Models\BarangBatch::findOrFail($batch_id);
        $barang = $batch->barang;
        
        // Kurangi stok barang utama sesuai stok batch yang dihapus
        if ($barang->stok >= $batch->stok) {
            $barang->stok -= $batch->stok;
        } else {
            $barang->stok = 0;
        }
        
        $barang->status = $barang->stok > 50 ? 'Tersedia' : ($barang->stok > 0 ? 'Stok Kritis' : 'Habis');
        $barang->save();
        
        $batch->delete();

        return back()->with('success', 'Batch berhasil dihapus!');
    }

    public function export()
    {
        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            
            // Header baris CSV
            fputcsv($handle, ['ID', 'Nama Barang', 'Merk', 'Kategori', 'Lokasi Blok', 'Stok', 'Status']);

            Barang::chunk(100, function($barangs) use($handle) {
                foreach ($barangs as $b) {
                    fputcsv($handle, [
                        '#B' . str_pad($b->id, 3, '0', STR_PAD_LEFT),
                        $b->nama_barang,
                        $b->merk,
                        $b->kategori,
                        $b->lokasi_blok,
                        $b->stok,
                        $b->status
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
