@extends('layouts.dasbor')

@section('title', 'Log Aktivitas Warehouse - TSTH2')

@section('header_title')
    <div class="flex items-center gap-2">
        <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200/50">Audit Trail</span>
        <span class="text-xs text-gray-400">/</span>
        <span class="text-xs font-medium text-gray-500">Riwayat Gudang</span>
    </div>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Riwayat & Log Aktivitas Gudang</h1>
            <p class="text-gray-500 font-medium">Transparansi seluruh riwayat mutasi, approval hirarki, penerimaan, penghapusan, dan pengelolaan inventaris TSTH2</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('gudang') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-all text-sm shadow-sm">
                <span class="material-symbols-rounded" style="font-size:18px;">arrow_back</span>
                <span>Kembali ke Data Barang</span>
            </a>
            <a href="{{ route('permintaan.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-800 text-white font-bold rounded-xl hover:bg-emerald-900 transition-all text-sm shadow-md">
                <span class="material-symbols-rounded" style="font-size:18px;">shopping_cart</span>
                <span>Daftar Permintaan</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center font-bold">
                <span class="material-symbols-rounded">history</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Catatan</p>
                <h3 class="text-2xl font-black text-gray-900">{{ number_format($stats['total'] ?? 0) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                <span class="material-symbols-rounded">inventory_2</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aktivitas Barang</p>
                <h3 class="text-2xl font-black text-gray-900">{{ number_format($stats['barang'] ?? 0) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                <span class="material-symbols-rounded">assignment</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aktivitas Permintaan</p>
                <h3 class="text-2xl font-black text-gray-900">{{ number_format($stats['permintaan'] ?? 0) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                <span class="material-symbols-rounded">alt_route</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Eskalasi Otomatis (3 Hari)</p>
                <h3 class="text-2xl font-black text-amber-700">{{ number_format($stats['eskalasi'] ?? 0) }}</h3>
            </div>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <form method="GET" action="{{ route('gudang.aktivitas') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-gray-600 mb-1">Cari Keterangan / Pelaku / Barang</label>
                <div class="relative">
                    <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size:20px;">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci pencarian..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                </div>
            </div>

            <!-- Modul -->
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Modul</label>
                <select name="modul" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                    <option value="">Semua Modul</option>
                    <option value="barang" {{ request('modul') == 'barang' ? 'selected' : '' }}>Data Barang</option>
                    <option value="batch" {{ request('modul') == 'batch' ? 'selected' : '' }}>Batch &amp; Restock</option>
                    <option value="permintaan" {{ request('modul') == 'permintaan' ? 'selected' : '' }}>Permintaan Barang</option>
                    <option value="satuan" {{ request('modul') == 'satuan' ? 'selected' : '' }}>Master Satuan</option>
                    <option value="konversi" {{ request('modul') == 'konversi' ? 'selected' : '' }}>Konversi Satuan</option>
                </select>
            </div>

            <!-- Aksi -->
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Aksi</label>
                <select name="aksi" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                    <option value="">Semua Aksi</option>
                    <option value="tambah" {{ request('aksi') == 'tambah' ? 'selected' : '' }}>Tambah Data</option>
                    <option value="edit" {{ request('aksi') == 'edit' ? 'selected' : '' }}>Ubah / Edit</option>
                    <option value="hapus" {{ request('aksi') == 'hapus' ? 'selected' : '' }}>Hapus Data</option>
                    <option value="ajukan" {{ request('aksi') == 'ajukan' ? 'selected' : '' }}>Ajukan Request</option>
                    <option value="approve" {{ request('aksi') == 'approve' ? 'selected' : '' }}>Persetujuan (Approve)</option>
                    <option value="tolak" {{ request('aksi') == 'tolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="eskalasi" {{ request('aksi') == 'eskalasi' ? 'selected' : '' }}>Eskalasi 3 Hari</option>
                    <option value="pengambilan" {{ request('aksi') == 'pengambilan' ? 'selected' : '' }}>Serah Terima / Ambil</option>
                    <option value="verifikasi_kembali" {{ request('aksi') == 'verifikasi_kembali' ? 'selected' : '' }}>Verifikasi Pengembalian</option>
                </select>
            </div>

            <!-- Tombol Filter -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold rounded-xl text-sm transition-all shadow-sm flex items-center justify-center gap-1.5">
                    <span class="material-symbols-rounded" style="font-size:18px;">tune</span>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['search', 'modul', 'aksi', 'tipe_mutasi', 'tanggal_dari', 'tanggal_sampai']))
                    <a href="{{ route('gudang.aktivitas') }}" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-semibold transition-all" title="Reset filter">
                        <span class="material-symbols-rounded" style="font-size:18px; vertical-align: middle;">restart_alt</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Activity Log Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <div>
                <h3 class="font-extrabold text-gray-900 text-lg">Catatan Audit Trail</h3>
                <p class="text-xs text-gray-500 mt-0.5">Menampilkan seluruh interaksi pengguna terhadap gudang secara kronologis</p>
            </div>
            <span class="text-xs font-bold px-3 py-1 bg-gray-100 text-gray-700 rounded-full">
                {{ $aktivitas->total() }} Riwayat Tercatat
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-black uppercase text-gray-400 tracking-wider">
                        <th class="py-3.5 px-6">Waktu &amp; Tanggal</th>
                        <th class="py-3.5 px-6">Pelaku (PIC / User)</th>
                        <th class="py-3.5 px-6">Modul &amp; Aksi</th>
                        <th class="py-3.5 px-6">Mutasi</th>
                        <th class="py-3.5 px-6">Keterangan Lengkap</th>
                        <th class="py-3.5 px-6 text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($aktivitas as $log)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <!-- Waktu -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="font-bold text-gray-900">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400 font-mono flex items-center gap-1 mt-0.5">
                                    <span class="material-symbols-rounded" style="font-size:13px;">schedule</span>
                                    <span>{{ $log->created_at->format('H:i:s') }} ({{ $log->created_at->diffForHumans() }})</span>
                                </div>
                            </td>

                            <!-- Pelaku -->
                            <td class="py-4 px-6">
                                <div class="font-extrabold text-gray-900 flex items-center gap-2">
                                    <span>{{ $log->user_name }}</span>
                                </div>
                                <div class="mt-1">
                                    @php
                                        $role = $log->user_role;
                                        $roleBadge = match($role) {
                                            'kepala_divisi'     => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'kepala_departemen' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'pic_gudang'        => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'web_admin'         => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'pimpinan'          => 'bg-rose-100 text-rose-800 border-rose-200',
                                            'employee', 'karyawan' => 'bg-gray-100 text-gray-700 border-gray-200',
                                            default             => 'bg-gray-50 text-gray-600 border-gray-200'
                                        };
                                        $roleName = match($role) {
                                            'kepala_divisi'     => 'Kepala Divisi Operasional',
                                            'kepala_departemen' => 'Kepala Departemen General Affair',
                                            'pic_gudang'        => 'PIC Gudang',
                                            'web_admin'         => 'Web Admin',
                                            'pimpinan'          => 'Pimpinan / Direktur',
                                            'agronom'           => 'Agronom & Peneliti',
                                            'employee', 'karyawan' => 'Karyawan',
                                            default             => ucfirst(str_replace('_', ' ', $role ?? 'Sistem'))
                                        };
                                    @endphp
                                    <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-md border {{ $roleBadge }}">
                                        {{ $roleName }}
                                    </span>
                                </div>
                            </td>

                            <!-- Modul & Aksi -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="font-bold text-gray-700 capitalize text-xs mb-1">
                                    Modul: {{ $log->modul }}
                                </div>
                                @php
                                    $aksiBadge = match($log->aksi) {
                                        'tambah', 'restock'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'edit', 'reschedule'     => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'hapus', 'hapus_batch'   => 'bg-red-50 text-red-700 border-red-200',
                                        'ajukan'                 => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'approve'                => 'bg-teal-50 text-teal-700 border-teal-200 font-extrabold',
                                        'tolak'                  => 'bg-rose-50 text-rose-700 border-rose-200 font-extrabold',
                                        'eskalasi'               => 'bg-amber-100 text-amber-900 border-amber-300 font-black animate-pulse',
                                        'pengambilan'            => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'verifikasi_kembali'     => 'bg-emerald-50 text-emerald-800 border-emerald-300',
                                        default                  => 'bg-gray-50 text-gray-700 border-gray-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-0.5 rounded-full border {{ $aksiBadge }}">
                                    {{ $log->aksi_label }}
                                </span>
                            </td>

                            <!-- Mutasi -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($log->tipe_mutasi === 'masuk')
                                    <span class="inline-flex items-center gap-1 text-xs font-extrabold px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-md">
                                        <span class="material-symbols-rounded" style="font-size:14px;">arrow_downward</span> Masuk
                                    </span>
                                @elseif($log->tipe_mutasi === 'keluar')
                                    <span class="inline-flex items-center gap-1 text-xs font-extrabold px-2 py-0.5 bg-rose-50 text-rose-700 rounded-md">
                                        <span class="material-symbols-rounded" style="font-size:14px;">arrow_upward</span> Keluar
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-xs font-medium px-2 py-0.5 bg-gray-100 text-gray-500 rounded-md">
                                        Netral
                                    </span>
                                @endif

                                @if($log->jumlah)
                                    <div class="text-xs font-bold text-gray-800 mt-1">
                                        {{ number_format($log->jumlah) }} {{ $log->satuan ?? 'unit' }}
                                    </div>
                                @endif
                            </td>

                            <!-- Keterangan Lengkap -->
                            <td class="py-4 px-6">
                                <p class="text-gray-800 text-xs font-medium leading-relaxed">
                                    {{ $log->keterangan }}
                                </p>
                                @if($log->nama_barang)
                                    <div class="text-[11px] text-gray-400 mt-1 flex items-center gap-1">
                                        <span class="material-symbols-rounded" style="font-size:13px;">label</span>
                                        <span>Barang: <strong class="text-gray-600">{{ $log->nama_barang }}</strong></span>
                                    </div>
                                @endif
                            </td>

                            <!-- IP Address -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <span class="text-[11px] font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded border border-gray-100">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <span class="material-symbols-rounded block mb-2" style="font-size:36px; color:#cbd5e1;">manage_search</span>
                                <p class="font-bold text-gray-600">Belum ada riwayat aktivitas yang cocok dengan filter.</p>
                                <p class="text-xs text-gray-400 mt-1">Semua aktivitas pembuatan, perubahan, approval, serah terima, dan penghapusan akan tercatat otomatis di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($aktivitas->hasPages())
            <div class="p-6 border-t border-gray-100 bg-gray-50/50">
                {{ $aktivitas->links() }}
            </div>
        @endif
    </div>
@endsection
