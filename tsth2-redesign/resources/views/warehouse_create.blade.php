@extends('layouts.dashboard')

@section('title', 'Tambah Barang Baru - TSTH2')

@section('content')
    <div class="mb-6">
        <a href="{{ route('warehouse') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-primary transition-colors font-medium text-sm mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Inventory
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Barang</h1>
        <p class="text-gray-500 font-medium mt-1">Masukkan informasi barang baru ke sistem inventory TSTH2</p>
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
        <!-- Tabs -->
        <div class="flex border-b border-gray-100 bg-gray-50/50">
            <button type="button" onclick="switchTab('new')" id="tab-new" class="flex-1 py-4 text-center font-bold text-primary border-b-2 border-primary bg-white transition-colors">
                Tambah Barang Baru
            </button>
            <button type="button" onclick="switchTab('restock')" id="tab-restock" class="flex-1 py-4 text-center font-bold text-gray-500 border-b-2 border-transparent hover:bg-gray-50 transition-colors">
                Restock Barang Lama
            </button>
        </div>

        <form id="form-new" action="{{ route('warehouse.store') }}" method="POST" enctype="multipart/form-data" class="block">
            @csrf

            <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-8">
                <!-- Left Column -->
                <div>
                    <div class="flex items-center gap-2 mb-6 pb-2 border-b border-gray-100">
                        <span class="text-xl">📋</span>
                        <h3 class="text-lg font-bold text-gray-900">Informasi Dasar</h3>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="nama_barang">Nama Barang <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="nama_barang" name="nama_barang"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium"
                                required placeholder="Contoh: Pupuk NPK" value="{{ old('nama_barang') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="merk">Merek / Brand</label>
                            <input type="text" id="merk" name="merk"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium"
                                placeholder="Contoh: YaraMila" value="{{ old('merk') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="kategori">Kategori Barang <span
                                    class="text-red-500">*</span></label>
                            <select id="kategori" name="kategori"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium"
                                required>
                                <option value="" disabled selected>Pilih Kategori...</option>
                                <option value="Pupuk" {{ old('kategori') == 'Pupuk' ? 'selected' : '' }}>Pupuk</option>
                                <option value="Bahan Kimia" {{ old('kategori') == 'Bahan Kimia' ? 'selected' : '' }}>Bahan
                                    Kimia</option>
                                <option value="Alat Budidaya" {{ old('kategori') == 'Alat Budidaya' ? 'selected' : '' }}>
                                    Alat Budidaya</option>
                                <option value="Bibit Tanaman" {{ old('kategori') == 'Bibit Tanaman' ? 'selected' : '' }}>
                                    Bibit Tanaman</option>
                                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2" for="tanggal_kedatangan">Tanggal Kedatangan <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal_kedatangan" name="tanggal_kedatangan"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium"
                                    required value="{{ old('tanggal_kedatangan') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2" for="tanggal_kadaluarsa">Tanggal Kadaluarsa <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <input type="date" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium"
                                    value="{{ old('tanggal_kadaluarsa') }}">
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
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="lokasi_blok">Lokasi Penyimpanan
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="lokasi_blok" name="lokasi_blok"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium"
                                required placeholder="Contoh: Blok A - Rak 01" value="{{ old('lokasi_blok') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="stok">Stok Awal (Unit) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" id="stok" name="stok"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium text-lg font-mono"
                                required min="0" placeholder="0" value="{{ old('stok', 0) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="gambar">Foto Barang
                                (Opsional)</label>
                            <div
                                class="w-full relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition-colors">
                                <input type="file" id="gambar" name="gambar"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                                <div class="text-gray-500">
                                    <span class="text-3xl block mb-2">📸</span>
                                    <span class="text-sm font-bold text-primary">Klik untuk unggah</span> atau seret file ke
                                    sini
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF (Maks. 2MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('warehouse') }}"
                    class="px-6 py-2.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg font-bold transition-colors shadow-sm">Batal</a>
                <button type="submit"
                    class="px-8 py-2.5 text-white bg-primary hover:bg-primary-light rounded-lg font-bold transition-colors shadow-md shadow-primary/20">Simpan
                    Data</button>
            </div>
        </form>

        <!-- Form Restock Barang Lama -->
        <form id="form-restock" action="" method="POST" class="hidden">
            @csrf
            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2" for="existing_barang_id">Pilih Barang <span class="text-red-500">*</span></label>
                    <select id="existing_barang_id" name="existing_barang_id" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium" required onchange="updateRestockAction(this.value)">
                        <option value="" disabled selected>Pilih Barang yang ingin di-restock...</option>
                        @foreach($existingBarangs as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_barang }} (Blok {{ $b->lokasi_blok }} | Stok Saat Ini: {{ $b->stok }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="restock_tanggal_kedatangan">Tanggal Kedatangan <span class="text-red-500">*</span></label>
                        <input type="date" id="restock_tanggal_kedatangan" name="tanggal_kedatangan" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="restock_tanggal_kadaluarsa">Tanggal Kadaluarsa <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="date" id="restock_tanggal_kadaluarsa" name="tanggal_kadaluarsa" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2" for="restock_jumlah">Jumlah Masuk (Unit) <span class="text-red-500">*</span></label>
                    <input type="number" id="restock_jumlah" name="jumlah" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium text-lg font-mono" required min="1" placeholder="1">
                </div>
            </div>
            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('warehouse') }}" class="px-6 py-2.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg font-bold transition-colors shadow-sm">Batal</a>
                <button type="submit" class="px-8 py-2.5 text-white bg-green-600 hover:bg-green-700 rounded-lg font-bold transition-colors shadow-md shadow-green-500/20">Simpan Restock Batch</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        const formNew = document.getElementById('form-new');
        const formRestock = document.getElementById('form-restock');
        const tabNew = document.getElementById('tab-new');
        const tabRestock = document.getElementById('tab-restock');

        if (tab === 'new') {
            formNew.classList.remove('hidden');
            formNew.classList.add('block');
            formRestock.classList.remove('block');
            formRestock.classList.add('hidden');

            tabNew.classList.add('text-primary', 'border-primary', 'bg-white');
            tabNew.classList.remove('text-gray-500', 'border-transparent', 'hover:bg-gray-50');
            
            tabRestock.classList.remove('text-primary', 'border-primary', 'bg-white');
            tabRestock.classList.add('text-gray-500', 'border-transparent', 'hover:bg-gray-50');
        } else {
            formNew.classList.remove('block');
            formNew.classList.add('hidden');
            formRestock.classList.remove('hidden');
            formRestock.classList.add('block');

            tabRestock.classList.add('text-primary', 'border-primary', 'bg-white');
            tabRestock.classList.remove('text-gray-500', 'border-transparent', 'hover:bg-gray-50');
            
            tabNew.classList.remove('text-primary', 'border-primary', 'bg-white');
            tabNew.classList.add('text-gray-500', 'border-transparent', 'hover:bg-gray-50');
        }
    }

    function updateRestockAction(id) {
        document.getElementById('form-restock').action = `/warehouse/${id}/batch`;
    }
</script>
@endpush
