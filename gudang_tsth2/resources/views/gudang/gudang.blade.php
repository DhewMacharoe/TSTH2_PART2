@extends('layouts.dasbor')

@section('title', 'Data Inventory - TSTH2')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('header_title')
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            @if(auth()->user()->isPicGudang())
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 mb-1.5">
                    <span class="material-symbols-rounded text-sm">warehouse</span>
                    Departemen General Affair (GA)
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Warehouse Gudang</h1>
                <p class="text-sm text-gray-500 font-medium mt-0.5">Kelola data stok barang umum, bibit, pupuk, dan perkakas operasional TSTH2</p>
            @elseif(auth()->user()->isPicLab())
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-800 border border-purple-200 mb-1.5">
                    <span class="material-symbols-rounded text-sm">science</span>
                    Departemen Riset dan Inovasi (RdI)
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Warehouse Laboratorium</h1>
                <p class="text-sm text-gray-500 font-medium mt-0.5">Kelola data stok reagen kimia, kaca preparat, dan instrumen riset lab TSTH2</p>
            @else
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20 mb-1.5">
                    <span class="material-symbols-rounded text-sm">domain</span>
                    Sistem Warehouse Terpadu
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Inventaris Warehouse</h1>
                <p class="text-sm text-gray-500 font-medium mt-0.5">Pengawasan terpadu inventaris Warehouse Gudang (GA) dan Warehouse Lab (RdI)</p>
            @endif
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('satuan.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl font-bold text-xs shadow-sm transition-colors">
                <span class="material-symbols-rounded text-base text-gray-500">straighten</span>
                Master Satuan
            </a>
            @if(auth()->user()->canManageWarehouse())
            <a href="{{ route('gudang.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-primary hover:bg-primary-light text-white rounded-xl font-bold text-xs shadow-sm transition-colors">
                <span class="material-symbols-rounded text-base">add</span>
                Tambah Barang
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @if(auth()->user()->isPicGudang())
            {{-- PIC GUDANG VIEW --}}
            <div class="bg-white p-5 rounded-xl border border-primary ring-1 ring-primary bg-primary/[0.02] shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Stok Gudang GA</span>
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">inventory_2</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalBarangLapangan ?? $totalBarang ?? 0) }} <span class="text-xs font-normal text-gray-400">Unit</span>
                </div>
                <p class="text-xs text-emerald-700 font-semibold mt-1">Stok aktif Gudang GA</p>
            </div>

            <div onclick="focusFilterKategori()" class="bg-white p-5 rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all duration-150 cursor-pointer group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori Barang</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 group-hover:text-gray-700 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">category</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalKategori ?? 0) }} <span class="text-xs font-normal text-gray-400">Jenis</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Klik untuk filter kategori</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Varian Barang</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">dns</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($countItemsLapangan ?? 0) }} <span class="text-xs font-normal text-gray-400">Item</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Item terdaftar di Gudang</p>
            </div>

            <a href="{{ route('satuan.index') }}" class="bg-white p-5 rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all duration-150 group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Satuan Digunakan</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 group-hover:text-primary flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">straighten</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalSatuanLapangan ?? 0) }} <span class="text-xs font-normal text-gray-400">Satuan</span>
                </div>
                <p class="text-xs text-primary font-semibold mt-1">Kelola master satuan &rarr;</p>
            </a>

        @elseif(auth()->user()->isPicLab())
            {{-- PIC LAB VIEW --}}
            <div class="bg-white p-5 rounded-xl border border-primary ring-1 ring-primary bg-primary/[0.02] shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Stok Lab RdI</span>
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">science</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalBarangLab ?? $totalBarang ?? 0) }} <span class="text-xs font-normal text-gray-400">Unit</span>
                </div>
                <p class="text-xs text-purple-700 font-semibold mt-1">Stok aktif Lab RdI</p>
            </div>

            <div onclick="focusFilterKategori()" class="bg-white p-5 rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all duration-150 cursor-pointer group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori Lab</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 group-hover:text-gray-700 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">category</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalKategori ?? 0) }} <span class="text-xs font-normal text-gray-400">Jenis</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Klik untuk filter kategori</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Varian Barang Lab</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">biotech</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($countItemsLab ?? 0) }} <span class="text-xs font-normal text-gray-400">Item</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Item terdaftar di Lab</p>
            </div>

            <a href="{{ route('satuan.index') }}" class="bg-white p-5 rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all duration-150 group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Satuan Digunakan</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 group-hover:text-primary flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">straighten</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalSatuanLab ?? 0) }} <span class="text-xs font-normal text-gray-400">Satuan</span>
                </div>
                <p class="text-xs text-primary font-semibold mt-1">Kelola master satuan &rarr;</p>
            </a>

        @else
            {{-- WEB ADMIN / PIMPINAN / SUPERVISOR VIEW --}}
            <a href="{{ route('gudang', ['jenis' => 'all']) }}" 
               class="bg-white p-5 rounded-xl border transition-all duration-150 cursor-pointer group {{ ($selectedJenis ?? 'all') === 'all' ? 'border-primary ring-1 ring-primary bg-primary/[0.02] shadow-sm' : 'border-gray-200 hover:border-gray-300 hover:shadow-sm' }}"
               title="Tampilkan semua barang gabungan">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Semua Stok</span>
                    <span class="w-8 h-8 rounded-lg {{ ($selectedJenis ?? 'all') === 'all' ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-500 group-hover:text-gray-700' }} flex items-center justify-center transition-colors">
                        <span class="material-symbols-rounded text-lg">inventory_2</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalBarang ?? 0) }} <span class="text-xs font-normal text-gray-400">Unit</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Gabungan Gudang & Lab</p>
            </a>

            <div onclick="focusFilterKategori()" 
                 class="bg-white p-5 rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all duration-150 cursor-pointer group"
                 title="Pilih kategori barang">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 group-hover:text-gray-700 flex items-center justify-center transition-colors">
                        <span class="material-symbols-rounded text-lg">category</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalKategori ?? 0) }} <span class="text-xs font-normal text-gray-400">Jenis</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Klik untuk filter kategori</p>
            </div>

            <a href="{{ route('gudang', ['jenis' => 'lapangan']) }}" 
               class="bg-white p-5 rounded-xl border transition-all duration-150 cursor-pointer group {{ ($selectedJenis ?? 'all') === 'lapangan' ? 'border-primary ring-1 ring-primary bg-primary/[0.02] shadow-sm' : 'border-gray-200 hover:border-gray-300 hover:shadow-sm' }}"
               title="Filter hanya Warehouse Gudang (General Affair)">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Warehouse Gudang (GA)</span>
                    <span class="w-8 h-8 rounded-lg {{ ($selectedJenis ?? 'all') === 'lapangan' ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-500 group-hover:text-gray-700' }} flex items-center justify-center transition-colors">
                        <span class="material-symbols-rounded text-lg">warehouse</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalBarangLapangan ?? 0) }} <span class="text-xs font-normal text-gray-400">Unit</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $countItemsLapangan ?? 0 }} jenis barang GA</p>
            </a>

            <a href="{{ route('gudang', ['jenis' => 'lab']) }}" 
               class="bg-white p-5 rounded-xl border transition-all duration-150 cursor-pointer group {{ ($selectedJenis ?? 'all') === 'lab' ? 'border-primary ring-1 ring-primary bg-primary/[0.02] shadow-sm' : 'border-gray-200 hover:border-gray-300 hover:shadow-sm' }}"
               title="Filter hanya Warehouse Lab (Riset & Inovasi)">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Warehouse Lab (RdI)</span>
                    <span class="w-8 h-8 rounded-lg {{ ($selectedJenis ?? 'all') === 'lab' ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-500 group-hover:text-gray-700' }} flex items-center justify-center transition-colors">
                        <span class="material-symbols-rounded text-lg">science</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalBarangLab ?? 0) }} <span class="text-xs font-normal text-gray-400">Unit</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $countItemsLab ?? 0 }} jenis barang RdI</p>
            </a>
        @endif
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Integrated Toolbar -->
        <div class="p-4 border-b border-gray-200 bg-white flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            {{-- Filter Segment & Search --}}
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Segmented Type Filter (Only if Admin or Supervisor) --}}
                @if(!auth()->user()->isPicGudang() && !auth()->user()->isPicLab())
                <div class="inline-flex p-1 bg-gray-100 rounded-lg border border-gray-200/80">
                    <a href="{{ route('gudang', ['jenis' => 'all']) }}"
                       class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors {{ ($selectedJenis ?? 'all') === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Semua ({{ ($countItemsLapangan ?? 0) + ($countItemsLab ?? 0) }})
                    </a>
                    <a href="{{ route('gudang', ['jenis' => 'lapangan']) }}"
                       class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors inline-flex items-center gap-1.5 {{ ($selectedJenis ?? 'all') === 'lapangan' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        <span class="material-symbols-rounded text-sm">warehouse</span>
                        <span>Gudang GA</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($selectedJenis ?? 'all') === 'lapangan' ? 'bg-primary/10 text-primary font-bold' : 'bg-gray-200 text-gray-600' }}">{{ $countItemsLapangan ?? 0 }}</span>
                    </a>
                    <a href="{{ route('gudang', ['jenis' => 'lab']) }}"
                       class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors inline-flex items-center gap-1.5 {{ ($selectedJenis ?? 'all') === 'lab' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        <span class="material-symbols-rounded text-sm">science</span>
                        <span>Lab RdI</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($selectedJenis ?? 'all') === 'lab' ? 'bg-primary/10 text-primary font-bold' : 'bg-gray-200 text-gray-600' }}">{{ $countItemsLab ?? 0 }}</span>
                    </a>
                </div>
                @else
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg border border-gray-200 text-xs font-bold">
                    <span class="material-symbols-rounded text-base text-primary">{{ auth()->user()->isPicGudang() ? 'warehouse' : 'science' }}</span>
                    <span>{{ auth()->user()->isPicGudang() ? 'Warehouse Gudang (GA)' : 'Warehouse Lab (RdI)' }}</span>
                </div>
                @endif

                {{-- Search Input --}}
                <div class="relative min-w-[200px] sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <span class="material-symbols-rounded text-base">search</span>
                    </span>
                    <input type="text" id="searchInput" placeholder="Cari nama, kode, merek..." 
                           class="w-full pl-9 pr-3 py-1.5 bg-white border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary focus:border-primary text-xs font-medium text-gray-800 placeholder-gray-400 transition-colors">
                </div>

                {{-- Category Select --}}
                <select id="filterKategori" 
                        class="bg-white border-gray-200 text-gray-700 rounded-lg text-xs focus:ring-1 focus:ring-primary focus:border-primary py-1.5 pl-3 pr-8 font-medium transition-colors">
                    <option value="">Semua Kategori</option>
                    @foreach($barangsByCategory ?? [] as $kat => $count)
                    <option value="{{ $kat }}">{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 justify-end">
                <a href="{{ route('gudang.export') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg text-xs font-semibold shadow-sm transition-colors">
                    <span class="material-symbols-rounded text-base text-gray-500">download</span>
                    Export CSV
                </a>
                @if(auth()->user()->canManageWarehouse())
                <a href="{{ route('gudang.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary text-white hover:bg-primary-light rounded-lg text-xs font-semibold shadow-sm transition-colors">
                    <span class="material-symbols-rounded text-base">add</span>
                    Tambah Barang
                </a>
                @endif
            </div>
        </div>

        <!-- Data Table -->
        <div class="w-full bg-gray-50/50 md:bg-white p-4 md:p-0 overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 block md:table">
                <thead class="hidden md:table-header-group bg-gray-50/80 text-gray-900 font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Gambar</th>
                        <th class="px-6 py-4">Kode Barang</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Merek</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Batch</th>
                        <th class="px-6 py-4">Lokasi</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4">Dibuat Oleh</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="block md:table-row-group divide-y-0 md:divide-y divide-gray-100 space-y-4 md:space-y-0">
                    @forelse($barangsPaginated as $barang)
                    <tr class="block md:table-row bg-white md:bg-transparent rounded-2xl md:rounded-none shadow-sm md:shadow-none border border-gray-100 md:border-none p-4 md:p-0 hover:bg-gray-50/80 transition-colors group" data-kategori="{{ strtolower($barang->kategori) }}">
                        <td class="flex justify-between items-center gap-4 md:table-cell px-0 md:px-6 py-2 md:py-4 border-b border-gray-50 md:border-none">
                            <span class="md:hidden font-bold text-gray-400 text-xs shrink-0">Gambar</span>
                            @if($barang->gambar)
                                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="w-12 h-12 rounded-lg object-cover shadow-sm border border-gray-100">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200">
                                    <span class="material-symbols-rounded" style="font-size:22px;">inventory_2</span>
                                </div>
                            @endif
                        </td>
                        <td class="flex justify-between items-center gap-4 md:table-cell px-0 md:px-6 py-2 md:py-4 border-b border-gray-50 md:border-none">
                            <span class="md:hidden font-bold text-gray-400 text-xs shrink-0">Kode Barang</span>
                            <a href="{{ route('permintaan.create', ['kode' => $barang->kode_barang]) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-900 hover:bg-primary text-white font-mono text-xs font-bold rounded-md shadow-sm transition-colors" title="Klik untuk langsung ajukan permintaan barang ini">
                                <span>#{{ $barang->kode_barang }}</span>
                            </a>
                        </td>
                        <td class="flex justify-between items-center gap-4 md:table-cell px-0 md:px-6 py-2 md:py-4 border-b border-gray-50 md:border-none text-right md:text-left">
                            <span class="md:hidden font-bold text-gray-400 text-xs shrink-0">Nama Barang</span>
                            <div class="flex flex-col md:items-start items-end">
                                <strong class="text-gray-900 block text-base truncate max-w-[200px] md:max-w-none text-right md:text-left">{{ $barang->nama_barang }}</strong>
                                @if($barang->jenis_barang === 'lab')
                                    <span class="mt-1 inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-700 border border-gray-200 rounded text-[10px] font-semibold">Laboratorium</span>
                                @else
                                    <span class="mt-1 inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded text-[10px] font-semibold">Lapangan</span>
                                @endif
                                @php
                                    $hasExpired = false;
                                    $hasRusak = false;
                                    foreach($barang->batches as $batch) {
                                        if($batch->tanggal_kadaluarsa && \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->endOfDay()->isPast() && $batch->stok > 0) {
                                            $hasExpired = true;
                                        }
                                        
                                        $details = is_array($batch->stok_detail) ? $batch->stok_detail : json_decode($batch->stok_detail ?? '[]', true);
                                        
                                        if (empty($details)) {
                                            if(in_array($batch->kondisi, ['Rusak', 'Rusak/Menggumpal']) && $batch->stok > 0) {
                                                $hasRusak = true;
                                            }
                                        } else {
                                            foreach($details as $item) {
                                                if (in_array($item['kondisi'] ?? '', ['Rusak', 'Rusak/Menggumpal']) && ($item['stok'] ?? 0) > 0) {
                                                    $hasRusak = true;
                                                    break;
                                                }
                                            }
                                        }
                                        
                                        if ($hasExpired && $hasRusak) break;
                                    }
                                @endphp
                                @if($hasExpired)
                                    <span class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 bg-red-50 text-red-600 border border-red-200 rounded text-[10px] font-bold uppercase tracking-wide">! Ada Batch Expired</span>
                                @endif
                                @if($hasRusak)
                                    <span class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 bg-red-50 text-red-600 border border-red-200 rounded text-[10px] font-bold uppercase tracking-wide">! Ada Batch Rusak</span>
                                @endif
                            </div>
                        </td>
                        <td class="flex justify-between items-center gap-4 md:table-cell px-0 md:px-6 py-2 md:py-4 border-b border-gray-50 md:border-none text-right md:text-left">
                            <span class="md:hidden font-bold text-gray-400 text-xs shrink-0">Merek</span>
                            <span class="truncate max-w-[150px] md:max-w-none">{{ $barang->merk ?? '-' }}</span>
                        </td>
                        <td class="flex justify-between items-center gap-4 md:table-cell px-0 md:px-6 py-2 md:py-4 border-b border-gray-50 md:border-none">
                            <span class="md:hidden font-bold text-gray-400 text-xs shrink-0">Kategori</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium border border-gray-200">
                                {{ $barang->kategori }}
                            </span>
                        </td>
                        <td class="flex justify-between items-center gap-4 md:table-cell px-0 md:px-6 py-2 md:py-4 border-b border-gray-50 md:border-none text-xs font-medium text-gray-500">
                            <span class="md:hidden font-bold text-gray-400 text-xs shrink-0">Batch</span>
                            {{ $barang->batches()->where('stok', '>', 0)->count() }} Batch Aktif
                        </td>
                        <td class="flex justify-between items-center gap-4 md:table-cell px-0 md:px-6 py-2 md:py-4 border-b border-gray-50 md:border-none font-medium text-gray-700">
                            <span class="md:hidden font-bold text-gray-400 text-xs shrink-0">Lokasi</span>
                            <span class="truncate max-w-[150px] md:max-w-none">{{ $barang->lokasi_blok }}</span>
                        </td>
                        <td class="flex justify-between items-center gap-4 md:table-cell px-0 md:px-6 py-2 md:py-4 border-b border-gray-50 md:border-none font-bold text-gray-900">
                            <span class="md:hidden font-bold text-gray-400 text-xs shrink-0">Stok</span>
                            @php
                                $stokTotal = $barang->batches->sum('stok');
                            @endphp
                            {{ $stokTotal }}
                        </td>
                        
                        <td class="flex justify-between items-center gap-4 md:table-cell px-0 md:px-6 py-2 md:py-4 border-b border-gray-50 md:border-none text-right md:text-left">
                            <span class="md:hidden font-bold text-gray-400 text-xs shrink-0">Dibuat Oleh</span>
                            <div>
                                <span class="font-bold text-gray-800 text-xs block truncate max-w-[120px] md:max-w-none text-right md:text-left">{{ $barang->creator->name ?? 'Sistem' }}</span>
                                @if($barang->created_at)
                                    <span class="text-[10px] text-gray-400 block text-right md:text-left">{{ $barang->created_at->format('d/m/Y') }}</span>
                                @endif
                            </div>
                        </td>

                        <td class="flex justify-end items-center md:table-cell px-0 md:px-6 py-4 md:py-4 mt-2 md:mt-0 text-right md:text-center">
                            @if(in_array(auth()->user()->role, ['pic_gudang', 'web_admin', 'kepala_divisi', 'kepala_departemen']))
                            <div class="flex items-center justify-end md:justify-center gap-1.5">
                                <a href="{{ route('gudang.show', $barang->id) }}" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="Detail Batches">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ route('gudang.edit', $barang->id) }}" class="p-1.5 text-gray-500 hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                @if(in_array(auth()->user()->role, ['pic_gudang', 'web_admin']))
                                <button type="button" onclick="openDeleteModal({{ $barang->id }}, '{{ addslashes($barang->nama_barang) }}')" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                                @endif
                            </div>
                            @else
                            <div class="flex items-center justify-end md:justify-center gap-2">
                                <a href="{{ route('gudang.show', $barang->id) }}" class="p-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors" title="Detail Batches">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                @php
                                    $stokTersedia = $barang->getAvailableTotalStok();
                                @endphp
                                <button type="button" onclick="openRequestModal({{ $barang->id }}, '{{ addslashes($barang->nama_barang) }}', {{ $stokTersedia }}, '{{ $barang->kategori }}', '{{ $barang->kode_barang }}')" class="px-3 py-1.5 bg-accent/10 text-accent-dark rounded-lg hover:bg-accent/20 transition-colors font-bold text-xs inline-flex items-center gap-1">
                                    <span class="material-symbols-rounded" style="font-size:14px;">shopping_cart</span> Request
                                </button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-400 font-medium">Belum ada data barang di sistem.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Info & Links -->
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50/50">
            <span class="text-sm text-gray-500 font-medium">Menampilkan <strong class="text-gray-900">{{ $barangsPaginated->firstItem() ?? 0 }} - {{ $barangsPaginated->lastItem() ?? 0 }}</strong> dari <strong class="text-gray-900">{{ $barangsPaginated->total() }}</strong> barang</span>
            @if($barangsPaginated->hasPages())
                <div>
                    {{ $barangsPaginated->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Konfirmasi Hapus</h2>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeDeleteModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-2">Apakah Anda yakin ingin menghapus data barang ini secara permanen?</p>
                <p id="deleteBarangName" class="text-lg font-bold text-gray-900 mb-6"></p>
                
                <div class="flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors" onclick="closeDeleteModal()">Batal</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg font-bold transition-colors shadow-md shadow-red-500/20">Ya, Hapus Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Restock Modal -->
    <div id="restockModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Restock Barang (Batch Baru)</h2>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeRestockModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <p id="restockBarangName" class="text-lg font-bold text-primary mb-4 bg-primary/5 p-3 rounded-xl border border-primary/10"></p>
                <form id="restockForm" method="POST">
                    @csrf
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1" for="tanggal_kedatangan">Tanggal Kedatangan <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_kedatangan" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-2.5 font-medium" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1" for="tanggal_kadaluarsa">Tanggal Kadaluarsa <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="date" name="tanggal_kadaluarsa" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-2.5 font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1" for="jumlah">Jumlah Masuk (Unit) <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah" min="1" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-2.5 font-medium" required>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors" onclick="closeRestockModal()">Batal</button>
                        <button type="submit" class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg font-bold transition-colors shadow-md shadow-green-500/20">Simpan Batch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Request Modal (For Non Admins) -->
    <div id="permintaanModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Ajukan Permintaan Barang</h2>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeRequestModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <form action="{{ route('permintaan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="barang_id" id="req_barang_id">
                    
                    <div class="mb-5 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Barang yang Diminta</label>
                        <div class="flex items-center gap-2 mb-1">
                            <span id="req_kode_barang" class="px-2 py-0.5 bg-gray-900 text-white font-mono text-xs font-bold rounded"></span>
                            <div id="req_nama_barang" class="text-lg font-bold text-primary"></div>
                        </div>
                        <div class="text-sm font-medium text-gray-600 flex items-center gap-2">
                            <span class="material-symbols-rounded" style="font-size:16px;">inventory_2</span>
                            <span>Stok Tersedia:</span>
                            <span id="req_stok_tersedia" class="font-bold text-gray-900"></span> Unit
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="jumlah">Jumlah Permintaan <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium" required min="1" placeholder="Contoh: 2">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="satuan_diminta">Satuan Barang <span class="text-red-500">*</span></label>
                            <select name="satuan_diminta" id="satuan_diminta" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium" required>
                                <option value="">-- Pilih Satuan --</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="catatan">Catatan / Keterangan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <textarea name="catatan" id="catatan" rows="3" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" class="px-4 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-bold transition-colors" onclick="closeRequestModal()">Batal</button>
                        <button type="submit" class="px-6 py-2.5 text-white bg-primary hover:bg-primary-light rounded-lg font-bold transition-colors shadow-md shadow-primary/20">Kirim Permintaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openDeleteModal(id, nama) {
            const modal = document.getElementById('deleteModal');
            document.getElementById('deleteForm').action = `/gudang/${id}`;
            document.getElementById('deleteBarangName').textContent = nama;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('opacity-0');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        function openRestockModal(id, nama) {
            const modal = document.getElementById('restockModal');
            document.getElementById('restockForm').action = `/gudang/${id}/batch`;
            document.getElementById('restockBarangName').textContent = nama;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            }, 10);
        }

        function closeRestockModal() {
            const modal = document.getElementById('restockModal');
            modal.classList.add('opacity-0');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        const satuanByKategori = @json($satuanByKategori ?? []);

        function openRequestModal(id, nama, stok, kategori, kode) {
            const modal = document.getElementById('permintaanModal');
            document.getElementById('req_barang_id').value = id;
            document.getElementById('req_nama_barang').textContent = nama;
            document.getElementById('req_kode_barang').textContent = kode ? '#' + kode : '';
            document.getElementById('req_stok_tersedia').textContent = stok;
            document.getElementById('jumlah').max = stok;
            
            // Populate Satuan Dropdown
            const satuanSelect = document.getElementById('satuan_diminta');
            satuanSelect.innerHTML = '<option value="">-- Pilih Satuan --</option>';
            
            let units = [];
            if (satuanByKategori[kategori]) {
                units = units.concat(satuanByKategori[kategori]);
            }
            // Remove duplicates just in case
            units = [...new Set(units)];
            
            units.forEach(u => {
                const opt = document.createElement('option');
                opt.value = u;
                opt.textContent = u.toUpperCase();
                satuanSelect.appendChild(opt);
            });
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            }, 10);
        }

        function closeRequestModal() {
            const modal = document.getElementById('permintaanModal');
            modal.classList.add('opacity-0');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        // Mencegah input jumlah melebihi max
        document.getElementById('jumlah').addEventListener('input', function() {
            let max = parseInt(this.max);
            if (max && parseInt(this.value) > max) {
                this.value = max;
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Chart initialization
            const kategoriData = @json($barangsByCategory ?? []);
            if(Object.keys(kategoriData).length > 0) {
                const ctx = document.getElementById('kategoriChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(kategoriData),
                        datasets: [{
                            data: Object.values(kategoriData),
                            backgroundColor: ['#054439', '#c09e5c', '#2ecc71', '#3498db', '#9b59b6', '#f1c40f'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                titleColor: '#111827',
                                bodyColor: '#4b5563',
                                borderColor: '#e5e7eb',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: true,
                                boxPadding: 4,
                                usePointStyle: true,
                            }
                        },
                        cutout: '75%'
                    }
                });
            }

            // Search and Filter logic combined
            const searchInput = document.getElementById('searchInput');
            const filterKategori = document.getElementById('filterKategori');

            function applyFilters() {
                const term = searchInput ? searchInput.value.toLowerCase().trim() : '';
                const kategori = filterKategori ? filterKategori.value.toLowerCase().trim() : '';
                
                const rows = document.querySelectorAll('#tableBody tr');
                rows.forEach(row => {
                    if (row.children.length === 1) return; // Skip empty row message
                    
                    const text = row.textContent.toLowerCase();
                    const rowKategori = (row.getAttribute('data-kategori') || '').toLowerCase().trim();
                    
                    const matchSearch = term === '' || text.includes(term);
                    const matchKategori = kategori === '' || rowKategori.includes(kategori);
                    
                    if(matchSearch && matchKategori) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            if (searchInput) searchInput.addEventListener('input', applyFilters);
            if (filterKategori) filterKategori.addEventListener('change', applyFilters);

            window.focusFilterKategori = function() {
                const el = document.getElementById('filterKategori');
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    el.focus();
                }
            };
        });
    </script>
@endpush
