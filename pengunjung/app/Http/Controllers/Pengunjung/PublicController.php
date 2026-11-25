<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use App\Models\Penelitian;
use App\Models\GaleriAlbum;
use App\Models\Berita;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Halaman Beranda (Home)
     */
    public function home()
    {
        $konten          = Konten::all()->keyBy('key');
        $homeBackgrounds = Konten::where('section', 'home_background')->get();
        $aboutBullets    = Konten::where('section', 'about_bullet')->get();
        $latestBerita    = Berita::published()->orderBy('date', 'desc')->limit(3)->get();

        return view('pengunjung.home', compact('konten', 'homeBackgrounds', 'aboutBullets', 'latestBerita'));
    }

    /**
     * Halaman Profil TSTH2
     */
    public function profil()
    {
        $konten       = Konten::all()->keyBy('key');
        $aboutBullets = Konten::where('section', 'about_bullet')->get();

        return view('pengunjung.profil', compact('konten', 'aboutBullets'));
    }

    /**
     * Halaman Layanan
     */
    public function layanan()
    {
        $konten = Konten::all()->keyBy('key');

        return view('pengunjung.layanan', compact('konten'));
    }

    /**
     * Halaman Riset & Aktivitas
     */
    public function penelitian(Request $request)
    {
        $konten = Konten::all()->keyBy('key');
        $query  = Penelitian::query();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $penelitianes = $query->latest()->paginate(9)->withQueryString();

        return view('pengunjung.penelitian', compact('konten', 'penelitianes'));
    }

    /**
     * Halaman Detail Riset
     */
    public function penelitianShow($id)
    {
        $konten = Konten::all()->keyBy('key');
        $item   = Penelitian::findOrFail($id);
        $terkait = Penelitian::where('id', '!=', $id)->latest()->take(3)->get();

        return view('pengunjung.penelitian-show', compact('konten', 'item', 'terkait'));
    }

    /**
     * Halaman Galeri Foto
     */
    public function galeri(Request $request)
    {
        $konten = Konten::all()->keyBy('key');
        $query  = GaleriAlbum::latest();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $galeri = $query->paginate(12)->withQueryString();

        return view('pengunjung.galeri', compact('konten', 'galeri'));
    }

    public function switchLanguage(string $locale)
    {
        if (in_array($locale, ['id', 'en'])) {
            session(['locale' => $locale]);
            cookie()->queue(cookie('site_pref_lang', $locale, 60 * 24 * 365, '/'));
        }

        return redirect()->back(fallback: route('home'));
    }
}
