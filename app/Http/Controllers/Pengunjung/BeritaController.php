<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Konten;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Halaman daftar berita publik — hanya status 'published' yang tampil.
     */
    public function index(Request $request)
    {
        $konten = Konten::all()->keyBy('key');

        $query = Berita::published()->orderBy('date', 'desc');

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('title',    'like', "%{$keyword}%")
                  ->orWhere('category',    'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $berita = $query->get();

        return view('pengunjung.berita', compact('konten', 'berita'));
    }
}
