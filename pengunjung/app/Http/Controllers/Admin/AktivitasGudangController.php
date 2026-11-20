<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasGudang;
use Illuminate\Http\Request;

class AktivitasGudangController extends Controller
{
    public function index(Request $request)
    {
        $query = AktivitasGudang::with(['user', 'barang'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->filled('tipe_mutasi')) {
            $query->where('tipe_mutasi', $request->tipe_mutasi);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('keterangan', 'like', "%{$s}%")
                  ->orWhere('user_name', 'like', "%{$s}%")
                  ->orWhere('nama_barang', 'like', "%{$s}%");
            });
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $aktivitas = $query->paginate(25)->withQueryString();

        $stats = [
            'total'      => AktivitasGudang::count(),
            'barang'     => AktivitasGudang::where('modul', AktivitasGudang::MODUL_BARANG)->count(),
            'permintaan' => AktivitasGudang::where('modul', AktivitasGudang::MODUL_PERMINTAAN)->count(),
            'eskalasi'   => AktivitasGudang::where('aksi', 'eskalasi')->count(),
        ];

        return view('gudang.aktivitas', compact('aktivitas', 'stats'));
    }
}
