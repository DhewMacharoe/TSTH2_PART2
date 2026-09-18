<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\HomeBackground;
use App\Models\AboutBullet;
use App\Models\Program;
use App\Models\Research;
use App\Models\News;
use App\Models\Gallery;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function home()
    {
        $contents = Content::all()->keyBy('key');
        $aboutBullets = AboutBullet::all();
        $homeBackgrounds = HomeBackground::all();
        return view('web_management.home', compact('contents', 'aboutBullets', 'homeBackgrounds'));
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
        Program::create($request->validate(['title' => 'required', 'description' => 'required']));
        return back()->with('success', 'Program ditambahkan!');
    }
    public function updateProgram(Request $request, $id) {
        $program = Program::findOrFail($id);
        $program->update($request->validate(['title' => 'required', 'description' => 'required']));
        return back()->with('success', 'Program diperbarui!');
    }
    public function destroyProgram($id) {
        Program::findOrFail($id)->delete();
        return back()->with('success', 'Program dihapus!');
    }

    public function storeResearch(Request $request) {
        $data = $request->validate(['title' => 'required', 'description' => 'required', 'image_path' => 'required|image']);
        if ($request->hasFile('image_path')) $data['image_path'] = $request->file('image_path')->store('contents', 'public');
        Research::create($data);
        return back()->with('success', 'Penelitian ditambahkan!');
    }
    public function updateResearch(Request $request, $id) {
        $research = Research::findOrFail($id);
        $data = $request->validate(['title' => 'required', 'description' => 'required', 'image_path' => 'nullable|image']);
        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request->file('image_path')->store('contents', 'public');
        }
        $research->update($data);
        return back()->with('success', 'Penelitian diperbarui!');
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
    public function updateNews(Request $request, $id) {
        $news = News::findOrFail($id);
        $data = $request->validate(['category' => 'required', 'title' => 'required', 'description' => 'required', 'date' => 'nullable', 'image_path' => 'nullable|image']);
        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request->file('image_path')->store('contents', 'public');
        }
        $news->update($data);
        return back()->with('success', 'Berita diperbarui!');
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
    public function updateGallery(Request $request, $id) {
        $gallery = Gallery::findOrFail($id);
        $data = $request->validate(['title' => 'required', 'description' => 'nullable', 'image_path' => 'nullable|image']);
        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request->file('image_path')->store('contents', 'public');
        }
        $gallery->update($data);
        return back()->with('success', 'Galeri diperbarui!');
    }
    public function destroyGallery($id) {
        Gallery::findOrFail($id)->delete();
        return back()->with('success', 'Galeri dihapus!');
    }

    public function storeHomeBackground(Request $request) {
        $data = $request->validate(['image_path' => 'required|image']);
        if ($request->hasFile('image_path')) $data['image_path'] = $request->file('image_path')->store('contents', 'public');
        HomeBackground::create($data);
        return back()->with('success', 'Background ditambahkan!');
    }
    public function destroyHomeBackground($id) {
        HomeBackground::findOrFail($id)->delete();
        return back()->with('success', 'Background dihapus!');
    }

}