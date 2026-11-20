<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Konten;
use App\Support\BeritaArticles;
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

        $berita = $query->paginate(9)->withQueryString();

        return view('pengunjung.berita', compact('konten', 'berita'));
    }

    /**
     * Detail berita publik berdasarkan slug.
     */
    public function show(string $slug)
    {
        $article = BeritaArticles::find($slug);
        abort_if(!$article, 404);

        if (!is_file(public_path($article['image'])) && isset($article['fallback_image'])) {
            $article['image'] = $article['fallback_image'];
        }

        return view('pengunjung.berita-show', compact('article'));
    }
}
