<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use App\Models\Penelitian;
use App\Models\Berita;
use App\Models\GaleriAlbum;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function home()
    {
        $konten          = Konten::all()->keyBy('key');
        $aboutBullets    = Konten::where('section', 'about_bullet')->get();
        $homeBackgrounds = Konten::where('section', 'home_background')->get();
        $allPenelitian   = Penelitian::latest()->get();
        $allBerita       = Berita::published()->orderBy('date', 'desc')->get();
        $allGaleri       = GaleriAlbum::latest()->get();
        return view('manajemen_web.home', compact('konten', 'aboutBullets', 'homeBackgrounds', 'allPenelitian', 'allBerita', 'allGaleri'));
    }


    public function penelitian(Request $permintaan)
    {
        $konten = Konten::all()->keyBy('key');
        $query  = Penelitian::query();
        if ($permintaan->has('search')) {
            $query->where('title', 'like', '%' . $permintaan->search . '%')
                  ->orWhere('description', 'like', '%' . $permintaan->search . '%');
        }
        $penelitian = $query->latest()->paginate(10)->withQueryString();
        $penelitianes = $penelitian;
        return view('manajemen_web.penelitian', compact('konten', 'penelitian', 'penelitianes'));
    }

    public function katalog(Request $request)
    {
        $konten = Konten::all()->keyBy('key');
        $plantData = app(AdminPlantController::class)->index($request)->getData();

        return view('manajemen_web.katalog', array_merge(['konten' => $konten], $plantData));
    }

    public function berita(Request $permintaan)
    {
        $konten = Konten::all()->keyBy('key');
        $query  = Berita::orderBy('date', 'desc');
        if ($permintaan->has('search')) {
            $query->where('title', 'like', '%' . $permintaan->search . '%')
                  ->orWhere('description', 'like', '%' . $permintaan->search . '%')
                  ->orWhere('category', 'like', '%' . $permintaan->search . '%');
        }
        $berita = $query->paginate(15)->withQueryString();
        $allBerita = $berita;
        return view('manajemen_web.berita', compact('konten', 'berita', 'allBerita'));
    }

    public function galeri(Request $permintaan)
    {
        $konten = Konten::all()->keyBy('key');
        $query  = GaleriAlbum::query();
        if ($permintaan->has('search')) {
            $query->where('title', 'like', '%' . $permintaan->search . '%')
                  ->orWhere('description', 'like', '%' . $permintaan->search . '%');
        }
        $galeri = $query->latest()->paginate(12)->withQueryString();
        return view('manajemen_web.galeri', compact('konten', 'galeri'));
    }

    public function layout()
    {
        $konten = Konten::all()->keyBy('key');
        return view('manajemen_web.layout', compact('konten'));
    }

    public function update(Request $permintaan)
    {
        $data = $permintaan->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            $section = explode('_', $key)[0];
            if ($section === 'navbar' || $section === 'footer') {
                $section = 'layout';
            }

            if ($permintaan->hasFile($key)) {
                $path = $permintaan->file($key)->store('konten', 'public');
                Konten::updateOrCreate(
                    ['key' => $key],
                    ['image_path' => $path, 'type' => 'image', 'section' => $section]
                );
            } elseif (is_array($value)) {
                Konten::updateOrCreate(
                    ['key' => $key],
                    ['value' => json_encode(array_values($value)), 'type' => 'json', 'section' => $section]
                );
            } elseif (is_string($value) || is_null($value)) {
                Konten::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value ?? '', 'type' => 'text', 'section' => $section]
                );
            }
        }

        return redirect()->back()->with('success', 'Konten publik berhasil diperbarui secara instan!');
    }

    // Forwarding methods for WebCrudController
    public function storePoinTentang(Request $request) { return app(WebCrudController::class)->storePoinTentang($request); }
    public function destroyPoinTentang($id) { return app(WebCrudController::class)->destroyPoinTentang($id); }
    public function storePenelitian(Request $request) { return app(WebCrudController::class)->storePenelitian($request); }
    public function updatePenelitian(Request $request, $id) { return app(WebCrudController::class)->updatePenelitian($request, $id); }
    public function destroyPenelitian($id) { return app(WebCrudController::class)->destroyPenelitian($id); }
    public function storeBerita(Request $request) { return app(WebCrudController::class)->storeBerita($request); }
    public function updateBerita(Request $request, $id) { return app(WebCrudController::class)->updateBerita($request, $id); }
    public function destroyBerita($id) { return app(WebCrudController::class)->destroyBerita($id); }
    public function storeGaleri(Request $request) { return app(WebCrudController::class)->storeGaleri($request); }
    public function updateGaleri(Request $request, $id) { return app(WebCrudController::class)->updateGaleri($request, $id); }
    public function destroyGaleri($id) { return app(WebCrudController::class)->destroyGaleri($id); }
    public function storeLatarBeranda(Request $request) { return app(WebCrudController::class)->storeLatarBeranda($request); }
    public function destroyLatarBeranda($id) { return app(WebCrudController::class)->destroyLatarBeranda($id); }
}