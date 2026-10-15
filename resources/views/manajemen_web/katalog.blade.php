@extends('layouts.dasbor')

@section('title', 'Manajemen Web - Katalog Tanaman')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - KATALOG TANAMAN</h1>
    <p class="text-gray-500 font-medium">Kelola konten pengantar katalog publik dan seluruh data koleksi tanaman herbal & hortikultura.</p>
</div>

@if(session('success'))
<div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 font-bold border border-emerald-100 flex items-center gap-3">
    <span class="text-xl">✅</span>
    {{ session('success') }}
</div>
@endif

<!-- BAGIAN 1: KONTEN STATIS HERO KATALOG -->
<form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-6 text-gray-900 flex items-center gap-3 border-b border-gray-100 pb-4">
            <span class="text-primary text-2xl">📝</span> Teks Pengantar Header Katalog Publik
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-bold mb-2 text-gray-700">Judul Header Katalog</label>
                <input type="text" name="katalog_hero_title" class="w-full p-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-700 bg-gray-50 hover:bg-white" value="{{ $konten['katalog_hero_title']->value ?? '' }}" placeholder="Contoh: Koleksi Tanaman Herbal">
            </div>
            <div>
                <label class="block font-bold mb-2 text-gray-700">Deskripsi Header Katalog</label>
                <textarea name="katalog_hero_desc" class="w-full min-h-[100px] p-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-700 bg-gray-50 hover:bg-white resize-none" placeholder="Contoh: Jelajahi Koleksi Plasma Nutfah Tanaman Obat Indonesia di Taman Sains Teknologi Herbal dan Hortikultura.">{{ $konten['katalog_hero_desc']->value ?? '' }}</textarea>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-8 rounded-xl shadow-md transition-all hover:scale-105">
                💾 Simpan Header Katalog
            </button>
        </div>
    </div>
</form>

<!-- BAGIAN 2: DAFTAR & KELOLA DATA TANAMAN -->
<div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-gray-100 pb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                <span class="text-primary text-2xl">🌿</span> Daftar Koleksi Tanaman
            </h2>
            <p class="text-xs text-gray-500 mt-1">Tambah, edit, atau sesuaikan publikasi spesies tanaman herbal di katalog.</p>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.categories.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl bg-gray-100 px-4 text-xs font-bold text-gray-700 hover:bg-gray-200 transition">
                📂 Kelola Kategori
            </a>
            <a href="{{ route('admin.plants.create') }}" class="inline-flex h-10 items-center justify-center rounded-xl bg-[#003f32] px-5 text-xs font-bold text-white shadow-sm hover:bg-[#00634e] transition">
                + Tambah Tanaman
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-6">
        <div class="flex items-center gap-4 rounded-2xl border border-[#dbe9dd] bg-[#fdfdfd] p-4 shadow-sm">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-700 font-bold text-xl">
                🏡
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Greenhouse</p>
                <h3 class="text-lg font-extrabold text-[#003f32]">{{ $summary['greenhouse'] }} <span class="text-xs font-normal text-gray-500">Spesies</span></h3>
            </div>
        </div>

        <div class="flex items-center gap-4 rounded-2xl border border-[#dbe9dd] bg-[#fdfdfd] p-4 shadow-sm">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-700 font-bold text-xl">
                🌿
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Outdoor / Koleksi</p>
                <h3 class="text-lg font-extrabold text-[#003f32]">{{ $summary['outdoor'] }} <span class="text-xs font-normal text-gray-500">Spesies</span></h3>
            </div>
        </div>

        <div class="flex items-center gap-4 rounded-2xl border border-[#dbe9dd] bg-[#fdfdfd] p-4 shadow-sm sm:col-span-2 lg:col-span-1">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700 font-bold text-xl">
                📊
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Populasi</p>
                <h3 class="text-lg font-extrabold text-[#003f32]">{{ number_format($summary['population'], 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">Tanaman</span></h3>
            </div>
        </div>
    </div>

    <!-- Search Bar & Filter Form -->
    <div class="mb-6">
        <form method="GET" action="{{ route('web.management.katalog') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative flex min-w-0 flex-1 items-center">
                <span class="pointer-events-none absolute left-3.5 flex items-center text-gray-400">
                    🔍
                </span>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari tanaman, kategori, atau lokasi..." class="h-10 w-full rounded-xl border border-gray-200 bg-gray-50 pl-10 pr-4 text-xs focus:border-[#00634e] focus:bg-white focus:outline-none">
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
                    <button type="button" onclick="toggleDropdown('dropdown-filter-category')" class="flex h-10 items-center justify-between gap-2 rounded-xl bg-gray-50 px-4 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-white focus:outline-none">
                        <span class="truncate max-w-[140px]">{{ $categoryButtonLabel }}</span>
                        <span>▼</span>
                    </button>
                    <div id="dropdown-filter-category" class="sort-dropdown hidden absolute right-0 top-12 z-50 w-64 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl">
                        <div class="max-h-64 space-y-1 overflow-y-auto p-3">
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-50">
                                <input type="checkbox" id="all-categories" @checked(!$selectedCategoryIds) class="h-4 w-4 rounded accent-[#00634e]">
                                <span>Semua kategori</span>
                            </label>
                            @foreach ($categories as $category)
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-50">
                                    <input type="checkbox" name="category[]" value="{{ $category->id }}" @checked(in_array((string) $category->id, $selectedCategoryIds, true)) class="category-option h-4 w-4 rounded accent-[#00634e]">
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-3 py-2 bg-gray-50">
                            <a href="{{ route('web.management.katalog', request()->except('category', 'page')) }}" class="px-3 py-1.5 text-xs font-bold text-gray-500 hover:bg-gray-200 rounded-lg">Reset</a>
                            <button type="submit" class="px-4 py-1.5 bg-[#00634e] text-xs font-bold text-white rounded-lg shadow-sm hover:bg-[#003f32]">Tampilkan</button>
                        </div>
                    </div>
                </div>

                <!-- Dropdown Status -->
                <div class="relative shrink-0">
                    <button type="button" onclick="toggleDropdown('dropdown-filter-status')" class="flex h-10 items-center justify-between gap-2 rounded-xl bg-gray-50 px-4 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-white focus:outline-none">
                        <span>{{ $statusButtonLabel }}</span>
                        <span>▼</span>
                    </button>
                    <div id="dropdown-filter-status" class="sort-dropdown hidden absolute right-0 top-12 z-50 w-48 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl">
                        <div class="p-3 space-y-1">
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-50">
                                <input type="radio" name="status" value="" @checked(!request('status')) class="h-4 w-4 accent-[#00634e]">
                                <span>Semua status</span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-50">
                                <input type="radio" name="status" value="active" @checked(request('status') === 'active') class="h-4 w-4 accent-[#00634e]">
                                <span>Aktif</span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-50">
                                <input type="radio" name="status" value="inactive" @checked(request('status') === 'inactive') class="h-4 w-4 accent-[#00634e]">
                                <span>Nonaktif</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-3 py-2 bg-gray-50">
                            <a href="{{ route('web.management.katalog', request()->except('status', 'page')) }}" class="px-3 py-1.5 text-xs font-bold text-gray-500 hover:bg-gray-200 rounded-lg">Reset</a>
                            <button type="submit" class="px-4 py-1.5 bg-[#00634e] text-xs font-bold text-white rounded-lg shadow-sm hover:bg-[#003f32]">Tampilkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Data Tanaman -->
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
        @php
            $sortUrl = fn ($column, $direction) => route('web.management.katalog', array_merge(request()->query(), ['sort' => $column, 'direction' => $direction, 'page' => null]));
            $activeSort = request('sort', 'name');
            $activeDir = request('direction', 'asc');
        @endphp

        <table class="w-full min-w-[750px] text-left text-xs">
            <thead class="border-b border-gray-200 bg-gray-50 text-[10px] uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3.5 font-bold">Tanaman</th>
                    <th class="px-4 py-3.5 font-bold">Kategori</th>
                    <th class="px-4 py-3.5 font-bold">Lokasi</th>
                    <th class="px-4 py-3.5 font-bold text-right">Populasi</th>
                    <th class="px-4 py-3.5 font-bold">Status</th>
                    <th class="px-4 py-3.5 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($plants as $plant)
                <tr class="hover:bg-gray-50/80 transition">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($plant->image)
                                <img src="{{ asset('storage/plants/'.$plant->image) }}" class="h-10 w-10 rounded-lg object-cover border">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-base">🌿</div>
                            @endif
                            <div>
                                <p class="font-bold text-gray-900">{{ $plant->name }}</p>
                                <p class="text-[10px] italic text-amber-700">{{ $plant->latin_name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600 font-medium">{{ $plant->category?->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($plant->location_type === 'greenhouse')
                            <span class="rounded bg-green-100 px-2 py-0.5 text-[10px] font-bold text-green-800">Greenhouse</span>
                        @else
                            <span class="rounded bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-800">Outdoor</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right text-gray-600 font-semibold">{{ $plant->population !== null ? number_format($plant->population, 0, ',', '.') : '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold {{ $plant->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $plant->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2 font-bold">
                            <a href="{{ route('admin.plants.edit', $plant) }}" class="text-amber-600 hover:bg-amber-50 px-2.5 py-1 rounded-lg transition">Edit</a>
                            <form method="POST" action="{{ route('admin.plants.destroy', $plant) }}" onsubmit="return confirm('Hapus tanaman ini?')">
                                @csrf 
                                @method('DELETE')
                                <button class="text-rose-600 hover:bg-rose-50 px-2.5 py-1 rounded-lg transition">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-xs text-gray-400">Belum ada data tanaman.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $plants->links() }}</div>
</div>

<!-- STICKY ACTION PRATINJAU -->
<div class="sticky bottom-6 bg-white/95 backdrop-blur-xl p-4 rounded-[2rem] shadow-2xl border border-gray-200 flex justify-end items-center gap-4 z-30">
    <a href="{{ route('katalog') }}" target="_blank" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-white border-2 border-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-xl hover:bg-gray-50 transition">
        👁️ Pratinjau Katalog Publik
    </a>
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
