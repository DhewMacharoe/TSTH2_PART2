<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Hapus percakapan forum yang sudah kadaluarsa setiap hari jam 02:00
Schedule::command('gudang:cleanup-forums')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground();

