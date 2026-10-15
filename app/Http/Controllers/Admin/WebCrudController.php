<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use App\Models\Penelitian;
use App\Models\Berita;
use App\Models\GaleriAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebCrudController extends Controller
{
    // Visi Misi / Poin Tentang (disimpan ke tabel Konten)
    public function storePoinTentang(Request $permintaan) {
        $validated = $permintaan->validate(['content' => 'required']);
        $uniqueKey = 'about_bullet_' . time() . '_' . rand(100, 999);
        Konten::create([
            'section' => 'about_bullet',
            'key'     => $uniqueKey,
            'value'   => $validated['content'],
            'type'    => 'text',
        ]);
        return back()->with('success', 'Visi Misi ditambahkan!');
    }
    public function destroyPoinTentang($id) {
        Konten::where('section', 'about_bullet')->where('id', $id)->delete();
        return back()->with('success', 'Visi Misi dihapus!');
    }


    // Penelitian
    public function storePenelitian(Request $permintaan) {
        $data = $permintaan->validate(['title' => 'required', 'description' => 'required', 'image_path' => 'required|image']);
        if ($permintaan->hasFile('image_path')) $data['image_path'] = $permintaan->file('image_path')->store('konten', 'public');
        Penelitian::create($data);
        return back()->with('success', 'Penelitian ditambahkan!');
    }
    public function updatePenelitian(Request $permintaan, $id) {
        $penelitian = Penelitian::findOrFail($id);
        $data = $permintaan->validate(['title' => 'required', 'description' => 'required', 'image_path' => 'nullable|image']);
        if ($permintaan->hasFile('image_path')) {
            $data['image_path'] = $permintaan->file('image_path')->store('konten', 'public');
        }
        $penelitian->update($data);
        return back()->with('success', 'Penelitian diperbarui!');
    }
    public function destroyPenelitian($id) {
        Penelitian::findOrFail($id)->delete();
        return back()->with('success', 'Penelitian dihapus!');
    }

    // Berita
    public function storeBerita(Request $permintaan) {
        $data = $permintaan->validate([
            'category'   => 'required',
            'title'      => 'required',
            'description'=> 'required',
            'date'       => 'nullable',
            'image_path' => 'nullable|image',
            'status'     => 'required|in:published,draft',
        ]);
        if ($permintaan->hasFile('image_path')) $data['image_path'] = $permintaan->file('image_path')->store('konten', 'public');
        Berita::create($data);
        return back()->with('success', 'Berita ditambahkan!');
    }
    public function updateBerita(Request $permintaan, $id) {
        $berita = Berita::findOrFail($id);
        $data = $permintaan->validate([
            'category'   => 'required',
            'title'      => 'required',
            'description'=> 'required',
            'date'       => 'nullable',
            'image_path' => 'nullable|image',
            'status'     => 'required|in:published,draft',
        ]);
        if ($permintaan->hasFile('image_path')) {
            $data['image_path'] = $permintaan->file('image_path')->store('konten', 'public');
        }
        $berita->update($data);
        return back()->with('success', 'Berita diperbarui!');
    }
    public function destroyBerita($id) {
        Berita::findOrFail($id)->delete();
        return back()->with('success', 'Berita dihapus!');
    }

    // Galeri
    public function storeGaleri(Request $permintaan) {
        $permintaan->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'images'       => 'required|array|min:1|max:5',
            'images.*'     => 'image|mimes:jpeg,png,jpg,webp,gif|max:20480',
        ], [
            'images.max'     => 'Maksimal 5 foto yang dapat diunggah dalam satu album.',
            'images.min'     => 'Minimal 1 foto harus diunggah.',
            'images.*.max'   => 'Foto ke-:position maksimal berukuran 20 MB.',
            'images.*.image' => 'File ke-:position bukan berupa gambar yang valid (harus file foto).',
            'images.*.mimes' => 'Format file ke-:position tidak didukung. Gunakan format JPG, PNG, WEBP, atau GIF.',
        ]);

        $photoPaths = [];
        if ($permintaan->hasFile('images')) {
            foreach ($permintaan->file('images') as $file) {
                $photoPaths[] = $file->store('galeri', 'public');
            }
        }

        GaleriAlbum::create([
            'title'       => $permintaan->title,
            'description' => $permintaan->description,
            'event_date'  => now(),
            'photos'      => $photoPaths,
        ]);

        return back()->with('success', 'Galeri berhasil ditambahkan dengan ' . count($photoPaths) . ' foto!');
    }

    public function updateGaleri(Request $permintaan, $id) {
        $album = GaleriAlbum::findOrFail($id);
        
        $permintaan->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'images'       => 'nullable|array|max:5',
            'images.*'     => 'image|mimes:jpeg,png,jpg,webp,gif|max:20480',
        ], [
            'images.max'     => 'Maksimal 5 foto yang dapat diunggah dalam satu album.',
            'images.*.max'   => 'Foto ke-:position maksimal berukuran 20 MB.',
            'images.*.image' => 'File ke-:position bukan berupa gambar yang valid (harus file foto).',
            'images.*.mimes' => 'Format file ke-:position tidak didukung. Gunakan format JPG, PNG, WEBP, atau GIF.',
        ]);

        $currentPhotos = is_array($album->photos) ? $album->photos : [];

        // Tambah gambar baru jika ada
        if ($permintaan->hasFile('images')) {
            $newFiles = $permintaan->file('images');
            if (count($currentPhotos) + count($newFiles) > 5) {
                return back()->withErrors(['images' => 'Total foto dalam album ini melebihi batas maksimal 5 foto.']);
            }
            foreach ($newFiles as $file) {
                $currentPhotos[] = $file->store('galeri', 'public');
            }
        }

        $album->update([
            'title'       => $permintaan->title,
            'description' => $permintaan->description,
            'photos'      => $currentPhotos,
        ]);

        return back()->with('success', 'Galeri berhasil diperbarui!');
    }

    public function destroyGaleri($id) {
        $album = GaleriAlbum::findOrFail($id);
        $photos = is_array($album->photos) ? $album->photos : [];
        foreach ($photos as $photoPath) {
            if (Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
        }
        $album->delete();
        return back()->with('success', 'Galeri berhasil dihapus!');
    }

    // Method baru untuk menghapus foto satuan di dalam album
    public function destroyPhoto($id, $index) {
        $album = GaleriAlbum::findOrFail($id);
        $photos = is_array($album->photos) ? $album->photos : [];

        if (isset($photos[$index])) {
            // Hapus file fisik dari storage
            if (Storage::disk('public')->exists($photos[$index])) {
                Storage::disk('public')->delete($photos[$index]);
            }

            // Hapus dari array
            unset($photos[$index]);
            
            // Re-index array agar kembali urut indeksnya (0, 1, 2, dst)
            $album->photos = array_values($photos);
            $album->save();

            return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus.']);
        }

        return response()->json(['success' => false, 'message' => 'Foto tidak ditemukan.'], 404);
    }

    // Latar Beranda (disimpan ke tabel Konten)
    public function storeLatarBeranda(Request $permintaan) {
        $data = $permintaan->validate(['image_path' => 'required|image']);
        $path = $permintaan->file('image_path')->store('konten', 'public');
        $uniqueKey = 'home_bg_' . time() . '_' . rand(100, 999);
        Konten::create([
            'section'    => 'home_background',
            'key'        => $uniqueKey,
            'value'      => '',
            'type'       => 'image',
            'image_path' => $path,
        ]);
        return back()->with('success', 'Background ditambahkan!');
    }
    public function destroyLatarBeranda($id) {
        $bg = Konten::where('section', 'home_background')->where('id', $id)->first();
        if ($bg) {
            if ($bg->image_path && Storage::disk('public')->exists($bg->image_path)) {
                Storage::disk('public')->delete($bg->image_path);
            }
            $bg->delete();
        }
        return back()->with('success', 'Background dihapus!');
    }
}