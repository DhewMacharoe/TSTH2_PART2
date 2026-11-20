@extends('layouts.dasbor')

@section('title', 'Katalog Tanaman - TSTH2 Web Management')

@section('header_title')
    <div>
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Katalog Tanaman</h2>
        <p class="text-xs text-gray-500 font-medium">Kelola pengantar katalog publik dan seluruh data koleksi tanaman herbal & hortikultura.</p>
    </div>
@endsection

@section('content')
    <!-- Top Header Banner -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-primary-900 via-primary-800 to-primary-950 p-6 sm:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-accent/20 text-accent border border-accent/30 mb-3">
                <span class="material-symbols-rounded text-sm">potted_plant</span> Koleksi Tanaman & Herbal
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Manajemen Katalog Tanaman</h1>
            <p class="text-white/70 text-sm mt-1 max-w-xl">Kelola teks pengantar halaman katalog publik serta seluruh koleksi data plasma nutfah tanaman herbal dan hortikultura TSTH2.</p>
        </div>
        <div class="relative z-10 shrink-0">
            <a href="{{ route('katalog') }}" target="_blank" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl text-xs font-bold backdrop-blur-sm transition-all hover:scale-105 shadow-sm">
                <span class="material-symbols-rounded text-sm">public</span> Lihat Katalog Publik
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

    <!-- BAGIAN 1: KONTEN STATIS HERO KATALOG -->
    <form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data" class="mb-8">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4 mb-6">
                <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-900 flex items-center justify-center font-bold text-lg">
                    <span class="material-symbols-rounded text-xl">edit_note</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Teks Pengantar Header Katalog Publik</h3>
                    <p class="text-xs text-gray-500">Teks judul dan deskripsi yang tampil di header halaman katalog publik.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-bold text-xs text-gray-700 uppercase tracking-wider mb-2">Judul Header Katalog</label>
                    <input type="text" name="katalog_hero_title" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all text-sm text-gray-800 bg-gray-50/50 hover:bg-white focus:bg-white" value="{{ $konten['katalog_hero_title']->value ?? '' }}" placeholder="Contoh: Koleksi Tanaman Herbal & Hortikultura">
                    <p class="text-[11px] text-gray-400 mt-1.5">Judul besar di bagian atas halaman katalog.</p>
                </div>
                <div>
                    <label class="block font-bold text-xs text-gray-700 uppercase tracking-wider mb-2">Deskripsi Header Katalog</label>
                    <textarea name="katalog_hero_desc" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all text-sm text-gray-800 bg-gray-50/50 hover:bg-white focus:bg-white resize-none" placeholder="Contoh: Jelajahi koleksi plasma nutfah tanaman obat dan hortikultura Indonesia di TSTH2.">{{ $konten['katalog_hero_desc']->value ?? '' }}</textarea>
                    <p class="text-[11px] text-gray-400 mt-1.5">Ringkasan singkat di bawah judul header katalog.</p>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-primary-900 hover:bg-primary-800 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-all hover:scale-105 text-sm">
                    <span class="material-symbols-rounded text-sm">save</span> Simpan Header Katalog
                </button>
            </div>
        </div>
    </form>

    <!-- BAGIAN 2: DAFTAR & KELOLA DATA TANAMAN -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 mb-8 shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-gray-100 pb-5">
            <div>
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2.5">
                    <span class="material-symbols-rounded text-emerald-700 text-xl">potted_plant</span> Daftar Koleksi Tanaman
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Tambah spesies baru, edit taksonomi, sesuaikan lokasi, dan atur status tayang.</p>
            </div>
            <div class="flex flex-wrap gap-2.5 w-full sm:w-auto">
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 px-4 py-2.5 text-xs font-bold text-gray-700 transition">
                    <span class="material-symbols-rounded text-sm">folder</span> Kelola Kategori
                </a>
                <a href="{{ route('admin.plants.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-900 hover:bg-primary-800 px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:scale-105">
                    <span class="material-symbols-rounded text-sm">add</span> Tambah Tanaman
                </a>
            </div>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="flex items-center gap-4 rounded-2xl border border-emerald-100 bg-emerald-50/50 p-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800 font-bold shadow-inner">
                    <span class="material-symbols-rounded text-2xl">roofing</span>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Greenhouse</p>
                    <h4 class="text-xl font-extrabold text-gray-900">{{ $summary['greenhouse'] }} <span class="text-xs font-normal text-gray-500">Spesies</span></h4>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-amber-100 bg-amber-50/50 p-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-800 font-bold shadow-inner">
                    <span class="material-symbols-rounded text-2xl">eco</span>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Outdoor / Koleksi</p>
                    <h4 class="text-xl font-extrabold text-gray-900">{{ $summary['outdoor'] }} <span class="text-xs font-normal text-gray-500">Spesies</span></h4>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-blue-100 bg-blue-50/50 p-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-800 font-bold shadow-inner">
                    <span class="material-symbols-rounded text-2xl">bar_chart</span>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-700">Total Populasi</p>
                    <h4 class="text-xl font-extrabold text-gray-900">{{ number_format($summary['population'], 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">Tanaman</span></h4>
                </div>
            </div>
        </div>

        <!-- Search Bar & Filter Form -->
        <div class="mb-6 p-4 rounded-2xl bg-gray-50 border border-gray-200/80">
            <form method="GET" action="{{ route('web.management.katalog') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="relative flex min-w-0 flex-1 items-center">
                    <span class="pointer-events-none absolute left-3.5 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama tanaman, nama latin, kategori, atau lokasi..." class="h-10 w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 text-xs font-medium text-gray-700 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 focus:outline-none transition">
                </div>

                @php
                    $selectedCategoryIds = array_map('strval', (array) request('category', []));
                    $selectedCategoryNames = $categories->whereIn('id', $selectedCategoryIds)->pluck('name')->all();
                    $categoryButtonLabel = $selectedCategoryNames ? implode(', ', $selectedCategoryNames) : 'Semua kategori';
                    $statusButtonLabel = match (request('status')) {
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif',
                        default => 'Semua status',
                    };
                @endphp

                <div class="flex flex-wrap gap-2">
                    <!-- Dropdown Kategori -->
                    <div class="relative shrink-0">
                        <button type="button" onclick="toggleDropdown('dropdown-filter-category')" class="flex h-10 items-center justify-between gap-2 rounded-xl bg-white px-4 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 focus:outline-none shadow-sm transition">
                            <span class="truncate max-w-[140px]">{{ $categoryButtonLabel }}</span>
                            <span class="text-[10px] text-gray-400">▼</span>
                        </button>
                        <div id="dropdown-filter-category" class="sort-dropdown hidden absolute right-0 top-12 z-50 w-64 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl">
                            <div class="max-h-64 space-y-1 overflow-y-auto p-3">
                                <label class="flex cursor-pointer items-center gap-2 rounded-xl px-2.5 py-1.5 text-xs font-bold text-gray-800 hover:bg-gray-50 transition">
                                    <input type="checkbox" id="all-categories" @checked(!$selectedCategoryIds) class="h-4 w-4 rounded accent-primary-900">
                                    <span>Semua kategori</span>
                                </label>
                                @foreach ($categories as $category)
                                    <label class="flex cursor-pointer items-center gap-2 rounded-xl px-2.5 py-1.5 text-xs text-gray-700 hover:bg-gray-50 transition">
                                        <input type="checkbox" name="category[]" value="{{ $category->id }}" @checked(in_array((string) $category->id, $selectedCategoryIds, true)) class="category-option h-4 w-4 rounded accent-primary-900">
                                        <span>{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-3 py-2.5 bg-gray-50">
                                <a href="{{ route('web.management.katalog', request()->except('category', 'page')) }}" class="px-3 py-1.5 text-xs font-bold text-gray-500 hover:bg-gray-200 rounded-lg transition">Reset</a>
                                <button type="submit" class="px-4 py-1.5 bg-primary-900 text-xs font-bold text-white rounded-lg shadow-sm hover:bg-primary-800 transition">Tampilkan</button>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Status -->
                    <div class="relative shrink-0">
                        <button type="button" onclick="toggleDropdown('dropdown-filter-status')" class="flex h-10 items-center justify-between gap-2 rounded-xl bg-white px-4 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 focus:outline-none shadow-sm transition">
                            <span>{{ $statusButtonLabel }}</span>
                            <span class="text-[10px] text-gray-400">▼</span>
                        </button>
                        <div id="dropdown-filter-status" class="sort-dropdown hidden absolute right-0 top-12 z-50 w-48 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl">
                            <div class="p-3 space-y-1">
                                <label class="flex cursor-pointer items-center gap-2 rounded-xl px-2.5 py-1.5 text-xs text-gray-700 hover:bg-gray-50 transition">
                                    <input type="radio" name="status" value="" @checked(!request('status')) class="h-4 w-4 accent-primary-900">
                                    <span>Semua status</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2 rounded-xl px-2.5 py-1.5 text-xs text-gray-700 hover:bg-gray-50 transition">
                                    <input type="radio" name="status" value="active" @checked(request('status') === 'active') class="h-4 w-4 accent-primary-900">
                                    <span>Aktif</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2 rounded-xl px-2.5 py-1.5 text-xs text-gray-700 hover:bg-gray-50 transition">
                                    <input type="radio" name="status" value="inactive" @checked(request('status') === 'inactive') class="h-4 w-4 accent-primary-900">
                                    <span>Nonaktif</span>
                                </label>
                            </div>
                            <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-3 py-2.5 bg-gray-50">
                                <a href="{{ route('web.management.katalog', request()->except('status', 'page')) }}" class="px-3 py-1.5 text-xs font-bold text-gray-500 hover:bg-gray-200 rounded-lg transition">Reset</a>
                                <button type="submit" class="px-4 py-1.5 bg-primary-900 text-xs font-bold text-white rounded-lg shadow-sm hover:bg-primary-800 transition">Tampilkan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Data Tanaman -->
        <div class="overflow-x-auto rounded-2xl border border-gray-200/80 bg-white">
            <table class="w-full min-w-[750px] text-left text-xs">
                <thead class="border-b border-gray-200 bg-gray-50/70 text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                    <tr>
                        <th class="px-5 py-4">Tanaman</th>
                        <th class="px-4 py-4">Kategori</th>
                        <th class="px-4 py-4">Lokasi</th>
                        <th class="px-4 py-4 text-right">Populasi</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($plants as $plant)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($plant->image)
                                    <img src="{{ asset('storage/plants/'.$plant->image) }}" class="h-11 w-11 rounded-xl object-cover border border-gray-200 shadow-sm shrink-0">
                                @else
                                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-100 shrink-0">
                                        <span class="material-symbols-rounded text-lg">eco</span>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900 truncate">{{ $plant->name }}</p>
                                    <p class="text-[11px] italic text-amber-700 truncate">{{ $plant->latin_name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-gray-600 font-medium">
                            {{ $plant->category?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3.5">
                            @if($plant->location_type === 'greenhouse')
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-bold text-emerald-800">
                                    <span class="material-symbols-rounded text-xs">roofing</span> Greenhouse
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-[10px] font-bold text-amber-800">
                                    <span class="material-symbols-rounded text-xs">eco</span> Outdoor
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right text-gray-700 font-bold">
                            {{ $plant->population !== null ? number_format($plant->population, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $plant->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $plant->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex justify-end items-center gap-1.5 font-bold">
                                <a href="{{ route('admin.plants.edit', $plant) }}" class="inline-flex items-center gap-1 text-amber-700 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-xl transition text-xs">
                                    <span class="material-symbols-rounded text-xs">edit</span> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.plants.destroy', $plant) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tanaman {{ $plant->name }}?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 text-rose-700 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-xl transition text-xs">
                                        <span class="material-symbols-rounded text-xs">delete</span> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <span class="material-symbols-rounded text-4xl mb-2 text-emerald-600">eco</span>
                                <p class="text-sm font-bold text-gray-600">Belum ada data tanaman ditemukan</p>
                                <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci pencarian atau tambahkan tanaman baru.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $plants->links() }}</div>
    </div>

    <!-- STICKY ACTION PRATINJAU -->
    <div class="sticky bottom-6 bg-white/95 backdrop-blur-xl p-4 rounded-2xl shadow-xl border border-gray-100 flex flex-wrap justify-between items-center gap-4 z-30">
        <div class="text-xs text-gray-500 font-medium pl-2 hidden sm:block">
            <span class="material-symbols-rounded text-sm align-middle text-amber-500">lightbulb</span> <span class="text-gray-700 font-bold">Tips:</span> Gunakan tombol "Lihat Katalog Publik" untuk memeriksa tampilan data pada website pengunjung.
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <a href="{{ route('admin.plants.create') }}" class="inline-flex items-center gap-2 bg-primary-900 text-white font-bold py-2.5 px-5 rounded-xl hover:bg-primary-800 transition text-xs shadow-md">
                <span class="material-symbols-rounded text-sm">add</span> Tambah Tanaman
            </a>
            <a href="{{ route('katalog') }}" target="_blank" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-xl hover:bg-gray-50 transition text-xs shadow-sm">
                <span class="material-symbols-rounded text-sm">language</span> Pratinjau Katalog Publik
            </a>
        </div>
    </div>

    <script>
        function toggleDropdown(dropdownId) {
            event.stopPropagation();
            document.querySelectorAll('.sort-dropdown').forEach(el => {
                if (el.id !== dropdownId) el.classList.add('hidden');
            });
            const target = document.getElementById(dropdownId);
            if (target) {
                target.classList.toggle('hidden');
            }
        }

        window.addEventListener('click', function () {
            document.querySelectorAll('.sort-dropdown').forEach(el => {
                el.classList.add('hidden');
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const allCategories = document.getElementById('all-categories');
            const categoryOptions = document.querySelectorAll('.category-option');

            if (allCategories) {
                allCategories.addEventListener('change', function () {
                    if (this.checked) {
                        categoryOptions.forEach(option => option.checked = false);
                    }
                });
            }

            categoryOptions.forEach(option => {
                option.addEventListener('change', function () {
                    if (this.checked && allCategories) {
                        allCategories.checked = false;
                    }
                });
            });
        });
    </script>
@endsection
