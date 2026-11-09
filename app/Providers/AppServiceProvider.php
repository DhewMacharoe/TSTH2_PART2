<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
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
        Paginator::useTailwind();

        // Global View Composer: bagikan konten untuk navbar, footer, dan layout umum
        View::composer('*', function ($view) {
            try {
                $view->with('konten', Konten::all()->keyBy('key'));
            } catch (\Throwable $e) {
                $view->with('konten', collect([]));
            }
        });

        // View Composer khusus Halaman Beranda (Home)
        View::composer('pengunjung.home', function ($view) {
            try {
                $kontenAll = Konten::all()->keyBy('key');

                // Helper: ambil kurasi admin (maks 6), atau isi otomatis hingga 6 item terbaru
                $getHomeSelection = function ($collection, $selectedJson) {
                    $selectedIds = [];
                    if (!empty($selectedJson)) {
                        $decoded = json_decode($selectedJson, true);
                        if (is_array($decoded)) {
                            $selectedIds = array_map('intval', $decoded);
                        }
                    }

                    if (!empty($selectedIds)) {
                        // Ambil item yang dipilih sesuai urutan
                        $chosen = collect($selectedIds)->map(function ($id) use ($collection) {
                            return $collection->firstWhere('id', $id);
                        })->filter()->values();

                        // Jika kurang dari 6, lengkapi dengan item terbaru lainnya hingga 6
                        if ($chosen->count() < 6) {
                            $chosenIds = $chosen->pluck('id')->all();
                            $remaining = $collection->reject(fn($item) => in_array($item->id, $chosenIds))->take(6 - $chosen->count());
                            $chosen = $chosen->concat($remaining)->values();
                        }
                        return $chosen->take(6);
                    }

                    // Default: 6 item terbaru
                    return $collection->take(6);
                };

                $penelitianes = Penelitian::latest()->get();
                $berita       = Berita::published()->orderBy('date', 'desc')->get();
                $galeri       = GaleriAlbum::latest()->get();

                $selPenelitian = $kontenAll['home_selected_penelitian']->value ?? null;
                $selBerita     = $kontenAll['home_selected_berita']->value ?? null;
                $selGaleri     = $kontenAll['home_selected_galeri']->value ?? null;

                $view->with('homeBackgrounds',  Konten::where('section', 'home_background')->get())
                     ->with('aboutBullets',     Konten::where('section', 'about_bullet')->get())
                     ->with('homePenelitianes', $getHomeSelection($penelitianes, $selPenelitian))
                     ->with('homeBerita',       $getHomeSelection($berita, $selBerita))
                     ->with('homeGalleries',    $getHomeSelection($galeri, $selGaleri));
            } catch (\Throwable $e) {
                $view->with('homeBackgrounds', collect([]))
                     ->with('aboutBullets', collect([]))
                     ->with('homePenelitianes', collect([]))
                     ->with('homeBerita', collect([]))
                     ->with('homeGalleries', collect([]));
            }
        });
    }
}
