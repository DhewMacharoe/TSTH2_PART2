<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Konten;
use App\Models\Penelitian;
use App\Models\Berita;
use App\Models\GaleriAlbum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // ── Penelitian ────────────────────────────────────
            $penelitianQuery = Penelitian::query();
            if (request()->routeIs('penelitian') && request('search')) {
                $search = request('search');
                $penelitianQuery->where('title', 'like', "%{$search}%")
                                ->orWhere('description', 'like', "%{$search}%");
            }
            $penelitianes = $penelitianQuery->get();

            // ── Berita Publik (hanya published) ───────────────
            $beritaQuery = Berita::published()->orderBy('date', 'desc');
            if (request()->routeIs('berita') && request('search')) {
                $search = request('search');
                $beritaQuery->where('title', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('category', 'like', "%{$search}%");
            }
            $berita = $beritaQuery->get();

            // ── Berita Admin (published + draft) ──────────────
            $allBeritaQuery = Berita::orderBy('date', 'desc');
            if (request()->routeIs('web.management.berita') && request('search')) {
                $search = request('search');
                $allBeritaQuery->where('title', 'like', "%{$search}%")
                               ->orWhere('description', 'like', "%{$search}%")
                               ->orWhere('category', 'like', "%{$search}%");
            }
            $allBerita = $allBeritaQuery->get();

            // ── Galeri ────────────────────────────────────────
            $galeriQuery = GaleriAlbum::query();
            if ((request()->routeIs('galeri') || request()->routeIs('web.management.galeri')) && request('search')) {
                $search = request('search');
                $galeriQuery->where('title', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
            }
            $galeri = $galeriQuery->latest()->get();

            // ── Helper: pilih 4 item representatif ───────────
            $getHomeSelection = function ($collection) {
                if ($collection->count() <= 4) return $collection;
                $sorted = $collection->sortBy('id')->values();
                $count  = $sorted->count();
                $mid    = (int) floor($count / 2);
                return collect([
                    $sorted->last(),        // terbaru
                    $sorted->get($mid),     // tengah 1
                    $sorted->get($mid - 1), // tengah 2
                    $sorted->first()        // terlama
                ]);
            };

            $view->with('konten',            Konten::all()->keyBy('key'))
                 ->with('homeBackgrounds',   Konten::where('section', 'home_background')->get())
                 ->with('aboutBullets',      Konten::where('section', 'about_bullet')->get())
                 ->with('penelitianes',      $penelitianes)
                 ->with('berita',            $berita)      // publik: published only
                 ->with('allBerita',         $allBerita)   // admin: semua termasuk draft
                 ->with('galeri',            $galeri)
                 ->with('homePenelitianes',  $getHomeSelection($penelitianes))
                 ->with('homeBerita',        $getHomeSelection($berita))
                 ->with('homeGalleries',     $getHomeSelection($galeri));
        });
    }
}
