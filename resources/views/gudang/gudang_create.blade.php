@extends('layouts.dasbor')

@section('title', 'Tambah Barang Baru - TSTH2')

@section('content')
    <div class="mb-6">
        <a href="{{ route('gudang') }}"
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

        <form id="form-new" action="{{ route('gudang.store') }}" method="POST" enctype="multipart/form-data" class="block">
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
                                @foreach(array_keys($satuanByKategori) as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2" for="tanggal_kedatangan">Tanggal Kedatangan <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal_kedatangan" name="tanggal_kedatangan"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium"
                                    required value="{{ old('tanggal_kedatangan') }}">
                            </div>

                            <div id="container-kadaluarsa" style="display: {{ old('kategori') == 'Alat Budidaya' ? 'none' : 'block' }}">
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

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-bold text-gray-700">Detail Stok (Multi-Satuan) <span class="text-red-500">*</span></label>
                                <button type="button" id="btn-add-stok" class="text-xs font-bold text-primary hover:text-primary-light flex items-center gap-1">
                                    <span>➕</span> Tambah Ukuran
                                </button>
                            </div>
                            
                            <div id="stok-container" class="space-y-3">
                                <div class="stok-row flex items-center gap-2">
                                    <div class="w-1/4">
                                        <input type="number" name="stok_jumlah[]" class="w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-2.5 font-medium font-mono" required min="1" placeholder="Jml">
                                    </div>
                                    <div class="w-2/4">
                                        <select name="stok_satuan[]" class="satuan-dropdown w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-2.5 font-medium" required>
                                            <option value="">-- Kategori Dulu --</option>
                                        </select>
                                    </div>
                                    <div class="w-1/4">
                                        <select name="stok_kondisi[]" class="kondisi-dropdown w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-2.5 font-medium" required>
                                            <option value="">-- Kategori Dulu --</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn-remove-stok text-gray-400 hover:text-red-500 transition-colors p-2 hidden">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-3">Kelola satuan di <a href="{{ route('satuan.index') }}" class="text-primary hover:underline font-bold">Master Satuan</a></p>
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
                <a href="{{ route('gudang') }}"
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
                            <option value="{{ $b->id }}" data-kategori="{{ $b->kategori }}">{{ $b->nama_barang }} (Blok {{ $b->lokasi_blok }} | Total Stok: {{ $b->batches->sum('stok') }})</option>
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-3">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-bold text-gray-700">Detail Stok (Multi-Satuan) <span class="text-red-500">*</span></label>
                                <button type="button" id="btn-add-restock-stok" class="text-xs font-bold text-primary hover:text-primary-light flex items-center gap-1">
                                    <span>➕</span> Tambah Ukuran
                                </button>
                            </div>
                            
                            <div id="restock-stok-container" class="space-y-3">
                                <div class="stok-row flex items-center gap-2">
                                    <div class="w-1/4">
                                        <input type="number" name="stok_jumlah[]" class="w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-2.5 font-medium font-mono" required min="1" placeholder="Jml">
                                    </div>
                                    <div class="w-2/4">
                                        <select name="stok_satuan[]" class="restock-satuan-dropdown w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-2.5 font-medium" required>
                                            <option value="">-- Kategori Dulu --</option>
                                        </select>
                                    </div>
                                    <div class="w-1/4">
                                        <select name="stok_kondisi[]" class="restock-kondisi-dropdown w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-2.5 font-medium" required>
                                            <option value="">-- Kategori Dulu --</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn-remove-stok text-gray-400 hover:text-red-500 transition-colors p-2 hidden">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('gudang') }}" class="px-6 py-2.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg font-bold transition-colors shadow-sm">Batal</a>
                <button type="submit" class="px-8 py-2.5 text-white bg-green-600 hover:bg-green-700 rounded-lg font-bold transition-colors shadow-md shadow-green-500/20">Simpan Restock Batch</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    const satuanByKategori = @json($satuanByKategori);
    const kondisiByKategori = @json($kondisiByKategori);

    function populateOptions(selectElement, options, isKondisi = false) {
        selectElement.innerHTML = '<option value="">-- Pilih --</option>';
        if (options) {
            options.forEach(opt => {
                const optionElement = document.createElement('option');
                if (isKondisi) {
                    optionElement.value = opt.value;
                    optionElement.textContent = opt.label;
                } else {
                    optionElement.value = opt;
                    optionElement.textContent = opt.toUpperCase();
                }
                selectElement.appendChild(optionElement);
            });
        }
    }

    // Setup Dynamic Stock Rows
    function setupDynamicRows(btnId, containerId, dropdownClass) {
        document.getElementById(btnId).addEventListener('click', function() {
            const container = document.getElementById(containerId);
            const rows = container.querySelectorAll('.stok-row');
            
            // Clone first row
            const newRow = rows[0].cloneNode(true);
            newRow.querySelector('input').value = '';
            
            // Show remove button
            const removeBtn = newRow.querySelector('.btn-remove-stok');
            removeBtn.classList.remove('hidden');
            removeBtn.addEventListener('click', function() {
                newRow.remove();
            });

            container.appendChild(newRow);
        });
    }

    setupDynamicRows('btn-add-stok', 'stok-container', 'satuan-dropdown');
    setupDynamicRows('btn-add-restock-stok', 'restock-stok-container', 'restock-satuan-dropdown');

    // Make sure first remove button doesn't work but subsequent ones do (already handled by clone logic)
    
    // New Item Form Kategori Change
    document.getElementById('kategori').addEventListener('change', function() {
        const kategori = this.value;
        const satuans = satuanByKategori[kategori] || satuanByKategori['Lainnya'] || [];
        const kondisis = kondisiByKategori[kategori] || kondisiByKategori['Lainnya'] || [];

        // Update all satuan and kondisi dropdowns in new item form
        document.querySelectorAll('.satuan-dropdown').forEach(select => {
            populateOptions(select, satuans);
        });
        document.querySelectorAll('.kondisi-dropdown').forEach(select => {
            populateOptions(select, kondisis, true);
        });
    });

    // Restock Form Barang Change
    document.getElementById('existing_barang_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const kategori = selectedOption.getAttribute('data-kategori');
        
        if (kategori) {
            const satuans = satuanByKategori[kategori] || satuanByKategori['Lainnya'] || [];
            const kondisis = kondisiByKategori[kategori] || kondisiByKategori['Lainnya'] || [];

            document.querySelectorAll('.restock-satuan-dropdown').forEach(select => {
                populateOptions(select, satuans);
            });
            document.querySelectorAll('.restock-kondisi-dropdown').forEach(select => {
                populateOptions(select, kondisis, true);
            });
        } else {
            document.querySelectorAll('.restock-satuan-dropdown').forEach(select => {
                select.innerHTML = '<option value="">-- Pilih Barang Dulu --</option>';
            });
            document.querySelectorAll('.restock-kondisi-dropdown').forEach(select => {
                select.innerHTML = '<option value="">-- Pilih Barang Dulu --</option>';
            });
        }
    });

    // Trigger change on load if value exists
    if(document.getElementById('kategori').value) {
        document.getElementById('kategori').dispatchEvent(new Event('change'));
    }

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
        document.getElementById('form-restock').action = `/gudang/${id}/batch`;
    }

    // Tanggal Kedatangan -> Tanggal Kadaluarsa Min (Form New & Form Restock)
    const attachDateConstraint = (kedatanganId, kadaluarsaId) => {
        const kedatanganInput = document.getElementById(kedatanganId);
        const kadaluarsaInput = document.getElementById(kadaluarsaId);
        if (kedatanganInput && kadaluarsaInput) {
            kedatanganInput.addEventListener('change', function() {
                kadaluarsaInput.min = this.value;
                if (kadaluarsaInput.value && kadaluarsaInput.value < this.value) {
                    kadaluarsaInput.value = this.value;
                }
            });
            if (kedatanganInput.value) kadaluarsaInput.min = kedatanganInput.value;
        }
    };
    
    attachDateConstraint('tanggal_kedatangan', 'tanggal_kadaluarsa');
    attachDateConstraint('restock_tanggal_kedatangan', 'restock_tanggal_kadaluarsa');
</script>
@endpush
