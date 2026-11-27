@extends('layouts.dasbor')

@section('title', 'Edit Barang - TSTH2')

@section('content')
    <div class="mb-6">
        <a href="{{ route('gudang') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary transition-colors font-medium text-sm mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Inventory
        </a>
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Barang</h1>
            <span class="px-3 py-1 bg-gray-900 text-white font-mono text-sm rounded-lg font-bold">#{{ $barang->kode_barang }}</span>
        </div>
        <p class="text-gray-500 font-medium mt-1">Perbarui informasi barang yang sudah terdaftar</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
        @if ($errors->any())
            <div class="m-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">Terdapat kesalahan pada input Anda:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        <form action="{{ route('gudang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-8">
                <!-- Left Column -->
                <div>
                    <div class="flex items-center gap-2 mb-6 pb-2 border-b border-gray-100">
                        <span class="material-symbols-rounded text-xl text-primary">description</span>
                        <h3 class="text-lg font-bold text-gray-900">Informasi Dasar</h3>
                    </div>
                    
                    <div class="space-y-5">
                        {{-- Tipe Barang (Warehouse Gudang GA vs Warehouse Lab RdI) --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Warehouse Tujuan & Peruntukan <span class="text-red-500">*</span>
                            </label>
                            @if(auth()->user()->isPicGudang())
                                <input type="hidden" name="jenis_barang" value="lapangan">
                                <div class="flex items-center gap-3 p-3.5 bg-amber-50 border border-amber-200 rounded-xl">
                                    <span class="w-9 h-9 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                                        <span class="material-symbols-rounded text-xl">warehouse</span>
                                    </span>
                                    <div>
                                        <span class="block text-xs font-bold text-amber-950">Warehouse Gudang — Departemen General Affair (GA)</span>
                                        <span class="block text-[11px] text-amber-700">Barang ini tercatat di inventaris Gudang Umum / GA.</span>
                                    </div>
                                </div>
                            @elseif(auth()->user()->isPicLab())
                                <input type="hidden" name="jenis_barang" value="lab">
                                <div class="flex items-center gap-3 p-3.5 bg-purple-50 border border-purple-200 rounded-xl">
                                    <span class="w-9 h-9 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center">
                                        <span class="material-symbols-rounded text-xl">science</span>
                                    </span>
                                    <div>
                                        <span class="block text-xs font-bold text-purple-950">Warehouse Laboratorium — Departemen Riset dan Inovasi (RdI)</span>
                                        <span class="block text-[11px] text-purple-700">Barang ini tercatat di inventaris Laboratorium Riset & Kimia RdI.</span>
                                    </div>
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-primary transition-all has-[:checked]:border-primary has-[:checked]:ring-1 has-[:checked]:ring-primary has-[:checked]:bg-primary/[0.02]">
                                        <input type="radio" name="jenis_barang" value="lapangan" {{ old('jenis_barang', $barang->jenis_barang ?? 'lapangan') === 'lapangan' ? 'checked' : '' }} class="text-primary focus:ring-primary" required>
                                        <div>
                                            <span class="block text-xs font-bold text-gray-900">🏢 Warehouse Gudang (GA)</span>
                                            <span class="block text-[11px] text-gray-500">Pupuk, Bibit, Perkakas, Umum</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-primary transition-all has-[:checked]:border-primary has-[:checked]:ring-1 has-[:checked]:ring-primary has-[:checked]:bg-primary/[0.02]">
                                        <input type="radio" name="jenis_barang" value="lab" {{ old('jenis_barang', $barang->jenis_barang) === 'lab' ? 'checked' : '' }} class="text-primary focus:ring-primary" required>
                                        <div>
                                            <span class="block text-xs font-bold text-gray-900">🔬 Warehouse Lab (RdI)</span>
                                            <span class="block text-[11px] text-gray-500">Reagen, Kimia, Glassware, Lab</span>
                                        </div>
                                    </label>
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="kode_barang">Kode Barang <span class="text-red-500">*</span></label>
                            <input type="text" id="kode_barang" name="kode_barang" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-mono font-bold uppercase" required value="{{ old('kode_barang', $barang->kode_barang) }}">
                            <p class="text-xs text-gray-400 mt-1">Format: <code>[Kategori][Blok][Rak][Urutan]</code>. Contoh: <strong>1111</strong>.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="nama_barang">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" id="nama_barang" name="nama_barang" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium" required value="{{ old('nama_barang', $barang->nama_barang) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="merk">Merek / Brand</label>
                            <input type="text" id="merk" name="merk" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium" value="{{ old('merk', $barang->merk) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="kategori">Kategori Barang <span class="text-red-500">*</span></label>
                            <select id="kategori" name="kategori" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium" required>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori', $barang->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>

                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="flex items-center gap-2 mb-6 pb-2 border-b border-gray-100">
                        <span class="material-symbols-rounded text-xl text-primary">domain</span>
                        <h3 class="text-lg font-bold text-gray-900">Penyimpanan & Media</h3>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="lokasi_blok">Lokasi Penyimpanan <span class="text-red-500">*</span></label>
                            <select id="lokasi_blok" name="lokasi_blok" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium" required>
                                @foreach($lokasiList ?? [] as $lok)
                                    <option value="{{ $lok }}" {{ old('lokasi_blok', $barang->lokasi_blok) == $lok ? 'selected' : '' }}>{{ $lok }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="stok">Total Stok Saat Ini <span class="text-gray-400 font-normal">(Auto)</span></label>
                            <input type="text" id="stok" name="stok" class="w-full bg-gray-100 border border-gray-200 text-gray-900 rounded-lg p-3 font-medium text-lg font-mono cursor-not-allowed" readonly required value="{{ old('stok', $barang->formatted_stok) }}" title="{{ $barang->stok_breakdown }}">
                            @if($barang->has_multiple_satuan)
                                <p class="text-xs text-emerald-700 font-semibold mt-1">Rincian stok: {{ $barang->stok_breakdown }}</p>
                            @else
                                <p class="text-xs text-gray-500 mt-1">Stok hanya bisa ditambah melalui fitur Restock.</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="gambar">Perbarui Foto (Opsional)</label>
                            
                            @if($barang->gambar)
                            <div class="flex items-center gap-4 mb-3">
                                <img src="{{ asset('storage/' . $barang->gambar) }}" class="w-16 h-16 rounded-xl object-cover border border-gray-200 shadow-sm">
                                <div class="text-xs text-gray-500 font-medium">Gambar Saat Ini<br>Biarkan kosong jika tidak diubah.</div>
                            </div>
                            @endif

                            <div class="w-full relative border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition-colors">
                                <input type="file" id="gambar" name="gambar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                                <div class="text-gray-500 flex flex-col items-center justify-center">
                                    <span class="text-sm font-bold text-primary">Klik untuk ganti foto</span>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('gudang') }}" class="px-6 py-2.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg font-bold transition-colors shadow-sm">Batal</a>
                <button type="submit" class="px-8 py-2.5 text-white bg-primary hover:bg-primary-light rounded-lg font-bold transition-colors shadow-md shadow-primary/20">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
