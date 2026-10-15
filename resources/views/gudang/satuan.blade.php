@extends('layouts.dasbor')

@section('title', 'Master Satuan Barang - TSTH2')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-1">Master Satuan Barang</h1>
        <p class="text-gray-500 font-medium">Kelola satuan per kategori barang</p>
    </div>
    <a href="{{ route('gudang') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-semibold text-sm transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>
</div>

@if(session('success'))
<div class="mb-5 flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl font-semibold shadow-sm">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-5 flex items-center gap-3 px-5 py-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl font-semibold shadow-sm">
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Form Tambah --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900 mb-5">Tambah Satuan Baru</h2>
                <form action="{{ route('satuan.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="nama_satuan">
                            Nama Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_satuan" id="nama_satuan"
                            placeholder="cth: sack, kg, botol..."
                            class="w-full bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm transition-all text-sm"
                            value="{{ old('nama_satuan') }}" required>
                        @error('nama_satuan')<p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="kategori_satuan">
                            Kategori Barang <span class="text-red-500">*</span>
                        </label>
                        <input list="kategori_options" name="kategori" id="kategori_satuan"
                            class="w-full bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm transition-all text-sm"
                            placeholder="Pilih atau ketik kategori baru" required>
                        <datalist id="kategori_options">
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat }}"></option>
                            @endforeach
                        </datalist>
                        <p class="text-xs text-gray-400 mt-1.5">Anda bisa mengetik nama kategori baru jika belum ada di daftar.</p>
                    </div>
                    <button type="submit" class="w-full px-5 py-3 bg-primary text-white rounded-xl font-bold hover:bg-primary-light transition-colors shadow-md shadow-primary/20 text-sm">
                        Tambahkan Satuan
                    </button>
                </form>
            </div>

            <hr class="border-gray-100">

            <div>
                <h2 class="text-lg font-bold text-gray-900 mb-5">Atur Konversi Satuan</h2>
                <form action="{{ route('satuan.konversi.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="konversi_kategori">Kategori Barang <span class="text-red-500">*</span></label>
                        <select name="kategori" id="konversi_kategori"
                            class="w-full bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm transition-all text-sm cursor-pointer"
                            required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat }}">{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-700 mb-1" for="dari_satuan">1 (Satu)</label>
                            <input list="satuan_options" name="dari_satuan" id="dari_satuan"
                                placeholder="cth: dus"
                                class="w-full bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm transition-all text-sm uppercase"
                                required>
                        </div>
                        <span class="font-bold text-gray-400 mt-5">=</span>
                        <div class="w-24">
                            <label class="block text-xs font-bold text-gray-700 mb-1" for="multiplier">Berapa</label>
                            <input type="number" step="0.01" name="multiplier" id="multiplier"
                                placeholder="24"
                                class="w-full bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm transition-all text-sm"
                                required>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-700 mb-1" for="ke_satuan">Satuan?</label>
                            <input list="satuan_options" name="ke_satuan" id="ke_satuan"
                                placeholder="cth: kg"
                                class="w-full bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm transition-all text-sm uppercase"
                                required>
                        </div>
                    </div>
                    <datalist id="satuan_options">
                    </datalist>
                    <button type="submit" class="w-full px-5 py-3 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition-colors shadow-md shadow-emerald-500/20 text-sm">
                        Simpan Konversi
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Daftar Satuan by Kategori --}}
    <div class="lg:col-span-2 space-y-5">

        @foreach($grouped as $kategoriName => $items)
        @if($items->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    {{ $kategoriName }}
                </h3>
                <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-full">{{ $items->count() }} satuan</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4">
                @foreach($items as $satuan)
                <div class="group flex items-center justify-between px-3 py-2.5 bg-gray-50 hover:bg-primary/5 border border-gray-100 hover:border-primary/20 rounded-xl transition-all duration-200">
                    <div>
                        <span class="font-bold text-gray-800 text-sm">{{ strtoupper($satuan->nama_satuan) }}</span>
                        @if($satuan->creator)
                            <p class="text-xs text-gray-400">{{ $satuan->creator->name }}</p>
                        @endif
                    </div>
                    <form action="{{ route('satuan.destroy', $satuan->id) }}" method="POST"
                          onsubmit="return confirm('Hapus satuan \'{{ $satuan->nama_satuan }}\'?');">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="opacity-0 group-hover:opacity-100 w-7 h-7 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach

        @if($satuans->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
            <p class="text-gray-500">Belum ada satuan terdaftar.</p>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-6">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-emerald-50/50">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    Aturan Konversi Satuan
                </h3>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">{{ $konversis->count() }} aturan</span>
            </div>
            <div class="p-4">
                @if($konversis->isEmpty())
                    <div class="text-center py-6 px-4">
                        <p class="text-gray-500 text-sm mb-2 font-semibold">Belum ada aturan konversi yang diatur.</p>
                        <p class="text-gray-400 text-xs leading-relaxed">
                            Fitur ini berfungsi agar sistem bisa otomatis <b>memecah kemasan besar</b> (misal: 1 Dus otomatis dipecah jadi 24 Pcs saat pengguna me-permintaan Pcs). 
                            <br><br>
                            Jika dibiarkan kosong, tidak masalah! Sistem akan tetap berjalan normal, namun sistem hanya akan melayani permintaan barang jika satuan yang diminta <b>sama persis</b> dengan satuan stok yang ada di gudang.
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($konversis as $konversi)
                        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 bg-gray-200 px-1.5 py-0.5 rounded">{{ $konversi->kategori }}</span>
                                <div class="font-bold text-gray-700 text-sm flex items-center gap-2 mt-1">
                                    <span class="bg-white px-2 py-1 rounded border shadow-sm">1 {{ strtoupper($konversi->dari_satuan) }}</span>
                                    <span class="text-gray-400">=</span>
                                    <span class="bg-white px-2 py-1 rounded border shadow-sm text-emerald-600">{{ $konversi->multiplier }} {{ strtoupper($konversi->ke_satuan) }}</span>
                                </div>
                            </div>
                            <form action="{{ route('satuan.konversi.destroy', $konversi->id) }}" method="POST"
                                onsubmit="return confirm('Hapus aturan konversi ini?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-7 h-7 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl flex gap-3">
            <div class="text-sm text-amber-800">
                <strong>Perhatian:</strong> Jangan hapus satuan yang sedang dipakai data barang aktif.
                <br>Kondisi barang (Tersegel, Sudah Terbuka, dll) sudah otomatis tersedia per kategori — tidak perlu didaftarkan di sini.
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriInput = document.getElementById('konversi_kategori');
        const datalist = document.getElementById('satuan_options');
        
        // Convert the PHP grouped array into a Javascript object
        const groupedSatuans = @json($grouped);

        function updateSatuanOptions() {
            const selectedKategori = kategoriInput.value.trim();
            datalist.innerHTML = '';
            
            if (selectedKategori && groupedSatuans[selectedKategori]) {
                const units = groupedSatuans[selectedKategori];
                units.forEach(unit => {
                    const opt = document.createElement('option');
                    opt.value = unit.nama_satuan;
                    datalist.appendChild(opt);
                });
            } else {
                // If no category selected, or category not found, show all or empty
                // Optional: we can leave it empty to force selecting a category first.
            }
        }

        kategoriInput.addEventListener('change', updateSatuanOptions);
        kategoriInput.addEventListener('input', updateSatuanOptions);
    });
</script>
@endpush
