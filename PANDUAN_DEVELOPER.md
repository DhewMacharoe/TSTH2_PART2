# 📖 PANDUAN PENGEMBANGAN MANDIRI (DEVELOPER CHEATSHEET)
### Sistem Informasi TSTH2 (Taman Sains dan Teknologi Herbal dan Hortikultura)

Panduan ini dibuat agar kamu bisa memahami struktur kode, alur logika, dan cara melacak error secara mandiri tanpa harus bingung atau tersesat di tumpukan file.

---

## 🧭 1. Peta Mental Arsitektur (Alur Request Laravel)

Setiap aksi di web (buka halaman, klik tombol, submit form) berjalan dengan alur:

```
[Browser / User Klik]
        │
        ▼
1. routes/web.php             ──► URL ini lari ke Controller & Method mana?
        │
        ▼
2. Controller (app/Http/...)  ──► Terima Request, Validasi data input, panggil Model.
        │
        ├─► 3. Model (app/Models/...) ──► Ambil / Simpan data ke Database MySQL.
        │
        ▼
4. View (resources/views/...) ──► Render tampilan HTML/Blade dengan data dari Controller.
        │
        ▼
[Tampil di Layar Pengguna]
```

---

## 🗺️ 2. Kamus Modul & Lokasi File Project TSTH2

Gunakan daftar ini untuk tahu persis file mana yang mengatur fitur tertentu:

| Modul / Fitur | URL / Route | Controller | Model Utama | Folder View (Blade) |
| :--- | :--- | :--- | :--- | :--- |
| **Gudang (Master Barang)** | `/gudang` | `Admin\GudangController` | `Barang`, `BarangBatch` | `resources/views/gudang/` |
| **Gudang (Batch Stok)** | `/gudang/{id}/batch` | `Admin\GudangBatchController`| `BarangBatch` | `resources/views/gudang/` |
| **Satuan & Konversi** | `/gudang/satuan` | `Admin\SatuanController` | `SatuanBarang`, `SatuanKonversi` | `resources/views/gudang/satuan/` |
| **Permintaan Barang** | `/permintaan` | `Admin\PermintaanController` | `PermintaanBarang` | `resources/views/permintaan/` |
| **Status Permintaan** | `/permintaan/{id}/status`| `Admin\PermintaanStatusController`| `PermintaanBarang` | `resources/views/permintaan/` |
| **Chat Permintaan** | `/permintaan/{id}/chat` | `Admin\PermintaanChatController`| `ObrolanPermintaan` | `resources/views/permintaan/` |
| **Web CMS (Landing & Konten)**| `/web-management/home`| `Admin\WebController` & `WebCrudController`| `Konten` (Hero, Visi-Misi, BG) | `resources/views/manajemen_web/` |
| **Web CMS (Penelitian & Berita)** | `/web-management/...` | `Admin\WebCrudController` | `Penelitian`, `Berita` | `resources/views/manajemen_web/` |
| **Galeri Dokumentasi** | `/galeri` & `/web-management/galeri` | `WebController` & `WebCrudController` | `GaleriAlbum` (Multi-foto JSON) | `resources/views/pengunjung/` & `manajemen_web/` |
| **Katalog Tanaman (Admin)** | `/admin/plants` | `Admin\AdminPlantController` | `Plant`, `Category` | `resources/views/admin/plants/` |
| **Katalog Tanaman (Public)**| `/katalog-tanaman` | `PlantController` | `Plant`, `Category` | `resources/views/catalog/` |
| **Halaman Publik (Beranda dll)** | `/`, `/profil-tsth2`, `/layanan` | `Pengunjung\PublicController`| `Konten`, `Berita`, `Penelitian` | `resources/views/pengunjung/` |
| **Logbook / Catatan** | `/catatan` | Inline closure di `web.php` | - | `resources/views/catatan/` |
| **Profil User** | `/profil` | `Admin\ProfilController` | `User` | `resources/views/profil/` |

---

## 🚨 3. Panduan Diagnosis Error: "Error Ini Harus Buka File Mana?"

Ketika muncul halaman error (Ignition Laravel):

### A. Error `Undefined variable $...`
* **Gejala:** Tiba-tiba halaman merah bertuliskan *Undefined variable $barangs* di file blade.
* **Trik:** Jangan ubah blade-nya dulu!
* **Buka File:** **Controller** yang memanggil view tersebut.
* **Solusi:** Pastikan controller mengirim variabel tersebut:
  ```php
  // SALAH:
  return view('gudang.index');
  // BENAR:
  return view('gudang.index', compact('barangs'));
  ```

### B. Error `Call to a member function ... on null`
* **Gejala:** Muncul error saat memanggil `$item->kategori->nama` atau `$barang->satuan->nama`.
* **Penyebab:** Relasi datanya kosong (`null`) atau data ID di database tidak ada pasangan di tabel relasinya.
* **Buka File:**
  1. Cek Model relasinya di `app/Models/`.
  2. Atau di View, gunakan null-safe operator PHP 8: `$item->kategori?->nama ?? '-'`.

### C. Error `404 | Not Found`
* **Gejala:** Klik link atau submit tombol tapi halaman not found.
* **Buka File:** `routes/web.php`.
* **Cek:**
  1. Apakah URL atau route name-nya terdaftar?
  2. Perhatikan urutan route! Route spesifik (seperti `/gudang/create`) **harus diletakkan di atas** route wildcard (seperti `/gudang/{id}`).

### D. Error `419 | Page Expired`
* **Gejala:** Submit form lalu muncul layar 419.
* **Buka File:** File View `.blade.php` yang berisi `<form>`.
* **Solusi:** Pastikan tepat di bawah `<form>` sudah ada `@csrf`:
  ```html
  <form action="..." method="POST">
      @csrf
      ...
  ```

### E. Error `MethodNotAllowedHttpException`
* **Gejala:** *The GET method is not supported for route ... Supported: POST, PUT.*
* **Buka File:** File View `.blade.php` & `routes/web.php`.
* **Penyebab:** Di form kamu pakai `GET` padahal di route didefinisikan `Route::post(...)` atau sebaliknya. Kalau mau update data (PUT), pasang `@method('PUT')` di dalam form.

---

## 🎨 4. Trik Menghadapi "Tampilan Tiba-Tiba Berantakan"

Kalau tampilan UI tiba-tiba pecah atau berantakan:

1. **Cek Tag HTML yang Tidak Ditutup:**
   * 90% tampilan rusak di Blade disebabkan oleh tag penutup `</div>` yang kelebihan atau kurang saat kamu copy-paste.
   * *Tips di VS Code:* Klik tag `<div>`, perhatikan pasangannya menyorot di mana.
2. **Cek Layout Wrapper:**
   * Di baris paling atas file blade, pastikan layout yang di-`@extends` benar:
     * Dashboard & Admin: `@extends('layouts.app')` atau `@extends('layouts.admin')`
     * Pengunjung: `@extends('layouts.public')` (atau layout pengunjung yang sesuai).
3. **Inspect Element (F12 di Browser):**
   * Klik kanan pada bagian yang rusak $\to$ pilih **Inspect**.
   * Lihat apakah ada class Tailwind/CSS yang tertimpa atau elemen meluap (*overflow*).

---

## 🛠️ 5. Senjata Rahasia Developer Laravel (Wajib Dicoba!)

1. **`dd($data);` (Dump and Die):**
   * Kalau bingung apa isi sebuah variabel di Controller, hentikan alur dan intip isinya:
     ```php
     $barang = Barang::find($id);
     dd($barang); // Browser akan menampilkan isi $barang dan berhenti di sini
     ```
2. **Lihat Daftar Semua Route:**
   * Buka terminal (PowerShell / Command Prompt), jalankan:
     ```bash
     php artisan route:list
     ```
     Atau filter route tertentu:
     ```bash
     php artisan route:list --name=gudang
     ```
3. **Bersihkan Cache Jika Perubahan Tidak Muncul:**
   * Jika route atau view baru tidak terdeteksi:
     ```bash
     php artisan optimize:clear
     ```

---
*Simpan file panduan ini sebagai pegangan. Setiap kali kamu bingung harus mulai dari mana, buka panduan ini, cocokkan modulnya, dan telusuri dari Route $\to$ Controller $\to$ View.*
