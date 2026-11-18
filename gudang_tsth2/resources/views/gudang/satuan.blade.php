@extends('layouts.dasbor')

@section('title', 'Master Satuan Barang - TSTH2')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('gudang') }}" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-primary transition-colors">
                    <span class="material-symbols-rounded text-lg mr-1">arrow_back</span>
                    Inventory Gudang
                </a>
                <span class="text-gray-300">/</span>
                <span class="text-sm font-bold text-gray-700">Master Satuan</span>
            </div>
            @if(auth()->user()->isPicGudang())
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 mb-1.5">
                    <span class="material-symbols-rounded text-sm">warehouse</span>
                    Departemen General Affair (GA)
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Master Satuan — Gudang GA</h1>
                <p class="text-gray-500 font-medium text-sm mt-0.5">Kelola satuan dan rumus konversi barang operasional, pupuk, bibit, dan kebutuhan umum</p>
            @elseif(auth()->user()->isPicLab())
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-800 border border-purple-200 mb-1.5">
                    <span class="material-symbols-rounded text-sm">science</span>
                    Departemen Riset dan Inovasi (RdI)
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Master Satuan — Lab RdI</h1>
                <p class="text-gray-500 font-medium text-sm mt-0.5">Kelola satuan dan rumus konversi reagen, bahan kimia, botani, dan instrumen laboratorium</p>
            @else
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20 mb-1.5">
                    <span class="material-symbols-rounded text-sm">domain</span>
                    Sistem Warehouse Terpadu
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Master Satuan & Konversi</h1>
                <p class="text-gray-500 font-medium text-sm mt-0.5">Kelola satuan peruntukan Warehouse Gudang (GA) dan Warehouse Laboratorium (RdI)</p>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('gudang') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl font-bold text-sm shadow-sm transition-colors">
                <span class="material-symbols-rounded text-lg">inventory_2</span>
                Lihat Stok Barang
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl font-semibold shadow-sm" id="flash-success">
            <span class="material-symbols-rounded text-xl text-emerald-600">check_circle</span>
            <span class="text-sm">{{ session('success') }}</span>
            <button class="ml-auto text-emerald-400 hover:text-emerald-600 inline-flex items-center" onclick="this.closest('#flash-success').remove()">
                <span class="material-symbols-rounded text-base">close</span>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 flex items-center gap-3 px-5 py-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl font-semibold shadow-sm" id="flash-error">
            <span class="material-symbols-rounded text-xl text-red-600">error</span>
            <span class="text-sm">{{ session('error') }}</span>
            <button class="ml-auto text-red-400 hover:text-red-600 inline-flex items-center" onclick="this.closest('#flash-error').remove()">
                <span class="material-symbols-rounded text-base">close</span>
            </button>
        </div>
    @endif

    {{-- Overview Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @if(auth()->user()->isPicGudang())
            <div class="bg-white p-5 rounded-xl border border-primary ring-1 ring-primary bg-primary/[0.02] shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Satuan Gudang GA</span>
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">straighten</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalSatuanLapangan ?? 0) }}
                </div>
                <p class="text-xs text-amber-700 font-semibold mt-1">{{ $totalKategoriLapangan ?? 0 }} Kategori aktif</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori Barang</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">category</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalKategoriLapangan ?? 0) }}
                </div>
                <p class="text-xs text-gray-400 mt-1">Kategori barang Gudang</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Aturan Konversi GA</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">sync_alt</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalKonversiLapangan ?? 0) }}
                </div>
                <p class="text-xs text-gray-400 mt-1">Rumus konversi terdaftar</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Departemen</span>
                    <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">domain</span>
                    </span>
                </div>
                <div class="text-lg font-bold text-gray-900 tracking-tight">
                    General Affair
                </div>
                <p class="text-xs text-gray-400 mt-1">1 PIC terdedikasi</p>
            </div>

        @elseif(auth()->user()->isPicLab())
            <div class="bg-white p-5 rounded-xl border border-primary ring-1 ring-primary bg-primary/[0.02] shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Satuan Lab RdI</span>
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">straighten</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalSatuanLab ?? 0) }}
                </div>
                <p class="text-xs text-purple-700 font-semibold mt-1">{{ $totalKategoriLab ?? 0 }} Kategori aktif</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori Lab</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">category</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalKategoriLab ?? 0) }}
                </div>
                <p class="text-xs text-gray-400 mt-1">Kategori barang Lab</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Aturan Konversi Lab</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">sync_alt</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalKonversiLab ?? 0) }}
                </div>
                <p class="text-xs text-gray-400 mt-1">Rumus konversi terdaftar</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Departemen</span>
                    <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">biotech</span>
                    </span>
                </div>
                <div class="text-lg font-bold text-gray-900 tracking-tight">
                    Riset & Inovasi
                </div>
                <p class="text-xs text-gray-400 mt-1">1 PIC terdedikasi</p>
            </div>

        @else
            {{-- Web Admin Stats --}}
            <a href="{{ route('satuan.index', ['jenis' => 'all']) }}" 
               class="bg-white p-5 rounded-xl border transition-all duration-150 cursor-pointer group {{ ($selectedJenis ?? 'all') === 'all' ? 'border-primary ring-1 ring-primary bg-primary/[0.02] shadow-sm' : 'border-gray-200 hover:border-gray-300 hover:shadow-sm' }}"
               title="Tampilkan semua satuan">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Semua Satuan</span>
                    <span class="w-8 h-8 rounded-lg {{ ($selectedJenis ?? 'all') === 'all' ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-500 group-hover:text-gray-700' }} flex items-center justify-center transition-colors">
                        <span class="material-symbols-rounded text-lg">straighten</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalSatuan ?? 0) }}
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ ($totalKategoriLapangan ?? 0) + ($totalKategoriLab ?? 0) }} Kategori</p>
            </a>

            <a href="{{ route('satuan.index', ['jenis' => 'lapangan']) }}" 
               class="bg-white p-5 rounded-xl border transition-all duration-150 cursor-pointer group {{ ($selectedJenis ?? 'all') === 'lapangan' ? 'border-primary ring-1 ring-primary bg-primary/[0.02] shadow-sm' : 'border-gray-200 hover:border-gray-300 hover:shadow-sm' }}"
               title="Filter satuan Gudang GA">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Satuan Gudang (GA)</span>
                    <span class="w-8 h-8 rounded-lg {{ ($selectedJenis ?? 'all') === 'lapangan' ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-500 group-hover:text-gray-700' }} flex items-center justify-center transition-colors">
                        <span class="material-symbols-rounded text-lg">warehouse</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalSatuanLapangan ?? 0) }}
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $totalKategoriLapangan ?? 0 }} Kategori GA</p>
            </a>

            <a href="{{ route('satuan.index', ['jenis' => 'lab']) }}" 
               class="bg-white p-5 rounded-xl border transition-all duration-150 cursor-pointer group {{ ($selectedJenis ?? 'all') === 'lab' ? 'border-primary ring-1 ring-primary bg-primary/[0.02] shadow-sm' : 'border-gray-200 hover:border-gray-300 hover:shadow-sm' }}"
               title="Filter satuan Lab RdI">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Satuan Lab (RdI)</span>
                    <span class="w-8 h-8 rounded-lg {{ ($selectedJenis ?? 'all') === 'lab' ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-500 group-hover:text-gray-700' }} flex items-center justify-center transition-colors">
                        <span class="material-symbols-rounded text-lg">science</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalSatuanLab ?? 0) }}
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $totalKategoriLab ?? 0 }} Kategori RdI</p>
            </a>

            <a href="#section-konversi" 
               class="bg-white p-5 rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all duration-150 cursor-pointer group"
               title="Lihat aturan konversi satuan">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Aturan Konversi</span>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 group-hover:text-gray-700 flex items-center justify-center transition-colors">
                        <span class="material-symbols-rounded text-lg">sync_alt</span>
                    </span>
                </div>
                <div class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ number_format($totalKonversi ?? 0) }}
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $totalKonversiLapangan ?? 0 }} GA / {{ $totalKonversiLab ?? 0 }} Lab</p>
            </a>
        @endif
    </div>

    {{-- Tabs Filter (Consistent Segment Switcher) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 p-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        @if(!auth()->user()->isPicGudang() && !auth()->user()->isPicLab())
        <div class="inline-flex p-1 bg-gray-100 rounded-lg border border-gray-200/80">
            <a href="{{ route('satuan.index', ['jenis' => 'all']) }}"
               class="px-3.5 py-1.5 rounded-md text-xs font-semibold transition-colors {{ ($selectedJenis ?? 'all') === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                Semua Satuan ({{ $totalSatuan }})
            </a>
            <a href="{{ route('satuan.index', ['jenis' => 'lapangan']) }}"
               class="px-3.5 py-1.5 rounded-md text-xs font-semibold transition-colors inline-flex items-center gap-1.5 {{ ($selectedJenis ?? 'all') === 'lapangan' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                <span class="material-symbols-rounded text-sm">warehouse</span>
                <span>Gudang GA</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($selectedJenis ?? 'all') === 'lapangan' ? 'bg-primary/10 text-primary font-bold' : 'bg-gray-200 text-gray-600' }}">{{ $totalSatuanLapangan }}</span>
            </a>
            <a href="{{ route('satuan.index', ['jenis' => 'lab']) }}"
               class="px-3.5 py-1.5 rounded-md text-xs font-semibold transition-colors inline-flex items-center gap-1.5 {{ ($selectedJenis ?? 'all') === 'lab' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                <span class="material-symbols-rounded text-sm">science</span>
                <span>Lab RdI</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($selectedJenis ?? 'all') === 'lab' ? 'bg-primary/10 text-primary font-bold' : 'bg-gray-200 text-gray-600' }}">{{ $totalSatuanLab }}</span>
            </a>
        </div>
        @else
        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg border border-gray-200 text-xs font-bold">
            <span class="material-symbols-rounded text-base text-primary">{{ auth()->user()->isPicGudang() ? 'warehouse' : 'science' }}</span>
            <span>{{ auth()->user()->isPicGudang() ? 'Master Satuan — Gudang General Affair (GA)' : 'Master Satuan — Lab Riset dan Inovasi (RdI)' }}</span>
        </div>
        @endif

        <div class="relative min-w-[220px]">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <span class="material-symbols-rounded text-base">search</span>
            </span>
            <input type="text" id="quickSearchInput" placeholder="Cari satuan / kategori..."
                   class="w-full pl-9 pr-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-primary focus:border-primary text-gray-800 placeholder-gray-400 transition-colors">
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN: Forms --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Card Form 1: Tambah Satuan Baru --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50/70 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">add_circle</span>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Tambah Satuan Baru</h2>
                        <p class="text-xs text-gray-500">Daftarkan satuan peruntukan barang</p>
                    </div>
                </div>

                <form action="{{ route('satuan.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf

                    {{-- Pilihan Jenis Peruntukan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Peruntukan Satuan <span class="text-red-500">*</span>
                        </label>
                        @if(auth()->user()->isPicGudang())
                            <input type="hidden" name="jenis_barang" value="lapangan">
                            <div class="flex items-center gap-2.5 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                                <span class="material-symbols-rounded text-lg text-amber-700">warehouse</span>
                                <div>
                                    <span class="block text-xs font-bold text-amber-950">Warehouse Gudang (GA)</span>
                                    <span class="block text-[10px] text-amber-700">Pupuk, Bibit, Perkakas, Kebutuhan Umum</span>
                                </div>
                            </div>
                        @elseif(auth()->user()->isPicLab())
                            <input type="hidden" name="jenis_barang" value="lab">
                            <div class="flex items-center gap-2.5 p-3 bg-purple-50 border border-purple-200 rounded-xl">
                                <span class="material-symbols-rounded text-lg text-purple-700">science</span>
                                <div>
                                    <span class="block text-xs font-bold text-purple-950">Warehouse Laboratorium (RdI)</span>
                                    <span class="block text-[10px] text-purple-700">Kimia, Reagen, Glassware, Instrumen</span>
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition-colors border-primary bg-primary/[0.04] text-gray-900" id="label-jenis-lapangan">
                                    <input type="radio" name="jenis_barang" value="lapangan" checked class="text-primary focus:ring-primary" onchange="switchFormType('lapangan')">
                                    <div class="text-left">
                                        <span class="block text-xs font-bold text-gray-900">Gudang GA</span>
                                        <span class="block text-[10px] text-gray-500">Pupuk, Bibit, Umum</span>
                                    </div>
                                </label>
                                <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition-colors border-gray-200 hover:border-gray-300 bg-white text-gray-700" id="label-jenis-lab">
                                    <input type="radio" name="jenis_barang" value="lab" class="text-primary focus:ring-primary" onchange="switchFormType('lab')">
                                    <div class="text-left">
                                        <span class="block text-xs font-bold text-gray-900">Lab RdI</span>
                                        <span class="block text-[10px] text-gray-500">Kimia, Reagen, Lab</span>
                                    </div>
                                </label>
                            </div>
                        @endif
                    </div>

                    {{-- Nama Satuan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="nama_satuan">
                            Nama Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_satuan" id="nama_satuan"
                               placeholder="Contoh: kg, sack, botol, ampul, pcs"
                               class="w-full bg-gray-50/70 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary p-2.5 font-medium text-sm transition-all"
                               value="{{ old('nama_satuan') }}" required>
                        @error('nama_satuan')
                            <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori Barang --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="kategori_satuan">
                            Kategori Barang <span class="text-red-500">*</span>
                        </label>
                        <input list="kategori_options" name="kategori" id="kategori_satuan"
                               class="w-full bg-gray-50/70 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary p-2.5 font-medium text-sm transition-all"
                               placeholder="Pilih atau ketik kategori baru" required>
                        <datalist id="kategori_options">
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat }}">{{ $kat }}</option>
                            @endforeach
                        </datalist>
                        <p class="text-[11px] text-gray-400 mt-1">Pilih kategori yang sesuai atau ketik kategori baru jika belum terdaftar.</p>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-primary text-white rounded-xl font-bold hover:bg-primary-light transition-colors shadow-md shadow-primary/20 text-sm flex items-center justify-center gap-1.5">
                        <span class="material-symbols-rounded text-lg">add</span>
                        Simpan Satuan
                    </button>
                </form>
            </div>

            {{-- Card Form 2: Atur Konversi Satuan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50/70 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-700 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">sync_alt</span>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Atur Rumus Konversi</h2>
                        <p class="text-xs text-gray-500">Pecah kemasan besar ke unit kecil secara otomatis</p>
                    </div>
                </div>

                <form action="{{ route('satuan.konversi.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="konversi_kategori">
                            Kategori Barang <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori" id="konversi_kategori"
                                class="w-full bg-gray-50/70 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary p-2.5 font-medium text-sm transition-all cursor-pointer"
                                required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat }}">{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Formula Row --}}
                    <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-200">
                        <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Rumus Konversi</span>
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold text-gray-500 mb-0.5">1 Satuan Besar</label>
                                <input list="satuan_options_konversi" name="dari_satuan" id="dari_satuan"
                                       placeholder="cth: sack"
                                       class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs font-bold uppercase focus:ring-primary focus:border-primary"
                                       required>
                            </div>
                            <span class="font-bold text-gray-400 text-lg mt-3">=</span>
                            <div class="w-20">
                                <label class="block text-[10px] font-bold text-gray-500 mb-0.5">Jumlah</label>
                                <input type="number" step="0.01" min="0.01" name="multiplier" id="multiplier"
                                       placeholder="25"
                                       class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs font-bold text-center focus:ring-primary focus:border-primary"
                                       required>
                            </div>
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold text-gray-500 mb-0.5">Satuan Kecil</label>
                                <input list="satuan_options_konversi" name="ke_satuan" id="ke_satuan"
                                       placeholder="cth: kilo"
                                       class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs font-bold uppercase focus:ring-primary focus:border-primary"
                                       required>
                            </div>
                        </div>
                        <datalist id="satuan_options_konversi"></datalist>
                        <p class="text-[10px] text-gray-400 mt-2">Contoh: <strong>1 SACK</strong> = <strong>25 KILO</strong>. Saat request minta Kilo, sistem FIFO dapat otomatis memotong stok dari Sack.</p>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-primary text-white rounded-xl font-semibold hover:bg-primary-light transition-colors shadow-sm text-sm flex items-center justify-center gap-1.5">
                        <span class="material-symbols-rounded text-lg">check</span>
                        Simpan Rumus Konversi
                    </button>
                </form>
            </div>
        </div>

        {{-- RIGHT COLUMN: Data List --}}
        <div class="lg:col-span-2 space-y-6">

            @php
                // Pisahkan grup kategori menjadi Lapangan & Lab
                $groupedLapangan = $grouped->filter(function($items) {
                    return $items->first()?->jenis_barang === 'lapangan';
                });
                $groupedLab = $grouped->filter(function($items) {
                    return $items->first()?->jenis_barang === 'lab';
                });
            @endphp

            {{-- ═══ SECTION: BARANG LAPANGAN ═══ --}}
            @if(($selectedJenis === 'all' || $selectedJenis === 'lapangan') && $groupedLapangan->isNotEmpty())
                <div class="space-y-3 section-category-lapangan">
                    <div class="flex items-center justify-between pb-2.5 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-700 flex items-center justify-center">
                                <span class="material-symbols-rounded text-sm">agriculture</span>
                            </span>
                            <h3 class="text-sm font-bold text-gray-900">Satuan Barang Lapangan</h3>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700">{{ $groupedLapangan->flatten()->count() }}</span>
                        </div>
                        <span class="text-xs text-gray-400">Pupuk, Bibit, Alat, dll</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        @foreach($groupedLapangan as $kategoriName => $items)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:border-gray-300 transition-all category-card" data-category="{{ strtolower($kategoriName) }}">
                                <div class="px-3.5 py-2.5 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                                    <h4 class="font-bold text-gray-800 text-xs">{{ $kategoriName }}</h4>
                                    <span class="text-[10px] font-semibold text-gray-500 bg-white border border-gray-200 px-2 py-0.5 rounded-full">{{ $items->count() }} satuan</span>
                                </div>
                                <div class="p-3 flex flex-wrap gap-1.5">
                                    @foreach($items as $satuan)
                                        <div class="group flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition-all satuan-chip" data-name="{{ strtolower($satuan->nama_satuan) }}">
                                            <div>
                                                <span class="font-bold text-xs text-gray-800 uppercase font-mono">{{ $satuan->nama_satuan }}</span>
                                                @if($satuan->creator)
                                                    <span class="block text-[9px] text-gray-400">oleh: {{ Str::limit($satuan->creator->name, 12) }}</span>
                                                @endif
                                            </div>
                                            <form action="{{ route('satuan.destroy', $satuan->id) }}" method="POST"
                                                  onsubmit="return confirm('Hapus satuan \'{{ $satuan->nama_satuan }}\' dari kategori {{ $kategoriName }}?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" title="Hapus satuan ini"
                                                        class="opacity-0 group-hover:opacity-100 w-4 h-4 flex items-center justify-center text-gray-400 hover:text-red-600 rounded transition-all">
                                                    <span class="material-symbols-rounded text-xs">close</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ═══ SECTION: BARANG LABORATORIUM ═══ --}}
            @if(($selectedJenis === 'all' || $selectedJenis === 'lab') && $groupedLab->isNotEmpty())
                <div class="space-y-3 section-category-lab {{ ($selectedJenis === 'all' && $groupedLapangan->isNotEmpty()) ? 'pt-4' : '' }}">
                    <div class="flex items-center justify-between pb-2.5 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-blue-50 text-blue-700 flex items-center justify-center">
                                <span class="material-symbols-rounded text-sm">science</span>
                            </span>
                            <h3 class="text-sm font-bold text-gray-900">Satuan Barang Laboratorium</h3>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700">{{ $groupedLab->flatten()->count() }}</span>
                        </div>
                        <span class="text-xs text-gray-400">Kimia, Reagen, Alat Lab, dll</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        @foreach($groupedLab as $kategoriName => $items)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:border-gray-300 transition-all category-card" data-category="{{ strtolower($kategoriName) }}">
                                <div class="px-3.5 py-2.5 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                                    <h4 class="font-bold text-gray-800 text-xs">{{ $kategoriName }}</h4>
                                    <span class="text-[10px] font-semibold text-gray-500 bg-white border border-gray-200 px-2 py-0.5 rounded-full">{{ $items->count() }} satuan</span>
                                </div>
                                <div class="p-3 flex flex-wrap gap-1.5">
                                    @foreach($items as $satuan)
                                        <div class="group flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition-all satuan-chip" data-name="{{ strtolower($satuan->nama_satuan) }}">
                                            <div>
                                                <span class="font-bold text-xs text-gray-800 uppercase font-mono">{{ $satuan->nama_satuan }}</span>
                                                @if($satuan->creator)
                                                    <span class="block text-[9px] text-gray-400">oleh: {{ Str::limit($satuan->creator->name, 12) }}</span>
                                                @endif
                                            </div>
                                            <form action="{{ route('satuan.destroy', $satuan->id) }}" method="POST"
                                                  onsubmit="return confirm('Hapus satuan \'{{ $satuan->nama_satuan }}\' dari kategori {{ $kategoriName }}?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" title="Hapus satuan ini"
                                                        class="opacity-0 group-hover:opacity-100 w-4 h-4 flex items-center justify-center text-gray-400 hover:text-red-600 rounded transition-all">
                                                    <span class="material-symbols-rounded text-xs">close</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Empty State if no satuan found --}}
            @if($satuans->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <span class="material-symbols-rounded text-5xl text-gray-300 block mb-2">straighten</span>
                    <h4 class="font-bold text-gray-800 mb-1">Belum Ada Satuan</h4>
                    <p class="text-sm text-gray-500">Gunakan form di sebelah kiri untuk menambahkan satuan pertama.</p>
                </div>
            @endif

            {{-- ═══ SECTION: ATURAN KONVERSI SATUAN ═══ --}}
            <div id="section-konversi" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6 scroll-mt-24">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gray-50/70">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-700 flex items-center justify-center">
                            <span class="material-symbols-rounded text-lg">sync</span>
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm">Aturan Konversi Satuan Aktif</h3>
                            <p class="text-xs text-gray-500">Rumus otomatis saat requester meminta satuan berbeda</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-gray-600 bg-gray-100 border border-gray-200 px-2.5 py-0.5 rounded-full">{{ $konversis->count() }} Aturan</span>
                </div>

                <div class="p-4">
                    @if($konversis->isEmpty())
                        <div class="text-center py-6 px-4">
                            <span class="material-symbols-rounded text-3xl text-gray-300 block mb-1">swap_horiz</span>
                            <p class="text-gray-600 text-xs font-bold mb-0.5">Belum Ada Aturan Konversi</p>
                            <p class="text-gray-400 text-xs max-w-md mx-auto leading-relaxed">
                                Fitur konversi memungkinkan sistem memecah kemasan besar secara otomatis (misal: 1 Sack dipecah menjadi 25 Kg).
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($konversis as $konversi)
                                @php
                                    $isLab = $konversi->jenis_barang === 'lab';
                                @endphp
                                <div class="flex items-center justify-between p-3 bg-gray-50/80 border border-gray-200 rounded-xl hover:bg-white hover:shadow-xs transition-all">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            @if($isLab)
                                                <span class="text-[10px] font-semibold text-gray-700 bg-gray-100 border border-gray-200 px-1.5 py-0.2 rounded">Laboratorium</span>
                                            @else
                                                <span class="text-[10px] font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 px-1.5 py-0.2 rounded">Lapangan</span>
                                            @endif
                                            <span class="text-xs font-semibold text-gray-600">{{ $konversi->kategori }}</span>
                                        </div>
                                        <div class="font-bold text-gray-800 text-xs flex items-center gap-1.5">
                                            <span class="bg-white px-2 py-0.5 rounded border border-gray-200 font-mono">1 {{ strtoupper($konversi->dari_satuan) }}</span>
                                            <span class="text-gray-400 font-bold">=</span>
                                            <span class="bg-white px-2 py-0.5 rounded border border-emerald-200 text-emerald-700 font-mono font-bold">
                                                {{ (float)$konversi->multiplier }} {{ strtoupper($konversi->ke_satuan) }}
                                            </span>
                                        </div>
                                    </div>
                                    <form action="{{ route('satuan.konversi.destroy', $konversi->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus aturan konversi 1 {{ $konversi->dari_satuan }} = {{ (float)$konversi->multiplier }} {{ $konversi->ke_satuan }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus rumus ini"
                                                class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                            <span class="material-symbols-rounded text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info Notice Box --}}
            <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-xl flex items-start gap-3">
                <span class="material-symbols-rounded text-gray-500 text-lg mt-0.5">info</span>
                <div class="text-xs text-gray-600 leading-relaxed">
                    <strong class="text-gray-800">Panduan Master Satuan:</strong>
                    <ul class="list-disc list-inside mt-0.5 space-y-0.5 text-gray-500">
                        <li>Pastikan satuan baku digunakan secara konsisten (misal: gunakan <code>kg</code>).</li>
                        <li>Kondisi barang (Tersegel, Terbuka, Rusak) sudah diatur otomatis per kategori.</li>
                        <li>Hindari menghapus satuan yang masih digunakan oleh barang aktif di gudang.</li>
                    </ul>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
<script>
    const kategoriLapangan = @json($kategoriLapangan ?? []);
    const kategoriLab = @json($kategoriLab ?? []);
    const groupedSatuans = @json($grouped ?? []);

    function switchFormType(type) {
        const labelLapangan = document.getElementById('label-jenis-lapangan');
        const labelLab = document.getElementById('label-jenis-lab');
        const kategoriDatalist = document.getElementById('kategori_options');
        const kategoriInput = document.getElementById('kategori_satuan');

        if (type === 'lapangan') {
            labelLapangan.className = 'flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition-colors border-primary bg-primary/[0.04] text-gray-900';
            labelLab.className = 'flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition-colors border-gray-200 hover:border-gray-300 bg-white text-gray-700';

            kategoriDatalist.innerHTML = '';
            kategoriLapangan.forEach(kat => {
                const opt = document.createElement('option');
                opt.value = kat;
                kategoriDatalist.appendChild(opt);
            });
            kategoriInput.placeholder = 'Contoh: Pupuk, Bibit Tanaman, Alat Budidaya';
        } else {
            labelLab.className = 'flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition-colors border-primary bg-primary/[0.04] text-gray-900';
            labelLapangan.className = 'flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition-colors border-gray-200 hover:border-gray-300 bg-white text-gray-700';

            kategoriDatalist.innerHTML = '';
            kategoriLab.forEach(kat => {
                const opt = document.createElement('option');
                opt.value = kat;
                kategoriDatalist.appendChild(opt);
            });
            kategoriInput.placeholder = 'Contoh: Bahan Kimia, Reagen, Alat Laboratorium';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize datalist for conversion
        const konversiKategori = document.getElementById('konversi_kategori');
        const satuanOptionsKonversi = document.getElementById('satuan_options_konversi');

        function updateKonversiUnits() {
            const selected = konversiKategori.value;
            satuanOptionsKonversi.innerHTML = '';
            if (selected && groupedSatuans[selected]) {
                groupedSatuans[selected].forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.nama_satuan;
                    satuanOptionsKonversi.appendChild(opt);
                });
            }
        }

        if (konversiKategori) {
            konversiKategori.addEventListener('change', updateKonversiUnits);
        }

        // Initialize form type
        switchFormType('lapangan');

        // Quick Search Filter in Page
        const searchInput = document.getElementById('quickSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                const cards = document.querySelectorAll('.category-card');

                cards.forEach(card => {
                    const category = card.getAttribute('data-category') || '';
                    const chips = card.querySelectorAll('.satuan-chip');
                    let anyChipMatches = false;

                    chips.forEach(chip => {
                        const name = chip.getAttribute('data-name') || '';
                        if (name.includes(term) || category.includes(term)) {
                            chip.style.display = '';
                            anyChipMatches = true;
                        } else {
                            chip.style.display = 'none';
                        }
                    });

                    if (category.includes(term) || anyChipMatches || term === '') {
                        card.style.display = '';
                        if (category.includes(term)) {
                            chips.forEach(chip => chip.style.display = '');
                        }
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush
