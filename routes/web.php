<?php

use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\PermintaanController;
use App\Http\Controllers\Admin\PermintaanStatusController;
use App\Http\Controllers\Admin\PermintaanChatController;
use App\Http\Controllers\Admin\SatuanController;
use App\Http\Controllers\Admin\GudangController;
use App\Http\Controllers\Admin\GudangBatchController;
use App\Http\Controllers\Admin\DasborController;
use App\Http\Controllers\Admin\WebController;
use App\Http\Controllers\Admin\WebCrudController;
use App\Http\Controllers\Admin\AdminPlantController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\Pengunjung\BeritaController;
use App\Http\Controllers\Pengunjung\PublicController;
use Illuminate\Support\Facades\Route;
use App\Support\BeritaArticles;

// ═══════════════════════════════════════════════
// Public Pages
// ═══════════════════════════════════════════════
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/profil-tsth2', [PublicController::class, 'profil'])->name('profil-public');
Route::get('/layanan', [PublicController::class, 'layanan'])->name('layanan');
Route::get('/penelitian', [PublicController::class, 'penelitian'])->name('penelitian');
Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/berita/{slug}', function (string $slug) {
    $article = BeritaArticles::find($slug);
    abort_if(!$article, 404);
    if (!is_file(public_path($article['image'])) && isset($article['fallback_image'])) {
        $article['image'] = $article['fallback_image'];
    }
    return view('pengunjung.berita-show', compact('article'));
})->name('berita.show');
Route::get('/galeri', [PublicController::class, 'galeri'])->name('galeri');

// ═══════════════════════════════════════════════
// Katalog Tanaman (Public)
// ═══════════════════════════════════════════════
Route::get('/katalog-tanaman', [PlantController::class, 'index'])->name('katalog');
Route::get('/katalog-tanaman/koleksi', [PlantController::class, 'collection'])->name('collection');
Route::get('/katalog-tanaman/tanaman/{plant:slug}', [PlantController::class, 'show'])->name('plant.show');

// ═══════════════════════════════════════════════
// Dashboard
// ═══════════════════════════════════════════════
Route::get('/dasbor', [DasborController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dasbor');

// ═══════════════════════════════════════════════
// Web Management (web_admin only)
// ═══════════════════════════════════════════════

Route::middleware(['auth', 'role:web_admin'])->group(function () {
    Route::get('/web-management/home',     [WebController::class, 'home'])->name('web.management.home');
    Route::get('/web-management/penelitian', [WebController::class, 'penelitian'])->name('web.management.penelitian');
    Route::get('/web-management/katalog',  [WebController::class, 'katalog'])->name('web.management.katalog');
    Route::get('/web-management/berita',     [WebController::class, 'berita'])->name('web.management.berita');
    Route::get('/web-management/galeri',  [WebController::class, 'galeri'])->name('web.management.galeri');
    Route::get('/web-management/layout',   [WebController::class, 'layout'])->name('web.management.layout');
    Route::put('/web-management',          [WebController::class, 'update'])->name('web.management.update');

    // Admin Katalog Tanaman
    Route::resource('admin/plants', AdminPlantController::class)->except(['show'])->names('admin.plants');
    Route::resource('admin/categories', AdminCategoryController::class)->except(['create', 'show', 'destroy'])->names('admin.categories');

    Route::post('/web-management/home-backgrounds',       [WebCrudController::class, 'storeLatarBeranda'])->name('web.management.home_bg.store');
    Route::delete('/web-management/home-backgrounds/{id}',[WebCrudController::class, 'destroyLatarBeranda'])->name('web.management.home_bg.destroy');
    Route::post('/web-management/about-bullets',          [WebCrudController::class, 'storePoinTentang'])->name('web.management.about.store');
    Route::delete('/web-management/about-bullets/{id}',   [WebCrudController::class, 'destroyPoinTentang'])->name('web.management.about.destroy');
    Route::post('/web-management/penelitian',             [WebCrudController::class, 'storePenelitian'])->name('web.management.penelitian.store');
    Route::put('/web-management/penelitian/{id}',         [WebCrudController::class, 'updatePenelitian'])->name('web.management.penelitian.update');
    Route::delete('/web-management/penelitian/{id}',      [WebCrudController::class, 'destroyPenelitian'])->name('web.management.penelitian.destroy');
    Route::post('/web-management/berita',                  [WebCrudController::class, 'storeBerita'])->name('web.management.berita.store');
    Route::put('/web-management/berita/{id}',              [WebCrudController::class, 'updateBerita'])->name('web.management.berita.update');
    Route::delete('/web-management/berita/{id}',           [WebCrudController::class, 'destroyBerita'])->name('web.management.berita.destroy');
    
    // Galeri CRUD & Hapus Foto Satuan
    Route::post('/web-management/galeri',                  [WebCrudController::class, 'storeGaleri'])->name('web.management.galeri.store');
    Route::put('/web-management/galeri/{id}',              [WebCrudController::class, 'updateGaleri'])->name('web.management.galeri.update');
    Route::delete('/web-management/galeri/{id}',           [WebCrudController::class, 'destroyGaleri'])->name('web.management.galeri.destroy');
    Route::delete('/web-management/galeri/{id}/photo/{index}', [WebCrudController::class, 'destroyPhoto'])->name('web.management.galeri.destroyPhoto');
});

// ═══════════════════════════════════════════════
// Warehouse — PIC Gudang only (mutations)
// ═══════════════════════════════════════════════
Route::middleware(['auth', 'role:pic_gudang'])->group(function () {
    // --- Master Satuan Barang ---
    Route::get('/gudang/satuan',        [SatuanController::class, 'index'])->name('satuan.index');
    Route::post('/gudang/satuan',       [SatuanController::class, 'store'])->name('satuan.store');
    Route::delete('/gudang/satuan/{id}',[SatuanController::class, 'destroy'])->name('satuan.destroy');

    // --- Konversi Satuan ---
    Route::post('/gudang/satuan/konversi',       [SatuanController::class, 'storeKonversi'])->name('satuan.konversi.store');
    Route::delete('/gudang/satuan/konversi/{id}',[SatuanController::class, 'destroyKonversi'])->name('satuan.konversi.destroy');

    // --- Barang CRUD ---
    Route::get('/gudang/create',              [GudangController::class, 'create'])->name('gudang.create');
    Route::post('/gudang',                    [GudangController::class, 'store'])->name('gudang.store');
    Route::get('/gudang/{id}/edit',         [GudangController::class, 'edit'])->name('gudang.edit');
    Route::put('/gudang/{id}',                [GudangController::class, 'update'])->name('gudang.update');
    Route::delete('/gudang/{id}',             [GudangController::class, 'destroy'])->name('gudang.destroy');

    // --- Batch ---
    Route::post('/gudang/{id}/batch',          [GudangBatchController::class, 'addBatch'])->name('gudang.batch.store');
    Route::post('/gudang/batch/{batch_no}/item', [GudangBatchController::class, 'addItemToBatch'])->name('gudang.batch.item.store');
    Route::delete('/gudang/batch/{batch_id}',    [GudangBatchController::class, 'destroyBatch'])->name('gudang.batch.destroy');
    Route::delete('/gudang/batch-item/{item_id}',[GudangBatchController::class, 'destroyBatchItem'])->name('gudang.batch.item.destroy');
});

// ═══════════════════════════════════════════════
// Warehouse — semua user auth (view only)
// ═══════════════════════════════════════════════
Route::middleware(['auth'])->group(function () {
    Route::get('/gudang',        [GudangController::class, 'index'])->name('gudang');
    Route::get('/gudang/export', [GudangController::class, 'export'])->name('gudang.export');
    Route::get('/api/satuan/{kategori}', [SatuanController::class, 'byKategori'])->name('satuan.by.kategori');
    Route::get('/gudang/{id}',   [GudangController::class, 'show'])->name('gudang.show');
});

// ═══════════════════════════════════════════════
// Permintaan Barang (semua user auth)
// ═══════════════════════════════════════════════
Route::middleware(['auth'])->group(function () {
    Route::get('/permintaan',        [PermintaanController::class, 'index'])->name('permintaan.index');
    Route::get('/permintaan/create', [PermintaanController::class, 'create'])->name('permintaan.create');
    Route::post('/permintaan',       [PermintaanController::class, 'store'])->name('permintaan.store');
    Route::put('/permintaan/{id}/status', [PermintaanStatusController::class, 'updateStatus'])->name('permintaan.status.update');
    Route::get('/permintaan/{id}/chat',   [PermintaanChatController::class, 'getChat'])->name('permintaan.chat.get');
    Route::post('/permintaan/{id}/chat',  [PermintaanChatController::class, 'storeChat'])->name('permintaan.chat.store');
});

// ═══════════════════════════════════════════════
// Logbook Karyawan
// ═══════════════════════════════════════════════
Route::middleware(['auth', 'role:pic_gudang,agronom,pimpinan'])->group(function () {
    Route::get('/catatan', fn() => view('catatan.index'))->name('catatan');
});

// ═══════════════════════════════════════════════
// Profile (Breeze)
// ═══════════════════════════════════════════════
Route::middleware('auth')->group(function () {
    Route::get('/profil',    [ProfilController::class, 'edit'])->name('profil.edit');
    Route::patch('/profil',  [ProfilController::class, 'update'])->name('profil.update');
    Route::delete('/profil', [ProfilController::class, 'destroy'])->name('profil.destroy');
});

require __DIR__.'/auth.php';