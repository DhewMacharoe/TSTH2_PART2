@extends('layouts.dasbor')

@section('title', 'Dashboard Panel - TSTH2')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold mb-2 {{ auth()->user()->isPicLab() ? 'bg-purple-100 text-purple-900 border border-purple-200' : (auth()->user()->isPicGudang() ? 'bg-emerald-100 text-emerald-900 border border-emerald-200' : 'bg-gray-100 text-gray-800') }}">
            <span class="material-symbols-rounded text-sm">{{ auth()->user()->isPicLab() ? 'science' : (auth()->user()->isPicGudang() ? 'warehouse' : 'shield_person') }}</span>
            <span>
                @if(auth()->user()->isPicLab())
                    Warehouse Laboratorium — Riset & Inovasi (RdI)
                @elseif(auth()->user()->isPicGudang())
                    Warehouse Gudang — General Affair (GA)
                @else
                    {{ auth()->user()->role_label }}
                @endif
            </span>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-500 font-medium mt-1">
            @if(auth()->user()->isPicLab())
                Kelola reagen, bahan kimia, alat ukur laboratorium, dan approval permintaan bahan riset.
            @elseif(auth()->user()->isPicGudang())
                Kelola inventaris operasional lapangan, pupuk, bibit tanaman, dan distribusi logistik umum.
            @else
                Ringkasan aktivitas dan sistem informasi manajemen terpadu TSTH2.
            @endif
        </p>
    </div>

    @if(auth()->user()->canManageWarehouse())
    <div class="flex items-center gap-2">
        <a href="{{ route('gudang.create') }}" class="px-4 py-2.5 bg-primary hover:bg-primary-light text-white rounded-xl font-bold text-xs shadow-md shadow-primary/20 transition-all flex items-center gap-2">
            <span class="material-symbols-rounded text-base">add_box</span>
            <span>Tambah Barang Baru</span>
        </a>
        <a href="{{ route('satuan.index') }}" class="px-4 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl font-bold text-xs shadow-sm transition-all flex items-center gap-2">
            <span class="material-symbols-rounded text-base">straighten</span>
            <span>Master Satuan</span>
        </a>
    </div>
    @endif
</div>

{{-- 4 Stat Metric Cards (Warehouse & Inventory) --}}
<div class="mb-3 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <span class="material-symbols-rounded text-primary text-xl">inventory_2</span>
        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Operasional Gudang & Inventaris</h2>
    </div>
    <span class="text-xs text-gray-400">Warehouse Subsystem</span>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Item Terdaftar</span>
            <span class="text-2xl font-extrabold text-gray-900 mt-1 block">{{ number_format($stats['total_barang'] ?? 0) }}</span>
            <span class="text-[11px] text-gray-400 mt-0.5 block">Jenis barang aktif</span>
        </div>
        <div class="w-12 h-12 rounded-xl {{ auth()->user()->isPicLab() ? 'bg-purple-50 text-purple-700' : 'bg-emerald-50 text-emerald-700' }} flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">{{ auth()->user()->isPicLab() ? 'biotech' : 'inventory_2' }}</span>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Total Stok Fisik</span>
            <span class="text-2xl font-extrabold text-gray-900 mt-1 block">{{ number_format($stats['total_stok'] ?? 0) }}</span>
            <span class="text-[11px] text-gray-400 mt-0.5 block">Akumulasi seluruh batch</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">stacks</span>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Antrean Permintaan</span>
            <span class="text-2xl font-extrabold {{ ($stats['pending_request'] ?? 0) > 0 ? 'text-amber-600' : 'text-gray-900' }} mt-1 block">
                {{ number_format($stats['pending_request'] ?? 0) }}
            </span>
            <span class="text-[11px] {{ ($stats['pending_request'] ?? 0) > 0 ? 'text-amber-600 font-semibold' : 'text-gray-400' }} mt-0.5 block">
                {{ ($stats['pending_request'] ?? 0) > 0 ? 'Perlu tindakan / approval' : 'Semua permintaan diproses' }}
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl {{ ($stats['pending_request'] ?? 0) > 0 ? 'bg-amber-50 text-amber-600 ring-2 ring-amber-300 animate-pulse' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">pending_actions</span>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Master Satuan</span>
            <span class="text-2xl font-extrabold text-gray-900 mt-1 block">{{ number_format($stats['total_satuan'] ?? 0) }}</span>
            <span class="text-[11px] text-gray-400 mt-0.5 block">Satuan & rumus konversi</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">straighten</span>
        </div>
    </div>
</div>

{{-- Section Monitoring Kinerja & Logbook Karyawan (Terintegrasi dari Sistem Logbook) --}}
<div class="mb-3 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <span class="material-symbols-rounded text-primary text-xl">assignment</span>
        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Monitoring Kinerja & Logbook Karyawan</h2>
    </div>
    <a href="{{ config('app.url_logbook', env('URL_LOGBOOK', 'http://logbook.tsth2.id')) }}/dashboard" target="_blank" class="text-xs text-primary hover:text-primary-light font-bold flex items-center gap-1 group">
        <span>Buka Portal Logbook</span>
        <span class="material-symbols-rounded text-sm group-hover:translate-x-0.5 transition-transform">open_in_new</span>
    </a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    {{-- Planning --}}
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Target Planning</span>
            <span class="text-2xl font-extrabold text-blue-600 mt-1 block">{{ $logbookStats['avg_planning'] ?? 0 }}%</span>
            <span class="text-[11px] text-gray-400 mt-0.5 block">Rata-rata target rencana</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">trending_up</span>
        </div>
    </div>

    {{-- Aktual --}}
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Pencapaian Aktual</span>
            <span class="text-2xl font-extrabold text-emerald-600 mt-1 block">{{ $logbookStats['avg_aktual'] ?? 0 }}%</span>
            <span class="text-[11px] text-gray-400 mt-0.5 block">Realisasi progress pekerjaan</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">verified</span>
        </div>
    </div>

    {{-- Status Kinerja --}}
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-2">Status Kinerja</span>
        <div class="flex items-center justify-between gap-1">
            <div class="text-center px-2 py-1 bg-emerald-50 rounded-lg flex-1">
                <span class="text-xs font-extrabold text-emerald-700 block">{{ $logbookStats['lebih_cepat'] ?? 0 }}</span>
                <span class="text-[9px] font-bold text-emerald-600 uppercase">Cepat</span>
            </div>
            <div class="text-center px-2 py-1 bg-blue-50 rounded-lg flex-1">
                <span class="text-xs font-extrabold text-blue-700 block">{{ $logbookStats['sesuai_rencana'] ?? 0 }}</span>
                <span class="text-[9px] font-bold text-blue-600 uppercase">Sesuai</span>
            </div>
            <div class="text-center px-2 py-1 bg-red-50 rounded-lg flex-1">
                <span class="text-xs font-extrabold text-red-700 block">{{ $logbookStats['tertinggal'] ?? 0 }}</span>
                <span class="text-[9px] font-bold text-red-600 uppercase">Tertinggal</span>
            </div>
        </div>
    </div>

    {{-- Logbook & Review --}}
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Logbook Bulan Ini</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-extrabold text-gray-900">{{ number_format($logbookStats['logbook_bulan_ini'] ?? 0) }}</span>
                @if(($logbookStats['pending_review'] ?? 0) > 0)
                    <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded-full">
                        {{ $logbookStats['pending_review'] }} pending
                    </span>
                @endif
            </div>
            <span class="text-[11px] text-gray-400 mt-0.5 block">Total tercatat: {{ number_format($logbookStats['total_logbook'] ?? 0) }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">edit_note</span>
        </div>
    </div>
</div>

<!-- Shortcuts Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('gudang') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/40 transition-all group">
        <div class="w-12 h-12 {{ auth()->user()->isPicLab() ? 'bg-purple-50 text-purple-700' : 'bg-emerald-50 text-emerald-700' }} rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <span class="material-symbols-rounded text-2xl">{{ auth()->user()->isPicLab() ? 'science' : 'inventory_2' }}</span>
        </div>
        <span class="font-bold text-gray-800 text-sm">Data Barang</span>
        <span class="text-[11px] text-gray-400 mt-0.5">{{ auth()->user()->isPicLab() ? 'Lab Riset (RdI)' : (auth()->user()->isPicGudang() ? 'Gudang Lapangan (GA)' : 'Semua Gudang') }}</span>
    </a>
    
    <a href="{{ route('permintaan.index') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/40 transition-all group relative">
        <div class="w-12 h-12 bg-blue-50 text-blue-700 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <span class="material-symbols-rounded text-2xl">shopping_cart</span>
        </div>
        @if(($stats['pending_request'] ?? 0) > 0)
            <span class="absolute top-4 right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $stats['pending_request'] }}</span>
        @endif
        <span class="font-bold text-gray-800 text-sm">Permintaan</span>
        <span class="text-[11px] text-gray-400 mt-0.5">Kelola & Approval</span>
    </a>

    @if(auth()->user()->canManageWarehouse())
    <a href="{{ route('satuan.index') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/40 transition-all group">
        <div class="w-12 h-12 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <span class="material-symbols-rounded text-2xl">straighten</span>
        </div>
        <span class="font-bold text-gray-800 text-sm">Master Satuan</span>
        <span class="text-[11px] text-gray-400 mt-0.5">Unit & Rumus Konversi</span>
    </a>
    @else
    <a href="{{ route('catatan') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/40 transition-all group">
        <div class="w-12 h-12 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <span class="material-symbols-rounded text-2xl">menu_book</span>
        </div>
        <span class="font-bold text-gray-800 text-sm">Logbook</span>
        <span class="text-[11px] text-gray-400 mt-0.5">Catatan Harian</span>
    </a>
    @endif

    <a href="{{ route('profil.edit') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/40 transition-all group">
        <div class="w-12 h-12 bg-purple-50 text-purple-700 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <span class="material-symbols-rounded text-2xl">person</span>
        </div>
        <span class="font-bold text-gray-800 text-sm">Profil Saya</span>
        <span class="text-[11px] text-gray-400 mt-0.5">Pengaturan Akun</span>
    </a>
</div>

{{-- Bottom Columns: Warehouse & Logbook Terkini --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Left: Permintaan Terkini (Warehouse) --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-primary text-xl">shopping_cart</span>
                <h3 class="font-bold text-gray-900 text-sm">Permintaan Barang Terkini</h3>
            </div>
            <a href="{{ route('permintaan.index') }}" class="text-xs text-primary hover:underline font-bold">Lihat Semua →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentPermintaan as $p)
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                    <div class="min-w-0 pr-3">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-xs text-gray-900 truncate">{{ $p->barang?->nama_barang ?? 'Barang #' . $p->barang_id }}</span>
                            <span class="font-mono text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">{{ $p->jumlah }} {{ $p->satuan_diminta }}</span>
                        </div>
                        <span class="text-[11px] text-gray-400 block mt-0.5">Pemohon: <strong class="text-gray-600">{{ $p->user?->name }}</strong> · {{ $p->created_at?->diffForHumans() }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $p->status === 'Menunggu Approval' ? 'bg-amber-100 text-amber-800' : ($p->status === 'Barang Diambil' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700') }}">
                            {{ $p->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 text-xs">
                    <span class="material-symbols-rounded text-3xl mb-1 block text-gray-300">inbox</span>
                    Belum ada antrean permintaan barang.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Right: Catatan Logbook Karyawan Terkini (Logbook Subsystem) --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-primary text-xl">assignment</span>
                <h3 class="font-bold text-gray-900 text-sm">Catatan Logbook Terkini</h3>
            </div>
            <a href="{{ config('app.url_logbook', env('URL_LOGBOOK', 'http://logbook.tsth2.id')) }}/dashboard" target="_blank" class="text-xs text-primary hover:underline font-bold">Buka Logbook →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentLogbooks as $lb)
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                    <div class="min-w-0 pr-3">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-xs text-gray-900 truncate">{{ Str::limit($lb->activity ?? 'Aktivitas Harian', 45) }}</span>
                            @if(isset($lb->progress))
                                <span class="font-mono text-[10px] text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded">{{ $lb->progress }}%</span>
                            @endif
                        </div>
                        <span class="text-[11px] text-gray-400 block mt-0.5">
                            @if(isset($lb->user_name))
                                Oleh: <strong class="text-gray-600">{{ $lb->user_name }}</strong> ·
                            @endif
                            {{ \Carbon\Carbon::parse($lb->created_at ?? $lb->date ?? now())->diffForHumans() }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ in_array($lb->status ?? '', ['pending', 'submitted']) ? 'bg-amber-100 text-amber-800' : (in_array($lb->status ?? '', ['approved', 'reviewed']) ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($lb->status ?? 'Tercatat') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 text-xs">
                    <span class="material-symbols-rounded text-3xl mb-1 block text-gray-300">edit_calendar</span>
                    Belum ada aktivitas logbook terbaru hari ini.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Riwayat Aktivitas Logistik --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="material-symbols-rounded text-primary text-xl">history</span>
            <h3 class="font-bold text-gray-900 text-sm">Riwayat Aktivitas Logistik</h3>
        </div>
        <a href="{{ route('gudang.aktivitas') }}" class="text-xs text-primary hover:underline font-bold">Lihat Log →</a>
    </div>
    <div class="divide-y divide-gray-100">
        @forelse($recentActivities as $act)
            <div class="px-6 py-3.5 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs text-gray-800 font-medium leading-relaxed">{{ $act->keterangan }}</p>
                        <span class="text-[10px] text-gray-400 mt-1 block">{{ $act->created_at?->diffForHumans() }}</span>
                    </div>
                    <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded {{ $act->tipe_mutasi === 'masuk' ? 'bg-emerald-50 text-emerald-700' : ($act->tipe_mutasi === 'keluar' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ $act->tipe_mutasi ?? 'info' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-400 text-xs">
                <span class="material-symbols-rounded text-3xl mb-1 block text-gray-300">receipt_long</span>
                Belum ada riwayat aktivitas tercatat.
            </div>
        @endforelse
    </div>
</div>
@endsection
