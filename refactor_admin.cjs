const fs = require('fs');
const path = require('path');

const basePath = 'e:/magang/tsth2-redesign';

// 1. Update WebController.php
const controllerPath = path.join(basePath, 'app', 'Http', 'Controllers', 'WebController.php');

const controllerContent = `<?php

namespace App\\Http\\Controllers;

use App\\Models\\Content;
use App\\Models\\AboutBullet;
use App\\Models\\Program;
use App\\Models\\Research;
use App\\Models\\News;
use App\\Models\\Gallery;
use Illuminate\\Http\\Request;

class WebController extends Controller
{
    public function home()
    {
        $contents = Content::all()->keyBy('key');
        $aboutBullets = AboutBullet::all();
        return view('web_management.home', compact('contents', 'aboutBullets'));
    }

    public function program()
    {
        $contents = Content::all()->keyBy('key');
        $programs = Program::all();
        return view('web_management.program', compact('contents', 'programs'));
    }

    public function research()
    {
        $contents = Content::all()->keyBy('key');
        $researches = Research::all();
        return view('web_management.research', compact('contents', 'researches'));
    }

    public function katalog()
    {
        $contents = Content::all()->keyBy('key');
        return view('web_management.katalog', compact('contents'));
    }

    public function news()
    {
        $contents = Content::all()->keyBy('key');
        $news = News::all();
        return view('web_management.news', compact('contents', 'news'));
    }

    public function gallery()
    {
        $contents = Content::all()->keyBy('key');
        $galleries = Gallery::all();
        return view('web_management.gallery', compact('contents', 'galleries'));
    }

    public function layout()
    {
        $contents = Content::all()->keyBy('key');
        return view('web_management.layout', compact('contents'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            $section = explode('_', $key)[0];
            if ($section === 'navbar' || $section === 'footer') {
                $section = 'layout';
            }

            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('contents', 'public');
                Content::updateOrCreate(
                    ['key' => $key],
                    ['image_path' => $path, 'type' => 'image', 'section' => $section]
                );
            } elseif (is_string($value) || is_null($value)) {
                Content::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value ?? '', 'type' => 'text', 'section' => $section]
                );
            }
        }

        return redirect()->back()->with('success', 'Konten publik berhasil diperbarui secara instan!');
    }

    // Dynamic CRUD Methods
    public function storeAboutBullet(Request $request) {
        AboutBullet::create($request->validate(['content' => 'required']));
        return back()->with('success', 'Visi Misi ditambahkan!');
    }
    public function destroyAboutBullet($id) {
        AboutBullet::findOrFail($id)->delete();
        return back()->with('success', 'Visi Misi dihapus!');
    }

    public function storeProgram(Request $request) {
        Program::create($request->validate(['icon' => 'nullable', 'title' => 'required', 'description' => 'required']));
        return back()->with('success', 'Program ditambahkan!');
    }
    public function destroyProgram($id) {
        Program::findOrFail($id)->delete();
        return back()->with('success', 'Program dihapus!');
    }

    public function storeResearch(Request $request) {
        $data = $request->validate(['icon' => 'nullable', 'title' => 'required', 'description' => 'required', 'image_path' => 'nullable|image']);
        if ($request->hasFile('image_path')) $data['image_path'] = $request->file('image_path')->store('contents', 'public');
        Research::create($data);
        return back()->with('success', 'Penelitian ditambahkan!');
    }
    public function destroyResearch($id) {
        Research::findOrFail($id)->delete();
        return back()->with('success', 'Penelitian dihapus!');
    }

    public function storeNews(Request $request) {
        $data = $request->validate(['category' => 'required', 'title' => 'required', 'description' => 'required', 'date' => 'nullable', 'image_path' => 'nullable|image']);
        if ($request->hasFile('image_path')) $data['image_path'] = $request->file('image_path')->store('contents', 'public');
        News::create($data);
        return back()->with('success', 'Berita ditambahkan!');
    }
    public function destroyNews($id) {
        News::findOrFail($id)->delete();
        return back()->with('success', 'Berita dihapus!');
    }

    public function storeGallery(Request $request) {
        $data = $request->validate(['title' => 'required', 'description' => 'nullable', 'image_path' => 'required|image']);
        if ($request->hasFile('image_path')) $data['image_path'] = $request->file('image_path')->store('contents', 'public');
        Gallery::create($data);
        return back()->with('success', 'Galeri ditambahkan!');
    }
    public function destroyGallery($id) {
        Gallery::findOrFail($id)->delete();
        return back()->with('success', 'Galeri dihapus!');
    }
}
`;
fs.writeFileSync(controllerPath, controllerContent);

// 2. Update routes/web.php
const routePath = path.join(basePath, 'routes', 'web.php');
let routes = fs.readFileSync(routePath, 'utf8');

const additionalRoutes = `
    // Dynamic CMS Routes
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
`;

routes = routes.replace("Route::put('/web-management', [WebController::class, 'update'])->name('web.management.update');", "Route::put('/web-management', [WebController::class, 'update'])->name('web.management.update');\n" + additionalRoutes);
fs.writeFileSync(routePath, routes);

console.log('WebController and Routes updated successfully.');
