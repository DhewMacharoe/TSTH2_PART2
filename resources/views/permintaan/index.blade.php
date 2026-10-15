@extends('layouts.dasbor')

@section('title', 'Permintaan Barang - TSTH2')

@section('content')

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl font-semibold shadow-sm"
            id="flash-success">
            <span class="text-xl">✅</span> {{ session('success') }}
            <button class="ml-auto text-emerald-400 hover:text-emerald-600"
                onclick="this.closest('#flash-success').remove()">✕</button>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 px-5 py-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl font-semibold shadow-sm"
            id="flash-error">
            <span class="text-xl">❌</span> {{ session('error') }}
            <button class="ml-auto text-red-400 hover:text-red-600" onclick="this.closest('#flash-error').remove()">✕</button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-1">Permintaan Barang</h1>
            <p class="text-gray-500 font-medium">Kelola permintaan dan pengembalian barang gudang</p>
        </div>
        @if (!auth()->user()->isWebAdmin() && !auth()->user()->isPicGudang())
            <a href="{{ route('permintaan.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-light text-white rounded-xl font-bold transition-colors shadow-md shadow-primary/20 text-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Permintaan
            </a>
        @endif
    </div>

    {{-- Status Stats (PIC only) --}}
    @if (auth()->user()->isPicGudang())
        @php
            $statCounts = [
                'Menunggu Approval' => $permintaans->where('status', 'Menunggu Approval')->count(),
                'Menunggu Pengambilan' => $permintaans->where('status', 'Menunggu Pengambilan')->count(),
                'Barang Diambil' => $permintaans->where('status', 'Barang Diambil')->count(),
                'Selesai' => $permintaans->whereIn('status', ['Selesai', 'Barang Dikembalikan'])->count(),
            ];
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-amber-100 p-4 shadow-sm">
                <div class="text-2xl font-extrabold text-amber-600">{{ $statCounts['Menunggu Approval'] }}</div>
                <div class="text-xs font-bold text-gray-500 mt-0.5">Menunggu Approval</div>
            </div>
            <div class="bg-white rounded-2xl border border-blue-100 p-4 shadow-sm">
                <div class="text-2xl font-extrabold text-blue-600">{{ $statCounts['Menunggu Pengambilan'] }}</div>
                <div class="text-xs font-bold text-gray-500 mt-0.5">Menunggu Pengambilan</div>
            </div>
            <div class="bg-white rounded-2xl border border-emerald-100 p-4 shadow-sm">
                <div class="text-2xl font-extrabold text-emerald-600">{{ $statCounts['Barang Diambil'] }}</div>
                <div class="text-xs font-bold text-gray-500 mt-0.5">Barang Diambil</div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                <div class="text-2xl font-extrabold text-gray-600">{{ $statCounts['Selesai'] }}</div>
                <div class="text-xs font-bold text-gray-500 mt-0.5">Selesai</div>
            </div>
        </div>
    @endif

    {{-- Main Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Filters --}}
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row items-center gap-3 bg-gray-50/50">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                    </svg>
                </span>
                <input type="text" id="searchInput" placeholder="Cari pemohon atau barang..."
                    class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm shadow-sm font-medium">
            </div>
            <select id="filterStatus"
                class="w-full sm:w-auto bg-white border-gray-200 text-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary px-4 py-2.5 shadow-sm font-medium">
                <option value="">Semua Status</option>
                <option value="Menunggu Approval">Menunggu Approval</option>
                <option value="Menunggu Pengambilan">Menunggu Pengambilan</option>
                <option value="Barang Diambil">Barang Diambil</option>
                <option value="Menunggu Verifikasi Pengembalian">Menunggu Verifikasi Pengembalian</option>
                <option value="Barang Dikembalikan">Barang Dikembalikan</option>
                <option value="Pending (Jadwal Ulang)">⏳ Pending (Jadwal Ulang)</option>
                <option value="Selesai">Selesai</option>
                <option value="Ditolak">Ditolak</option>
                <option value="Dibatalkan">Dibatalkan</option>
            </select>
            <span class="hidden sm:block ml-auto text-sm text-gray-400 font-medium whitespace-nowrap">
                Total: <strong class="text-gray-700">{{ count($permintaans) }}</strong> permintaan
            </span>
        </div>

        {{-- Table --}}
        <div class="w-full bg-gray-50/50 md:bg-white p-4 md:p-0 overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 block md:table">
                <thead class="hidden md:table-header-group bg-gray-50/80 text-gray-900 font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4 text-xs uppercase tracking-wider">Tanggal</th>
                        @if (auth()->user()->isPicGudang())
                            <th class="px-5 py-4 text-xs uppercase tracking-wider">Pemohon</th>
                        @endif
                        <th class="px-5 py-4 text-xs uppercase tracking-wider">Barang</th>
                        <th class="px-5 py-4 text-xs uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-xs uppercase tracking-wider">Info</th>
                        <th class="px-5 py-4 text-xs uppercase tracking-wider text-center">Aksi</th>
                        <th class="px-5 py-4 text-xs uppercase tracking-wider text-center">Forum</th>
                    </tr>
                </thead>
                <tbody id="tableBody"
                    class="block md:table-row-group divide-y-0 md:divide-y divide-gray-100 space-y-4 md:space-y-0">
                    @forelse($permintaans as $req)
                        @php
                            $statusConfig = [
                                'Menunggu Approval' => [
                                    'bg' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'dot' => 'bg-amber-400',
                                ],
                                'Menunggu Pengambilan' => [
                                    'bg' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'dot' => 'bg-blue-400',
                                ],
                                'Barang Diambil' => [
                                    'bg' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'dot' => 'bg-emerald-400',
                                ],
                                'Menunggu Verifikasi Pengembalian' => [
                                    'bg' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                    'dot' => 'bg-indigo-400',
                                ],
                                'Barang Dikembalikan' => [
                                    'bg' => 'bg-teal-100 text-teal-700 border-teal-200',
                                    'dot' => 'bg-teal-400',
                                ],
                                'Pending (Jadwal Ulang)' => [
                                    'bg' => 'bg-orange-100 text-orange-700 border-orange-200',
                                    'dot' => 'bg-orange-400',
                                ],
                                'Selesai' => [
                                    'bg' => 'bg-green-100 text-green-700 border-green-200',
                                    'dot' => 'bg-green-400',
                                ],
                                'Ditolak' => ['bg' => 'bg-red-100 text-red-700 border-red-200', 'dot' => 'bg-red-400'],
                                'Dibatalkan' => [
                                    'bg' => 'bg-gray-100 text-gray-600 border-gray-200',
                                    'dot' => 'bg-gray-400',
                                ],
                            ];
                            $sc = $statusConfig[$req->status] ?? [
                                'bg' => 'bg-gray-100 text-gray-600 border-gray-200',
                                'dot' => 'bg-gray-400',
                            ];
                            $isOverdue = $req->jadwal_pengambilan &&
                                \Carbon\Carbon::parse($req->jadwal_pengambilan)->endOfDay()->isPast() &&
                                $req->status === 'Menunggu Pengambilan';
                            $isOwner = auth()->id() === $req->user_id;
                            $role = auth()->user()->role;
                            $forumActive = $req->isForumActive();
                        @endphp
                        <tr class="block md:table-row bg-white md:bg-transparent rounded-2xl md:rounded-none shadow-sm md:shadow-none border border-gray-100 md:border-none p-4 md:p-0 hover:bg-gray-50/60 transition-colors"
                            data-status="{{ $req->status }}"
                            data-search="{{ strtolower($req->user->name . ' ' . $req->barang->nama_barang) }}">
                            <td
                                class="flex justify-between items-center md:table-cell px-0 md:px-5 py-2 md:py-4 border-b border-gray-50 md:border-none text-right md:text-left">
                                <span class="md:hidden font-bold text-gray-400 text-xs">Tanggal</span>
                                <div>
                                    <span
                                        class="text-xs text-gray-600 font-medium">{{ $req->created_at->format('d M Y') }}</span>
                                    <span class="block text-xs text-gray-400">{{ $req->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            @if (auth()->user()->isPicGudang())
                                <td
                                    class="flex justify-between items-center md:table-cell px-0 md:px-5 py-2 md:py-4 border-b border-gray-50 md:border-none text-right md:text-left">
                                    <span class="md:hidden font-bold text-gray-400 text-xs">Pemohon</span>
                                    <div>
                                        <strong class="text-gray-900 block text-sm">{{ $req->user->name }}</strong>
                                        <span
                                            class="text-xs text-gray-400">{{ $req->user->divisi ? ucfirst($req->user->divisi) : ucfirst(str_replace('_', ' ', $req->user->role)) }}</span>
                                    </div>
                                </td>
                            @endif
                            <td
                                class="flex justify-between items-center md:table-cell px-0 md:px-5 py-2 md:py-4 border-b border-gray-50 md:border-none text-right md:text-left">
                                <span class="md:hidden font-bold text-gray-400 text-xs">Barang</span>
                                <div>
                                    <strong class="text-gray-900 block text-sm">{{ $req->barang->nama_barang }}</strong>
                                    <span class="text-xs font-bold text-primary">{{ $req->jumlah }}
                                        {{ strtoupper($req->satuan_diminta ?? 'unit') }}</span>
                                    @if ($req->is_habis_pakai_saat_lapor)
                                        <span class="block text-xs text-orange-500 font-semibold">🔥 Habis pakai</span>
                                    @endif
                                </div>
                            </td>
                            <td
                                class="flex justify-between items-center md:table-cell px-0 md:px-5 py-2 md:py-4 border-b border-gray-50 md:border-none">
                                <span class="md:hidden font-bold text-gray-400 text-xs">Status</span>
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $sc['bg'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                    {{ $req->status }}
                                </span>
                            </td>
                            <td
                                class="flex flex-col md:table-cell px-0 md:px-5 py-2 md:py-4 border-b border-gray-50 md:border-none whitespace-normal min-w-[180px]">
                                @if ($req->catatan_pic)
                                    <div class="mb-2 p-2 bg-blue-50 border border-blue-100 rounded-lg">
                                        <span class="text-xs font-bold text-blue-700 block">📋 Catatan PIC:</span>
                                        <span class="text-xs text-blue-800">{{ Str::limit($req->catatan_pic, 80) }}</span>
                                    </div>
                                @elseif($req->catatan)
                                    <div class="mb-2 p-2 bg-amber-50 border border-amber-100 rounded-lg">
                                        <span class="text-xs font-bold text-amber-700 block">📝 Catatan:</span>
                                        <span class="text-xs text-amber-800">{{ Str::limit($req->catatan, 80) }}</span>
                                    </div>
                                @endif
                                @if ($req->jadwal_pengambilan)
                                    @php
                                        $jadwalParsed = \Carbon\Carbon::parse($req->jadwal_pengambilan);
                                        $isJadwalLewat = $jadwalParsed->endOfDay()->isPast();
                                    @endphp
                                    <div class="p-2 {{ $isJadwalLewat ? 'bg-red-50 border-red-200' : 'bg-emerald-50 border-emerald-100' }} border rounded-lg mb-2">
                                        <span class="text-xs font-bold {{ $isJadwalLewat ? 'text-red-700' : 'text-emerald-700' }} block">⏰ Jadwal Pengambilan:</span>
                                        <span class="text-xs {{ $isJadwalLewat ? 'text-red-800 line-through' : 'text-emerald-800' }} font-medium">{{ $jadwalParsed->format('d M Y, H:i') }}</span>
                                        @if($isJadwalLewat)
                                            <span class="text-[10px] text-red-600 font-bold block mt-0.5">⚠️ Sudah Terlewat!</span>
                                        @endif
                                    </div>
                                @endif
                                <div class="flex gap-2 flex-wrap mt-1">
                                    @if ($req->foto_pengambilan)
                                        <a href="{{ asset('storage/' . $req->foto_pengambilan) }}" target="_blank"
                                            class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 hover:bg-gray-200 text-xs font-bold text-gray-600 rounded-lg transition-colors">📸
                                            Foto Ambil</a>
                                    @endif
                                    @if ($req->foto_pengembalian)
                                        <a href="{{ asset('storage/' . $req->foto_pengembalian) }}" target="_blank"
                                            class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 hover:bg-gray-200 text-xs font-bold text-gray-600 rounded-lg transition-colors">📸
                                            Foto Kembali</a>
                                    @endif
                                </div>
                            </td>

                            {{-- AKSI --}}
                            <td
                                class="flex justify-end items-center md:table-cell px-0 md:px-4 py-2 md:py-4 text-right md:text-center min-w-[150px] border-b border-gray-50 md:border-none">
                                <div class="flex md:flex-col gap-1.5 justify-end">
                                    @if (auth()->user()->isPicGudang())
                                        {{-- PIC: Approve atau Reject --}}
                                        @if ($req->status === 'Menunggu Approval')
                                            <button type="button"
                                                onclick="openModalApprove({{ $req->id }}, '{{ addslashes($req->user->name) }}', '{{ addslashes($req->barang->nama_barang) }}', {{ $req->jumlah }}, '{{ $req->satuan_diminta ?? 'unit' }}')"
                                                class="w-full px-3 py-1.5 bg-primary text-white rounded-lg hover:bg-primary-light text-xs font-bold shadow-sm transition-colors">
                                                📋 Tinjau Request
                                            </button>
                                        @endif
                                        {{-- PIC: Jadwalkan Ulang jika Pending --}}
                                        @if ($req->status === 'Pending (Jadwal Ulang)')
                                            <button type="button"
                                                onclick="openModalReschedule({{ $req->id }}, '{{ addslashes($req->user->name) }}', '{{ addslashes($req->barang->nama_barang) }}')"
                                                class="w-full px-3 py-1.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-xs font-bold shadow-sm transition-colors">
                                                📅 Jadwalkan Ulang
                                            </button>
                                        @endif
                                        {{-- PIC: Verifikasi Pengembalian --}}
                                        @if ($req->status === 'Menunggu Verifikasi Pengembalian')
                                            <form action="{{ route('permintaan.status.update', $req->id) }}" method="POST"
                                                onsubmit="return confirm('Verifikasi pengembalian barang?');">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="action" value="verify_return">
                                                <button type="submit"
                                                    class="w-full px-3 py-1.5 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 text-xs font-bold shadow-sm transition-colors">✅
                                                    Verifikasi Kembali</button>
                                            </form>
                                        @endif
                                        {{-- PIC: Selesaikan (hanya setelah barang dikembalikan) --}}
                                        @if ($req->status === 'Barang Dikembalikan')
                                            <form action="{{ route('permintaan.status.update', $req->id) }}" method="POST"
                                                onsubmit="return confirm('Tandai permintaan ini sebagai Selesai? Forum diskusi akan ditutup dalam 3 hari.');">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="action" value="close">
                                                <button type="submit"
                                                    class="w-full px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 text-xs font-bold shadow-sm transition-colors">🏁
                                                    Selesaikan</button>
                                            </form>
                                        @endif
                                    @endif

                                    @if ($isOwner)
                                        {{-- Requester: Batalkan --}}
                                        @if (in_array($req->status, ['Menunggu Approval', 'Menunggu Pengambilan']))
                                            <form action="{{ route('permintaan.status.update', $req->id) }}" method="POST"
                                                onsubmit="return confirm('Batalkan permintaan ini?');">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="action" value="cancel">
                                                <button type="submit"
                                                    class="w-full px-3 py-1.5 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg text-xs font-bold shadow-sm transition-colors">❌
                                                    Batalkan</button>
                                            </form>
                                        @endif
                                        {{-- Requester: Foto Ambil Barang --}}
                                        @if ($req->status === 'Menunggu Pengambilan')
                                            <button type="button"
                                                onclick="openModalPickup({{ $req->id }}, '{{ addslashes($req->barang->nama_barang) }}')"
                                                class="w-full px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-xs font-bold shadow-sm transition-colors">
                                                📸 Foto Ambil
                                            </button>
                                        @endif
                                        {{-- Requester: Info jika Pending --}}
                                        @if ($req->status === 'Pending (Jadwal Ulang)')
                                            <span class="w-full block text-center px-2 py-1.5 bg-orange-50 text-orange-700 border border-orange-200 rounded-lg text-xs font-bold">
                                                ⏳ Hubungi PIC untuk jadwal ulang
                                            </span>
                                        @endif
                                        {{-- Requester: Lapor Penggunaan Barang --}}
                                        @if ($req->status === 'Barang Diambil')
                                            <button type="button"
                                                onclick="openModalReturn({{ $req->id }}, '{{ addslashes($req->barang->nama_barang) }}', {{ $req->jumlah }})"
                                                class="w-full px-3 py-1.5 bg-teal-500 text-white rounded-lg hover:bg-teal-600 text-xs font-bold shadow-sm transition-colors">
                                                🔄 Lapor Selesai / Kembali
                                            </button>
                                        @endif
                                    @endif

                                    @if (!auth()->user()->isPicGudang() && !$isOwner)
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </div>
                            </td>

                            {{-- FORUM DISKUSI --}}
                            <td
                                class="flex justify-end items-center md:table-cell px-0 md:px-5 py-4 md:py-4 text-right md:text-center border-b border-gray-50 md:border-none">
                                @php
                                    // $forumActive is strictly the active statuses (Menunggu Approval, dll)
                                    $isInGracePeriod = $req->forum_ends_at && $req->forum_ends_at->isFuture();
                                @endphp

                                @if ($forumActive)
                                    <button type="button" onclick="openChatPanel({{ $req->id }})"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white hover:bg-primary-light shadow-md shadow-primary/20 rounded-xl transition-all font-bold text-xs relative">
                                        <span class="text-base leading-none">💬</span>
                                        <span class="hidden md:inline">Forum Diskusi</span>
                                        <span class="md:hidden">Forum</span>
                                        @php $chatCount = $req->chats->count(); @endphp
                                        @if ($chatCount > 0)
                                            <span
                                                class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center font-bold animate-pulse border border-white">{{ min($chatCount, 9) }}{{ $chatCount > 9 ? '+' : '' }}</span>
                                        @endif
                                    </button>
                                @elseif($isInGracePeriod)
                                    <button type="button" onclick="openChatPanel({{ $req->id }})"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-xl transition-all font-bold text-xs">
                                        <span class="text-base leading-none">💬</span>
                                        <span class="hidden md:inline">Lihat Arsip</span>
                                        <span class="md:hidden">Arsip</span>
                                    </button>
                                @else
                                    <span class="text-xs text-gray-300 font-medium block md:inline">Tidak Tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <span class="text-4xl block mb-3">📭</span>
                                <p class="text-gray-400 font-medium">Belum ada riwayat permintaan barang.</p>
                                @if (!auth()->user()->isWebAdmin())
                                    <a href="{{ route('permintaan.create') }}"
                                        class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary-light transition-colors">
                                        + Buat Permintaan Pertama
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <span class="text-xs text-gray-400 font-medium">Total <strong
                    class="text-gray-600">{{ count($permintaans) }}</strong> permintaan</span>
            <span class="text-xs text-gray-400" id="filteredCount"></span>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- CHAT SIDEBAR PANEL (Global, Shared untuk semua permintaan)   --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div id="chatBackdrop"
        class="fixed inset-0 bg-black/20 backdrop-blur-[2px] z-40 hidden opacity-0 transition-opacity duration-300"
        onclick="closeChatPanel()"></div>

    <div id="chatPanel"
        class="fixed top-0 right-0 h-full w-full sm:w-[400px] bg-white shadow-2xl z-50 flex flex-col transform translate-x-full transition-transform duration-300 ease-out hidden">
        {{-- Panel Header --}}
        <div class="flex items-center gap-3 px-5 py-4 bg-primary text-white flex-shrink-0">
            <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-sm truncate" id="chatPanelTitle">Forum Diskusi</p>
                <p class="text-xs text-white/70 truncate" id="chatPanelSubtitle">Loading...</p>
            </div>
            <button onclick="closeChatPanel()"
                class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/20 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Forum Ended Banner --}}
        <div id="forumEndedBanner" class="hidden flex-shrink-0">
            <div class="px-5 py-3 bg-orange-50 border-b border-orange-100 flex items-center gap-2">
                <span class="text-base">⏳</span>
                <p class="text-xs text-orange-700 font-semibold" id="forumEndedText">Forum diskusi ini akan menghilang
                    dalam 3 hari.</p>
            </div>
        </div>

        {{-- Loading State --}}
        <div id="chatLoading" class="flex-1 flex items-center justify-center">
            <div class="text-center">
                <div class="w-8 h-8 border-3 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-3">
                </div>
                <p class="text-sm text-gray-400 font-medium">Memuat percakapan...</p>
            </div>
        </div>

        {{-- Chat Messages --}}
        <div id="chatMessages" class="flex-1 overflow-y-auto px-4 py-3 space-y-3 hidden"
            style="scroll-behavior: smooth;"></div>

        {{-- No Messages State --}}
        <div id="chatEmpty" class="flex-1 items-center justify-center hidden">
            <div class="text-center p-6">
                <span class="text-4xl block mb-3">💬</span>
                <p class="text-gray-500 font-medium text-sm">Belum ada pesan.</p>
                <p class="text-gray-400 text-xs mt-1">Mulai diskusi dengan mengirim pesan pertama.</p>
            </div>
        </div>

        {{-- Chat Input --}}
        <div id="chatInput" class="flex-shrink-0 border-t border-gray-100 p-4 bg-gray-50/50 hidden">
            <form id="chatForm" class="flex gap-2">
                <textarea id="chatMessageInput" rows="1" placeholder="Ketik pesan..."
                    class="flex-1 bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium resize-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all outline-none"
                    style="max-height: 120px; overflow-y: auto;" onkeydown="handleChatKeydown(event)"></textarea>
                <button type="submit" id="chatSendBtn"
                    class="w-10 h-10 flex items-center justify-center bg-primary hover:bg-primary-light text-white rounded-xl transition-colors shadow-sm flex-shrink-0 self-end"
                    title="Kirim (Enter)">
                    <svg class="w-4 h-4 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>

        {{-- Forum Closed Input --}}
        <div id="chatInputClosed" class="flex-shrink-0 border-t border-gray-100 p-4 hidden">
            <div class="text-center py-2 text-sm text-gray-400 font-medium">🔒 Forum diskusi telah ditutup</div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MODAL: PIC Approve Request                                --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div id="modalApprove"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300">
            <div
                class="p-6 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-primary/5 to-accent/5">
                <h2 class="text-xl font-bold text-gray-900">Tinjau Permintaan Barang</h2>
                <button type="button"
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                    onclick="closeModal('modalApprove')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-5 p-4 bg-gray-50 rounded-xl border border-gray-100 grid gap-2 text-sm">
                    <div class="flex gap-3"><span
                            class="w-20 font-bold text-gray-500 text-xs uppercase tracking-wider">Pemohon</span> <strong
                            id="approve_pemohon" class="text-gray-900"></strong></div>
                    <div class="flex gap-3"><span
                            class="w-20 font-bold text-gray-500 text-xs uppercase tracking-wider">Barang</span> <strong
                            id="approve_barang" class="text-primary"></strong></div>
                </div>

                <form id="formApprove" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="jadwal_pengambilan">
                            Jadwal Pengambilan <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="jadwal_pengambilan" id="jadwal_pengambilan"
                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm">
                        <p class="text-xs text-gray-400 mt-1">Wajib jika menyetujui permintaan.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="catatan_pic_modal">
                            Catatan PIC <span class="text-red-500">*</span>
                        </label>
                        <textarea name="catatan_pic" id="catatan_pic_modal" rows="3"
                            placeholder="Tulis catatan untuk permintaaner (wajib saat approve)..."
                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm resize-none"></textarea>
                        <p class="text-xs text-gray-400 mt-1">Catatan wajib diisi saat menyetujui. Opsional saat menolak.
                        </p>
                    </div>

                    <div class="flex justify-between gap-3">
                        <button type="submit" name="action" value="reject"
                            class="px-5 py-2.5 text-white bg-red-500 hover:bg-red-600 rounded-xl font-bold transition-colors shadow-md shadow-red-500/20 text-sm">
                            ❌ Tolak
                        </button>
                        <button type="submit" name="action" value="approve"
                            class="px-6 py-2.5 text-white bg-primary hover:bg-primary-light rounded-xl font-bold transition-colors shadow-md shadow-primary/20 text-sm">
                            ✅ Setujui & Jadwalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MODAL: Requester Foto Pengambilan (Kamera Only)           --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div id="modalPickup"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md sm:max-w-lg overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">📸 Foto Bukti Pengambilan</h2>
                <button type="button"
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                    onclick="closeModal('modalPickup')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-5">
                <div class="mb-5 p-4 bg-blue-50 border border-blue-100 rounded-xl text-sm text-blue-800">
                    <strong>⚠️ Penting:</strong> Anda wajib mengambil foto langsung menggunakan kamera saat ini untuk bukti
                    pengambilan <strong><span id="pickup_barang_name"></span></strong>. Foto dari galeri dinonaktifkan.
                </div>
                <form id="formPickup" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input type="hidden" name="action" value="pickup">

                    <div class="mb-5">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-bold text-gray-700">
                                Foto Bukti (Kamera Langsung) <span class="text-red-500">*</span>
                            </label>
                            <span class="text-[11px] font-semibold text-blue-700 bg-blue-100/70 px-2 py-0.5 rounded-full border border-blue-200">
                                🔒 Galeri Dinonaktifkan
                            </span>
                        </div>

                        <!-- Hidden file input submitted with form -->
                        <input type="file" name="foto_pengambilan" id="foto_pengambilan" accept="image/jpeg,image/png" class="hidden" required>

                        <!-- Direct system camera input for mobile fallback / HTTP mode -->
                        <input type="file" id="pickupDirectInput" accept="image/*" capture="environment" class="hidden" onchange="handleDirectCapture(this, 'pickup')">

                        <!-- Camera Container -->
                        <div class="relative bg-slate-950 rounded-2xl overflow-hidden border-2 border-gray-200 shadow-inner aspect-[4/3] flex items-center justify-center">
                            <!-- Live Video Stream -->
                            <video id="pickupVideo" autoplay playsinline muted class="w-full h-full object-cover"></video>

                            <!-- Loading State -->
                            <div id="pickupCameraLoading" class="absolute inset-0 bg-slate-900/90 flex flex-col items-center justify-center p-4 text-center z-20">
                                <div class="w-10 h-10 border-4 border-blue-400 border-t-transparent rounded-full animate-spin mb-3"></div>
                                <p class="text-sm font-bold text-white">Menghubungkan ke kamera...</p>
                                <p class="text-xs text-gray-400 mt-1">Harap izinkan akses kamera pada browser</p>
                            </div>

                            <!-- Error / Fallback State -->
                            <div id="pickupCameraError" class="absolute inset-0 bg-slate-900/95 hidden flex-col items-center justify-center p-6 text-center z-20 overflow-y-auto">
                                <div class="w-12 h-12 bg-blue-500/20 text-blue-400 rounded-full flex items-center justify-center text-2xl mb-2">📷</div>
                                <p class="text-sm font-bold text-white mb-1" id="pickupCameraErrorMsg">Akses Kamera Diperlukan</p>
                                <p class="text-xs text-gray-300 mb-4 max-w-xs" id="pickupCameraErrorDesc">Foto wajib diambil langsung melalui kamera saat ini.</p>
                                
                                <div class="w-full max-w-xs space-y-2" id="pickupActionButtons">
                                    <button type="button" onclick="openDirectCamera('pickup')" id="pickupDirectBtn"
                                        class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                                        <span>📸</span> Buka Kamera HP Langsung
                                    </button>
                                    <a href="#" id="pickupLocalhostLink"
                                        class="hidden w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition text-center flex items-center justify-center gap-1.5">
                                        <span>🚀</span> Buka via localhost:8000
                                    </a>
                                    <button type="button" onclick="startCamera('pickup')" id="pickupRetryBtn"
                                        class="w-full py-2 px-4 bg-slate-800 hover:bg-slate-700 text-gray-300 text-xs font-semibold rounded-xl border border-gray-700 transition">
                                        🔄 Coba Hubungkan Kamera Lagi
                                    </button>
                                </div>
                            </div>

                            <!-- Live Controls -->
                            <div id="pickupCameraControls" class="absolute bottom-3 inset-x-0 flex items-center justify-center gap-4 z-20 hidden">
                                <button type="button" onclick="switchCamera('pickup')" id="pickupSwitchBtn" title="Balik Kamera"
                                    class="w-10 h-10 rounded-full bg-black/60 hover:bg-black/80 backdrop-blur border border-white/20 text-white flex items-center justify-center text-base transition active:scale-95 shadow">
                                    🔄
                                </button>
                                <button type="button" onclick="takePhoto('pickup')"
                                    class="px-6 py-2.5 bg-white hover:bg-gray-100 text-gray-900 font-extrabold text-sm rounded-full shadow-lg flex items-center gap-2 border-2 border-blue-500 active:scale-95 transition">
                                    <span class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></span>
                                    Ambil Foto
                                </button>
                                <div class="w-10"></div>
                            </div>

                            <!-- Preview Result -->
                            <div id="pickupPreviewWrap" class="absolute inset-0 hidden z-30 bg-black">
                                <img id="pickupPreview" src="" alt="Hasil Foto" class="w-full h-full object-cover">
                                <div class="absolute top-3 left-3 bg-emerald-600/90 backdrop-blur text-white text-xs px-3 py-1.5 rounded-full font-bold flex items-center gap-1.5 shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Foto Berhasil Diambil
                                </div>
                                <div class="absolute bottom-3 inset-x-0 flex justify-center">
                                    <button type="button" onclick="retakePhoto('pickup')"
                                        class="px-5 py-2 bg-slate-900/80 hover:bg-slate-900 text-white text-xs font-bold rounded-xl border border-white/20 backdrop-blur shadow-lg flex items-center gap-1.5 transition active:scale-95">
                                        🔄 Ambil Ulang Foto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button"
                            class="px-4 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl font-bold transition-colors text-sm"
                            onclick="closeModal('modalPickup')">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-white bg-blue-600 hover:bg-blue-700 rounded-xl font-bold transition-colors shadow-md text-sm">Kirim
                            Bukti</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MODAL: Requester Kembalikan Barang                        --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div id="modalReturn"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md sm:max-w-lg overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">🔄 Lapor Selesai / Kembalikan</h2>
                <button type="button"
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                    onclick="closeModal('modalReturn')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-5">
                <div class="mb-5 p-4 bg-teal-50 border border-teal-100 rounded-xl text-sm text-teal-800">
                    Lapor penggunaan <strong><span id="return_barang_name"></span></strong>. Pilih status akhir barang.
                </div>
                <form id="formReturn" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input type="hidden" name="action" value="return">

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status Akhir Barang</label>
                        <div class="space-y-2">
                            <label
                                class="flex items-start gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors"
                                onclick="toggleReturnInputs()">
                                <input type="radio" name="is_habis_pakai_saat_lapor" id="radio_habis" value="1"
                                    class="mt-1 w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                                <div>
                                    <span class="block text-sm font-bold text-gray-900">Barang Habis Total</span>
                                    <span class="block text-xs text-gray-500">Pilih jika barang habis digunakan (misal:
                                        pupuk/benih habis dipakai, tidak ada sisa).</span>
                                </div>
                            </label>
                            <label
                                class="flex items-start gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors"
                                onclick="toggleReturnInputs()">
                                <input type="radio" name="is_habis_pakai_saat_lapor" id="radio_sisa" value="0"
                                    class="mt-1 w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300" checked>
                                <div>
                                    <span class="block text-sm font-bold text-gray-900">Ada Barang Dikembalikan</span>
                                    <span class="block text-xs text-gray-500">Pilih jika Anda mengembalikan alat (cangkul)
                                        atau ada sisa barang (sisa pupuk).</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="return_details_container">
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="jumlah_dikembalikan">
                                Jumlah Dikembalikan / Sisa <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jumlah_dikembalikan" id="jumlah_dikembalikan"
                                class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm"
                                min="1" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="kondisi_pengembalian">
                                Kondisi Barang <span class="text-red-500">*</span>
                            </label>
                            <select name="kondisi_pengembalian" id="kondisi_pengembalian"
                                class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary p-3 font-medium shadow-sm"
                                required>
                                <option value="">-- Pilih Kondisi --</option>
                                <optgroup label="🌱 Barang Habis Pakai (Pupuk, dll)">
                                    <option value="Sisa Sedikit">Sisa Sedikit</option>
                                    <option value="Belum Terbuka">Belum Terbuka</option>
                                    <option value="Sudah Terbuka">Sudah Terbuka</option>
                                </optgroup>
                                <optgroup label="🔧 Alat / Pinjaman">
                                    <option value="Baru / Bagus">Baru / Bagus</option>
                                    <option value="Bekas Pakai - Bagus">Bekas Pakai - Bagus</option>
                                    <option value="Bekas Pakai - Perlu Perbaikan">Bekas Pakai - Perlu Perbaikan</option>
                                    <option value="Rusak">Rusak</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-bold text-gray-700">
                                Foto Pengembalian (Kamera Langsung) <span class="text-red-500">*</span>
                            </label>
                            <span class="text-[11px] font-semibold text-teal-700 bg-teal-100/70 px-2 py-0.5 rounded-full border border-teal-200">
                                🔒 Galeri Dinonaktifkan
                            </span>
                        </div>

                        <!-- Hidden file input submitted with form -->
                        <input type="file" name="foto_pengembalian" id="foto_pengembalian" accept="image/jpeg,image/png" class="hidden" required>

                        <!-- Direct system camera input for mobile fallback / HTTP mode -->
                        <input type="file" id="returnDirectInput" accept="image/*" capture="environment" class="hidden" onchange="handleDirectCapture(this, 'return')">

                        <!-- Camera Container -->
                        <div class="relative bg-slate-950 rounded-2xl overflow-hidden border-2 border-gray-200 shadow-inner aspect-[4/3] flex items-center justify-center">
                            <!-- Live Video Stream -->
                            <video id="returnVideo" autoplay playsinline muted class="w-full h-full object-cover"></video>

                            <!-- Loading State -->
                            <div id="returnCameraLoading" class="absolute inset-0 bg-slate-900/90 flex flex-col items-center justify-center p-4 text-center z-20">
                                <div class="w-10 h-10 border-4 border-teal-400 border-t-transparent rounded-full animate-spin mb-3"></div>
                                <p class="text-sm font-bold text-white">Menghubungkan ke kamera...</p>
                                <p class="text-xs text-gray-400 mt-1">Harap izinkan akses kamera pada browser</p>
                            </div>

                            <!-- Error / Fallback State -->
                            <div id="returnCameraError" class="absolute inset-0 bg-slate-900/95 hidden flex-col items-center justify-center p-6 text-center z-20 overflow-y-auto">
                                <div class="w-12 h-12 bg-teal-500/20 text-teal-400 rounded-full flex items-center justify-center text-2xl mb-2">📷</div>
                                <p class="text-sm font-bold text-white mb-1" id="returnCameraErrorMsg">Akses Kamera Diperlukan</p>
                                <p class="text-xs text-gray-300 mb-4 max-w-xs" id="returnCameraErrorDesc">Foto wajib diambil langsung melalui kamera saat ini.</p>
                                
                                <div class="w-full max-w-xs space-y-2" id="returnActionButtons">
                                    <button type="button" onclick="openDirectCamera('return')" id="returnDirectBtn"
                                        class="w-full py-2.5 px-4 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                                        <span>📸</span> Buka Kamera HP Langsung
                                    </button>
                                    <a href="#" id="returnLocalhostLink"
                                        class="hidden w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition text-center flex items-center justify-center gap-1.5">
                                        <span>🚀</span> Buka via localhost:8000
                                    </a>
                                    <button type="button" onclick="startCamera('return')" id="returnRetryBtn"
                                        class="w-full py-2 px-4 bg-slate-800 hover:bg-slate-700 text-gray-300 text-xs font-semibold rounded-xl border border-gray-700 transition">
                                        🔄 Coba Hubungkan Kamera Lagi
                                    </button>
                                </div>
                            </div>

                            <!-- Live Controls -->
                            <div id="returnCameraControls" class="absolute bottom-3 inset-x-0 flex items-center justify-center gap-4 z-20 hidden">
                                <button type="button" onclick="switchCamera('return')" id="returnSwitchBtn" title="Balik Kamera"
                                    class="w-10 h-10 rounded-full bg-black/60 hover:bg-black/80 backdrop-blur border border-white/20 text-white flex items-center justify-center text-base transition active:scale-95 shadow">
                                    🔄
                                </button>
                                <button type="button" onclick="takePhoto('return')"
                                    class="px-6 py-2.5 bg-white hover:bg-gray-100 text-gray-900 font-extrabold text-sm rounded-full shadow-lg flex items-center gap-2 border-2 border-teal-500 active:scale-95 transition">
                                    <span class="w-3 h-3 rounded-full bg-teal-500 animate-pulse"></span>
                                    Ambil Foto
                                </button>
                                <div class="w-10"></div>
                            </div>

                            <!-- Preview Result -->
                            <div id="returnPreviewWrap" class="absolute inset-0 hidden z-30 bg-black">
                                <img id="returnPreview" src="" alt="Hasil Foto" class="w-full h-full object-cover">
                                <div class="absolute top-3 left-3 bg-teal-600/90 backdrop-blur text-white text-xs px-3 py-1.5 rounded-full font-bold flex items-center gap-1.5 shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Foto Berhasil Diambil
                                </div>
                                <div class="absolute bottom-3 inset-x-0 flex justify-center">
                                    <button type="button" onclick="retakePhoto('return')"
                                        class="px-5 py-2 bg-slate-900/80 hover:bg-slate-900 text-white text-xs font-bold rounded-xl border border-white/20 backdrop-blur shadow-lg flex items-center gap-1.5 transition active:scale-95">
                                        🔄 Ambil Ulang Foto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button"
                            class="px-4 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl font-bold transition-colors text-sm"
                            onclick="closeModal('modalReturn')">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-white bg-teal-600 hover:bg-teal-700 rounded-xl font-bold transition-colors shadow-md text-sm">Kirim
                            Pengembalian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MODAL: PIC Jadwalkan Ulang (Pending)                      --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div id="modalReschedule"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-orange-50 to-amber-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-xl">📅</div>
                    <h2 class="text-xl font-bold text-gray-900">Jadwalkan Ulang Pengambilan</h2>
                </div>
                <button type="button"
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                    onclick="closeModal('modalReschedule')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-5 p-4 bg-orange-50 border border-orange-100 rounded-xl text-sm text-orange-800">
                    ⚠️ Permintaan dari <strong id="reschedule_pemohon"></strong> untuk <strong id="reschedule_barang"></strong> melewati jadwal pengambilan dan kini berstatus <strong>Pending</strong>. Tentukan jadwal baru.
                </div>

                <form id="formReschedule" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="action" value="reschedule">

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="reschedule_jadwal">
                            Jadwal Pengambilan Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="jadwal_pengambilan" id="reschedule_jadwal"
                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-orange-300 focus:border-orange-400 p-3 font-medium shadow-sm">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="reschedule_catatan">
                            Catatan PIC <span class="text-gray-400 font-normal text-xs">(opsional)</span>
                        </label>
                        <textarea name="catatan_pic" id="reschedule_catatan" rows="2"
                            placeholder="Catatan untuk pemohon (misal: jadwal diubah karena gudang tutup)..."
                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-orange-300 focus:border-orange-400 p-3 font-medium shadow-sm resize-none"></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button"
                            class="px-4 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl font-bold transition-colors text-sm"
                            onclick="closeModal('modalReschedule')">Batal</button>
                        <button type="submit"
                            class="px-6 py-2.5 text-white bg-orange-500 hover:bg-orange-600 rounded-xl font-bold transition-colors shadow-md shadow-orange-500/20 text-sm">
                            📅 Simpan Jadwal Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Utils ─────────────────────────────────────────────────────────────────────
        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return String(unsafe)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // ── State ────────────────────────────────────────────────────────────────────
        let currentRequestId = null;
        let chatPollingInterval = null;
        const currentUserId = {{ auth()->id() }};

        // ── Chat Panel ───────────────────────────────────────────────────────────────
        function openChatPanel(permintaanId) {
            currentRequestId = permintaanId;
            const panel = document.getElementById('chatPanel');
            const backdrop = document.getElementById('chatBackdrop');

            // Reset state
            showChatLoading();
            document.getElementById('chatPanelTitle').textContent = 'Forum Diskusi #REQ' + String(permintaanId).padStart(4,
                '0');
            document.getElementById('chatPanelSubtitle').textContent = 'Memuat...';
            document.getElementById('forumEndedBanner').classList.add('hidden');

            // Open panel
            backdrop.classList.remove('hidden');
            panel.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
            }, 10);
            panel.classList.remove('translate-x-full');
            panel.classList.add('translate-x-0');

            // Load chats
            loadChats(permintaanId);

            // Poll every 5 seconds
            clearInterval(chatPollingInterval);
            chatPollingInterval = setInterval(() => {
                if (currentRequestId) loadChats(currentRequestId, true);
            }, 5000);
        }

        function closeChatPanel() {
            const panel = document.getElementById('chatPanel');
            const backdrop = document.getElementById('chatBackdrop');

            panel.classList.remove('translate-x-0');
            panel.classList.add('translate-x-full');
            backdrop.classList.add('opacity-0');
            setTimeout(() => {
                backdrop.classList.add('hidden');
                panel.classList.add('hidden');
            }, 300);

            clearInterval(chatPollingInterval);
            currentRequestId = null;
        }

        async function loadChats(permintaanId, silent = false) {
            try {
                const response = await fetch(`/permintaan/${permintaanId}/chat?_t=${Date.now()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.status === 403) {
                    const data = await response.json();
                    if (data.forum_ended) {
                        showForumEnded();
                    } else {
                        alert(data.error || 'Akses ditolak.');
                        closeChatPanel();
                    }
                    return;
                }

                if (!response.ok) {
                    if (!silent) console.error('HTTP Error:', response.status);
                    return;
                }

                const data = await response.json();
                if (!data || !Array.isArray(data.chats)) {
                    if (!silent) console.error('Invalid JSON format:', data);
                    return;
                }

                // Update header
                document.getElementById('chatPanelTitle').textContent =
                    `Forum — ${data.barang_name ?? 'Barang'}`;
                document.getElementById('chatPanelSubtitle').textContent =
                    `${data.permintaaner_name ?? 'User'} • ${data.permintaan_status}`;

                // Forum ended banner
                if (!data.is_forum_active && data.days_left !== null) {
                    const banner = document.getElementById('forumEndedBanner');
                    const text = document.getElementById('forumEndedText');
                    banner.classList.remove('hidden');
                    text.textContent = data.days_left > 0 ?
                        `⏳ Forum diskusi ini akan menghilang dalam ${data.days_left} hari.` :
                        '⌛ Forum diskusi ini telah berakhir.';
                }

                renderChats(data.chats, data.is_forum_active);

            } catch (err) {
                if (!silent) console.error('Failed to load chats:', err);
            } finally {
                document.getElementById('chatLoading').classList.add('hidden');
            }
        }

        function renderChats(chats, isForumActive) {
            const container = document.getElementById('chatMessages');
            const empty = document.getElementById('chatEmpty');
            const loading = document.getElementById('chatLoading');
            const inputEl = document.getElementById('chatInput');
            const inputClosed = document.getElementById('chatInputClosed');

            loading.classList.add('hidden');

            if (chats.length === 0) {
                container.classList.add('hidden');
                empty.classList.remove('hidden');
                empty.classList.add('flex');
            } else {
                empty.classList.add('hidden');
                empty.classList.remove('flex');
                container.classList.remove('hidden');

                const wasAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 60;

                container.innerHTML = chats.map(chat => {
                    const isMe = chat.user_id === currentUserId;
                    const isPIC = chat.user_role === 'pic_gudang';
                    const userName = chat.user_name || 'User';
                    return `
            <div class="flex ${isMe ? 'justify-end' : 'justify-start'} gap-2">
                ${!isMe ? `<div class="w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center text-xs font-bold text-white ${isPIC ? 'bg-primary' : 'bg-accent'} mt-1">
                                    ${userName.charAt(0).toUpperCase()}
                                </div>` : ''}
                <div class="max-w-[75%]">
                    ${!isMe ? `<p class="text-xs font-bold ${isPIC ? 'text-primary' : 'text-accent'} mb-0.5 px-1">${userName} ${isPIC ? '• PIC' : ''}</p>` : ''}
                    <div class="px-3 py-2 rounded-2xl text-sm ${isMe
                        ? 'bg-primary text-white rounded-tr-sm'
                        : 'bg-gray-100 text-gray-800 rounded-tl-sm'}">
                        ${escapeHtml(chat.message).replace(/\n/g, '<br>')}
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5 px-1 ${isMe ? 'text-right' : ''}">${chat.created_at}</p>
                </div>
                ${isMe ? `<div class="w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center text-xs font-bold text-white bg-primary mt-1">
                                    ${chat.user_name.charAt(0).toUpperCase()}
                                </div>` : ''}
            </div>`;
                }).join('');

                if (wasAtBottom || !container.classList.contains('scrolled')) {
                    container.scrollTop = container.scrollHeight;
                    container.classList.add('scrolled');
                }
            }

            // Show/hide input
            if (isForumActive) {
                inputEl.classList.remove('hidden');
                inputClosed.classList.add('hidden');
            } else {
                inputEl.classList.add('hidden');
                inputClosed.classList.remove('hidden');
            }
        }

        function showChatLoading() {
            document.getElementById('chatLoading').classList.remove('hidden');
            document.getElementById('chatMessages').classList.add('hidden');
            document.getElementById('chatEmpty').classList.add('hidden');
            document.getElementById('chatEmpty').classList.remove('flex');
            document.getElementById('chatInput').classList.add('hidden');
            document.getElementById('chatInputClosed').classList.add('hidden');
        }

        function showForumEnded() {
            document.getElementById('chatLoading').classList.add('hidden');
            document.getElementById('chatMessages').classList.add('hidden');
            document.getElementById('chatEmpty').classList.remove('hidden');
            document.getElementById('chatEmpty').classList.add('flex');
            document.getElementById('chatInput').classList.add('hidden');
            document.getElementById('chatInputClosed').classList.remove('hidden');
        }

        // ── Chat Form Submit ──────────────────────────────────────────────────────────
        document.getElementById('chatForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const input = document.getElementById('chatMessageInput');
            const message = input.value.trim();
            if (!message || !currentRequestId) return;

            const btn = document.getElementById('chatSendBtn');
            btn.disabled = true;
            btn.classList.add('opacity-50');

            try {
                const response = await fetch(`/permintaan/${currentRequestId}/chat`, {
                    method: 'POST',
                    headers: {
                        'Konten-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message
                    })
                });

                if (response.ok) {
                    input.value = '';
                    input.style.height = 'auto';
                    await loadChats(currentRequestId);
                } else {
                    const err = await response.json();
                    alert(err.error || 'Gagal mengirim pesan.');
                }
            } catch (err) {
                console.error(err);
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-50');
                input.focus();
            }
        });

        function handleChatKeydown(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('chatForm').dispatchEvent(new Event('submit'));
            }
            // Auto-resize
            const ta = e.target;
            ta.style.height = 'auto';
            ta.style.height = Math.min(ta.scrollHeight, 120) + 'px';
        }

        // ── Modal Helpers ─────────────────────────────────────────────────────────────
        function openModalApprove(id, pemohon, barang, jumlah, satuan) {
            document.getElementById('formApprove').action = `/permintaan/${id}/status`;
            document.getElementById('approve_pemohon').textContent = pemohon;
            document.getElementById('approve_barang').textContent = `${barang} — ${jumlah} ${satuan.toUpperCase()}`;
            // Set min datetime to now
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('jadwal_pengambilan').min = now.toISOString().slice(0, 16);

            const modal = document.getElementById('modalApprove');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // ── Direct Camera Handlers (No Gallery Selection) ──────────────────────────
        const cameraStates = {
            pickup: {
                stream: null,
                facingMode: 'environment',
                inputName: 'foto_pengambilan',
                hasPhoto: false
            },
            return: {
                stream: null,
                facingMode: 'environment',
                inputName: 'foto_pengembalian',
                hasPhoto: false
            }
        };

        function isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
                (navigator.maxTouchPoints > 1 && !window.MSStream);
        }

        function openDirectCamera(type) {
            if (!isMobileDevice()) {
                alert('Fitur ini khusus untuk perangkat HP/Mobile. Pada laptop/komputer, sistem Windows tidak memiliki kamera picker sehingga akan membuka File Explorer/Galeri. Gunakan webcam laptop (buka lewat localhost) atau buka aplikasi dari HP Anda.');
                return;
            }
            const input = document.getElementById(`${type}DirectInput`);
            if (input) {
                input.value = '';
                input.click();
            }
        }

        function handleDirectCapture(input, type) {
            if (!input.files || input.files.length === 0) return;

            const file = input.files[0];
            const targetInput = document.getElementById(cameraStates[type].inputName);
            const preview = document.getElementById(`${type}Preview`);
            const previewWrap = document.getElementById(`${type}PreviewWrap`);
            const errorBox = document.getElementById(`${type}CameraError`);
            const loading = document.getElementById(`${type}CameraLoading`);
            const controls = document.getElementById(`${type}CameraControls`);

            try {
                const dt = new DataTransfer();
                dt.items.add(file);
                targetInput.files = dt.files;
            } catch (e) {
                targetInput.files = input.files;
            }
            cameraStates[type].hasPhoto = true;

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                if (loading) loading.classList.add('hidden');
                if (errorBox) {
                    errorBox.classList.add('hidden');
                    errorBox.classList.remove('flex');
                }
                if (controls) controls.classList.add('hidden');
                if (previewWrap) previewWrap.classList.remove('hidden');
                stopCamera(type);
            };
            reader.readAsDataURL(file);
        }

        async function startCamera(type) {
            const state = cameraStates[type];
            const video = document.getElementById(`${type}Video`);
            const loading = document.getElementById(`${type}CameraLoading`);
            const errorBox = document.getElementById(`${type}CameraError`);
            const errorMsg = document.getElementById(`${type}CameraErrorMsg`);
            const errorDesc = document.getElementById(`${type}CameraErrorDesc`);
            const controls = document.getElementById(`${type}CameraControls`);
            const previewWrap = document.getElementById(`${type}PreviewWrap`);
            const directBtn = document.getElementById(`${type}DirectBtn`);
            const localhostLink = document.getElementById(`${type}LocalhostLink`);

            // UI states
            if (previewWrap) previewWrap.classList.add('hidden');
            if (errorBox) {
                errorBox.classList.add('hidden');
                errorBox.classList.remove('flex');
            }
            if (loading) {
                loading.classList.remove('hidden');
                loading.classList.add('flex');
            }
            if (controls) controls.classList.add('hidden');

            stopCamera(type);

            const isMobile = isMobileDevice();
            const isLocalhost = location.hostname === 'localhost' || location.hostname === '127.0.0.1';

            // Configure action buttons according to device
            if (directBtn) {
                if (isMobile) {
                    directBtn.classList.remove('hidden');
                } else {
                    directBtn.classList.add('hidden');
                }
            }

            if (localhostLink) {
                if (!isMobile && !isLocalhost) {
                    localhostLink.href = location.href.replace(location.host, 'localhost' + (location.port ? ':' + location.port : ''));
                    localhostLink.textContent = `🚀 Buka via localhost${location.port ? ':' + location.port : ''}`;
                    localhostLink.classList.remove('hidden');
                } else {
                    localhostLink.classList.add('hidden');
                }
            }

            // Check if WebRTC mediaDevices API is available
            const hasMediaDevices = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);

            if (!hasMediaDevices) {
                if (loading) {
                    loading.classList.add('hidden');
                    loading.classList.remove('flex');
                }
                if (errorBox) {
                    errorBox.classList.remove('hidden');
                    errorBox.classList.add('flex');
                }

                if (!isMobile) {
                    // Desktop non-localhost
                    errorMsg.textContent = 'Webcam Diblokir Browser pada Alamat IP';
                    if (!isLocalhost) {
                        const localUrl = location.href.replace(location.host, 'localhost' + (location.port ? ':' + location.port : ''));
                        errorDesc.innerHTML = `
                            Browser komputer (Windows) memblokir webcam karena dibuka via alamat IP (<strong>${location.hostname}</strong>).<br><br>
                            👉 <strong>Buka website via localhost:</strong><br>
                            <a href="${localUrl}" class="inline-block mt-2 px-3 py-1.5 bg-emerald-600 text-white font-bold rounded-lg text-xs hover:bg-emerald-700 transition shadow">
                                🚀 Klik untuk Pindah ke localhost
                            </a>
                        `;
                    } else {
                        errorDesc.innerHTML = 'Browser ini tidak mengizinkan akses kamera atau perangkat webcam belum diaktifkan di Windows Settings.';
                    }
                } else {
                    // Mobile
                    errorMsg.textContent = 'Kamera HP (Mode HTTP)';
                    errorDesc.textContent = 'Browser HP membatasi stream langsung pada HTTP. Silakan gunakan tombol "Buka Kamera HP Langsung" di bawah:';
                }
                return;
            }

            try {
                let stream = null;
                // Attempt 1: with ideal facingMode
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: { ideal: state.facingMode } },
                        audio: false
                    });
                } catch (e1) {
                    console.warn('Attempt 1 failed, retrying with fallback constraint:', e1);
                    // Attempt 2: fallback to generic video constraint
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: true,
                        audio: false
                    });
                }

                state.stream = stream;
                video.srcObject = stream;
                await video.play();

                if (loading) {
                    loading.classList.add('hidden');
                    loading.classList.remove('flex');
                }
                if (controls) controls.classList.remove('hidden');

                // Check available cameras to decide if switch button should be shown
                checkMultipleCameras(type);
            } catch (err) {
                console.error('Camera stream error:', err);
                if (loading) {
                    loading.classList.add('hidden');
                    loading.classList.remove('flex');
                }
                if (errorBox) {
                    errorBox.classList.remove('hidden');
                    errorBox.classList.add('flex');
                }

                if (errorMsg && errorDesc) {
                    if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                        errorMsg.textContent = 'Izin Kamera Diblokir di Browser';
                        errorDesc.innerHTML = `
                            Izin kamera pernah diblokir/ditolak pada browser ini.<br><br>
                            <strong>Cara Mengaktifkan:</strong><br>
                            1. Klik ikon <strong>gembok 🔒</strong> atau <strong>kamera 🎥</strong> di sebelah kiri URL address bar (di samping teks <code>${location.protocol}//...</code>).<br>
                            2. Ubah izin <strong>Kamera</strong> menjadi <strong>"Izinkan" (Allow)</strong>.<br>
                            3. Klik tombol <strong>"🔄 Coba Hubungkan Kamera Lagi"</strong> di bawah.
                        `;
                    } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                        errorMsg.textContent = 'Perangkat Kamera Tidak Ditemukan';
                        errorDesc.textContent = 'Tidak ditemukan perangkat webcam/kamera yang aktif pada komputer/laptop ini.';
                    } else {
                        errorMsg.textContent = 'Gagal Mengakses Kamera';
                        errorDesc.textContent = (err.message || err.name) + '. Periksa izin kamera pada browser Anda.';
                    }
                }
            }
        }

        function stopCamera(type) {
            const state = cameraStates[type];
            if (state && state.stream) {
                state.stream.getTracks().forEach(track => track.stop());
                state.stream = null;
            }
            const video = document.getElementById(`${type}Video`);
            if (video) {
                video.srcObject = null;
            }
        }

        function switchCamera(type) {
            const state = cameraStates[type];
            state.facingMode = (state.facingMode === 'environment') ? 'user' : 'environment';
            startCamera(type);
        }

        async function checkMultipleCameras(type) {
            const switchBtn = document.getElementById(`${type}SwitchBtn`);
            if (!switchBtn) return;
            try {
                if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    const videoDevices = devices.filter(d => d.kind === 'videoinput');
                    if (videoDevices.length <= 1) {
                        switchBtn.classList.add('hidden');
                    } else {
                        switchBtn.classList.remove('hidden');
                    }
                }
            } catch (e) {
                // Keep default visible
            }
        }

        function takePhoto(type) {
            const state = cameraStates[type];
            const video = document.getElementById(`${type}Video`);
            const preview = document.getElementById(`${type}Preview`);
            const previewWrap = document.getElementById(`${type}PreviewWrap`);
            const input = document.getElementById(state.inputName);

            if (!video || !video.videoWidth || !video.videoHeight) {
                alert('Kamera belum siap, silakan tunggu sesaat.');
                return;
            }

            // Visual shutter flash effect
            const flash = document.createElement('div');
            flash.className = 'absolute inset-0 bg-white z-40 transition-opacity duration-200 pointer-events-none';
            video.parentElement.appendChild(flash);
            setTimeout(() => {
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 200);
            }, 50);

            // Draw current frame to canvas
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(blob => {
                if (!blob) return;

                const filename = `${type}_${Date.now()}.jpg`;
                const file = new File([blob], filename, { type: 'image/jpeg', lastModified: Date.now() });

                try {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                    state.hasPhoto = true;
                } catch (err) {
                    console.error('DataTransfer assignment error:', err);
                }

                preview.src = canvas.toDataURL('image/jpeg', 0.9);
                if (previewWrap) previewWrap.classList.remove('hidden');

                // Stop the active camera stream now that photo is secured
                stopCamera(type);
            }, 'image/jpeg', 0.9);
        }

        function retakePhoto(type) {
            const state = cameraStates[type];
            const input = document.getElementById(state.inputName);
            const directInput = document.getElementById(`${type}DirectInput`);
            const previewWrap = document.getElementById(`${type}PreviewWrap`);

            if (input) input.value = '';
            if (directInput) directInput.value = '';
            state.hasPhoto = false;
            if (previewWrap) previewWrap.classList.add('hidden');

            startCamera(type);
        }

        function openModalPickup(id, barang) {
            document.getElementById('formPickup').action = `/permintaan/${id}/status`;
            document.getElementById('pickup_barang_name').textContent = barang;

            // Reset photo & preview
            const input = document.getElementById('foto_pengambilan');
            if (input) input.value = '';
            const directInput = document.getElementById('pickupDirectInput');
            if (directInput) directInput.value = '';
            cameraStates.pickup.hasPhoto = false;

            const previewWrap = document.getElementById('pickupPreviewWrap');
            if (previewWrap) previewWrap.classList.add('hidden');

            const modal = document.getElementById('modalPickup');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Open live camera stream automatically
            startCamera('pickup');
        }

        function openModalReturn(id, barang, maxJumlah) {
            document.getElementById('formReturn').action = `/permintaan/${id}/status`;
            document.getElementById('return_barang_name').textContent = barang;

            document.getElementById('jumlah_dikembalikan').max = maxJumlah;
            document.getElementById('jumlah_dikembalikan').value = maxJumlah;

            // Reset inputs
            document.getElementById('radio_sisa').checked = true;
            toggleReturnInputs();

            // Reset photo & preview
            const input = document.getElementById('foto_pengembalian');
            if (input) input.value = '';
            const directInput = document.getElementById('returnDirectInput');
            if (directInput) directInput.value = '';
            cameraStates.return.hasPhoto = false;

            const previewWrap = document.getElementById('returnPreviewWrap');
            if (previewWrap) previewWrap.classList.add('hidden');

            const modal = document.getElementById('modalReturn');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Open live camera stream automatically
            startCamera('return');
        }

        function toggleReturnInputs() {
            const isHabis = document.getElementById('radio_habis').checked;
            const container = document.getElementById('return_details_container');
            const jumlahInput = document.getElementById('jumlah_dikembalikan');
            const kondisiInput = document.getElementById('kondisi_pengembalian');

            if (isHabis) {
                container.style.display = 'none';
                jumlahInput.required = false;
                kondisiInput.required = false;
            } else {
                container.style.display = 'block';
                jumlahInput.required = true;
                kondisiInput.required = true;
            }
        }

        function closeModal(modalId) {
            // Stop camera streams if closing camera modals
            if (modalId === 'modalPickup') stopCamera('pickup');
            if (modalId === 'modalReturn') stopCamera('return');

            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Close modal on backdrop click
        ['modalApprove', 'modalPickup', 'modalReturn', 'modalReschedule'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', function(e) {
                    if (e.target === this) closeModal(id);
                });
            }
        });

        // Close on Escape key and cleanup camera
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ['modalApprove', 'modalPickup', 'modalReturn', 'modalReschedule'].forEach(closeModal);
            }
        });

        // Ensure camera stops if user navigates away
        window.addEventListener('beforeunload', function() {
            stopCamera('pickup');
            stopCamera('return');
        });

        // Form submit validation: enforce photo taken directly from camera
        document.getElementById('formPickup')?.addEventListener('submit', function(e) {
            const input = document.getElementById('foto_pengambilan');
            if (!input || !input.files || input.files.length === 0) {
                e.preventDefault();
                alert('⚠️ Wajib mengambil foto langsung menggunakan kamera terlebih dahulu sebelum mengirim bukti!');
                return false;
            }
        });

        document.getElementById('formReturn')?.addEventListener('submit', function(e) {
            const input = document.getElementById('foto_pengembalian');
            if (!input || !input.files || input.files.length === 0) {
                e.preventDefault();
                alert('⚠️ Wajib mengambil foto kondisi barang langsung menggunakan kamera terlebih dahulu!');
                return false;
            }
        });

        // ── Table Filters ─────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const filterStatus = document.getElementById('filterStatus');
            const rows = () => document.querySelectorAll('#tableBody tr[data-status]');
            const filteredCountEl = document.getElementById('filteredCount');

            function applyFilters() {
                const term = searchInput.value.toLowerCase();
                const status = filterStatus.value;
                let visible = 0;

                rows().forEach(row => {
                    const matchSearch = !term || row.dataset.search.includes(term);
                    const matchStatus = !status || row.dataset.status === status;
                    const show = matchSearch && matchStatus;
                    row.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                filteredCountEl.textContent = term || status ? `Menampilkan ${visible} hasil` : '';
            }

            searchInput.addEventListener('input', applyFilters);
            filterStatus.addEventListener('change', applyFilters);
        });

        // ── Utility ──────────────────────────────────────────────────────────────────
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }
    </script>
@endpush
