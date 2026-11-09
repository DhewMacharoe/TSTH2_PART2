@extends('layouts.dasbor')

@section('title', 'Buat Permintaan Barang - TSTH2')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('permintaan.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 font-semibold text-sm mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Permintaan
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-1">Buat Permintaan Barang</h1>
        <p class="text-gray-500 font-medium">Ajukan permintaan pengambilan barang dari gudang</p>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
        <p class="font-bold text-red-700 mb-2">Terdapat kesalahan:</p>
        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-primary/5 to-accent/5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-md shadow-primary/20">
                    <span class="material-symbols-rounded text-xl">shopping_cart</span>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900">Form Permintaan Barang</h2>
                    <p class="text-xs text-gray-500">Permintaan akan dikirim ke PIC Gudang</p>
                </div>
            </div>
        </div>

        <form action="{{ route('permintaan.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- Quick Search by Kode Barang --}}
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                <div class="flex items-center justify-between mb-1.5">
                    <label class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">
                        <span class="material-symbols-rounded text-base text-primary">gps_fixed</span> Tembak Langsung Kode Barang
                    </label>
                    <span class="text-[11px] font-semibold text-primary bg-primary/10 px-2 py-0.5 rounded-full">
                        Otomatis Pilih Sasaran
                    </span>
                </div>
                <div class="flex gap-2">
                    <input type="text" id="quick_kode_input" placeholder="Ketik / tempel kode barang, misal: 1111-1 atau 1111..." 
                           value="{{ $requestedKode ?? old('quick_kode', '') }}"
                           class="flex-1 px-3.5 py-2 bg-white border border-gray-200 rounded-lg text-sm font-mono font-semibold text-gray-800 focus:ring-primary focus:border-primary">
                    <button type="button" id="btn_tembak_kode" class="px-4 py-2 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-lg transition-colors">
                        Tembak Kode
                    </button>
                </div>
                <p id="kode_status_msg" class="text-xs text-gray-500 mt-1.5 font-medium">Format: <code>[Kategori][Blok][Rak][Urutan]-[Batch]</code>. Contoh: <strong>1111-1</strong> (Pupuk, Blok 1 Rak 1, NPK #1, Batch 1).</p>
            </div>

            {{-- Pilih Barang --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2" for="barang_id">
                    Pilih Barang <span class="text-red-500">*</span>
                </label>
                <select name="barang_id" id="barang_id"
                    class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm transition-all @error('barang_id') border-red-400 @enderror"
                    required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barangs as $barang)
                        @php $totalStok = $barang->batches->sum('stok'); @endphp
                        <option value="{{ $barang->id }}"
                            data-kode="{{ $barang->kode_barang }}"
                            data-kategori="{{ $barang->kategori }}"
                            data-stok="{{ $totalStok }}"
                            {{ old('barang_id', $selectedBarangId ?? null) == $barang->id ? 'selected' : '' }}
                            {{ $totalStok <= 0 ? 'disabled' : '' }}>
                            [{{ $barang->kode_barang }}] {{ $barang->nama_barang }}
                            @if($barang->merk) ({{ $barang->merk }}) @endif
                            — Stok: {{ $totalStok }}
                            {{ $totalStok <= 0 ? '[HABIS]' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('barang_id')<p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>@enderror
            </div>

            {{-- Info Stok Tersedia + Kondisi FIFO --}}
            <div id="stokInfoBox" class="hidden p-4 bg-blue-50 border border-blue-100 rounded-xl text-sm">
                <p class="font-bold text-blue-800 mb-2 flex items-center gap-1.5">
                    <span class="material-symbols-rounded text-base">inventory_2</span> Stok Tersedia 
                    <span id="infoKategori" class="ml-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full font-bold"></span>
                </p>
                <div id="stokInfoKonten" class="space-y-1 text-blue-700"></div>
            </div>

            {{-- Jumlah & Satuan (dinamis by kategori) --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2" for="jumlah">
                        Jumlah <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" min="1"
                        value="{{ old('jumlah', 1) }}"
                        class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm transition-all"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2" for="satuan_diminta">
                        Satuan <span class="text-red-500">*</span>
                    </label>
                    <select name="satuan_diminta" id="satuan_diminta"
                        class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm transition-all"
                        required>
                        <option value="">-- Pilih Barang dulu --</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Satuan otomatis disesuaikan dengan kategori barang</p>
                    @error('satuan_diminta')<p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Catatan --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2" for="catatan">
                    Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea name="catatan" id="catatan" rows="3"
                    placeholder="Keperluan atau keterangan tambahan untuk PIC Gudang..."
                    class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm transition-all resize-none">{{ old('catatan') }}</textarea>
            </div>

            {{-- Info Alur --}}
            <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl">
                <p class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Alur setelah submit:</p>
                <div class="flex items-center gap-2 flex-wrap text-xs text-gray-500">
                    <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full font-semibold">Menunggu Approval</span>
                    <span>→</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">Jadwal Ditetapkan</span>
                    <span>→</span>
                    <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full font-semibold inline-flex items-center gap-1">
                        <span class="material-symbols-rounded text-xs">photo_camera</span> Foto Ambil
                    </span>
                    <span>→</span>
                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full font-semibold">Selesai</span>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('permintaan.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition-colors text-sm">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-light text-white rounded-xl font-bold transition-colors shadow-md shadow-primary/20 text-sm">
                    Kirim Permintaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Data satuan & kondisi per kategori (dari server)
const satuanByKategori = @json($satuanByKategori);
const kondisiByKategori = @json($kondisiByKategori);

// Data barang (id → {kategori, batches stok info})
@php
    $processedBarangs = $barangs->map(function($b) {
        $allDetails = collect();
        foreach ($b->batches as $batch) {
            $isExpired = $batch->tanggal_kadaluarsa && \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->endOfDay()->isPast();
            if ($isExpired) continue;

            $batchCleanNo = (int) ltrim((string)($batch->batch_no ?? '1'), 'B');
            $details = is_array($batch->stok_detail) ? $batch->stok_detail : json_decode($batch->stok_detail ?? '[]', true);
            if(empty($details)) {
                if($batch->stok > 0 && \App\Models\SatuanBarang::kondisiPriority($batch->kondisi ?? '') !== 99) {
                    $allDetails->push([
                        'batch_no'   => $batchCleanNo,
                        'stok'       => $batch->stok,
                        'satuan'     => $batch->satuan ?? '-',
                        'kondisi'    => $batch->kondisi ?? '-',
                        'kadaluarsa' => $batch->tanggal_kadaluarsa ? \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->format('d M Y') : null,
                    ]);
                }
            } else {
                foreach($details as $item) {
                    if(isset($item['stok']) && $item['stok'] > 0 && \App\Models\SatuanBarang::kondisiPriority($item['kondisi'] ?? '') !== 99) {
                        $allDetails->push([
                            'batch_no'   => $batchCleanNo,
                            'stok'       => $item['stok'],
                            'satuan'     => $item['satuan'] ?? '-',
                            'kondisi'    => $item['kondisi'] ?? '-',
                            'kadaluarsa' => $batch->tanggal_kadaluarsa ? \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->format('d M Y') : null,
                        ]);
                    }
                }
            }
        }
        
        return [
            'id'          => $b->id,
            'kategori'    => $b->kategori,
            'kode_barang' => $b->kode_barang,
            'lokasi_blok' => $b->lokasi_blok,
            'batches'     => $allDetails->values(),
        ];
    })->keyBy('id');
@endphp
const barangData = @json($processedBarangs);

const barangSelect  = document.getElementById('barang_id');
const satuanSelect  = document.getElementById('satuan_diminta');
const stokInfoBox   = document.getElementById('stokInfoBox');
const stokInfoKonten = document.getElementById('stokInfoKonten');
let targetBatchSelected = @json($targetBatchNumber ?? null);

barangSelect.addEventListener('change', function() {
    const id = this.value;
    const barang = barangData[id];

    if (!barang) {
        satuanSelect.innerHTML = '<option value="">-- Pilih Barang dulu --</option>';
        stokInfoBox.classList.add('hidden');
        return;
    }

    const kategori = barang.kategori;

    // Update satuan dropdown berdasarkan satuan yang tersedia di batch stok ditambah satuan dari master
    let unitsFromBatches = barang.batches.map(b => b.satuan.toLowerCase());
    let unitsFromMaster = satuanByKategori[kategori] ? satuanByKategori[kategori].map(s => s.toLowerCase()) : [];
    const availableUnits = [...new Set([...unitsFromBatches, ...unitsFromMaster])];
    
    satuanSelect.innerHTML = '<option value="">-- Pilih Satuan --</option>';
    if (availableUnits.length > 0) {
        availableUnits.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s;
            opt.textContent = s.toUpperCase();
            satuanSelect.appendChild(opt);
        });
    } else {
        satuanSelect.innerHTML = '<option value="">-- Stok Habis --</option>';
    }

    // Tampilkan info stok dengan FIFO indicator & target batch highlight
    document.getElementById('infoKategori').textContent = kategori;
    const batches = barang.batches;

    if (batches.length === 0) {
        stokInfoKonten.innerHTML = '<p class="text-orange-600 font-semibold flex items-center gap-1"><span class="material-symbols-rounded text-base">warning</span> Semua stok habis.</p>';
    } else {
        const fifoOrder = [
            'Sudah Terbuka', 'Sisa Sedikit', 'Hampir Habis', 'Bekas Pakai - Perlu Perbaikan',
            'Bekas Pakai - Bagus', 'Sudah Dipakai'
        ];

        const sorted = [...batches].sort((a, b) => {
            const pa = fifoOrder.indexOf(a.kondisi) !== -1 ? fifoOrder.indexOf(a.kondisi) : 99;
            const pb = fifoOrder.indexOf(b.kondisi) !== -1 ? fifoOrder.indexOf(b.kondisi) : 99;
            return pa - pb;
        });

        stokInfoKonten.innerHTML = sorted.map((b, i) => {
            const isFirst = i === 0;
            const isTargetBatch = targetBatchSelected && b.batch_no === targetBatchSelected;
            const rowClass = isTargetBatch ? 'bg-emerald-100/70 border border-emerald-300 rounded-lg p-2 my-1' : 'py-1 border-b border-blue-100 last:border-0';
            
            return `<div class="flex items-center gap-3 ${rowClass}">
                ${isTargetBatch ? '<span class="text-xs bg-emerald-700 text-white px-2 py-0.5 rounded font-extrabold shadow-sm animate-pulse inline-flex items-center gap-1"><span class="material-symbols-rounded text-xs">gps_fixed</span> BATCH #' + b.batch_no + '</span>' : (isFirst ? '<span class="text-xs bg-blue-600 text-white px-1.5 py-0.5 rounded font-bold">Prioritas FIFO</span>' : '<span class="text-xs text-gray-500 font-mono">Batch #' + (b.batch_no || (i+1)) + '</span>')}
                <span class="font-semibold text-gray-900">${b.stok} ${b.satuan.toUpperCase()}</span>
                <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full font-medium">${b.kondisi}</span>
                ${b.kadaluarsa ? `<span class="text-xs text-orange-600 font-semibold ml-auto">Exp: ${b.kadaluarsa}</span>` : ''}
            </div>`;
        }).join('');
    }

    stokInfoBox.classList.remove('hidden');
});

satuanSelect.addEventListener('change', function() {
    const id = barangSelect.value;
    const barang = barangData[id];
    if (!barang) return;

    const selectedSatuan = this.value;
    let totalStok = 0;
    
    barang.batches.forEach(b => {
        if (b.satuan.toLowerCase() === selectedSatuan.toLowerCase()) {
            totalStok += b.stok;
        }
    });

    const jumlahInput = document.getElementById('jumlah');
    if (totalStok > 0) {
        jumlahInput.max = totalStok;
    } else {
        jumlahInput.removeAttribute('max');
    }
});

// Mencegah input jumlah melebihi max
document.getElementById('jumlah').addEventListener('input', function() {
    let max = parseInt(this.max);
    if (max && parseInt(this.value) > max) {
        this.value = max;
    }
});

// Tembak Kode Barang Logic (Mendukung 1111 atau 1111-1)
const quickKodeInput = document.getElementById('quick_kode_input');
const btnTembakKode = document.getElementById('btn_tembak_kode');
const kodeStatusMsg = document.getElementById('kode_status_msg');

function tembakKodeBarang(kode) {
    if (!kode) return;
    const rawKode = kode.trim();
    const parts = rawKode.split('-');
    const cleanBaseKode = parts[0].trim().toUpperCase();
    const batchTarget = parts[1] ? parseInt(parts[1]) : null;
    targetBatchSelected = batchTarget;

    let found = false;
    
    for (let opt of barangSelect.options) {
        const optKode = (opt.getAttribute('data-kode') || '').trim().toUpperCase();
        if (optKode === cleanBaseKode || optKode === rawKode.toUpperCase()) {
            if (opt.disabled) {
                kodeStatusMsg.innerHTML = `<span class="text-red-500 font-semibold flex items-center gap-1"><span class="material-symbols-rounded text-base">warning</span> Barang [${cleanBaseKode}] stoknya saat ini sedang habis!</span>`;
            } else {
                barangSelect.value = opt.value;
                barangSelect.dispatchEvent(new Event('change'));
                
                // Terjemahkan makna 4 digit kode: [Kategori][Blok][Rak][Urutan]
                let kategoriName = opt.getAttribute('data-kategori') || 'Kategori';
                let rawText = opt.textContent.replace(/\[.*?\]/, '').split('—')[0].trim();
                let breakdown = '';
                if (cleanBaseKode.length >= 4 && /^\d+$/.test(cleanBaseKode)) {
                    let dKat  = cleanBaseKode.charAt(0);
                    let dBlok = cleanBaseKode.charAt(1);
                    let dRak  = cleanBaseKode.charAt(2);
                    let dSeq  = cleanBaseKode.substring(3);
                    breakdown = `<br><span class="text-xs text-emerald-800 font-normal flex items-center gap-1"><span class="material-symbols-rounded text-xs">place</span> Target Terpilih: Kategori ${kategoriName} (Kode: ${dKat}) • Lokasi: Blok ${dBlok}, Rak ${dRak} • Barang: ${rawText} (Item #${dSeq})${batchTarget ? ` • <strong>Batch #${batchTarget}</strong>` : ''}</span>`;
                }

                kodeStatusMsg.innerHTML = `<span class="text-emerald-700 font-bold flex items-center gap-1"><span class="material-symbols-rounded text-base">check_circle</span> Tepat sasaran! Berhasil memilih [${rawKode}].</span>${breakdown}`;
            }
            found = true;
            break;
        }
    }
    
    if (!found) {
        kodeStatusMsg.innerHTML = `<span class="text-rose-500 font-semibold flex items-center gap-1"><span class="material-symbols-rounded text-base">cancel</span> Kode [${rawKode}] tidak ditemukan dalam inventaris.</span>`;
    }
}

if (btnTembakKode && quickKodeInput) {
    btnTembakKode.addEventListener('click', () => tembakKodeBarang(quickKodeInput.value));
    quickKodeInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            tembakKodeBarang(quickKodeInput.value);
        }
    });
}

// Auto-trigger if already selected on load (e.g. via ?kode= or ?barang_id=)
const initialKode = @json($requestedKode ?? '');
if (initialKode) {
    if (quickKodeInput) quickKodeInput.value = initialKode;
    tembakKodeBarang(initialKode);
} else if (barangSelect && barangSelect.value) {
    barangSelect.dispatchEvent(new Event('change'));
}
</script>
@endpush
