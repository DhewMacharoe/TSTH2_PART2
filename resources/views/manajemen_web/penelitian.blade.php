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
                <span class="material-symbols-rounded text-sm">biotech</span> Data Riset & Fasilitas
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Manajemen Fasilitas & Penelitian</h1>
            <p class="text-white/70 text-sm mt-1 max-w-xl">Tambahkan informasi laboratorium, kebun riset, dan proyek penelitian yang sedang berjalan di TSTH2.</p>
        </div>
        <div class="relative z-10 shrink-0">
            <a href="{{ route('penelitian') }}" target="_blank" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl text-xs font-bold backdrop-blur-sm transition-all hover:scale-105 shadow-sm">
                <span class="material-symbols-rounded text-sm">language</span> Lihat Halaman Riset
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl mb-8 font-semibold border border-emerald-200/80 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0">
                <span class="material-symbols-rounded text-base">check</span>
            </div>
            <div class="text-sm">{{ session('success') }}</div>
        </div>
    @endif

    <!-- 1. KONTEN HERO PENELITIAN -->
    <form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data" class="mb-8">
        @csrf @method('PUT')
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-violet-50 text-violet-700 flex items-center justify-center shadow-inner">
                    <span class="material-symbols-rounded text-xl">push_pin</span>
                </div>
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
                    <span class="material-symbols-rounded text-sm">save</span> Simpan Header Riset
                </button>
            </div>
        </div>
    </form>

    <!-- 2. DAFTAR FASILITAS / RISET -->
    <script id="penelitian-records" type="application/json">
        {!! json_encode($penelitianes->getCollection()->keyBy('id')) !!}
    </script>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow"
         x-data="{
             items: JSON.parse(document.getElementById('penelitian-records')?.textContent || '{}'),
             open: false,
             editOpen: false,
             detailOpen: false,
             activeItem: {},
             tab: 'narrative',
             editTab: 'narrative',
             detailTab: 'narrative',

             // Create state
             createRoadmap: [],
             createParams: [],
             createBenchmarks: [],
             createMetrics: [],

             // Edit state
             editRoadmap: [],
             editParams: [],
             editBenchmarks: [],
             editMetrics: [],

             openCreate() {
                 this.tab = 'narrative';
                 this.createRoadmap = [];
                 this.createParams = [];
                 this.createBenchmarks = [];
                 this.createMetrics = [];
                 this.open = true;
             },

             openEdit(item) {
                 this.activeItem = JSON.parse(JSON.stringify(item));
                 this.editTab = 'narrative';
                 this.editRoadmap = Array.isArray(this.activeItem.roadmap) ? JSON.parse(JSON.stringify(this.activeItem.roadmap)) : [];
                 this.editParams = Array.isArray(this.activeItem.params) ? JSON.parse(JSON.stringify(this.activeItem.params)) : [];
                 this.editBenchmarks = Array.isArray(this.activeItem.benchmarks) ? JSON.parse(JSON.stringify(this.activeItem.benchmarks)) : [];
                 this.editMetrics = Array.isArray(this.activeItem.metrics) ? JSON.parse(JSON.stringify(this.activeItem.metrics)) : [];
                 this.editOpen = true;
             },

             openDetail(item) {
                 this.activeItem = JSON.parse(JSON.stringify(item));
                 this.detailTab = 'narrative';
                 this.detailOpen = true;
             },

             openEditById(id) {
                 if (this.items[id]) {
                     this.openEdit(this.items[id]);
                 }
             },

             openDetailById(id) {
                 if (this.items[id]) {
                     this.openDetail(this.items[id]);
                 }
             }
         }">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-gray-100 pb-5 gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-gray-900">Daftar Fasilitas & Riset</h2>
                <p class="text-xs text-gray-500">Kelola informasi laboratorium, petak uji, dan 5 dossier menu riset (Uraian, Roadmap, Parameter, Komparasi, Metrik).</p>
            </div>
            <div class="flex flex-wrap sm:flex-nowrap gap-2.5 w-full md:w-auto">
                <form action="{{ route('web.management.penelitian') }}" method="GET" class="flex flex-1 sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari fasilitas atau riset..."
                        class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/10 text-xs font-medium">
                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl ml-2 text-xs font-bold hover:bg-black transition">Cari</button>
                </form>
                <button @click="openCreate()" class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary-900 text-white font-bold py-2 px-4 rounded-xl text-xs shadow-md shrink-0 transition">
                    <span class="material-symbols-rounded text-sm">add</span> Tambah Riset
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/70 border-y border-gray-100 text-gray-600 text-xs uppercase tracking-wider font-bold">
                        <th class="p-4 w-20">Foto</th>
                        <th class="p-4">Judul Fasilitas / Riset</th>
                        <th class="p-4">Bidang & Status</th>
                        <th class="p-4">Dossier Menu</th>
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
                                     @click="openDetailById({{ $item->id }})">
                                    {{ $item->title }}
                                </div>
                            </td>
                            <td class="p-4 text-xs">
                                <span class="font-bold text-gray-700 block">{{ $item->field ?? 'Pemuliaan & Biosains' }}</span>
                                <span class="text-emerald-700 font-semibold">{{ $item->status_riset ?? 'Aktif Berjalan' }}</span>
                            </td>
                            <td class="p-4 text-xs">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold">Uraian</span>
                                    <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-bold">Roadmap ({{ is_array($item->roadmap) ? count($item->roadmap) : 'Default' }})</span>
                                    <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 text-[10px] font-bold">Params ({{ is_array($item->params) ? count($item->params) : 'Default' }})</span>
                                    <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 text-[10px] font-bold">Komparasi</span>
                                    <span class="px-2 py-0.5 rounded-md bg-teal-50 text-teal-700 text-[10px] font-bold">Metrik</span>
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end items-center gap-1.5">
                                    <button type="button" @click="openDetailById({{ $item->id }})"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg text-xs font-bold transition inline-flex items-center justify-center" title="Detail Dossier"><span class="material-symbols-rounded text-base">visibility</span></button>
                                    <button type="button" @click="openEditById({{ $item->id }})"
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg text-xs font-bold transition inline-flex items-center justify-center" title="Edit Dossier"><span class="material-symbols-rounded text-base">edit</span></button>
                                    <form action="{{ route('web.management.penelitian.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data riset ini?');" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg text-xs font-bold transition inline-flex items-center justify-center" title="Hapus"><span class="material-symbols-rounded text-base">delete</span></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400 text-xs">Belum ada data fasilitas atau riset.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($penelitianes, 'hasPages') && $penelitianes->hasPages())
            <div class="p-6 bg-white border-t border-gray-100 flex justify-center">
                {{ $penelitianes->links() }}
            </div>
        @endif

        <!-- ══════════════════════════════════════════════════════════════════ -->
        <!-- MODAL TAMBAH RISET (DENGAN 5 DOSSIER MENU CUSTOM) -->
        <!-- ══════════════════════════════════════════════════════════════════ -->
        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm p-3 sm:p-6 flex items-center justify-center" style="display: none;">
            <div class="bg-white rounded-3xl w-full max-w-4xl shadow-2xl border border-gray-100 my-auto flex flex-col max-h-[90vh] overflow-hidden" @click.away="open = false">
                <div class="p-5 sm:p-6 border-b border-gray-100 shrink-0 flex items-center justify-between bg-white">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900">Tambah Riset &amp; Konfigurasi Dossier</h3>
                        <p class="text-xs text-gray-500">Sesuaikan informasi dan 5 menu tab riset yang tampil di halaman publik.</p>
                    </div>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100 transition inline-flex items-center justify-center">
                        <span class="material-symbols-rounded text-xl">close</span>
                    </button>
                </div>

                <!-- Custom Menu Tabs Nav (Create Modal) -->
                <div class="px-5 sm:px-6 pt-3 pb-3 border-b border-gray-100 shrink-0 overflow-x-auto flex gap-2 bg-gray-50/70">
                    <button type="button" @click="tab = 'narrative'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shrink-0"
                        :class="tab === 'narrative' ? 'bg-primary text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'">
                        <span class="material-symbols-rounded text-sm">description</span> 1. Uraian &amp; Info
                    </button>
                    <button type="button" @click="tab = 'roadmap'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shrink-0"
                        :class="tab === 'roadmap' ? 'bg-primary text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'">
                        <span class="material-symbols-rounded text-sm">route</span> 2. Roadmap Riset
                    </button>
                    <button type="button" @click="tab = 'params'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shrink-0"
                        :class="tab === 'params' ? 'bg-primary text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'">
                        <span class="material-symbols-rounded text-sm">tune</span> 3. Parameter Teknis
                    </button>
                    <button type="button" @click="tab = 'comparison'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shrink-0"
                        :class="tab === 'comparison' ? 'bg-primary text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'">
                        <span class="material-symbols-rounded text-sm">balance</span> 4. Komparasi Lapangan
                    </button>
                    <button type="button" @click="tab = 'metrics'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shrink-0"
                        :class="tab === 'metrics' ? 'bg-primary text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'">
                        <span class="material-symbols-rounded text-sm">analytics</span> 5. Metrik &amp; Capaian
                    </button>
                </div>

                <form action="{{ route('web.management.penelitian.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                    @csrf
                    
                    <!-- Hidden JSON inputs for custom tabs -->
                    <input type="hidden" name="roadmap" :value="JSON.stringify(createRoadmap)">
                    <input type="hidden" name="params" :value="JSON.stringify(createParams)">
                    <input type="hidden" name="benchmarks" :value="JSON.stringify(createBenchmarks)">
                    <input type="hidden" name="metrics" :value="JSON.stringify(createMetrics)">

                    <div class="flex-1 min-h-0 overflow-y-auto p-5 sm:p-6 space-y-4">
                        <!-- TAB 1: Uraian & Info Utama -->
                        <div x-show="tab === 'narrative'" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Nama / Judul Riset <span class="text-red-500">*</span></label>
                                    <input type="text" name="title"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm font-semibold"
                                        placeholder="Contoh: Pemuliaan Bawang Merah Dataran Tinggi" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Bidang Riset</label>
                                    <input type="text" name="field"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm"
                                        placeholder="Contoh: Pemuliaan Benih & Hortikultura Dataran Tinggi">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Status Proyek</label>
                                    <input type="text" name="status_riset"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm"
                                        placeholder="Contoh: Fase 3: Uji Adaptasi & Panen Evaluasi">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Fasilitas / Laboratorium</label>
                                    <input type="text" name="facility"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm"
                                        placeholder="Contoh: Lab Biosains & Kebun Percobaan">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Lokasi Pengujian</label>
                                    <input type="text" name="location"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm"
                                        placeholder="Contoh: Desa Parsingguran II, Pollung (1.400 mdpl)">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Peneliti Utama</label>
                                    <input type="text" name="peneliti"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm"
                                        placeholder="Contoh: Dr. Ir. Budi Santoso, M.Sc & Tim Biosains">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Foto Fasilitas / Petak Riset <span class="text-red-500">*</span></label>
                                    <input type="file" name="image_path"
                                        class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white cursor-pointer"
                                        accept="image/*" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Tujuan &amp; Sasaran Riset</label>
                                <textarea name="tujuan" rows="3"
                                    class="w-full p-3.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm leading-relaxed"
                                    placeholder="Jelaskan target terukur dan tujuan ilmiah riset ini..."></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Deskripsi / Uraian Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="description" rows="5"
                                    class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary text-sm leading-relaxed"
                                    placeholder="Uraikan metodologi pengujian, latar belakang, dan hasil riset secara komprehensif..." required></textarea>
                            </div>
                        </div>

                        <!-- TAB 2: Roadmap Riset -->
                        <div x-show="tab === 'roadmap'" class="space-y-4">
                            <div class="flex items-center justify-between bg-blue-50/70 p-3.5 rounded-2xl border border-blue-100">
                                <div class="text-xs text-blue-900">
                                    <strong class="block font-bold">Kustomisasi Tahapan / Fase Riset</strong>
                                    Tahapan ini tampil pada tab kedua ("Roadmap &amp; Fase Riset") pada modal publik.
                                </div>
                                <button type="button" @click="createRoadmap.push({ title: 'Tahap Baru', desc: '', status: 'active', pct: '50%' })"
                                    class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs shadow-sm">
                                    <span class="material-symbols-rounded text-sm">add</span> Tambah Tahap
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(step, idx) in createRoadmap" :key="idx">
                                    <div class="p-4 border border-gray-200 rounded-2xl bg-gray-50/60 relative space-y-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-xs font-extrabold text-gray-500 uppercase" x-text="`Tahap #${idx + 1}`"></span>
                                            <button type="button" @click="createRoadmap.splice(idx, 1)" class="text-red-500 hover:text-red-700 text-xs font-bold inline-flex items-center gap-0.5">
                                                <span class="material-symbols-rounded text-sm">delete</span> Hapus
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div class="sm:col-span-2">
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Judul Tahap</label>
                                                <input type="text" x-model="step.title" class="w-full px-3 py-2 border rounded-xl text-xs bg-white" placeholder="Contoh: Fase 1: Uji Lab">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Status</label>
                                                <select x-model="step.status" class="w-full px-3 py-2 border rounded-xl text-xs bg-white">
                                                    <option value="completed">Selesai (100%)</option>
                                                    <option value="active">Sedang Berjalan</option>
                                                    <option value="planned">Fase Rencana</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                            <div class="sm:col-span-1">
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Progres (%)</label>
                                                <input type="text" x-model="step.pct" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-mono" placeholder="Contoh: 75%">
                                            </div>
                                            <div class="sm:col-span-3">
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Keterangan Aktivitas</label>
                                                <input type="text" x-model="step.desc" class="w-full px-3 py-2 border rounded-xl text-xs bg-white" placeholder="Uraian singkat pekerjaan pada tahap ini...">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- TAB 3: Parameter Agroklimat & Lab -->
                        <div x-show="tab === 'params'" class="space-y-4">
                            <div class="flex items-center justify-between bg-purple-50/70 p-3.5 rounded-2xl border border-purple-100">
                                <div class="text-xs text-purple-900">
                                    <strong class="block font-bold">Kustomisasi Parameter Teknis</strong>
                                    Kondisi agroklimatologi, elevasi, pH tanah, atau instrumen lab (Tab 3: "Parameter Agroklimat &amp; Lab").
                                </div>
                                <button type="button" @click="createParams.push({ key: '', val: '' })"
                                    class="inline-flex items-center gap-1 bg-purple-600 hover:bg-purple-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs shadow-sm">
                                    <span class="material-symbols-rounded text-sm">add</span> Tambah Parameter
                                </button>
                            </div>

                            <div class="space-y-2.5">
                                <template x-for="(p, idx) in createParams" :key="idx">
                                    <div class="flex items-center gap-3 p-2.5 border border-gray-200 rounded-xl bg-gray-50/60">
                                        <div class="flex-1">
                                            <input type="text" x-model="p.key" class="w-full px-3 py-2 border rounded-xl text-xs bg-white" placeholder="Nama Parameter (Contoh: Ketinggian / pH)">
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" x-model="p.val" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-semibold" placeholder="Nilai / Spesifikasi (Contoh: 1.400 mdpl)">
                                        </div>
                                        <button type="button" @click="createParams.splice(idx, 1)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                            <span class="material-symbols-rounded text-base">delete</span>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- TAB 4: Komparasi Lapangan -->
                        <div x-show="tab === 'comparison'" class="space-y-4">
                            <div class="flex items-center justify-between bg-amber-50/70 p-3.5 rounded-2xl border border-amber-100">
                                <div class="text-xs text-amber-900">
                                    <strong class="block font-bold">Kustomisasi Komparasi Lapangan</strong>
                                    Perbandingan inovasi riset TSTH2 terhadap metode konvensional (Tab 4: "Komparasi Lapangan").
                                </div>
                                <button type="button" @click="createBenchmarks.push({ title: 'Parameter Komparasi', note: '+30% Peningkatan', tsth2_val: '12 Ton/Ha', tsth2_pct: 85, conv_val: '9 Ton/Ha', conv_pct: 60 })"
                                    class="inline-flex items-center gap-1 bg-amber-600 hover:bg-amber-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs shadow-sm">
                                    <span class="material-symbols-rounded text-sm">add</span> Tambah Komparasi
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(b, idx) in createBenchmarks" :key="idx">
                                    <div class="p-4 border border-gray-200 rounded-2xl bg-gray-50/60 space-y-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-xs font-extrabold text-gray-500 uppercase" x-text="`Perbandingan #${idx + 1}`"></span>
                                            <button type="button" @click="createBenchmarks.splice(idx, 1)" class="text-red-500 hover:text-red-700 text-xs font-bold inline-flex items-center gap-0.5">
                                                <span class="material-symbols-rounded text-sm">delete</span> Hapus
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Indikator Komparasi</label>
                                                <input type="text" x-model="b.title" class="w-full px-3 py-2 border rounded-xl text-xs bg-white" placeholder="Contoh: Produktivitas Panen">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Badge Keunggulan</label>
                                                <input type="text" x-model="b.note" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-bold text-emerald-700" placeholder="Contoh: +48% Lebih Tinggi">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                            <div>
                                                <label class="block text-[11px] font-bold text-emerald-800 mb-1">Nilai TSTH2</label>
                                                <input type="text" x-model="b.tsth2_val" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-bold text-emerald-700" placeholder="14.2 Ton/Ha">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-emerald-800 mb-1">Panjang Bar (%)</label>
                                                <input type="number" x-model="b.tsth2_pct" min="0" max="100" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-mono" placeholder="90">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Nilai Konvensional</label>
                                                <input type="text" x-model="b.conv_val" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-medium text-gray-600" placeholder="9.6 Ton/Ha">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Panjang Bar (%)</label>
                                                <input type="number" x-model="b.conv_pct" min="0" max="100" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-mono" placeholder="60">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- TAB 5: Metrik & Capaian -->
                        <div x-show="tab === 'metrics'" class="space-y-4">
                            <div class="flex items-center justify-between bg-teal-50/70 p-3.5 rounded-2xl border border-teal-100">
                                <div class="text-xs text-teal-900">
                                    <strong class="block font-bold">Kustomisasi Metrik &amp; Capaian</strong>
                                    Kartu angka capaian utama hasil riset (Tab 5: "Metrik &amp; Capaian").
                                </div>
                                <button type="button" @click="createMetrics.push({ val: '10 Ton/Ha', label: 'Estimasi Hasil', badge: 'Teruji', badge_color: '#166534', badge_bg: '#dcfce7', sub: 'Rata-rata uji coba' })"
                                    class="inline-flex items-center gap-1 bg-teal-600 hover:bg-teal-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs shadow-sm">
                                    <span class="material-symbols-rounded text-sm">add</span> Tambah Metrik
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(m, idx) in createMetrics" :key="idx">
                                    <div class="p-4 border border-gray-200 rounded-2xl bg-gray-50/60 space-y-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-xs font-extrabold text-gray-500 uppercase" x-text="`Metrik #${idx + 1}`"></span>
                                            <button type="button" @click="createMetrics.splice(idx, 1)" class="text-red-500 hover:text-red-700 text-xs font-bold inline-flex items-center gap-0.5">
                                                <span class="material-symbols-rounded text-sm">delete</span> Hapus
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Nilai Angka / Capaian</label>
                                                <input type="text" x-model="m.val" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-black text-gray-900" placeholder="Contoh: 14.2 Ton/Ha">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Nama Indikator / Label</label>
                                                <input type="text" x-model="m.label" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-bold" placeholder="Contoh: Potensi Produktivitas">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Badge Tag</label>
                                                <input type="text" x-model="m.badge" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-bold text-emerald-700" placeholder="Contoh: Grade Super A">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-600 mb-1">Keterangan / Subteks</label>
                                            <input type="text" x-model="m.sub" class="w-full px-3 py-2 border rounded-xl text-xs bg-white" placeholder="Contoh: Rata-rata bobot umbi di lahan dataran tinggi">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Sticky Action Footer -->
                    <div class="p-4 sm:px-6 bg-gray-50 border-t border-gray-100 shrink-0 flex justify-between items-center">
                        <div class="text-[11px] text-gray-400">
                            Kosongkan tab jika tidak ingin ditampilkan di halaman detail publik.
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="open = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-200 rounded-xl text-xs transition">Batal</button>
                            <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-900 text-white font-bold rounded-xl text-xs shadow-md transition">Simpan Riset &amp; Dossier</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════════ -->
        <!-- MODAL EDIT RISET (DENGAN 5 DOSSIER MENU CUSTOM) -->
        <!-- ══════════════════════════════════════════════════════════════════ -->
        <div x-show="editOpen" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm p-3 sm:p-6 flex items-center justify-center" style="display: none;">
            <div class="bg-white rounded-3xl w-full max-w-4xl shadow-2xl border border-gray-100 my-auto flex flex-col max-h-[90vh] overflow-hidden" @click.away="editOpen = false">
                <div class="p-5 sm:p-6 border-b border-gray-100 shrink-0 flex items-center justify-between bg-white">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900">Edit Data Riset &amp; Konfigurasi Dossier</h3>
                        <p class="text-xs text-gray-500">Perbarui informasi utama atau kustomisasi 5 menu tab riset.</p>
                    </div>
                    <button type="button" @click="editOpen = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100 transition inline-flex items-center justify-center">
                        <span class="material-symbols-rounded text-xl">close</span>
                    </button>
                </div>

                <!-- Custom Menu Tabs Nav (Edit Modal) -->
                <div class="px-5 sm:px-6 pt-3 pb-3 border-b border-gray-100 shrink-0 overflow-x-auto flex gap-2 bg-gray-50/70">
                    <button type="button" @click="editTab = 'narrative'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shrink-0"
                        :class="editTab === 'narrative' ? 'bg-amber-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'">
                        <span class="material-symbols-rounded text-sm">description</span> 1. Uraian &amp; Info
                    </button>
                    <button type="button" @click="editTab = 'roadmap'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shrink-0"
                        :class="editTab === 'roadmap' ? 'bg-amber-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'">
                        <span class="material-symbols-rounded text-sm">route</span> 2. Roadmap Riset
                    </button>
                    <button type="button" @click="editTab = 'params'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shrink-0"
                        :class="editTab === 'params' ? 'bg-amber-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'">
                        <span class="material-symbols-rounded text-sm">tune</span> 3. Parameter Teknis
                    </button>
                    <button type="button" @click="editTab = 'comparison'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shrink-0"
                        :class="editTab === 'comparison' ? 'bg-amber-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'">
                        <span class="material-symbols-rounded text-sm">balance</span> 4. Komparasi Lapangan
                    </button>
                    <button type="button" @click="editTab = 'metrics'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shrink-0"
                        :class="editTab === 'metrics' ? 'bg-amber-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'">
                        <span class="material-symbols-rounded text-sm">analytics</span> 5. Metrik &amp; Capaian
                    </button>
                </div>

                <form :action="activeItem.id ? ('{{ url('/web-management/penelitian') }}/' + activeItem.id) : '#'" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                    @csrf @method('PUT')

                    <!-- Hidden JSON inputs for custom tabs -->
                    <input type="hidden" name="roadmap" :value="JSON.stringify(editRoadmap)">
                    <input type="hidden" name="params" :value="JSON.stringify(editParams)">
                    <input type="hidden" name="benchmarks" :value="JSON.stringify(editBenchmarks)">
                    <input type="hidden" name="metrics" :value="JSON.stringify(editMetrics)">

                    <div class="flex-1 min-h-0 overflow-y-auto p-5 sm:p-6 space-y-4">
                        <!-- EDIT TAB 1: Uraian & Info Utama -->
                        <div x-show="editTab === 'narrative'" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Nama / Judul Riset <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" x-model="activeItem.title"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm font-semibold" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Bidang Riset</label>
                                    <input type="text" name="field" x-model="activeItem.field"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Status Proyek</label>
                                    <input type="text" name="status_riset" x-model="activeItem.status_riset"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Fasilitas / Lab</label>
                                    <input type="text" name="facility" x-model="activeItem.facility"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Lokasi Pengujian</label>
                                    <input type="text" name="location" x-model="activeItem.location"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Peneliti Utama</label>
                                    <input type="text" name="peneliti" x-model="activeItem.peneliti"
                                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Ganti Foto (Opsional)</label>
                                    <input type="file" name="image_path"
                                        class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white cursor-pointer"
                                        accept="image/*">
                                    <template x-if="activeItem.image_path">
                                        <div class="mt-2 flex items-center gap-2">
                                            <img :src="`{{ asset('storage') }}/${activeItem.image_path}`" class="h-10 w-16 object-cover rounded-lg border border-gray-200 shadow-sm">
                                            <span class="text-[11px] text-gray-500">Foto aktif saat ini</span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Tujuan &amp; Sasaran Riset</label>
                                <textarea name="tujuan" x-model="activeItem.tujuan" rows="3"
                                    class="w-full p-3.5 border-2 border-gray-200 rounded-xl focus:border-primary text-sm leading-relaxed"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Deskripsi / Uraian Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="description" x-model="activeItem.description" rows="5"
                                    class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary text-sm leading-relaxed" required></textarea>
                            </div>
                        </div>

                        <!-- EDIT TAB 2: Roadmap Riset -->
                        <div x-show="editTab === 'roadmap'" class="space-y-4">
                            <div class="flex items-center justify-between bg-blue-50/70 p-3.5 rounded-2xl border border-blue-100">
                                <div class="text-xs text-blue-900">
                                    <strong class="block font-bold">Kustomisasi Tahapan / Fase Riset</strong>
                                    Daftar tahapan pada tab kedua ("Roadmap &amp; Fase Riset").
                                </div>
                                <button type="button" @click="editRoadmap.push({ title: 'Tahap Baru', desc: '', status: 'active', pct: '50%' })"
                                    class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs shadow-sm">
                                    <span class="material-symbols-rounded text-sm">add</span> Tambah Tahap
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(step, idx) in editRoadmap" :key="idx">
                                    <div class="p-4 border border-gray-200 rounded-2xl bg-gray-50/60 space-y-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-xs font-extrabold text-gray-500 uppercase" x-text="`Tahap #${idx + 1}`"></span>
                                            <button type="button" @click="editRoadmap.splice(idx, 1)" class="text-red-500 hover:text-red-700 text-xs font-bold inline-flex items-center gap-0.5">
                                                <span class="material-symbols-rounded text-sm">delete</span> Hapus
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div class="sm:col-span-2">
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Judul Tahap</label>
                                                <input type="text" x-model="step.title" class="w-full px-3 py-2 border rounded-xl text-xs bg-white">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Status</label>
                                                <select x-model="step.status" class="w-full px-3 py-2 border rounded-xl text-xs bg-white">
                                                    <option value="completed">Selesai (100%)</option>
                                                    <option value="active">Sedang Berjalan</option>
                                                    <option value="planned">Fase Rencana</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                            <div class="sm:col-span-1">
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Progres (%)</label>
                                                <input type="text" x-model="step.pct" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-mono">
                                            </div>
                                            <div class="sm:col-span-3">
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Keterangan Aktivitas</label>
                                                <input type="text" x-model="step.desc" class="w-full px-3 py-2 border rounded-xl text-xs bg-white">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- EDIT TAB 3: Parameter Agroklimat & Lab -->
                        <div x-show="editTab === 'params'" class="space-y-4">
                            <div class="flex items-center justify-between bg-purple-50/70 p-3.5 rounded-2xl border border-purple-100">
                                <div class="text-xs text-purple-900">
                                    <strong class="block font-bold">Kustomisasi Parameter Teknis</strong>
                                    Daftar parameter agroklimat dan profil pengujian (Tab 3: "Parameter Agroklimat &amp; Lab").
                                </div>
                                <button type="button" @click="editParams.push({ key: '', val: '' })"
                                    class="inline-flex items-center gap-1 bg-purple-600 hover:bg-purple-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs shadow-sm">
                                    <span class="material-symbols-rounded text-sm">add</span> Tambah Parameter
                                </button>
                            </div>

                            <div class="space-y-2.5">
                                <template x-for="(p, idx) in editParams" :key="idx">
                                    <div class="flex items-center gap-3 p-2.5 border border-gray-200 rounded-xl bg-gray-50/60">
                                        <div class="flex-1">
                                            <input type="text" x-model="p.key" class="w-full px-3 py-2 border rounded-xl text-xs bg-white" placeholder="Nama Parameter">
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" x-model="p.val" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-semibold" placeholder="Nilai / Spesifikasi">
                                        </div>
                                        <button type="button" @click="editParams.splice(idx, 1)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                            <span class="material-symbols-rounded text-base">delete</span>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- EDIT TAB 4: Komparasi Lapangan -->
                        <div x-show="editTab === 'comparison'" class="space-y-4">
                            <div class="flex items-center justify-between bg-amber-50/70 p-3.5 rounded-2xl border border-amber-100">
                                <div class="text-xs text-amber-900">
                                    <strong class="block font-bold">Kustomisasi Komparasi Lapangan</strong>
                                    Perbandingan inovasi TSTH2 vs metode konvensional (Tab 4: "Komparasi Lapangan").
                                </div>
                                <button type="button" @click="editBenchmarks.push({ title: 'Parameter Komparasi', note: '+30% Peningkatan', tsth2_val: '12 Ton/Ha', tsth2_pct: 85, conv_val: '9 Ton/Ha', conv_pct: 60 })"
                                    class="inline-flex items-center gap-1 bg-amber-600 hover:bg-amber-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs shadow-sm">
                                    <span class="material-symbols-rounded text-sm">add</span> Tambah Komparasi
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(b, idx) in editBenchmarks" :key="idx">
                                    <div class="p-4 border border-gray-200 rounded-2xl bg-gray-50/60 space-y-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-xs font-extrabold text-gray-500 uppercase" x-text="`Perbandingan #${idx + 1}`"></span>
                                            <button type="button" @click="editBenchmarks.splice(idx, 1)" class="text-red-500 hover:text-red-700 text-xs font-bold inline-flex items-center gap-0.5">
                                                <span class="material-symbols-rounded text-sm">delete</span> Hapus
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Indikator Komparasi</label>
                                                <input type="text" x-model="b.title" class="w-full px-3 py-2 border rounded-xl text-xs bg-white">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Badge Keunggulan</label>
                                                <input type="text" x-model="b.note" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-bold text-emerald-700">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                            <div>
                                                <label class="block text-[11px] font-bold text-emerald-800 mb-1">Nilai TSTH2</label>
                                                <input type="text" x-model="b.tsth2_val" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-bold text-emerald-700">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-emerald-800 mb-1">Panjang Bar (%)</label>
                                                <input type="number" x-model="b.tsth2_pct" min="0" max="100" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-mono">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Nilai Konvensional</label>
                                                <input type="text" x-model="b.conv_val" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-medium text-gray-600">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Panjang Bar (%)</label>
                                                <input type="number" x-model="b.conv_pct" min="0" max="100" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-mono">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- EDIT TAB 5: Metrik & Capaian -->
                        <div x-show="editTab === 'metrics'" class="space-y-4">
                            <div class="flex items-center justify-between bg-teal-50/70 p-3.5 rounded-2xl border border-teal-100">
                                <div class="text-xs text-teal-900">
                                    <strong class="block font-bold">Kustomisasi Metrik &amp; Capaian</strong>
                                    Daftar kartu capaian utama hasil riset (Tab 5: "Metrik &amp; Capaian").
                                </div>
                                <button type="button" @click="editMetrics.push({ val: '10 Ton/Ha', label: 'Estimasi Hasil', badge: 'Teruji', badge_color: '#166534', badge_bg: '#dcfce7', sub: 'Rata-rata uji coba' })"
                                    class="inline-flex items-center gap-1 bg-teal-600 hover:bg-teal-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs shadow-sm">
                                    <span class="material-symbols-rounded text-sm">add</span> Tambah Metrik
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(m, idx) in editMetrics" :key="idx">
                                    <div class="p-4 border border-gray-200 rounded-2xl bg-gray-50/60 space-y-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-xs font-extrabold text-gray-500 uppercase" x-text="`Metrik #${idx + 1}`"></span>
                                            <button type="button" @click="editMetrics.splice(idx, 1)" class="text-red-500 hover:text-red-700 text-xs font-bold inline-flex items-center gap-0.5">
                                                <span class="material-symbols-rounded text-sm">delete</span> Hapus
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Nilai Angka / Capaian</label>
                                                <input type="text" x-model="m.val" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-black text-gray-900">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Nama Indikator / Label</label>
                                                <input type="text" x-model="m.label" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Badge Tag</label>
                                                <input type="text" x-model="m.badge" class="w-full px-3 py-2 border rounded-xl text-xs bg-white font-bold text-emerald-700">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-600 mb-1">Keterangan / Subteks</label>
                                            <input type="text" x-model="m.sub" class="w-full px-3 py-2 border rounded-xl text-xs bg-white">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Sticky Action Footer -->
                    <div class="p-4 sm:px-6 bg-gray-50 border-t border-gray-100 shrink-0 flex justify-between items-center">
                        <div class="text-[11px] text-gray-400">
                            Perubahan seluruh tab otomatis diperbarui ke database.
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="editOpen = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-200 rounded-xl text-xs transition">Batal</button>
                            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs shadow-md transition">Perbarui Riset &amp; Dossier</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════════ -->
        <!-- MODAL DETAIL RISET DENGAN TAB PREVIEW LENGKAP -->
        <!-- ══════════════════════════════════════════════════════════════════ -->
        <div x-show="detailOpen" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm p-3 sm:p-6 flex items-center justify-center" style="display: none;">
            <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl border border-gray-100 my-auto flex flex-col max-h-[90vh] overflow-hidden" @click.away="detailOpen = false">
                <div class="p-5 sm:p-6 border-b border-gray-100 shrink-0 flex items-center justify-between bg-white">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900" x-text="activeItem.title"></h3>
                    </div>
                    <button type="button" @click="detailOpen = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100 transition inline-flex items-center justify-center">
                        <span class="material-symbols-rounded text-xl">close</span>
                    </button>
                </div>

                <!-- Tabs Preview in Detail Modal -->
                <div class="px-5 sm:px-6 pt-3 pb-3 border-b border-gray-100 shrink-0 overflow-x-auto flex gap-1.5 bg-gray-50/70">
                    <button type="button" @click="detailTab = 'narrative'"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition inline-flex items-center gap-1 shrink-0"
                        :class="detailTab === 'narrative' ? 'bg-primary text-white' : 'text-gray-500 hover:bg-gray-100'">
                        <span class="material-symbols-rounded text-sm">description</span> Uraian
                    </button>
                    <button type="button" @click="detailTab = 'roadmap'"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition inline-flex items-center gap-1 shrink-0"
                        :class="detailTab === 'roadmap' ? 'bg-primary text-white' : 'text-gray-500 hover:bg-gray-100'">
                        <span class="material-symbols-rounded text-sm">route</span> Roadmap
                    </button>
                    <button type="button" @click="detailTab = 'params'"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition inline-flex items-center gap-1 shrink-0"
                        :class="detailTab === 'params' ? 'bg-primary text-white' : 'text-gray-500 hover:bg-gray-100'">
                        <span class="material-symbols-rounded text-sm">tune</span> Parameter
                    </button>
                    <button type="button" @click="detailTab = 'comparison'"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition inline-flex items-center gap-1 shrink-0"
                        :class="detailTab === 'comparison' ? 'bg-primary text-white' : 'text-gray-500 hover:bg-gray-100'">
                        <span class="material-symbols-rounded text-sm">balance</span> Komparasi
                    </button>
                    <button type="button" @click="detailTab = 'metrics'"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition inline-flex items-center gap-1 shrink-0"
                        :class="detailTab === 'metrics' ? 'bg-primary text-white' : 'text-gray-500 hover:bg-gray-100'">
                        <span class="material-symbols-rounded text-sm">analytics</span> Metrik
                    </button>
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto p-5 sm:p-6 space-y-4">
                    <!-- Tab 1: Narrative Preview -->
                    <div x-show="detailTab === 'narrative'" class="space-y-4">
                        <template x-if="activeItem.image_path">
                            <img :src="`/storage/${activeItem.image_path}`" class="w-full h-52 object-cover rounded-2xl shadow-sm border border-gray-100">
                        </template>
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="p-3 rounded-xl bg-gray-50">
                                <span class="text-gray-400 block font-bold">Peneliti Utama</span>
                                <span class="font-bold text-gray-800" x-text="activeItem.peneliti || 'Tim Riset TSTH2'"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-gray-50">
                                <span class="text-gray-400 block font-bold">Bidang</span>
                                <span class="font-bold text-gray-800" x-text="activeItem.field || 'Bioteknologi'"></span>
                            </div>
                        </div>
                        <template x-if="activeItem.tujuan">
                            <div class="p-4 rounded-xl bg-emerald-50 text-emerald-900 border border-emerald-100 text-xs">
                                <strong class="block font-bold mb-1">Tujuan &amp; Sasaran:</strong>
                                <span x-text="activeItem.tujuan"></span>
                            </div>
                        </template>
                        <div class="pt-2 border-t border-gray-100">
                            <h4 class="text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi Lengkap:</h4>
                            <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-wrap" x-text="activeItem.description"></p>
                        </div>
                    </div>

                    <!-- Tab 2: Roadmap Preview -->
                    <div x-show="detailTab === 'roadmap'" class="space-y-3">
                        <template x-if="Array.isArray(activeItem.roadmap) && activeItem.roadmap.length > 0">
                            <div class="space-y-2">
                                <template x-for="(s, i) in activeItem.roadmap" :key="i">
                                    <div class="p-3 rounded-xl border border-gray-100 bg-gray-50 flex items-start justify-between gap-3 text-xs">
                                        <div>
                                            <strong class="font-bold text-gray-900 block" x-text="s.title"></strong>
                                            <span class="text-gray-500" x-text="s.desc"></span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-full font-bold text-[10px] shrink-0"
                                            :class="s.status === 'completed' ? 'bg-emerald-100 text-emerald-800' : (s.status === 'active' ? 'bg-amber-100 text-amber-800' : 'bg-gray-200 text-gray-700')"
                                            x-text="s.pct || s.status"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="!Array.isArray(activeItem.roadmap) || activeItem.roadmap.length === 0">
                            <div class="text-center py-6 text-xs text-gray-400">Belum ada tahapan roadmap yang ditambahkan.</div>
                        </template>
                    </div>

                    <!-- Tab 3: Params Preview -->
                    <div x-show="detailTab === 'params'" class="space-y-2">
                        <template x-if="Array.isArray(activeItem.params) && activeItem.params.length > 0">
                            <div class="divide-y divide-gray-100 border border-gray-100 rounded-xl overflow-hidden">
                                <template x-for="(p, i) in activeItem.params" :key="i">
                                    <div class="flex justify-between p-3 text-xs bg-white odd:bg-gray-50">
                                        <span class="text-gray-500 font-medium" x-text="p.key"></span>
                                        <span class="font-bold text-gray-900" x-text="p.val"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="!Array.isArray(activeItem.params) || activeItem.params.length === 0">
                            <div class="text-center py-6 text-xs text-gray-400">Belum ada parameter teknis yang ditambahkan.</div>
                        </template>
                    </div>

                    <!-- Tab 4: Comparison Preview -->
                    <div x-show="detailTab === 'comparison'" class="space-y-3">
                        <template x-if="Array.isArray(activeItem.benchmarks) && activeItem.benchmarks.length > 0">
                            <div class="space-y-2.5">
                                <template x-for="(b, i) in activeItem.benchmarks" :key="i">
                                    <div class="p-3 rounded-xl border border-gray-100 bg-gray-50 text-xs space-y-1.5">
                                        <div class="flex justify-between font-bold">
                                            <span x-text="b.title"></span>
                                            <span class="text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded" x-text="b.note"></span>
                                        </div>
                                        <div class="flex justify-between text-gray-500 text-[11px]">
                                            <span>TSTH2: <strong class="text-emerald-800" x-text="b.tsth2_val"></strong></span>
                                            <span>Konvensional: <strong class="text-gray-700" x-text="b.conv_val"></strong></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="!Array.isArray(activeItem.benchmarks) || activeItem.benchmarks.length === 0">
                            <div class="text-center py-6 text-xs text-gray-400">Belum ada komparasi lapangan yang ditambahkan.</div>
                        </template>
                    </div>

                    <!-- Tab 5: Metrics Preview -->
                    <div x-show="detailTab === 'metrics'" class="space-y-3">
                        <template x-if="Array.isArray(activeItem.metrics) && activeItem.metrics.length > 0">
                            <div class="grid grid-cols-2 gap-2.5">
                                <template x-for="(m, i) in activeItem.metrics" :key="i">
                                    <div class="p-3 rounded-xl border border-gray-100 bg-gray-50 text-xs">
                                        <div class="text-lg font-extrabold text-emerald-900" x-text="m.val"></div>
                                        <div class="font-bold text-gray-800 text-[11px]" x-text="m.label"></div>
                                        <div class="text-gray-400 text-[10px] mt-0.5" x-text="m.sub"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="!Array.isArray(activeItem.metrics) || activeItem.metrics.length === 0">
                            <div class="text-center py-6 text-xs text-gray-400">Belum ada metrik capaian yang ditambahkan.</div>
                        </template>
                    </div>
                </div>

                <div class="p-4 sm:px-6 bg-gray-50 border-t border-gray-100 shrink-0 flex justify-end">
                    <button type="button" @click="detailOpen = false" class="px-6 py-2.5 bg-gray-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
