@extends('layouts.dashboard')

@section('title', 'Edit Barang - TSTH2')

@section('content')
    <div class="mb-6">
        <a href="{{ route('warehouse') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary transition-colors font-medium text-sm mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Inventory
        </a>
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Barang</h1>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 font-mono text-sm rounded-lg font-bold">#INV{{ str_pad($barang->id, 3, '0', STR_PAD_LEFT) }}</span>
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
        <form action="{{ route('warehouse.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-8">
                <!-- Left Column -->
                <div>
                    <div class="flex items-center gap-2 mb-6 pb-2 border-b border-gray-100">
                        <span class="text-xl">📋</span>
                        <h3 class="text-lg font-bold text-gray-900">Informasi Dasar</h3>
                    </div>
                    
                    <div class="space-y-5">
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
                                <option value="Pupuk" {{ old('kategori', $barang->kategori) == 'Pupuk' ? 'selected' : '' }}>Pupuk</option>
                                <option value="Bahan Kimia" {{ old('kategori', $barang->kategori) == 'Bahan Kimia' ? 'selected' : '' }}>Bahan Kimia</option>
                                <option value="Alat Budidaya" {{ old('kategori', $barang->kategori) == 'Alat Budidaya' ? 'selected' : '' }}>Alat Budidaya</option>
                                <option value="Bibit Tanaman" {{ old('kategori', $barang->kategori) == 'Bibit Tanaman' ? 'selected' : '' }}>Bibit Tanaman</option>
                                <option value="Lainnya" {{ old('kategori', $barang->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="flex items-center gap-2 mb-6 pb-2 border-b border-gray-100">
                        <span class="text-xl">🏢</span>
                        <h3 class="text-lg font-bold text-gray-900">Penyimpanan & Media</h3>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="lokasi_blok">Lokasi Penyimpanan <span class="text-red-500">*</span></label>
                            <input type="text" id="lokasi_blok" name="lokasi_blok" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium" required value="{{ old('lokasi_blok', $barang->lokasi_blok) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="stok">Total Stok Saat Ini (Unit) <span class="text-gray-400 font-normal">(Auto)</span></label>
                            <input type="number" id="stok" name="stok" class="w-full bg-gray-100 border border-gray-200 text-gray-900 rounded-lg p-3 font-medium text-lg font-mono cursor-not-allowed" readonly required min="0" value="{{ old('stok', $barang->stok) }}">
                            <p class="text-xs text-gray-500 mt-1">Stok hanya bisa ditambah melalui fitur Restock.</p>
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
                <a href="{{ route('warehouse') }}" class="px-6 py-2.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg font-bold transition-colors shadow-sm">Batal</a>
                <button type="submit" class="px-8 py-2.5 text-white bg-primary hover:bg-primary-light rounded-lg font-bold transition-colors shadow-md shadow-primary/20">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
