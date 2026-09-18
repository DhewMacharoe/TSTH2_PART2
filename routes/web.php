<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;
use App\Support\NewsArticles;

// Public Pages
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/program', function () { return view('pages.program'); })->name('program');
Route::get('/research', function () { return view('pages.research'); })->name('research');
Route::get('/news', function () { return view('pages.news'); })->name('news');
Route::get('/news/{slug}', function (string $slug) {
    $article = NewsArticles::find($slug);
    abort_if(!$article, 404);
    if (!is_file(public_path($article['image'])) && isset($article['fallback_image'])) {
        $article['image'] = $article['fallback_image'];
    }
    return view('pages.news-show', compact('article'));
})->name('news.show');
Route::get('/gallery', function () { return view('pages.gallery'); })->name('gallery');

Route::get('/katalog-tanaman', function () {
    return view('pages.katalog');
})->name('katalog');

// Dashboard (Breeze Default)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\WebController;

// Web Management Routes
Route::middleware(['auth', 'role:web_admin'])->group(function () {
    Route::get('/web-management/home', [WebController::class, 'home'])->name('web.management.home');
    Route::get('/web-management/program', [WebController::class, 'program'])->name('web.management.program');
    Route::get('/web-management/research', [WebController::class, 'research'])->name('web.management.research');
    Route::get('/web-management/katalog', [WebController::class, 'katalog'])->name('web.management.katalog');
    Route::get('/web-management/news', [WebController::class, 'news'])->name('web.management.news');
    Route::get('/web-management/gallery', [WebController::class, 'gallery'])->name('web.management.gallery');
    Route::get('/web-management/layout', [WebController::class, 'layout'])->name('web.management.layout');
    Route::put('/web-management', [WebController::class, 'update'])->name('web.management.update');

    // Dynamic CMS Routes
    Route::post('/web-management/home-backgrounds', [WebController::class, 'storeHomeBackground'])->name('web.management.home_bg.store');
    Route::delete('/web-management/home-backgrounds/{id}', [WebController::class, 'destroyHomeBackground'])->name('web.management.home_bg.destroy');

    Route::post('/web-management/about-bullets', [WebController::class, 'storeAboutBullet'])->name('web.management.about.store');
    Route::delete('/web-management/about-bullets/{id}', [WebController::class, 'destroyAboutBullet'])->name('web.management.about.destroy');

    Route::post('/web-management/programs', [WebController::class, 'storeProgram'])->name('web.management.programs.store');
    Route::delete('/web-management/programs/{id}', [WebController::class, 'destroyProgram'])->name('web.management.programs.destroy');

    Route::post('/web-management/researches', [WebController::class, 'storeResearch'])->name('web.management.researches.store');
    Route::delete('/web-management/researches/{id}', [WebController::class, 'destroyResearch'])->name('web.management.researches.destroy');

    Route::post('/web-management/news', [WebController::class, 'storeNews'])->name('web.management.news.store');
    Route::delete('/web-management/news/{id}', [WebController::class, 'destroyNews'])->name('web.management.news.destroy');

    Route::post('/web-management/galleries', [WebController::class, 'storeGallery'])->name('web.management.galleries.store');
    Route::delete('/web-management/galleries/{id}', [WebController::class, 'destroyGallery'])->name('web.management.galleries.destroy');

});

// Warehouse Routes (Mutations - PIC Only)
Route::middleware(['auth', 'role:pic_warehouse'])->group(function () {
    Route::get('/warehouse/create', [WarehouseController::class, 'create'])->name('warehouse.create');
    Route::post('/warehouse', [WarehouseController::class, 'store'])->name('warehouse.store');
    Route::get('/warehouse/{id}/edit', [WarehouseController::class, 'edit'])->name('warehouse.edit');
    Route::put('/warehouse/{id}', [WarehouseController::class, 'update'])->name('warehouse.update');
    Route::delete('/warehouse/{id}', [WarehouseController::class, 'destroy'])->name('warehouse.destroy');
    Route::post('/warehouse/{id}/batch', [WarehouseController::class, 'addBatch'])->name('warehouse.batch.store');
    Route::delete('/warehouse/batch/{batch_id}', [WarehouseController::class, 'destroyBatch'])->name('warehouse.batch.destroy');
});

// Warehouse Routes (View & Export - PIC, Warehouse & Agronom)
Route::middleware(['auth', 'role:pic_warehouse,warehouse,agronom'])->group(function () {
    Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse');
    Route::get('/warehouse/export', [WarehouseController::class, 'export'])->name('warehouse.export');
    Route::get('/warehouse/{id}', [WarehouseController::class, 'show'])->name('warehouse.show');
});

use App\Http\Controllers\DivisionChatController;

// Division Chat Routes (PIC & Warehouse)
Route::middleware(['auth', 'role:pic_warehouse,warehouse'])->group(function () {
    Route::get('/division-chat', [DivisionChatController::class, 'index'])->name('division-chat.index');
    Route::post('/division-chat', [DivisionChatController::class, 'store'])->name('division-chat.store');
    Route::post('/division-chat/read', [DivisionChatController::class, 'markAsRead'])->name('division-chat.read');
    Route::get('/division-chat/unread', [DivisionChatController::class, 'unreadCount'])->name('division-chat.unread');
});

// Permintaan Barang Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/request', [RequestController::class, 'index'])->name('request.index');
    Route::post('/request', [RequestController::class, 'store'])->name('request.store');
    Route::put('/request/{id}/status', [RequestController::class, 'updateStatus'])->name('request.status.update');
    Route::put('/request/{id}/assign', [RequestController::class, 'assign'])->name('request.assign');
    Route::get('/request/{id}/chat', [RequestController::class, 'getChat'])->name('request.chat.get');
    Route::post('/request/{id}/chat', [RequestController::class, 'storeChat'])->name('request.chat.store');
    Route::get('/request/{id}/status', function() { return redirect()->route('request.index'); });
});

// Logbook Karyawan (Semua kecuali web_admin)
Route::middleware(['auth', 'role:pic_warehouse,warehouse,agronom,pimpinan'])->group(function () {
    Route::get('/logbook', function() {
        return view('logbook.index');
    })->name('logbook');
});

// Profile Routes (Breeze Default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
