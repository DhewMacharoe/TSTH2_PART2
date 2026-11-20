<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminPlantController;
use App\Http\Controllers\Admin\DasborController;
use App\Http\Controllers\Admin\GudangBatchController;
use App\Http\Controllers\Admin\GudangController;
use App\Http\Controllers\Admin\KategoriBarangController;
use App\Http\Controllers\Admin\AktivitasGudangController;
use App\Http\Controllers\Admin\PermintaanChatController;
use App\Http\Controllers\Admin\PermintaanController;
use App\Http\Controllers\Admin\PermintaanStatusController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\SatuanController;
use App\Http\Controllers\Admin\WebController;
use App\Http\Controllers\Admin\WebCrudController;
use App\Http\Controllers\Pengunjung\BeritaController;
use App\Http\Controllers\Pengunjung\PublicController;
use App\Http\Controllers\PlantController;
use Illuminate\Support\Facades\Route;

// SSO Hub Auth Bridge & Universal Jump
Route::get('/hub-auth', function (\Illuminate\Http\Request $request) {
    $email = $request->query('email');
    $ts = $request->query('ts');
    $target = $request->query('target', '/web-management/home');
    $sig = $request->query('sig');

    $secret = 'tsth2_shared_secret_hub_auth_2024';
    $expected = hash_hmac('sha256', "{$email}|{$ts}|{$target}", $secret);

    if (abs(time() - (int)$ts) > 300 || !hash_equals($expected, (string)$sig)) {
        return redirect()->route('login');
    }

    $user = \App\Models\User::where('email', $email)->first() ?: \App\Models\User::first();
    \Illuminate\Support\Facades\Auth::login($user, true);
    $request->session()->regenerate();

    return redirect($target);
})->name('hub.auth');

Route::get('/sso-jump', function (\Illuminate\Http\Request $request) {
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }
    $portal = $request->query('portal', 'katalog');
    $target = $request->query('target', '/');
    $secret = 'tsth2_shared_secret_hub_auth_2024';
    $ts = time();
    $sig = hash_hmac('sha256', "{$user->email}|{$ts}|{$target}", $secret);
    $query = http_build_query([
        'email'  => $user->email,
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
})->middleware('auth')->name('sso.jump');

// Pengaturan Bahasa
Route::get('/lang/{locale}', [PublicController::class, 'switchLanguage'])->name('lang.switch');

// Halaman Publik
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/profil-tsth2', [PublicController::class, 'profil'])->name('profil-public');
Route::get('/layanan', [PublicController::class, 'layanan'])->name('layanan');
Route::get('/penelitian', [PublicController::class, 'penelitian'])->name('penelitian');
Route::get('/penelitian/{id}', [PublicController::class, 'penelitianShow'])->name('penelitian.show');
Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/galeri', [PublicController::class, 'galeri'])->name('galeri');

// Katalog Tanaman (Publik) -> Terintegrasi ke Subdomain Katalog (:8003)
Route::get('/katalog-tanaman', function () {
    return redirect()->away(rtrim(config('app.url_katalog', env('URL_KATALOG', 'http://localhost:8003')), '/'));
})->name('katalog');

Route::get('/katalog-tanaman/koleksi', function () {
    return redirect()->away(rtrim(config('app.url_katalog', env('URL_KATALOG', 'http://localhost:8003')), '/') . '/collection');
})->name('collection');

Route::get('/katalog-tanaman/tanaman/{slug}', function ($slug) {
    return redirect()->away(rtrim(config('app.url_katalog', env('URL_KATALOG', 'http://localhost:8003')), '/') . '/plant/' . $slug);
})->name('plant.show');

// Dashboard Utama
Route::get('/dasbor', [DasborController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dasbor');

// Manajemen Web
Route::middleware(['auth', 'role:web_admin'])->group(function () {
    Route::get('/web-management/home',       [WebController::class, 'home'])->name('web.management.home');
    Route::get('/web-management/penelitian', [WebController::class, 'penelitian'])->name('web.management.penelitian');
    // Katalog Tanaman telah dipindahkan sepenuhnya ke Port 8003 (Katalog Flora) dengan SSO Otomatis
    Route::get('/web-management/katalog', function () {
        $secret = 'tsth2_shared_secret_hub_auth_2024';
        $ts = time();
        $target = '/admin/plants';
        $email = auth()->user()->email ?? 'admin@tsth2.id';
        $sig = hash_hmac('sha256', "{$email}|{$ts}|{$target}", $secret);
        $urlKatalog = \App\Services\TsthUrl::katalog();
        $query = http_build_query([
            'email'  => $email,
            'ts'     => $ts,
            'target' => $target,
            'sig'    => $sig,
        ]);
        return redirect()->away($urlKatalog . '/hub-auth?' . $query);
    })->name('web.management.katalog');
    Route::get('/web_management/katalog', function () {
        return redirect()->route('web.management.katalog');
    });
    Route::get('/web-management/berita',     [WebController::class, 'berita'])->name('web.management.berita');
    Route::get('/web-management/galeri',     [WebController::class, 'galeri'])->name('web.management.galeri');
    Route::get('/web-management/layout',     [WebController::class, 'layout'])->name('web.management.layout');
    Route::put('/web-management',            [WebController::class, 'update'])->name('web.management.update');

    Route::resource('admin/plants', AdminPlantController::class)->except(['show'])->names('admin.plants');
    Route::resource('admin/categories', AdminCategoryController::class)->except(['create', 'show', 'destroy'])->names('admin.categories');

    Route::post('/web-management/home-backgrounds',            [WebCrudController::class, 'storeLatarBeranda'])->name('web.management.home_bg.store');
    Route::delete('/web-management/home-backgrounds/{id}',     [WebCrudController::class, 'destroyLatarBeranda'])->name('web.management.home_bg.destroy');
    Route::post('/web-management/about-bullets',               [WebCrudController::class, 'storePoinTentang'])->name('web.management.about.store');
    Route::delete('/web-management/about-bullets/{id}',        [WebCrudController::class, 'destroyPoinTentang'])->name('web.management.about.destroy');
    Route::post('/web-management/penelitian',                  [WebCrudController::class, 'storePenelitian'])->name('web.management.penelitian.store');
    Route::put('/web-management/penelitian/{id}',              [WebCrudController::class, 'updatePenelitian'])->name('web.management.penelitian.update');
    Route::delete('/web-management/penelitian/{id}',           [WebCrudController::class, 'destroyPenelitian'])->name('web.management.penelitian.destroy');
    Route::post('/web-management/berita',                      [WebCrudController::class, 'storeBerita'])->name('web.management.berita.store');
    Route::put('/web-management/berita/{id}',                  [WebCrudController::class, 'updateBerita'])->name('web.management.berita.update');
    Route::delete('/web-management/berita/{id}',               [WebCrudController::class, 'destroyBerita'])->name('web.management.berita.destroy');
    Route::post('/web-management/galeri',                      [WebCrudController::class, 'storeGaleri'])->name('web.management.galeri.store');
    Route::put('/web-management/galeri/{id}',                  [WebCrudController::class, 'updateGaleri'])->name('web.management.galeri.update');
    Route::delete('/web-management/galeri/{id}',               [WebCrudController::class, 'destroyGaleri'])->name('web.management.galeri.destroy');
    Route::delete('/web-management/galeri/{id}/photo/{index}', [WebCrudController::class, 'destroyPhoto'])->name('web.management.galeri.destroyPhoto');
});

// Gudang (PIC Warehouse Gudang GA, PIC Lab RdI, & Web Admin)
Route::middleware(['auth', 'role:pic_gudang|pic_lab|web_admin'])->group(function () {
    Route::post('/gudang/kategori',              [KategoriBarangController::class, 'store'])->name('gudang.kategori.store');
    Route::delete('/gudang/kategori/{id}',       [KategoriBarangController::class, 'destroy'])->name('gudang.kategori.destroy');

    Route::get('/gudang/satuan',                 [SatuanController::class, 'index'])->name('satuan.index');
    Route::post('/gudang/satuan',                [SatuanController::class, 'store'])->name('satuan.store');
    Route::delete('/gudang/satuan/{id}',         [SatuanController::class, 'destroy'])->name('satuan.destroy');

    Route::post('/gudang/satuan/konversi',       [SatuanController::class, 'storeKonversi'])->name('satuan.konversi.store');
    Route::delete('/gudang/satuan/konversi/{id}',[SatuanController::class, 'destroyKonversi'])->name('satuan.konversi.destroy');

    Route::get('/gudang/create',                 [GudangController::class, 'create'])->name('gudang.create');
    Route::post('/gudang',                       [GudangController::class, 'store'])->name('gudang.store');
    Route::get('/gudang/{id}/edit',              [GudangController::class, 'edit'])->name('gudang.edit');
    Route::put('/gudang/{id}',                   [GudangController::class, 'update'])->name('gudang.update');
    Route::delete('/gudang/{id}',                [GudangController::class, 'destroy'])->name('gudang.destroy');

    Route::post('/gudang/{id}/batch',            [GudangBatchController::class, 'addBatch'])->name('gudang.batch.store');
    Route::post('/gudang/batch/{batch_no}/item', [GudangBatchController::class, 'addItemToBatch'])->name('gudang.batch.item.store');
    Route::delete('/gudang/batch/{batch_id}',    [GudangBatchController::class, 'destroyBatch'])->name('gudang.batch.destroy');
    Route::delete('/gudang/batch-item/{item_id}',[GudangBatchController::class, 'destroyBatchItem'])->name('gudang.batch.item.destroy');
});

// Gudang (Semua Pengguna Terautentikasi - Lihat Saja)
Route::middleware(['auth'])->group(function () {
    Route::get('/gudang',                [GudangController::class, 'index'])->name('gudang');
    Route::get('/gudang/aktivitas',      [AktivitasGudangController::class, 'index'])->name('gudang.aktivitas');
    Route::get('/gudang/export',         [GudangController::class, 'export'])->name('gudang.export');
    Route::get('/api/satuan/{kategori}', [SatuanController::class, 'byKategori'])->name('satuan.by.kategori');
    Route::get('/gudang/{id}',           [GudangController::class, 'show'])->name('gudang.show');
});

// Permintaan Barang
Route::middleware(['auth'])->group(function () {
    Route::get('/permintaan',             [PermintaanController::class, 'index'])->name('permintaan.index');
    Route::get('/permintaan/create',      [PermintaanController::class, 'create'])->name('permintaan.create');
    Route::post('/permintaan',            [PermintaanController::class, 'store'])->name('permintaan.store');
    Route::put('/permintaan/{id}/status', [PermintaanStatusController::class, 'updateStatus'])->name('permintaan.status.update');
    Route::get('/permintaan/{id}/chat',   [PermintaanChatController::class, 'getChat'])->name('permintaan.chat.get');
    Route::post('/permintaan/{id}/chat',  [PermintaanChatController::class, 'storeChat'])->name('permintaan.chat.store');
});

// Pintasan & Integrasi Layanan Terpisah
Route::redirect('/admin', '/dasbor')->name('admin.shortcut');
Route::redirect('/admin/login', '/login')->name('admin.login.shortcut');
Route::redirect('/gudang/login', '/login')->name('gudang.login.shortcut');

Route::middleware(['auth'])->group(function () {
    Route::get('/catatan', [DasborController::class, 'logbook'])->name('catatan');
});

// Profil Akun
Route::middleware('auth')->group(function () {
    Route::get('/profil',    [ProfilController::class, 'edit'])->name('profil.edit');
    Route::patch('/profil',  [ProfilController::class, 'update'])->name('profil.update');
    Route::delete('/profil', [ProfilController::class, 'destroy'])->name('profil.destroy');
});

require __DIR__.'/auth.php';