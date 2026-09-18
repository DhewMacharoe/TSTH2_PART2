@extends('layouts.dashboard')

@section('title', 'Detail Barang - TSTH2')

@section('content')
    <div class="mb-6">
        <a href="{{ route('warehouse') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary transition-colors font-medium text-sm mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Inventory
        </a>
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Detail Barang</h1>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 font-mono text-sm rounded-lg font-bold">#INV{{ str_pad($barang->id, 3, '0', STR_PAD_LEFT) }}</span>
        </div>
        <p class="text-gray-500 font-medium mt-1">Lihat detail barang dan riwayat batch kedatangan.</p>
    </div>

    <!-- Informasi Barang -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8">
            <div class="md:w-1/3">
                @if($barang->gambar)
                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="w-full h-64 object-cover rounded-xl shadow-sm border border-gray-100">
                @else
                    <div class="w-full h-64 bg-gray-50 rounded-xl flex items-center justify-center border-2 border-dashed border-gray-200">
                        <span class="text-gray-400 text-6xl">📦</span>
                    </div>
                @endif
            </div>
            <div class="md:w-2/3 space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $barang->nama_barang }}</h2>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium border border-gray-200">{{ $barang->kategori }}</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium border border-gray-200">Blok: {{ $barang->lokasi_blok }}</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium border border-gray-200">Merek: {{ $barang->merk ?? '' }}</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-6 bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <div>
                        <p class="text-sm text-gray-500 font-bold mb-1">Total Stok Saat Ini</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $barang->stok }} <span class="text-lg text-gray-500 font-medium">Unit</span></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold mb-1">Status Ketersediaan</p>
                        <div>
                            @if($barang->status == 'Tersedia')
                                <span class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-lg font-bold border border-emerald-200 flex items-center w-max gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> {{ $barang->status }}</span>
                            @elseif($barang->status == 'Stok Kritis')
                                <span class="bg-amber-100 text-amber-700 px-4 py-2 rounded-lg font-bold border border-amber-200 flex items-center w-max gap-2"><span class="w-2 h-2 rounded-full bg-amber-500"></span> {{ $barang->status }}</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-4 py-2 rounded-lg font-bold border border-red-200 flex items-center w-max gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span> {{ $barang->status }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Batch -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Riwayat Batch Kedatangan</h3>
                <p class="text-gray-500 text-sm mt-1">Daftar batch yang memiliki stok untuk prinsip FIFO.</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/50 text-gray-700 font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">No.</th>
                        <th class="px-6 py-4">Tanggal Kedatangan</th>
                        <th class="px-6 py-4">Tanggal Kadaluarsa</th>
                        <th class="px-6 py-4">Sisa Stok (Unit)</th>
                        <th class="px-6 py-4">Status Batch</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($barang->batches as $index => $batch)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium">{{ \Carbon\Carbon::parse($batch->tanggal_kedatangan)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $batch->tanggal_kadaluarsa ? \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $batch->stok }}</td>
                        <td class="px-6 py-4">
                            @if($batch->stok == 0)
                                <span class="text-xs font-bold px-2 py-1 bg-gray-100 text-gray-600 rounded-lg">Habis</span>
                            @else
                                <span class="text-xs font-bold px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg">Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(in_array(auth()->user()->role, ['pic_warehouse', 'web_admin']))
                            <form action="{{ route('warehouse.batch.destroy', $batch->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus batch ini? Stok total akan ikut berkurang.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="Hapus Batch">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">Belum ada riwayat batch untuk barang ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
