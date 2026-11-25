@extends('layouts.dasbor')

@section('title', 'Detail Barang - TSTH2')

@section('content')
    <div class="mb-6">
        <a href="{{ route('gudang') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary transition-colors font-medium text-sm mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Inventory
        </a>
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Detail Barang</h1>
            <span class="px-3 py-1 bg-gray-900 text-white font-mono text-sm rounded-lg font-bold shadow-sm">#{{ $barang->kode_barang }}</span>
        </div>
        <p class="text-gray-500 font-medium mt-1">Lihat detail barang, kode unik inventaris, dan riwayat batch kedatangan.</p>
    </div>

    <!-- Informasi Barang -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8">
            <div class="md:w-1/3">
                @if($barang->gambar)
                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="w-full h-64 object-cover rounded-xl shadow-sm border border-gray-100">
                @else
                    <div class="w-full h-64 bg-gray-50 rounded-xl flex items-center justify-center border-2 border-dashed border-gray-200">
                        <span class="material-symbols-rounded" style="font-size:4rem;color:#cbd5e1;">inventory_2</span>
                    </div>
                @endif
            </div>
            <div class="md:w-2/3 space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $barang->nama_barang }}</h2>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-800 rounded-lg text-sm font-mono font-bold border border-emerald-200">
                            Kode: {{ $barang->kode_barang }}
                        </span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium border border-gray-200">{{ $barang->kategori }}</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium border border-gray-200">Blok: {{ $barang->lokasi_blok }}</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium border border-gray-200">Merek: {{ $barang->merk ?? '-' }}</span>
                        @if($barang->jenis_barang === 'lab')
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium border border-gray-200">Laboratorium</span>
                        @else
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-800 rounded-lg text-sm font-medium border border-emerald-200">Lapangan</span>
                        @endif
                    </div>
                    {{-- Audit Trail: Dibuat & Diperbarui Oleh --}}
                    <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-sm text-emerald-500">add_circle</span>
                            <span>Ditambahkan oleh: <strong class="text-gray-700">{{ $barang->creator->name ?? 'Sistem' }}</strong></span>
                            @if($barang->created_at)
                                <span class="text-gray-400">— {{ $barang->created_at->format('d M Y, H:i') }}</span>
                            @endif
                        </div>
                        @if($barang->updater && $barang->updater->id !== $barang->creator?->id)
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-sm text-blue-500">edit</span>
                            <span>Terakhir diubah oleh: <strong class="text-gray-700">{{ $barang->updater->name }}</strong></span>
                            @if($barang->updated_at && $barang->updated_at != $barang->created_at)
                                <span class="text-gray-400">— {{ $barang->updated_at->format('d M Y, H:i') }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500 font-bold mb-1">Total Stok Saat Ini</p>
                        <div class="flex items-center gap-2" title="{{ $barang->stok_breakdown }}">
                            <span class="text-3xl font-bold text-gray-900">{{ $barang->formatted_stok_main }}</span>
                            @if($barang->has_multiple_satuan)
                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-base font-extrabold text-emerald-700 bg-emerald-100 border border-emerald-200 rounded-lg cursor-help" title="Rincian: {{ $barang->stok_breakdown }}">+</span>
                            @endif
                        </div>
                        @if($barang->has_multiple_satuan)
                            <p class="text-xs text-gray-500 mt-1 font-medium">Rincian stok: <span class="font-bold text-gray-700">{{ $barang->stok_breakdown }}</span></p>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('permintaan.create', ['kode' => $barang->kode_barang]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-light text-white rounded-xl font-bold text-sm shadow-md shadow-primary/20 transition-all">
                            <span class="material-symbols-rounded text-base">near_me</span>
                            <span>Ajukan Permintaan via Kode Ini</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $canManageThisItem = auth()->user()->isWebAdmin()
            || (auth()->user()->isPicGudang() && $barang->jenis_barang === 'lapangan')
            || (auth()->user()->isPicLab() && $barang->jenis_barang === 'lab');
    @endphp

    <!-- Daftar Batch -->
    <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h3 class="text-xl font-bold text-gray-900">Manajemen Batch Kedatangan</h3>
            <p class="text-gray-500 text-sm mt-1">Satu batch bisa berisi beberapa kondisi dan satuan berbeda.</p>
        </div>
        @if($canManageThisItem)
            <div class="flex items-center gap-2">
                <a href="{{ route('gudang.edit', $barang->id) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg font-bold text-sm shadow-sm transition-colors flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    <span>Edit Barang</span>
                </a>
                <button onclick="document.getElementById('modalNewBatch').classList.remove('hidden')" class="px-4 py-2 bg-primary text-white rounded-lg font-bold text-sm shadow-md hover:bg-primary-light transition-colors">
                    + Tambah Batch Baru
                </button>
            </div>
        @endif
    </div>

    @forelse($batchGroups as $batchNo => $batches)
        @php
            $firstBatch = $batches->first();
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="text-lg font-bold text-gray-900">Batch {{ (int)ltrim($batchNo, 'B') }}</h4>
                        @php $fullBatchCode = ($barang->kode_barang ?? '0000') . '-' . max(1, (int)ltrim($batchNo, 'B')); @endphp
                        <a href="{{ route('permintaan.create', ['kode' => $fullBatchCode]) }}" class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-gray-900 hover:bg-primary text-white font-mono text-xs font-bold rounded-md shadow-sm transition-colors" title="Klik untuk ajukan permintaan batch ini">
                            <span>#{{ $fullBatchCode }}</span>
                        </a>
                        @if($firstBatch)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-md border border-gray-200" title="{{ $firstBatch->stok_breakdown }}">
                                <span>{{ $firstBatch->stock_info['formatted_main'] }}</span>
                                @if($firstBatch->has_multiple_satuan)
                                    <span class="text-emerald-600 font-black">+</span>
                                @endif
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Datang: {{ \Carbon\Carbon::parse($firstBatch->tanggal_kedatangan)->format('d M Y') }}</p>
                </div>
                @if($canManageThisItem)
                    <button onclick="openModalItem('{{ $batchNo }}', '{{ \Carbon\Carbon::parse($firstBatch->tanggal_kedatangan)->format('Y-m-d') }}')" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg text-xs font-bold transition-colors">
                        + Tambah Item ke Batch Ini
                    </button>
                @endif
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-white text-gray-700 font-bold border-b border-gray-100 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Prioritas</th>
                            <th class="px-6 py-3">Sisa Stok</th>
                            <th class="px-6 py-3">Satuan</th>
                            <th class="px-6 py-3">Kondisi</th>
                            <th class="px-6 py-3">Tanggal Expired</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($batches as $batchRowIndex => $batch)
                            @php
                                $details = is_array($batch->stok_detail) ? $batch->stok_detail : json_decode($batch->stok_detail ?? '[]', true);
                            @endphp
                            
                            @if(empty($details))
                                <!-- Fallback untuk batch lama -->
                                @php
                                    $priority = \App\Models\SatuanBarang::kondisiPriority($batch->kondisi ?? '');
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors {{ $priority == 1 && $batch->stok > 0 ? 'bg-blue-50/30' : '' }}">
                                    <td class="px-6 py-4">
                                        @if($batch->stok > 0)
                                            @if($priority == 1)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-full uppercase tracking-wider">Tinggi</span>
                                            @elseif($priority == 2)
                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full uppercase tracking-wider">Sedang</span>
                                            @elseif($priority == 99)
                                                <span class="px-2 py-1 bg-red-100 text-red-600 text-[10px] font-bold rounded-full uppercase tracking-wider">Jgn Diambil</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-[10px] font-bold rounded-full uppercase tracking-wider">Rendah</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400 text-xs italic">Habis</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold {{ $batch->stok > 0 ? 'text-gray-900' : 'text-gray-400' }}">{{ $batch->stok }}</td>
                                    <td class="px-6 py-4 uppercase text-xs font-bold">{{ $batch->satuan ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $isRusak = in_array($batch->kondisi, ['Rusak', 'Rusak/Menggumpal']);
                                        @endphp
                                        <span class="text-xs font-bold px-2 py-1 border rounded-lg {{ $batch->stok == 0 ? 'bg-gray-50 text-gray-400 border-gray-200' : ($isRusak ? 'bg-red-50 text-red-600 border-red-200' : 'bg-gray-50 text-gray-700 border-gray-200') }}">
                                            @if($isRusak && $batch->stok > 0)! @endif{{ $batch->kondisi ?? '-' }}
                                        </span>
                                    </td>
                                    @php
                                        $isExpired = $batch->tanggal_kadaluarsa && \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->endOfDay()->isPast();
                                    @endphp
                                    <td class="px-6 py-4 text-xs font-medium">
                                        @if($isExpired)
                                            <span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded-md border border-red-100 flex items-center w-max gap-1">! {{ \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->format('d M Y') }} (Expired)</span>
                                        @else
                                            <span class="text-gray-500">{{ $batch->tanggal_kadaluarsa ? \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->format('d M Y') : '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($canManageThisItem)
                                            <form action="{{ route('gudang.batch.item.destroy', $batch->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus sub-batch ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus Item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @else
                                <!-- JSON stok_detail implementation -->
                                @foreach($details as $detailIndex => $item)
                                    @php
                                        $priority = \App\Models\SatuanBarang::kondisiPriority($item['kondisi'] ?? '');
                                        $subStok = $item['stok'] ?? 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors {{ $priority == 1 && $subStok > 0 ? 'bg-blue-50/30' : '' }}">
                                        <td class="px-6 py-4">
                                            @if($subStok > 0)
                                                @if($priority == 1)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-full uppercase tracking-wider">Tinggi</span>
                                                @elseif($priority == 2)
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full uppercase tracking-wider">Sedang</span>
                                                @elseif($priority == 99)
                                                    <span class="px-2 py-1 bg-red-100 text-red-600 text-[10px] font-bold rounded-full uppercase tracking-wider">Jgn Diambil</span>
                                                @else
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-400 text-[10px] font-bold rounded-full uppercase tracking-wider">Rendah</span>
                                                @endif
                                            @else
                                                <span class="text-gray-400 text-xs italic">Habis</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-bold {{ $subStok > 0 ? 'text-gray-900' : 'text-gray-400' }}">{{ $subStok }}</td>
                                        <td class="px-6 py-4 uppercase text-xs font-bold text-gray-800">{{ $item['satuan'] ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $isRusakItem = in_array($item['kondisi'] ?? '', ['Rusak', 'Rusak/Menggumpal']);
                                            @endphp
                                            <span class="text-xs font-bold px-2 py-1 border rounded-lg {{ $subStok == 0 ? 'bg-gray-50 text-gray-400 border-gray-200' : ($isRusakItem ? 'bg-red-50 text-red-600 border-red-200' : 'bg-gray-50 text-gray-700 border-gray-200') }}">
                                                @if($isRusakItem && $subStok > 0)! @endif{{ $item['kondisi'] ?? '-' }}
                                            </span>
                                        </td>
                                        @php
                                            $isExpired = $batch->tanggal_kadaluarsa && \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->endOfDay()->isPast();
                                        @endphp
                                        <td class="px-6 py-4 text-xs font-medium">
                                            @if($isExpired)
                                                <span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded-md border border-red-100 flex items-center w-max gap-1">
                                                    <span class="material-symbols-rounded text-sm">warning</span>
                                                    <span>{{ \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->format('d M Y') }} (Expired)</span>
                                                </span>
                                            @else
                                                <span class="text-gray-500">{{ $batch->tanggal_kadaluarsa ? \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->format('d M Y') : '-' }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($detailIndex == 0 && $canManageThisItem)
                                                <!-- Hanya tampilkan tombol hapus batch induk di baris pertama detailnya -->
                                                <form action="{{ route('gudang.batch.item.destroy', $batch->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus seluruh rincian stok dalam sub-batch ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus Sub-Batch">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <span class="material-symbols-rounded text-4xl mb-3 block text-gray-400">inbox</span>
            <p class="text-gray-500 font-medium">Belum ada riwayat batch untuk barang ini.</p>
        </div>
    @endforelse

    {{-- ═══ RIWAYAT AKTIVITAS ═══ --}}
    @if($riwayatAktivitas->isNotEmpty())
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-900 mb-3">Riwayat Aktivitas Barang</h3>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="divide-y divide-gray-50">
                @foreach($riwayatAktivitas as $log)
                <div class="px-6 py-4 flex items-start gap-4">
                    <div class="mt-0.5 w-8 h-8 rounded-full flex items-center justify-center shrink-0
                        @if(in_array($log->aksi, ['tambah','restock'])) bg-emerald-50 text-emerald-600
                        @elseif(in_array($log->aksi, ['hapus'])) bg-red-50 text-red-600
                        @elseif(in_array($log->aksi, ['approve','verify_return'])) bg-blue-50 text-blue-600
                        @elseif($log->aksi === 'eskalasi') bg-orange-50 text-orange-600
                        @else bg-gray-100 text-gray-500 @endif">
                        <span class="material-symbols-rounded text-sm">
                            @if(in_array($log->aksi, ['tambah','restock'])) add_circle
                            @elseif($log->aksi === 'hapus') delete
                            @elseif($log->aksi === 'edit') edit
                            @elseif($log->aksi === 'approve') check_circle
                            @elseif($log->aksi === 'eskalasi') swap_horiz
                            @else history @endif
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700">{{ $log->keterangan }}</p>
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
                            <span class="font-semibold text-gray-600">{{ $log->user_name ?? 'Sistem' }}</span>
                            @if($log->user_role)
                                <span class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-500 font-medium">{{ ucfirst(str_replace('_',' ',$log->user_role)) }}</span>
                            @endif
                            <span>{{ $log->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    @if($log->stok_berubah && $log->stok_berubah != 0)
                    <div class="shrink-0 text-sm font-bold {{ $log->stok_berubah > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $log->stok_berubah > 0 ? '+' : '' }}{{ $log->stok_berubah }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Modals -->
    @if($canManageThisItem)
    
    <!-- Modal Add Item to Existing Batch -->
    <div id="modalAddItem" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-900">Tambah Item ke <span id="modalBatchNoLabel"></span></h3>
                <button type="button" onclick="document.getElementById('modalAddItem').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="formAddItem" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Stok Tambahan</label>
                    <input type="number" name="stok" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary" required min="1">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Satuan</label>
                    <select name="satuan" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary" required>
                        <option value="">-- Pilih Satuan --</option>
                        @foreach($satuans as $sat)
                            <option value="{{ $sat->nama_satuan }}">{{ strtoupper($sat->nama_satuan) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Kondisi</label>
                    <select name="kondisi" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary" required>
                        <option value="">-- Pilih Kondisi --</option>
                        @foreach($kondisiList as $kon)
                            <option value="{{ $kon['value'] }}">{{ $kon['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                @if(!in_array($barang->kategori, ['Alat Budidaya']))
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Expired (Opsional)</label>
                    <input type="date" id="add_item_tanggal_kadaluarsa" name="tanggal_kadaluarsa" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary">
                    <input type="hidden" id="add_item_tanggal_kedatangan" name="tanggal_kedatangan" value="{{ date('Y-m-d') }}">
                </div>
                @else
                <input type="hidden" name="tanggal_kedatangan" value="{{ date('Y-m-d') }}">
                @endif
                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modalAddItem').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white font-bold text-sm rounded-lg hover:bg-primary-light">Simpan Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal New Batch -->
    <div id="modalNewBatch" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm transition-opacity overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden my-8">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="font-bold text-gray-900">Tambah Batch Baru ({{ $nextBatchNo }})</h3>
                    <p class="text-xs text-gray-500 mt-1">Anda bisa menambahkan beberapa item sekaligus.</p>
                </div>
                <button type="button" onclick="document.getElementById('modalNewBatch').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('gudang.batch.store', $barang->id) }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-100">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Kedatangan <span class="text-red-500">*</span></label>
                        <input type="date" id="new_batch_tanggal_kedatangan" name="tanggal_kedatangan" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary" required value="{{ date('Y-m-d') }}">
                    </div>
                    @if(!in_array($barang->kategori, ['Alat Budidaya']))
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Kadaluarsa Umum (Opsional)</label>
                        <input type="date" id="new_batch_tanggal_kadaluarsa" name="tanggal_kadaluarsa" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary">
                    </div>
                    @endif
                </div>

                <div id="newBatchItems">
                    <!-- Item 0 -->
                    <div class="flex gap-3 mb-3 items-start batch-item-row">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Stok</label>
                            <input type="number" name="stok_jumlah[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary" required min="1" placeholder="Jumlah">
                        </div>
                        <div class="flex-[1.5]">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Satuan</label>
                            <select name="stok_satuan[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuans as $sat)
                                    <option value="{{ $sat->nama_satuan }}">{{ strtoupper($sat->nama_satuan) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-[1.5]">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kondisi</label>
                            <select name="stok_kondisi[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary" required>
                                <option value="">-- Pilih Kondisi --</option>
                                @foreach($kondisiList as $kon)
                                    <option value="{{ $kon['value'] }}">{{ $kon['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pt-6">
                            <button type="button" class="p-2.5 text-gray-400 hover:text-red-500 opacity-50 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-4 mb-8">
                    <button type="button" onclick="addBatchItemRow()" class="text-sm font-bold text-primary hover:text-primary-light flex items-center gap-1">
                        <span>+ Tambah baris item</span>
                    </button>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modalNewBatch').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-200 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold text-sm rounded-lg shadow-md hover:bg-primary-light transition-colors">Simpan Batch</button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>
    let itemIndex = 0;

    function openModalItem(batchNo, kedatangan) {
        document.getElementById('modalBatchNoLabel').textContent = batchNo;
        document.getElementById('formAddItem').action = `/gudang/batch/${batchNo}/item`;
        
        const kedatanganInput = document.getElementById('add_item_tanggal_kedatangan');
        const kadaluarsaInput = document.getElementById('add_item_tanggal_kadaluarsa');
        
        if (kedatanganInput) kedatanganInput.value = kedatangan;
        if (kadaluarsaInput) {
            kadaluarsaInput.min = kedatangan;
            if (kadaluarsaInput.value && kadaluarsaInput.value < kedatangan) {
                kadaluarsaInput.value = kedatangan;
            }
        }

        document.getElementById('modalAddItem').classList.remove('hidden');
    }

    function addBatchItemRow() {
        itemIndex++;
        const container = document.getElementById('newBatchItems');
        const row = document.createElement('div');
        row.className = 'flex gap-3 mb-3 items-start batch-item-row';
        row.innerHTML = `
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-700 mb-1">Stok</label>
                <input type="number" name="stok_jumlah[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary" required min="1" placeholder="Jumlah">
            </div>
            <div class="flex-[1.5]">
                <label class="block text-xs font-bold text-gray-700 mb-1">Satuan</label>
                <select name="stok_satuan[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary" required>
                    <option value="">-- Pilih Satuan --</option>
                    @foreach($satuans as $sat)
                        <option value="{{ $sat->nama_satuan }}">{{ strtoupper($sat->nama_satuan) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-[1.5]">
                <label class="block text-xs font-bold text-gray-700 mb-1">Kondisi</label>
                <select name="stok_kondisi[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm font-medium focus:ring-primary focus:border-primary" required>
                    <option value="">-- Pilih Kondisi --</option>
                    @foreach($kondisiList as $kon)
                        <option value="{{ $kon['value'] }}">{{ $kon['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pt-6">
                <button type="button" onclick="this.closest('.batch-item-row').remove()" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus Baris">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        `;
        container.appendChild(row);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const kedatanganInput = document.getElementById('new_batch_tanggal_kedatangan');
        const kadaluarsaInput = document.getElementById('new_batch_tanggal_kadaluarsa');
        if (kedatanganInput && kadaluarsaInput) {
            kedatanganInput.addEventListener('change', function() {
                kadaluarsaInput.min = this.value;
                if (kadaluarsaInput.value && kadaluarsaInput.value < this.value) {
                    kadaluarsaInput.value = this.value;
                }
            });
            if (kedatanganInput.value) kadaluarsaInput.min = kedatanganInput.value;
            
            // Re-apply constraints when form opens or initially
            kadaluarsaInput.min = kedatanganInput.value;
        }
        
        // Also attach constraint to item modal if user manually changes kadaluarsa
        const addItemKadaluarsa = document.getElementById('add_item_tanggal_kadaluarsa');
        const addItemKedatangan = document.getElementById('add_item_tanggal_kedatangan');
        if (addItemKadaluarsa && addItemKedatangan) {
            addItemKadaluarsa.addEventListener('change', function() {
                if (this.value && this.value < addItemKedatangan.value) {
                    this.value = addItemKedatangan.value;
                }
            });
        }
    });
</script>
@endpush

