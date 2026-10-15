@extends('layouts.dasbor')

@section('title', 'Fasilitas & Riset - TSTH2 Web Management')

@section('header_title')
    <div>
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Fasilitas & Riset</h2>
        <p class="text-xs text-gray-500 font-medium">Kelola data fasilitas laboratorium dan proyek riset herbal.</p>
    </div>
@endsection

@section('content')
    <!-- Top Header Banner -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-primary-900 via-primary-800 to-primary-950 p-6 sm:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-accent/20 text-accent border border-accent/30 mb-3">
                <span>🔬</span> Data Riset & Fasilitas
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Manajemen Fasilitas & Penelitian</h1>
            <p class="text-white/70 text-sm mt-1 max-w-xl">Tambahkan informasi laboratorium, kebun riset, dan proyek penelitian yang sedang berjalan di TSTH2.</p>
        </div>
        <div class="relative z-10 shrink-0">
            <a href="{{ route('penelitian') }}" target="_blank" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl text-xs font-bold backdrop-blur-sm transition-all hover:scale-105 shadow-sm">
                <span>🌐</span> Lihat Halaman Riset
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

    <!-- 1. KONTEN HERO PENELITIAN -->
    <form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data" class="mb-8">
        @csrf @method('PUT')
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-violet-50 text-violet-700 flex items-center justify-center font-extrabold text-xl shadow-inner">📌</div>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-900">Header Halaman Fasilitas & Riset</h2>
                    <p class="text-xs text-gray-500">Teks dan gambar yang tampil di banner atas halaman publik Fasilitas & Penelitian.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Judul Banner</label>
                    <input type="text" name="penelitian_hero_title"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-semibold bg-gray-50/50 hover:bg-white"
                        placeholder="Contoh: Fasilitas Riset Terintegrasi"
                        value="{{ $konten['penelitian_hero_title']->value ?? '' }}">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Deskripsi Banner</label>
                    <textarea name="penelitian_hero_desc" rows="2"
                        class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white resize-none"
                        placeholder="Deskripsi singkat tentang komitmen riset herbal di TSTH2...">{{ $konten['penelitian_hero_desc']->value ?? '' }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Gambar Latar Banner</label>
                    <div class="p-4 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-white transition-colors">
                        <input type="file" name="penelitian_hero_bg" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary-900 cursor-pointer" accept="image/*">
                        @if (isset($konten['penelitian_hero_bg']) && $konten['penelitian_hero_bg']->image_path)
                            <div class="mt-4 flex items-center gap-3 p-2 bg-white rounded-xl border border-gray-100 shadow-sm">
                                <img src="{{ asset('storage/' . $konten['penelitian_hero_bg']->image_path) }}" class="h-16 w-28 object-cover rounded-lg border border-gray-200">
                                <div class="text-[11px] text-gray-500">
                                    <span class="font-bold text-gray-700 block">Gambar Aktif Saat Ini</span>
                                    Pilih file baru jika ingin mengganti.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gray-900 hover:bg-black text-white font-bold py-2.5 px-6 rounded-xl text-xs transition shadow-md">
                    <span>💾</span> Simpan Header Riset
                </button>
            </div>
        </div>
    </form>

    <!-- 2. DAFTAR FASILITAS / RISET -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow"
         x-data="{ open: false, editOpen: false, detailOpen: false, activeItem: {} }">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-gray-100 pb-5 gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-gray-900">Daftar Fasilitas & Riset</h2>
                <p class="text-xs text-gray-500">Data laboratorium, kebun riset, dan proyek-proyek penelitian aktif.</p>
            </div>
            <div class="flex flex-wrap sm:flex-nowrap gap-2.5 w-full md:w-auto">
                <form action="{{ route('web.management.penelitian') }}" method="GET" class="flex flex-1 sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari fasilitas atau riset..."
                        class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/10 text-xs font-medium">
                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl ml-2 text-xs font-bold hover:bg-black transition">Cari</button>
                </form>
                <button @click="open = true" class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary-900 text-white font-bold py-2 px-4 rounded-xl text-xs shadow-md shrink-0 transition">
                    <span>+</span> Tambah Riset
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/70 border-y border-gray-100 text-gray-600 text-xs uppercase tracking-wider font-bold">
                        <th class="p-4 w-20">Foto</th>
                        <th class="p-4">Judul Fasilitas / Riset</th>
                        <th class="p-4">Deskripsi Singkat</th>
                        <th class="p-4 text-right w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($penelitianes as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-4">
                                @if ($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}"
                                        class="w-16 h-12 object-cover rounded-xl shadow-sm border border-gray-100">
                                @else
                                    <div class="w-16 h-12 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-xs text-gray-400 font-bold">—</div>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-900 cursor-pointer hover:text-primary transition-colors"
                                     @click="activeItem = {{ json_encode($item) }}; detailOpen = true">
                                    {{ $item->title }}
                                </div>
                            </td>
                            <td class="p-4 text-xs text-gray-500 leading-relaxed">{{ Str::limit($item->description, 80) }}</td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end items-center gap-1.5">
                                    <button @click="activeItem = {{ json_encode($item) }}; detailOpen = true"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg text-xs font-bold transition" title="Detail">👁️</button>
                                    <button @click="activeItem = {{ json_encode($item) }}; editOpen = true"
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg text-xs font-bold transition" title="Edit">✏️</button>
                                    <form action="{{ route('web.management.penelitian.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data riset ini?');" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg text-xs font-bold transition" title="Hapus">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-gray-400 text-xs">Belum ada data fasilitas atau riset.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- MODAL TAMBAH RISET -->
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;">
            <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-xl shadow-2xl border border-gray-100 max-h-[90vh] flex flex-col" @click.away="open = false">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-extrabold text-gray-900">Tambah Fasilitas / Riset Baru</h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-sm">✕</button>
                </div>
                <form action="{{ route('web.management.penelitian.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto custom-scrollbar pt-4">
                    @csrf
                    <div class="space-y-4 px-1">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Nama / Judul Fasilitas</label>
                            <input type="text" name="title"
                                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm"
                                placeholder="Contoh: Laboratorium Bioteknologi Herbal" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Deskripsi Lengkap</label>
                            <textarea name="description" rows="5"
                                class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary text-sm leading-relaxed resize-none"
                                placeholder="Jelaskan fungsi, kapasitas, dan kegunaan fasilitas ini..." required></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Foto Fasilitas</label>
                            <input type="file" name="image_path"
                                class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white cursor-pointer"
                                accept="image/*" required>
                            <p class="text-[11px] text-gray-400 mt-1.5">Unggah foto fasilitas dengan resolusi yang jelas.</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2.5 mt-6 pt-4 border-t border-gray-100">
                        <button type="button" @click="open = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl text-xs transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-900 text-white font-bold rounded-xl text-xs shadow-md transition">Simpan Riset</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT RISET -->
        <div x-show="editOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;">
            <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-xl shadow-2xl border border-gray-100 max-h-[90vh] flex flex-col" @click.away="editOpen = false">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-extrabold text-gray-900">Edit Data Riset</h3>
                    <button @click="editOpen = false" class="text-gray-400 hover:text-gray-600 text-sm">✕</button>
                </div>
                <form :action="`/web-management/penelitian/${activeItem.id}`" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto custom-scrollbar pt-4">
                    @csrf @method('PUT')
                    <div class="space-y-4 px-1">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Nama / Judul Fasilitas</label>
                            <input type="text" name="title" x-model="activeItem.title"
                                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm font-semibold" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Deskripsi Lengkap</label>
                            <textarea name="description" x-model="activeItem.description" rows="5"
                                class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary text-sm leading-relaxed resize-none" required></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Ganti Foto (Opsional)</label>
                            <input type="file" name="image_path"
                                class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white cursor-pointer"
                                accept="image/*">
                            <p class="text-[11px] text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengganti foto.</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2.5 mt-6 pt-4 border-t border-gray-100">
                        <button type="button" @click="editOpen = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl text-xs transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs shadow-md transition">Perbarui Data</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL DETAIL RISET -->
        <div x-show="detailOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;">
            <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-lg shadow-2xl border border-gray-100 max-h-[85vh] flex flex-col" @click.away="detailOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <h3 class="text-base font-extrabold text-gray-900">Detail Fasilitas / Riset</h3>
                    <button @click="detailOpen = false" class="text-gray-400 hover:text-gray-600 text-sm">✕</button>
                </div>
                <div class="space-y-4 overflow-y-auto custom-scrollbar flex-1 pr-1">
                    <template x-if="activeItem.image_path">
                        <img :src="`/storage/${activeItem.image_path}`" class="w-full h-52 object-cover rounded-2xl shadow-sm border border-gray-100">
                    </template>
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900 leading-snug" x-text="activeItem.title"></h3>
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap" x-text="activeItem.description"></p>
                    </div>
                </div>
                <div class="flex justify-end pt-4 border-t border-gray-100 mt-4">
                    <button type="button" @click="detailOpen = false" class="px-6 py-2.5 bg-gray-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
