@extends('layouts.dasbor')

@section('title', 'Berita - TSTH2 Web Management')

@section('header_title')
    <div>
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Berita</h2>
        <p class="text-xs text-gray-500 font-medium">Kelola publikasi warta kegiatan, riset, dan artikel edukasi herbal.</p>
    </div>
@endsection

@section('content')
    <!-- Top Header Banner -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-primary-900 via-primary-800 to-primary-950 p-6 sm:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-accent/20 text-accent border border-accent/30 mb-3">
                <span class="material-symbols-rounded" style="font-size:14px;">newspaper</span> Publikasi Informasi
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Manajemen Berita</h1>
            <p class="text-white/70 text-sm mt-1 max-w-xl">Publikasikan berita terbaru, riset terhangat, dan pengumuman resmi TSTH2 kepada masyarakat luas.</p>
        </div>
        <div class="relative z-10 shrink-0">
            <a href="{{ route('berita') }}" target="_blank" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl text-xs font-bold backdrop-blur-sm transition-all hover:scale-105 shadow-sm">
                <span class="material-symbols-rounded" style="font-size:15px;">open_in_new</span> Lihat Halaman Berita
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl mb-8 font-semibold border border-emerald-200/80 flex items-center gap-3 shadow-sm animate-fade-in">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0">
                <span class="material-symbols-rounded text-base">check</span>
            </div>
            <div class="text-sm">{{ session('success') }}</div>
        </div>
    @endif

    <!-- 1. KONTEN HEADER / HERO BERITA -->
    <form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data" class="mb-8">
        @csrf 
        @method('PUT')
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-extrabold shadow-inner">
                    <span class="material-symbols-rounded" style="font-size:20px;">push_pin</span>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-900">Header Halaman Berita</h2>
                    <p class="text-xs text-gray-500">Teks judul dan deskripsi yang tampil di banner atas halaman berita publik.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Judul Banner Berita</label>
                    <input type="text" name="berita_hero_title"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-semibold bg-gray-50/50 hover:bg-white" 
                        placeholder="Contoh: Berita & Publikasi Ilmiah"
                        value="{{ $konten['berita_hero_title']->value ?? '' }}">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Deskripsi Banner Berita</label>
                    <textarea name="berita_hero_desc" rows="2"
                        class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white resize-none"
                        placeholder="Ikuti perkembangan riset herbal terbaru, agenda kegiatan, dan artikel edukasi dari TSTH2...">{{ $konten['berita_hero_desc']->value ?? '' }}</textarea>
                </div>
            </div>
            <div class="mt-5 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gray-900 hover:bg-black text-white font-bold py-2.5 px-6 rounded-xl text-xs transition shadow-md">
                    <span class="material-symbols-rounded" style="font-size:16px;">save</span> Simpan Header Berita
                </button>
            </div>
        </div>
    </form>

    <!-- 2. DAFTAR BERITA -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow" 
         x-data="{ open: false, editOpen: false, detailOpen: false, activeItem: {} }">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-gray-100 pb-5 gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-gray-900">Daftar Berita</h2>
                <p class="text-xs text-gray-500">Kelola draf artikel atau langsung tayangkan ke website publik.</p>
            </div>
            <div class="flex flex-wrap sm:flex-nowrap gap-2.5 w-full md:w-auto">
                <form action="{{ route('web.management.berita') }}" method="GET" class="flex flex-1 sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita..." class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/10 text-xs font-medium">
                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl ml-2 text-xs font-bold hover:bg-black transition">Cari</button>
                </form>
                <button @click="open = true" class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary-900 text-white font-bold py-2 px-4 rounded-xl text-xs shadow-md shrink-0 transition">
                    <span>+</span> Tulis Berita
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/70 border-y border-gray-100 text-gray-600 text-xs uppercase tracking-wider font-bold">
                        <th class="p-4 w-20">Cover</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Judul & Ringkasan</th>
                        <th class="p-4 w-32">Tanggal</th>
                        <th class="p-4 w-28">Status</th>
                        <th class="p-4 text-right w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse(($berita ?? $allBerita) as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-4">
                                @if ($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}"
                                        class="w-16 h-12 object-cover rounded-xl shadow-sm border border-gray-100">
                                @else
                                    <div class="w-16 h-12 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-xs text-gray-400 font-bold">No Pic</div>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-primary/10 text-primary">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-900 hover:text-primary transition-colors cursor-pointer"
                                     @click="activeItem = {{ json_encode($item) }}; detailOpen = true">
                                    {{ $item->title }}
                                </div>
                                <div class="text-xs text-gray-500 line-clamp-1 mt-0.5">
                                    {{ Str::limit(strip_tags($item->description), 80) }}
                                </div>
                            </td>
                            <td class="p-4 text-xs font-medium text-gray-500">
                                {{ $item->date ? \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="p-4">
                                @if($item->status === 'published')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tayang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end items-center gap-1.5">
                                    <button @click="activeItem = {{ json_encode($item) }}; detailOpen = true" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg text-xs font-bold transition" title="Lihat Detail">
                                        <span class="material-symbols-rounded" style="font-size:16px;">visibility</span>
                                    </button>
                                    <button @click="activeItem = {{ json_encode($item) }}; editOpen = true" 
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg text-xs font-bold transition" title="Edit Berita">
                                        <span class="material-symbols-rounded" style="font-size:16px;">edit</span>
                                    </button>
                                    <form action="{{ route('web.management.berita.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus berita ini? Data yang dihapus tidak dapat dikembalikan.');" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg text-xs font-bold transition" title="Hapus Berita">
                                            <span class="material-symbols-rounded" style="font-size:16px;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-gray-400 text-xs">Belum ada berita atau artikel yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($berita, 'hasPages') && $berita->hasPages())
            <div class="p-6 bg-white border-t border-gray-100 flex justify-center">
                {{ $berita->links() }}
            </div>
        @endif

        <!-- MODAL TAMBAH BERITA -->
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            style="display: none;">
            <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-xl shadow-2xl border border-gray-100 max-h-[90vh] flex flex-col" @click.away="open = false">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-extrabold text-gray-900">Tulis Berita / Artikel Baru</h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-sm inline-flex items-center justify-center">
                        <span class="material-symbols-rounded text-base">close</span>
                    </button>
                </div>
                <form action="{{ route('web.management.berita.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto custom-scrollbar pt-4">
                    @csrf
                    <div class="space-y-4 px-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Kategori</label>
                                <input type="text" name="category" list="kategoriList"
                                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm" required placeholder="Misal: Inovasi, Riset, Edukasi">
                                <datalist id="kategoriList">
                                    <option value="Inovasi">
                                    <option value="Riset Herbal">
                                    <option value="Pengumuman">
                                    <option value="Kunjungan">
                                    <option value="Bioteknologi">
                                </datalist>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Tanggal Berita</label>
                                <input type="date" name="date" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Judul Lengkap</label>
                            <input type="text" name="title" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm font-semibold" required placeholder="Judul artikel atau berita">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Isi / Uraian Berita</label>
                            <textarea name="description" rows="5" class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary text-sm leading-relaxed resize-none" required placeholder="Tuliskan isi berita secara lengkap..."></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Foto Sampul (Cover)</label>
                                <input type="file" name="image_path" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white cursor-pointer" accept="image/*">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Status Publikasi</label>
                                <select name="status" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm font-medium">
                                    <option value="published">Tayangkan (Published)</option>
                                    <option value="draft">Simpan Draf (Draft)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2.5 mt-6 pt-4 border-t border-gray-100">
                        <button type="button" @click="open = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl text-xs transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-900 text-white font-bold rounded-xl text-xs shadow-md transition">Simpan & Publikasikan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT BERITA -->
        <div x-show="editOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;">
            <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-xl shadow-2xl border border-gray-100 max-h-[90vh] flex flex-col" @click.away="editOpen = false">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-extrabold text-gray-900">Edit Berita</h3>
                    <button @click="editOpen = false" class="text-gray-400 hover:text-gray-600 text-sm inline-flex items-center justify-center">
                        <span class="material-symbols-rounded text-base">close</span>
                    </button>
                </div>
                <form :action="`/web-management/berita/${activeItem.id}`" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto custom-scrollbar pt-4">
                    @csrf 
                    @method('PUT')
                    <div class="space-y-4 px-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Kategori</label>
                                <input type="text" name="category" x-model="activeItem.category" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Tanggal</label>
                                <input type="date" name="date" x-model="activeItem.date" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Judul Lengkap</label>
                            <input type="text" name="title" x-model="activeItem.title" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm font-semibold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Isi / Uraian Berita</label>
                            <textarea name="description" x-model="activeItem.description" rows="5" class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary text-sm leading-relaxed resize-none" required></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Ganti Foto Sampul</label>
                                <input type="file" name="image_path" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white cursor-pointer" accept="image/*">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Status</label>
                                <select name="status" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm font-medium"
                                    x-effect="$el.value = activeItem.status ?? 'published'">
                                    <option value="published">Tayangkan (Published)</option>
                                    <option value="draft">Simpan Draf (Draft)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2.5 mt-6 pt-4 border-t border-gray-100">
                        <button type="button" @click="editOpen = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl text-xs transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs shadow-md transition">Perbarui Berita</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL DETAIL BERITA -->
        <div x-show="detailOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;">
            <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-lg shadow-2xl border border-gray-100 max-h-[85vh] flex flex-col" @click.away="detailOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-primary/10 text-primary" x-text="activeItem.category"></span>
                    <button @click="detailOpen = false" class="text-gray-400 hover:text-gray-600 text-sm inline-flex items-center justify-center">
                        <span class="material-symbols-rounded text-base">close</span>
                    </button>
                </div>
                <div class="space-y-4 overflow-y-auto custom-scrollbar flex-1 pr-1">
                    <template x-if="activeItem.image_path">
                        <img :src="`/storage/${activeItem.image_path}`" class="w-full h-52 object-cover rounded-2xl shadow-sm border border-gray-100">
                    </template>
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900 leading-snug" x-text="activeItem.title"></h3>
                        <p class="text-xs text-gray-400 mt-1 font-medium" x-text="activeItem.date"></p>
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap" x-text="activeItem.description"></p>
                    </div>
                </div>
                <div class="flex justify-end pt-4 border-t border-gray-100 mt-4">
                    <button type="button" @click="detailOpen = false" class="px-6 py-2.5 bg-gray-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">Tutup Pratinjau</button>
                </div>
            </div>
        </div>
    </div>
@endsection
