@extends('layouts.dasbor')

@section('title', 'Galeri Foto - TSTH2 Web Management')

@section('header_title')
    <div>
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Galeri Dokumentasi</h2>
        <p class="text-xs text-gray-500 font-medium">Kelola album foto kegiatan, kunjungan, dan dokumentasi fasilitas.</p>
    </div>
@endsection

@section('content')
    <!-- Top Header Banner -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-primary-900 via-primary-800 to-primary-950 p-6 sm:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-accent/20 text-accent border border-accent/30 mb-3">
                <span>🖼️</span> Album Foto Dokumentasi
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Manajemen Galeri Foto</h1>
            <p class="text-white/70 text-sm mt-1 max-w-xl">Unggah dan kelola album foto kegiatan — hingga 10 gambar per album, dengan kontrol per foto.</p>
        </div>
        <div class="relative z-10 shrink-0">
            <a href="{{ route('galeri') }}" target="_blank" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl text-xs font-bold backdrop-blur-sm transition-all hover:scale-105 shadow-sm">
                <span>🌐</span> Lihat Halaman Galeri
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl mb-8 font-semibold border border-emerald-200/80 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0">✓</div>
            <div class="text-sm">{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-2xl mb-8 font-semibold border border-red-200/80 space-y-1">
            @foreach ($errors->all() as $error)
                <p class="text-sm">⚠️ {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- 1. KONTEN HERO GALERI -->
    <form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data" class="mb-8">
        @csrf @method('PUT')
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-pink-50 text-pink-700 flex items-center justify-center font-extrabold text-xl shadow-inner">📌</div>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-900">Header Halaman Galeri</h2>
                    <p class="text-xs text-gray-500">Teks judul dan deskripsi yang tampil di banner atas halaman galeri publik.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Judul Banner Galeri</label>
                    <input type="text" name="galeri_hero_title"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-semibold bg-gray-50/50 hover:bg-white"
                        placeholder="Contoh: Galeri Dokumentasi TSTH2"
                        value="{{ $konten['galeri_hero_title']->value ?? '' }}">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Deskripsi Banner Galeri</label>
                    <textarea name="galeri_hero_desc" rows="2"
                        class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white resize-none"
                        placeholder="Koleksi momen dari berbagai kegiatan riset, kunjungan, dan pelatihan di TSTH2...">{{ $konten['galeri_hero_desc']->value ?? '' }}</textarea>
                </div>
            </div>
            <div class="mt-5 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gray-900 hover:bg-black text-white font-bold py-2.5 px-6 rounded-xl text-xs transition shadow-md">
                    <span>💾</span> Simpan Header Galeri
                </button>
            </div>
        </div>
    </form>

    <!-- 2. DAFTAR ALBUM GALERI -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow"
         x-data="{
            open: false,
            editOpen: false,
            detailOpen: false,
            activeItem: { id: '', title: '', description: '', photos: [] },
            setItem(item) {
                this.activeItem = Object.assign({}, item);
                let p = this.activeItem.photos;
                if (typeof p === 'string') {
                    try { p = JSON.parse(p); } catch(e) { p = []; }
                }
                this.activeItem.photos = Array.isArray(p) ? p : [];
            }
         }">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-gray-100 pb-5 gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-gray-900">Daftar Album Foto</h2>
                <p class="text-xs text-gray-500">Setiap album menampung hingga 10 foto dokumentasi kegiatan.</p>
            </div>
            <div class="flex flex-wrap sm:flex-nowrap gap-2.5 w-full md:w-auto">
                <form action="{{ route('web.management.galeri') }}" method="GET" class="flex flex-1 sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari album foto..."
                        class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/10 text-xs font-medium">
                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl ml-2 text-xs font-bold hover:bg-black transition">Cari</button>
                </form>
                <button type="button" @click="open = true" class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary-900 text-white font-bold py-2 px-4 rounded-xl text-xs shadow-md shrink-0 transition">
                    <span>+</span> Buat Album Baru
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/70 border-y border-gray-100 text-gray-600 text-xs uppercase tracking-wider font-bold">
                        <th class="p-4 w-24">Sampul</th>
                        <th class="p-4">Nama Album</th>
                        <th class="p-4 w-28">Jumlah Foto</th>
                        <th class="p-4">Deskripsi</th>
                        <th class="p-4 text-right w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @if(isset($galeri) && count($galeri) > 0)
                        @foreach($galeri as $item)
                            @php
                                $photos = is_array($item->photos) ? $item->photos : (json_decode($item->photos, true) ?? []);
                                $cover = count($photos) > 0 ? $photos[0] : 'default.jpg';
                                $photoCount = count($photos);
                                $itemData = [
                                    'id' => $item->id,
                                    'title' => $item->title,
                                    'description' => $item->description,
                                    'photos' => $photos
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-4">
                                    <div class="relative w-20 h-14 rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-100">
                                        <img src="{{ asset('storage/' . $cover) }}" class="w-full h-full object-cover">
                                        @if($photoCount > 1)
                                            <span class="absolute -top-1.5 -right-1.5 bg-primary text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-full shadow-sm border-2 border-white">
                                                +{{ $photoCount - 1 }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-900 hover:text-primary cursor-pointer transition-colors"
                                         @click="setItem(@js($itemData)); detailOpen = true">
                                        {{ $item->title }}
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-800 font-bold text-[11px] rounded-full border border-emerald-100">
                                        📷 {{ $photoCount }} / 10
                                    </span>
                                </td>
                                <td class="p-4 text-xs text-gray-500 leading-relaxed">{{ Str::limit($item->description, 60) }}</td>
                                <td class="p-4 text-right">
                                    <div class="flex justify-end items-center gap-1.5">
                                        <button type="button" @click="setItem(@js($itemData)); detailOpen = true"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg text-xs font-bold transition" title="Detail">👁️</button>
                                        <button type="button" @click="setItem(@js($itemData)); editOpen = true"
                                            class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg text-xs font-bold transition" title="Edit">✏️</button>
                                        <form action="{{ route('web.management.galeri.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus album beserta semua fotonya?');" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg text-xs font-bold transition" title="Hapus">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400 text-xs">Belum ada album galeri yang diunggah.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- MODAL TAMBAH ALBUM -->
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;">
            <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-xl shadow-2xl border border-gray-100 max-h-[90vh] flex flex-col" @click.away="open = false">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-extrabold text-gray-900">Buat Album Foto Baru</h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-sm">✕</button>
                </div>
                <form action="{{ route('web.management.galeri.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto custom-scrollbar pt-4">
                    @csrf
                    <div class="space-y-4 px-1">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Judul Album / Dokumentasi</label>
                            <input type="text" name="title"
                                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm"
                                placeholder="Contoh: Peresmian Gedung Laboratorium TSTH2" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Deskripsi Album (Opsional)</label>
                            <textarea name="description" rows="3"
                                class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary text-sm leading-relaxed resize-none"
                                placeholder="Penjelasan singkat mengenai dokumentasi kegiatan..."></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Unggah Foto (Bisa Pilih Banyak Sekaligus)</label>
                            <div class="p-5 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-white transition-colors text-center">
                                <input type="file" name="images[]"
                                    class="w-full text-xs text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white cursor-pointer"
                                    accept="image/*" multiple required>
                                <p class="text-[11px] text-gray-400 mt-3 leading-relaxed">
                                    💡 Tekan <kbd class="px-1.5 py-0.5 bg-gray-200 rounded text-[10px] font-bold text-gray-600">Ctrl</kbd> / <kbd class="px-1.5 py-0.5 bg-gray-200 rounded text-[10px] font-bold text-gray-600">Shift</kbd> untuk memilih beberapa foto.<br>
                                    Maks. <strong>10 foto</strong> per album, ukuran maks. 20MB per foto.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2.5 mt-6 pt-4 border-t border-gray-100">
                        <button type="button" @click="open = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl text-xs transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-900 text-white font-bold rounded-xl text-xs shadow-md transition">Unggah Album</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT ALBUM -->
        <div x-show="editOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;">
            <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-xl shadow-2xl border border-gray-100 max-h-[90vh] flex flex-col" @click.away="editOpen = false">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-extrabold text-gray-900">Edit Album Foto</h3>
                    <button @click="editOpen = false" class="text-gray-400 hover:text-gray-600 text-sm">✕</button>
                </div>
                <form :action="`/web-management/galeri/${activeItem.id}`" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto custom-scrollbar pt-4">
                    @csrf @method('PUT')
                    <div class="space-y-5 px-1">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Judul Album</label>
                            <input type="text" name="title" x-model="activeItem.title"
                                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm font-semibold" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Deskripsi Album</label>
                            <textarea name="description" x-model="activeItem.description" rows="3"
                                class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary text-sm leading-relaxed resize-none"></textarea>
                        </div>

                        <!-- Kelola Foto Saat Ini -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Foto Saat Ini dalam Album</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-3">
                                <template x-for="(photo, index) in activeItem.photos" :key="index">
                                    <div class="group relative rounded-2xl overflow-hidden border border-gray-200 shadow-sm aspect-square bg-gray-100">
                                        <img :src="`/storage/${photo}`" class="w-full h-full object-cover">
                                        <template x-if="index === 0">
                                            <span class="absolute top-2 left-2 bg-primary text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow z-10">Cover</span>
                                        </template>
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <button type="button"
                                                @click="if(confirm('Hapus foto ini dari album?')) {
                                                    fetch(`/web-management/galeri/${activeItem.id}/photo/${index}`, {
                                                        method: 'DELETE',
                                                        headers: {
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content'),
                                                            'Accept': 'application/json'
                                                        }
                                                    }).then(res => { if(res.ok) location.reload(); });
                                                }"
                                                class="bg-red-500 hover:bg-red-600 text-white text-[10px] font-bold py-1.5 px-3 rounded-xl shadow transition">
                                                🗑️ Hapus
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div x-show="!activeItem.photos || activeItem.photos.length === 0" class="text-xs text-gray-400 italic p-4 text-center bg-gray-50 rounded-xl border border-gray-100">
                                Album ini belum memiliki foto.
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Tambah Foto Baru ke Album (Opsional)</label>
                            <input type="file" name="images[]"
                                class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white cursor-pointer"
                                accept="image/*" multiple>
                            <p class="text-[11px] text-gray-400 mt-1">Pilih foto baru jika ingin menambah ke album ini.</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2.5 mt-6 pt-4 border-t border-gray-100">
                        <button type="button" @click="editOpen = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl text-xs transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs shadow-md transition">Perbarui Album</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL DETAIL ALBUM -->
        <div x-show="detailOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;">
            <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-2xl shadow-2xl border border-gray-100 max-h-[85vh] flex flex-col" @click.away="detailOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900" x-text="activeItem.title"></h3>
                        <p class="text-xs text-gray-500 mt-0.5 whitespace-pre-wrap" x-text="activeItem.description"></p>
                    </div>
                    <button @click="detailOpen = false" class="text-gray-400 hover:text-gray-600 text-sm shrink-0 ml-4">✕</button>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar pr-1">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <template x-for="(photo, index) in activeItem.photos" :key="index">
                            <div class="relative rounded-2xl overflow-hidden border border-gray-200 shadow-sm aspect-square bg-gray-100 group">
                                <img :src="`/storage/${photo}`" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <template x-if="index === 0">
                                    <span class="absolute top-2 left-2 bg-primary text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow z-10">Cover Utama</span>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div x-show="!activeItem.photos || activeItem.photos.length === 0" class="text-xs text-gray-400 italic p-8 text-center">
                        Album ini belum memiliki foto.
                    </div>
                </div>
                <div class="flex justify-end pt-4 border-t border-gray-100 mt-4">
                    <button type="button" @click="detailOpen = false" class="px-6 py-2.5 bg-gray-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection