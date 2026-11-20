<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                Logbook — {{ $user->name }}
            </h2>
            <a href="{{ route('supervisor.logbooks.index') }}"
               class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- FLASH MESSAGE --}}
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- ============================================================ --}}
            {{-- PANEL STATISTIK VISUAL --}}
            {{-- ============================================================ --}}
            @php
                $pctApproved = $total > 0 ? round(($approved / $total) * 100, 1) : 0;
                $pctRejected = $total > 0 ? round(($rejected / $total) * 100, 1) : 0;
                $pctPending  = $total > 0 ? round(($pending  / $total) * 100, 1) : 0;
                // Radius lingkaran SVG
                $r = 44; $circ = round(2 * 3.14159 * $r, 2);
                $dashApproved = round($pctApproved / 100 * $circ, 2);
                $dashRejected = round($pctRejected / 100 * $circ, 2);
                $offsetApproved = 0;
                $offsetRejected = $circ - $dashApproved;
                $offsetPending  = $circ - $dashApproved - $dashRejected;
            @endphp

            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">

                {{-- JUDUL PANEL --}}
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-semibold text-gray-800">Statistik Review Logbook</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $user->name }} · {{ $user->division ?: '-' }}</p>
                    </div>
                    <span class="text-xs text-gray-400">Total: <span class="font-bold text-gray-700">{{ $total }}</span> logbook</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">

                    {{-- ── LINGKARAN PROGRESS (Donut) ── --}}
                    <div class="flex flex-col items-center justify-center">
                        <div class="relative w-36 h-36">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                {{-- Track --}}
                                <circle cx="50" cy="50" r="{{ $r }}" fill="none" stroke="#f3f4f6" stroke-width="12"/>
                                {{-- Pending (kuning) --}}
                                @if($pctPending > 0)
                                <circle cx="50" cy="50" r="{{ $r }}" fill="none"
                                    stroke="#fbbf24" stroke-width="12"
                                    stroke-dasharray="{{ round($pctPending/100*$circ,2) }} {{ $circ }}"
                                    stroke-dashoffset="{{ -1 * ($dashApproved + $dashRejected) }}"
                                    stroke-linecap="butt"/>
                                @endif
                                {{-- Rejected (merah) --}}
                                @if($pctRejected > 0)
                                <circle cx="50" cy="50" r="{{ $r }}" fill="none"
                                    stroke="#ef4444" stroke-width="12"
                                    stroke-dasharray="{{ $dashRejected }} {{ $circ }}"
                                    stroke-dashoffset="{{ -1 * $dashApproved }}"
                                    stroke-linecap="butt"/>
                                @endif
                                {{-- Approved (hijau) --}}
                                @if($pctApproved > 0)
                                <circle cx="50" cy="50" r="{{ $r }}" fill="none"
                                    stroke="#22c55e" stroke-width="12"
                                    stroke-dasharray="{{ $dashApproved }} {{ $circ }}"
                                    stroke-dashoffset="0"
                                    stroke-linecap="butt"/>
                                @endif
                            </svg>
                            {{-- Teks tengah --}}
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-2xl font-bold text-gray-800">{{ $progress }}%</span>
                                <span class="text-xs text-gray-400">Progress</span>
                            </div>
                        </div>
                        {{-- Legenda --}}
                        <div class="flex gap-4 mt-3 text-xs text-gray-600">
                            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span>Approved</span>
                            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>Rejected</span>
                            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400 inline-block"></span>Pending</span>
                        </div>
                    </div>

                    {{-- ── KARTU ANGKA ── --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-green-50 border border-green-100 p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-green-500 text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-green-600">{{ $approved }}</p>
                                <p class="text-xs text-gray-500">Approved<br><span class="font-semibold text-green-600">{{ $pctApproved }}%</span></p>
                            </div>
                        </div>
                        <div class="rounded-xl bg-red-50 border border-red-100 p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-red-500 text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-red-600">{{ $rejected }}</p>
                                <p class="text-xs text-gray-500">Rejected<br><span class="font-semibold text-red-600">{{ $pctRejected }}%</span></p>
                            </div>
                        </div>
                        <div class="rounded-xl bg-yellow-50 border border-yellow-100 p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-yellow-400 text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-yellow-600">{{ $pending }}</p>
                                <p class="text-xs text-gray-500">Pending<br><span class="font-semibold text-yellow-600">{{ $pctPending }}%</span></p>
                            </div>
                        </div>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-400 text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-700">{{ $total }}</p>
                                <p class="text-xs text-gray-500">Total Logbook<br>
                                    @if($total > 0)<span class="font-semibold text-gray-600">1 logbook = {{ round(100/$total, 1) }}%</span>@endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ── DIAGRAM BATANG BERTUMPUK (Stacked Horizontal) ── --}}
                    <div class="space-y-4">
                        <p class="text-sm font-semibold text-gray-700">Distribusi Status</p>

                        {{-- Approved --}}
                        <div>
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span class="flex items-center gap-1.5 font-medium text-green-700">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Approved
                                </span>
                                <span class="font-bold text-green-600">{{ $approved }} <span class="text-gray-400 font-normal">({{ $pctApproved }}%)</span></span>
                            </div>
                            <div class="h-4 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full transition-all duration-700"
                                     style="width: {{ $pctApproved }}%"></div>
                            </div>
                        </div>

                        {{-- Rejected --}}
                        <div>
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span class="flex items-center gap-1.5 font-medium text-red-700">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span> Rejected
                                </span>
                                <span class="font-bold text-red-600">{{ $rejected }} <span class="text-gray-400 font-normal">({{ $pctRejected }}%)</span></span>
                            </div>
                            <div class="h-4 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-red-400 to-red-600 rounded-full transition-all duration-700"
                                     style="width: {{ $pctRejected }}%"></div>
                            </div>
                        </div>

                        {{-- Pending --}}
                        <div>
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span class="flex items-center gap-1.5 font-medium text-yellow-700">
                                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span> Pending
                                </span>
                                <span class="font-bold text-yellow-600">{{ $pending }} <span class="text-gray-400 font-normal">({{ $pctPending }}%)</span></span>
                            </div>
                            <div class="h-4 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-yellow-300 to-yellow-500 rounded-full transition-all duration-700"
                                     style="width: {{ $pctPending }}%"></div>
                            </div>
                        </div>

                        {{-- Bar Gabungan --}}
                        <div class="mt-2">
                            <p class="text-xs text-gray-400 mb-1.5">Total keseluruhan</p>
                            <div class="h-5 rounded-full overflow-hidden flex gap-0.5 bg-gray-100">
                                @if($pctApproved > 0)
                                    <div class="h-full bg-green-500 transition-all duration-700 flex items-center justify-center"
                                         style="width: {{ $pctApproved }}%"
                                         title="Approved: {{ $approved }}">
                                        @if($pctApproved > 8)<span class="text-white text-[9px] font-bold">{{ $approved }}</span>@endif
                                    </div>
                                @endif
                                @if($pctRejected > 0)
                                    <div class="h-full bg-red-500 transition-all duration-700 flex items-center justify-center"
                                         style="width: {{ $pctRejected }}%"
                                         title="Rejected: {{ $rejected }}">
                                        @if($pctRejected > 8)<span class="text-white text-[9px] font-bold">{{ $rejected }}</span>@endif
                                    </div>
                                @endif
                                @if($pctPending > 0)
                                    <div class="h-full bg-yellow-400 transition-all duration-700 flex items-center justify-center"
                                         style="width: {{ $pctPending }}%"
                                         title="Pending: {{ $pending }}">
                                        @if($pctPending > 8)<span class="text-white text-[9px] font-bold">{{ $pending }}</span>@endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- DIAGRAM BATANG PER LOGBOOK --}}
            {{-- ============================================================ --}}
            @if($total > 0)
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-semibold text-gray-800">Diagram Batang per Logbook</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Setiap batang mewakili 1 logbook — tinggi batang menunjukkan urutan</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-green-500 inline-block"></span> Approved</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-red-500 inline-block"></span> Rejected</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-yellow-400 inline-block"></span> Pending</span>
                    </div>
                </div>

                {{-- Container diagram --}}
                <div class="overflow-x-auto">
                    <div class="flex items-end gap-1.5 min-w-max px-2 pb-2" style="height: 160px;">
                        @foreach($logbooks->sortBy('date') as $i => $lb)
                            @php
                                $barColor = match($lb->status) {
                                    'approved' => 'bg-green-500 hover:bg-green-400',
                                    'rejected' => 'bg-red-500 hover:bg-red-400',
                                    default    => 'bg-yellow-400 hover:bg-yellow-300',
                                };
                                $borderColor = match($lb->status) {
                                    'approved' => 'border-green-600',
                                    'rejected' => 'border-red-600',
                                    default    => 'border-yellow-500',
                                };
                                // Tinggi batang = progress kalau ada, kalau pending = 30%
                                $barHeight = $lb->status === 'pending' ? 30 : max(15, (int) ($lb->progress ?? 0));
                            @endphp
                            <div class="relative group flex flex-col items-center" style="width: 28px;">
                                {{-- Tooltip --}}
                                <div class="absolute bottom-full mb-2 hidden group-hover:flex flex-col items-center z-10 pointer-events-none">
                                    <div class="bg-gray-800 text-white text-[10px] rounded-lg px-2.5 py-1.5 whitespace-nowrap shadow-lg text-center">
                                        <div class="font-semibold">{{ $lb->date?->format('d M Y') }}</div>
                                        <div>{{ ucfirst($lb->status) }}</div>
                                        @if($lb->status !== 'pending' && $lb->progress !== null)<div class="text-gray-300">{{ $lb->progress }}%</div>@endif
                                    </div>
                                    <div class="w-2 h-2 bg-gray-800 rotate-45 -mt-1"></div>
                                </div>
                                {{-- Batang --}}
                                <div
                                    class="{{ $barColor }} border-b-2 {{ $borderColor }} rounded-t-md cursor-pointer transition-all duration-200"
                                    style="width: 100%; height: {{ $barHeight }}%;"
                                ></div>
                                {{-- Label nomor --}}
                                <span class="text-[9px] text-gray-400 mt-1">{{ $loop->iteration }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Keterangan bawah --}}
                <p class="text-xs text-gray-400 text-center mt-2">Arahkan kursor ke batang untuk melihat detail tanggal dan status</p>
            </div>
            @endif

            {{-- INFO KARYAWAN --}}
            <div class="bg-white shadow-sm rounded-2xl p-5 border border-gray-100 flex flex-wrap gap-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Nama</p>
                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Email</p>
                    <p class="font-semibold text-gray-800">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Divisi</p>
                    <p class="font-semibold text-gray-800">{{ $user->division ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Progress Keseluruhan</p>
                    <p class="font-semibold text-gray-800">
                        {{ $approved }} approved / {{ $total }} logbook
                        @if($total > 0)
                            <span class="text-xs text-gray-500">(1 logbook = {{ round(100/$total, 1) }}%)</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- TABEL LOGBOOK --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kegiatan</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Dokumentasi</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide min-w-56">Aksi Review</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logbooks as $logbook)
                            <tr class="align-top hover:bg-gray-50 transition">
                                <td class="px-5 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ $logbook->date?->format('d M Y') }}<br>
                                    <span class="text-xs text-gray-400">{{ $logbook->date?->format('H:i') }}</span>
                                </td>
                                <td class="px-5 py-4 max-w-xs">
                                    <p class="text-sm text-gray-800 line-clamp-3">{{ $logbook->activity }}</p>
                                    @if($logbook->obstacle)
                                        <p class="text-xs text-orange-500 mt-1">⚠ {{ Str::limit($logbook->obstacle, 80) }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $rowImages = $logbook->documentation_images;
                                        $rowLinks = $logbook->documentation_links;
                                    @endphp
                                    <div class="space-y-1.5">
                                        @if(!empty($rowImages))
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($rowImages as $imgItem)
                                                    @php
                                                        $imgSrc = asset('storage/' . $imgItem);
                                                    @endphp
                                                    <img src="{{ $imgSrc }}"
                                                         alt="Dokumentasi"
                                                         class="w-14 h-14 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition"
                                                         onclick="openImageModal('{{ $imgSrc }}')"
                                                         title="Klik untuk memperbesar">
                                                @endforeach
                                            </div>
                                        @endif
                                        @if(!empty($rowLinks))
                                            <div class="flex flex-col gap-1">
                                                @foreach($rowLinks as $linkItem)
                                                    <a
                                                        href="{{ $linkItem }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium hover:underline truncate max-w-xs"
                                                        title="{{ $linkItem }}"
                                                    >
                                                        <span>Link {{ count($rowLinks) > 1 ? '#' . $loop->iteration : 'Drive' }} ↗</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if(empty($rowImages) && empty($rowLinks))
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($logbook->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            ✓ Approved
                                        </span>
                                    @elseif($logbook->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            ✗ Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            ⏳ Pending
                                        </span>
                                    @endif
                                    @if($logbook->review_note)
                                        <p class="text-xs text-gray-400 mt-1 max-w-28 mx-auto">{{ Str::limit($logbook->review_note, 50) }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <form method="POST" action="{{ route('supervisor.logbooks.review', $logbook) }}" class="space-y-2">
                                        @csrf
                                        @method('PUT')
                                        <input
                                            type="text"
                                            name="review_note"
                                            placeholder="Catatan (opsional)"
                                            value="{{ $logbook->review_note }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-blue-500 transition"
                                        >
                                        <div class="flex gap-2">
                                            <button
                                                type="button"
                                                class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition"
                                                onclick="openConfirmModal(this.closest('form'), 'approved', 'Approve logbook ini?')"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                Approve
                                            </button>
                                            <button
                                                type="button"
                                                class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition"
                                                onclick="openConfirmModal(this.closest('form'), 'rejected', 'Reject logbook ini?')"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Reject
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-gray-400 text-sm">Karyawan ini belum memiliki logbook.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- MODAL KONFIRMASI REVIEW --}}
    <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl shadow-lg p-6 max-w-sm w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Review</h3>
            <p id="confirmMessage" class="text-sm text-gray-600 mb-6"></p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                    Batal
                </button>
                <button type="button" id="confirmSubmitBtn" onclick="submitConfirm()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL PREVIEW GAMBAR (FULLSCREEN) --}}
    <div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 p-4" onclick="closeImageModal()">
        <button
            type="button"
            onclick="closeImageModal()"
            class="fixed top-4 right-4 z-50 w-10 h-10 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center text-2xl transition"
            aria-label="Tutup"
        >
            &times;
        </button>
        <img id="modalImage" src="" alt="Dokumentasi Pekerjaan" class="max-w-full max-h-screen object-contain" onclick="event.stopPropagation()">
    </div>

    <script>
        let pendingForm = null;
        let pendingDecision = null;

        function openConfirmModal(form, decision, message) {
            pendingForm = form;
            pendingDecision = decision;
            document.getElementById('confirmMessage').innerText = message;
            
            const submitBtn = document.getElementById('confirmSubmitBtn');
            if (decision === 'approved') {
                submitBtn.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition';
            } else {
                submitBtn.className = 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition';
            }

            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmModal').classList.add('flex');
        }

        function closeConfirmModal() {
            pendingForm = null;
            pendingDecision = null;
            document.getElementById('confirmModal').classList.add('hidden');
            document.getElementById('confirmModal').classList.remove('flex');
        }

        function submitConfirm() {
            if (pendingForm && pendingDecision) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'decision';
                input.value = pendingDecision;
                pendingForm.appendChild(input);
                
                pendingForm.submit();
            }
        }

        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            const modal = document.getElementById('imageModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('modalImage').src = '';
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</x-app-layout>
