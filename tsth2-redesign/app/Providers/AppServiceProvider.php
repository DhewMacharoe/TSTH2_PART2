<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Content;
use App\Models\HomeBackground;
use App\Models\AboutBullet;
use App\Models\Program;
use App\Models\Research;
use App\Models\News;
use App\Models\Gallery;

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
            $programs = Program::all();
            $researches = Research::all();
            $news = News::all();
            $galleries = Gallery::all();

            $getHomeSelection = function($collection) {
                if ($collection->count() <= 4) return $collection;
                $sorted = $collection->sortBy('id')->values();
                $count = $sorted->count();
                $mid = (int) floor($count / 2);
                return collect([
                    $sorted->last(), // terbaru
                    $sorted->get($mid), // tengah 1
                    $sorted->get($mid - 1), // tengah 2
                    $sorted->first() // terlama
                ]);
            };

            $view->with('contents', Content::all()->keyBy('key'))
                 ->with('homeBackgrounds', HomeBackground::all())
                 ->with('aboutBullets', AboutBullet::all())
                 ->with('programs', $programs)
                 ->with('researches', $researches)
                 ->with('news', $news)
                 ->with('galleries', $galleries)
                 ->with('homePrograms', $getHomeSelection($programs))
                 ->with('homeResearches', $getHomeSelection($researches))
                 ->with('homeNews', $getHomeSelection($news))
                 ->with('homeGalleries', $getHomeSelection($galleries));
        });
    }
}
