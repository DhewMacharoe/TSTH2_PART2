<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\AdminPlantController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\FileController; // <-- Tambahkan import ini

// ==========================================
// RUTE PUBLIK (Bisa diakses pengunjung)
// ==========================================

// SSO Jump Helper
Route::get('/sso-jump', function (\Illuminate\Http\Request $request) {
    $portal = $request->query('portal', 'pengunjung');
    $target = $request->query('target', '/');
    $secret = 'tsth2_shared_secret_hub_auth_2024';
    $ts = time();
    $email = 'admin@tsth2.id';
    $sig = hash_hmac('sha256', "{$email}|{$ts}|{$target}", $secret);
    $query = http_build_query([
        'email'  => $email,
        'ts'     => $ts,
        'target' => $target,
        'sig'    => $sig,
    ]);

    $baseUrl = match($portal) {
        'logbook'    => \App\Services\TsthUrl::logbook(),
        'warehouse'  => \App\Services\TsthUrl::gudang(),
        'katalog'    => \App\Services\TsthUrl::katalog(),
        'pengunjung' => \App\Services\TsthUrl::pengunjung(),
        default      => url('/'),
    };

    return redirect()->away($baseUrl . '/hub-auth?' . $query);
})->name('sso.jump');

// SSO Hub Auth Bridge dari Portal Hub
Route::get('/hub-auth', function (\Illuminate\Http\Request $request) {
    $email = $request->query('email');
    $ts = $request->query('ts');
    $target = $request->query('target', '/admin/plants');
    $sig = $request->query('sig');

    $secret = 'tsth2_shared_secret_hub_auth_2024';
    $expected = hash_hmac('sha256', "{$email}|{$ts}|{$target}", $secret);

    if (abs(time() - (int)$ts) > 300 || !hash_equals($expected, (string)$sig)) {
        return redirect()->route('admin.login');
    }

    $user = \App\Models\User::where('email', $email)->first() ?: \App\Models\User::first();
    $request->session()->regenerate();
    $request->session()->put('admin_authenticated', true);
    $request->session()->put('admin_user_id', $user?->id ?? 1);

    return redirect($target);
})->name('hub.auth');

// Switch Bahasa (EN / ID)
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
        cookie()->queue(cookie('site_pref_lang', $locale, 60 * 24 * 365, '/'));
    }
    return redirect()->back(fallback: route('home'));
})->name('lang.switch');

// Halaman katalog (index)
Route::get('/', [PlantController::class, 'index'])->name('home');
Route::get('/collection', [PlantController::class, 'collection'])->name('collection');

// Halaman detail tanaman (show)
Route::get('/plant/{plant:slug}', [PlantController::class, 'show'])->name('plant.show');
Route::get('/plant/qr/{token}', [PlantController::class, 'showByQr'])->name('plant.qr');

// RUTE PROXY FILEBASE (Wajib di luar grup admin)
Route::get('/storage-filebase/{path}', [FileController::class, 'show'])->where('path', '.*')->name('filebase.show');


// ==========================================
// RUTE ADMIN (Wajib Login)
// ==========================================

Route::get('/admin/login', [AdminPlantController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminPlantController::class, 'authenticate'])->name('admin.authenticate');

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::post('/logout', [AdminPlantController::class, 'logout'])->name('logout');
    
    // Route CRUD untuk Tanaman
    Route::get('/plants/export', [AdminPlantController::class, 'export'])->name('plants.export');
    Route::resource('plants', AdminPlantController::class)->except(['show']);
    Route::get('/plants/{plant}/qr', [AdminPlantController::class, 'qr'])->name('plants.qr');
    Route::get('/plants/{plant}/qr/download', [AdminPlantController::class, 'qrDownload'])->name('plants.qr.download');
    Route::get('/plants/{plant}/qr/print', [AdminPlantController::class, 'qrPrint'])->name('plants.qr.print');
    Route::get('/plants/{plant}/history', [AdminPlantController::class, 'history'])->name('plants.history');
    Route::post('/plants/{plant}/population', [AdminPlantController::class, 'updatePopulation'])->name('plants.population.update');
    
    // Route CRUD untuk Kategori
    Route::resource('categories', AdminCategoryController::class)->except(['create', 'show']);
});