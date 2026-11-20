<x-app-layout>

    {{-- ===================== STYLES ===================== --}}
    <style>
        .stat-card {
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px -4px rgba(0,0,0,0.10);
        }
        .logbook-row:hover {
            background: #f8f7ff;
        }
        .badge-approved {
            background: #d1fae5; color: #065f46; font-weight: 600;
        }
        .badge-rejected {
            background: #fee2e2; color: #991b1b; font-weight: 600;
        }
        .modal-backdrop {
            animation: fadeIn 0.2s ease;
        }
        .modal-box {
            animation: slideUp 0.25s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; } to { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .keyword-chip {
            display: inline-block;
            background: #ede9fe;
            color: #6d28d9;
            border-radius: 9999px;
            padding: 2px 12px;
            font-size: 0.78rem;
            font-weight: 600;
            margin: 2px;
        }
        .progress-bar {
            height: 6px;
            border-radius: 9999px;
            background: #e5e7eb;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 9999px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            transition: width 0.6s cubic-bezier(.4,0,.2,1);
        }
    </style>


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ===================== HEADER ===================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

            <div>
                <a href="{{ route('leader.employees.logbooks', $user) }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 transition mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Detail Karyawan
                </a>

                <h1 class="text-2xl font-bold text-gray-900">
                    Ringkasan Bulanan Logbook
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Dibuat otomatis dari data logbook — tanpa AI.
                </p>
            </div>

            {{-- FILTER BULAN --}}
            <form method="GET" action="{{ route('leader.employees.monthly-summary', $user) }}"
                  class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Pilih Bulan:</label>
                <select name="month" onchange="this.form.submit()"
                        class="rounded-xl border border-gray-300 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm">
                    @if($availableMonths->isEmpty())
                        <option value="{{ $monthParam }}">
                            {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                        </option>
                    @else
                        @foreach($availableMonths as $m)
                            @php
                                [$my, $mm] = explode('-', $m);
                                $label = \Carbon\Carbon::create((int)$my, (int)$mm, 1)->translatedFormat('F Y');
                            @endphp
                            <option value="{{ $m }}" @selected($m === $monthParam)>{{ $label }}</option>
                        @endforeach
                    @endif
                </select>
            </form>

        </div>


        {{-- ===================== INFO KARYAWAN ===================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-6 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-base font-bold text-gray-900 truncate">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->division ?? 'Tidak ada divisi' }}</p>
            </div>
            <div class="text-right hidden sm:block">
                <p class="text-xs text-gray-400">Periode</p>
                <p class="text-sm font-semibold text-gray-700">
                    {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                </p>
            </div>
        </div>


        {{-- ===================== STATISTIK ===================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">

            <div class="stat-card bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Total Logbook</p>
                <p class="text-3xl font-bold text-gray-900">{{ $total }}</p>
                <p class="text-xs text-gray-400 mt-1">dari {{ $workdaysInMonth }} hari kerja</p>
            </div>

            <div class="stat-card bg-green-50 border border-green-100 rounded-2xl p-5 shadow-sm">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Approved</p>
                <p class="text-3xl font-bold text-green-600">{{ $approved }}</p>
                <p class="text-xs text-green-500 mt-1">logbook disetujui</p>
            </div>

            <div class="stat-card bg-red-50 border border-red-100 rounded-2xl p-5 shadow-sm">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Rejected</p>
                <p class="text-3xl font-bold text-red-600">{{ $rejected }}</p>
                <p class="text-xs text-red-400 mt-1">logbook ditolak</p>
            </div>

            <div class="stat-card bg-indigo-50 border border-indigo-100 rounded-2xl p-5 shadow-sm">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Approval Rate</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $approvalRate }}%</p>
                <div class="progress-bar mt-2">
                    <div class="progress-fill" style="width: {{ $approvalRate }}%; background: linear-gradient(90deg,#4f46e5,#7c3aed)"></div>
                </div>
            </div>

            <div class="stat-card bg-violet-50 border border-violet-100 rounded-2xl p-5 shadow-sm">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Rata-rata Progress</p>
                <p class="text-3xl font-bold text-violet-600">{{ $avgProgress }}%</p>
                <div class="progress-bar mt-2">
                    <div class="progress-fill" style="width: {{ $avgProgress }}%"></div>
                </div>
            </div>

        </div>


        {{-- ===================== RINGKASAN OTOMATIS ===================== --}}
        @if($total > 0 && !empty($autoSummary))
        <div class="bg-white border border-indigo-200 rounded-2xl overflow-hidden shadow-sm mb-6">

            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-violet-50 border-b border-indigo-100 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900">Ringkasan Otomatis</h2>
                </div>
            </div>

            <div class="p-6 space-y-5">

                {{-- Gambaran Umum --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">1</span>
                        Gambaran Umum
                    </h3>
                    <ul class="space-y-1.5 pl-7">
                        @foreach($autoSummary['gambaran_umum'] as $item)
                            <li class="text-sm text-gray-700 leading-relaxed flex gap-2">
                                <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if(!empty($autoSummary['aktivitas_utama']))
                <div>
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-bold">2</span>
                        Contoh Aktivitas Utama
                    </h3>
                    <ul class="space-y-1.5 pl-7">
                        @foreach($autoSummary['aktivitas_utama'] as $item)
                            <li class="text-sm text-gray-700 leading-relaxed flex gap-2">
                                <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($autoSummary['kata_kunci']))
                <div>
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center text-xs font-bold">3</span>
                        Kata Kunci Dominan
                    </h3>
                    <div class="pl-7 flex flex-wrap gap-1">
                        @foreach($autoSummary['kata_kunci'] as $kw)
                            <span class="keyword-chip">{{ $kw }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div>
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">4</span>
                        Progress Pekerjaan
                    </h3>
                    <p class="text-sm text-gray-700 leading-relaxed pl-7">{{ $autoSummary['progress_note'] }}</p>
                </div>

                @if(!empty($autoSummary['kendala']))
                <div>
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center text-xs font-bold">5</span>
                        Kendala yang Tercatat ({{ $obstacleCount }}x)
                    </h3>
                    <ul class="space-y-1.5 pl-7">
                        @foreach($autoSummary['kendala'] as $item)
                            <li class="text-sm text-gray-700 leading-relaxed flex gap-2">
                                <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-orange-400 flex-shrink-0"></span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @else
                <div>
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center text-xs font-bold">5</span>
                        Kendala
                    </h3>
                    <p class="text-sm text-gray-500 pl-7">Tidak ada kendala yang tercatat di bulan ini.</p>
                </div>
                @endif

                {{-- Catatan Pimpinan --}}
                @php
                    $isGood = str_contains($autoSummary['catatan_pimpinan'], 'baik');
                    $noteClass = $isGood
                        ? 'bg-green-50 border-green-200'
                        : 'bg-amber-50 border-amber-200';
                    $iconClass  = $isGood ? 'text-green-600' : 'text-amber-600';
                    $textClass  = $isGood ? 'text-green-700' : 'text-amber-700';
                @endphp
                <div class="mt-2 p-4 rounded-xl border {{ $noteClass }}">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-bold {{ $textClass }} mb-0.5">Catatan untuk Pimpinan</p>
                            <p class="text-sm {{ $textClass }}">{{ $autoSummary['catatan_pimpinan'] }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @endif


        {{-- ===================== TABEL LOGBOOK HARIAN ===================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">
                    Daftar Logbook Harian
                    <span class="ml-2 text-sm font-normal text-gray-400">({{ $total }} entri)</span>
                </h2>
                <span class="text-xs text-gray-400 hidden sm:block">
                    Klik <span class="font-semibold text-indigo-600">Lihat Detail</span> untuk melihat isi lengkap per hari
                </span>
            </div>

            @if($logbooks->isEmpty())
                <div class="py-16 text-center">
                    <div class="text-4xl mb-3">📋</div>
                    <p class="font-semibold text-gray-700">Tidak ada logbook di bulan ini</p>
                    <p class="text-sm text-gray-400 mt-1">Pilih bulan lain yang memiliki data logbook.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide w-32">Tanggal</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kegiatan</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide w-28">Progress</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide w-28">Status</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($logbooks as $logbook)
                            <tr class="logbook-row transition">

                                <td class="px-5 py-4 text-sm text-gray-600 whitespace-nowrap align-top">
                                    <p class="font-semibold text-gray-800">{{ $logbook->date?->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $logbook->date?->translatedFormat('l') }}</p>
                                </td>

                                <td class="px-5 py-4 align-top max-w-sm">
                                    <p class="text-sm text-gray-700 line-clamp-3 leading-relaxed">
                                        {{ $logbook->activity }}
                                    </p>
                                    @if($logbook->obstacle)
                                    <p class="text-xs text-orange-500 mt-1 line-clamp-1">
                                        ⚠ {{ Str::limit($logbook->obstacle, 70) }}
                                    </p>
                                    @endif
                                </td>

                                <td class="px-5 py-4 align-top text-center">
                                    @if($logbook->progress !== null)
                                        <p class="text-sm font-bold text-gray-800">{{ $logbook->progress }}%</p>
                                        <div class="progress-bar mt-1.5 mx-auto w-16">
                                            <div class="progress-fill" style="width:{{ $logbook->progress }}%"></div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 align-top text-center">
                                    @if($logbook->status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs badge-approved">
                                            ✓ Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs badge-rejected">
                                            ✗ Rejected
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 align-top text-center">
                                    <button
                                        onclick="openModal({{ $logbook->id }})"
                                        id="btn-detail-{{ $logbook->id }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Detail
                                    </button>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>

    </div>


    {{-- ===================== MODAL DETAIL ===================== --}}
    <div id="detail-modal"
         class="modal-backdrop fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4"
         onclick="closeModalOnBackdrop(event)">

        <div class="modal-box bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                <div>
                    <h3 class="font-bold text-gray-900" id="modal-title">Detail Logbook</h3>
                    <p class="text-xs text-gray-400 mt-0.5" id="modal-date"></p>
                </div>
                <button onclick="closeModal()"
                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 px-6 py-5 space-y-5" id="modal-body">

                <div class="flex items-center gap-3 flex-wrap">
                    <span id="modal-status-badge" class="px-3 py-1 rounded-full text-xs font-bold"></span>
                    <span class="text-sm text-gray-500">Progress: <strong id="modal-progress" class="text-gray-800"></strong></span>
                </div>

                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Kegiatan</p>
                    <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line bg-gray-50 rounded-xl p-4 border border-gray-100"
                       id="modal-activity"></p>
                </div>

                <div id="modal-obstacle-section" class="hidden">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Kendala</p>
                    <p class="text-sm text-orange-700 leading-relaxed whitespace-pre-line bg-orange-50 rounded-xl p-4 border border-orange-100"
                       id="modal-obstacle"></p>
                </div>

                <div id="modal-plan-section" class="hidden">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Rencana Berikutnya</p>
                    <p class="text-sm text-blue-700 leading-relaxed whitespace-pre-line bg-blue-50 rounded-xl p-4 border border-blue-100"
                       id="modal-plan"></p>
                </div>

                <div id="modal-image-section" class="hidden">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Foto Dokumentasi</p>
                    <img id="modal-image" src="" alt="Dokumentasi"
                         class="rounded-xl border border-gray-200 max-h-64 object-cover"/>
                </div>

                <div id="modal-link-section" class="hidden">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Tautan Dokumen / Google Drive</p>
                    <a id="modal-link" href="#" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-semibold border border-blue-200 transition">
                        <span>Buka Tautan Google Drive ↗</span>
                    </a>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Informasi Review</p>
                    <div class="space-y-1 text-sm text-gray-600">
                        <p>Reviewer: <strong id="modal-reviewer" class="text-gray-800"></strong></p>
                        <p>Tanggal Review: <span id="modal-reviewed-at" class="text-gray-700"></span></p>
                        <div id="modal-note-section" class="hidden">
                            <p class="mt-2 text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Catatan Review</p>
                            <p id="modal-note" class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 text-sm text-yellow-800 leading-relaxed"></p>
                        </div>
                    </div>
                </div>

                <div id="modal-comments-section" class="hidden">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Komentar</p>
                    <div id="modal-comments" class="space-y-2"></div>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end flex-shrink-0">
                <button onclick="closeModal()"
                        class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm font-semibold text-gray-700 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>


    {{-- ===================== SCRIPT ===================== --}}
    <script>
        const LOGBOOKS_DATA = @json($logbooksJson);

        function openModal(id) {
            const data = LOGBOOKS_DATA[id];
            if (!data) return;

            document.getElementById('modal-title').textContent = 'Detail Logbook — ' + data.date;
            document.getElementById('modal-date').textContent  = (data.day ?? '') + ', ' + (data.date ?? '');

            const badge = document.getElementById('modal-status-badge');
            if (data.status === 'approved') {
                badge.textContent = '✓ Approved';
                badge.className   = 'px-3 py-1 rounded-full text-xs font-bold badge-approved';
            } else {
                badge.textContent = '✗ Rejected';
                badge.className   = 'px-3 py-1 rounded-full text-xs font-bold badge-rejected';
            }

            document.getElementById('modal-progress').textContent = (data.progress ?? 0) + '%';
            document.getElementById('modal-activity').textContent = data.activity;

            toggleSection('modal-obstacle-section', data.obstacle, 'modal-obstacle');
            toggleSection('modal-plan-section',     data.next_plan, 'modal-plan');

            const imgSec = document.getElementById('modal-image-section');
            if (data.documentation_image) {
                document.getElementById('modal-image').src = data.documentation_image;
                imgSec.classList.remove('hidden');
            } else {
                imgSec.classList.add('hidden');
            }

            const linkSec = document.getElementById('modal-link-section');
            if (data.documentation_link) {
                document.getElementById('modal-link').href = data.documentation_link;
                linkSec.classList.remove('hidden');
            } else {
                linkSec.classList.add('hidden');
            }

            document.getElementById('modal-reviewer').textContent    = data.reviewer    ?? '-';
            document.getElementById('modal-reviewed-at').textContent = data.reviewed_at ?? '-';
            toggleSection('modal-note-section', data.review_note, 'modal-note');

            const commentsSec = document.getElementById('modal-comments-section');
            const commentsEl  = document.getElementById('modal-comments');
            if (data.comments && data.comments.length > 0) {
                commentsEl.innerHTML = data.comments.map(c => `
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold text-gray-700">${escHtml(c.user)}</span>
                            <span class="text-xs text-gray-400">${escHtml(c.created ?? '')}</span>
                        </div>
                        <p class="text-sm text-gray-600">${escHtml(c.body)}</p>
                    </div>
                `).join('');
                commentsSec.classList.remove('hidden');
            } else {
                commentsSec.classList.add('hidden');
            }

            document.getElementById('detail-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function toggleSection(sectionId, value, fieldId) {
            const sec = document.getElementById(sectionId);
            if (value) {
                document.getElementById(fieldId).textContent = value;
                sec.classList.remove('hidden');
            } else {
                sec.classList.add('hidden');
            }
        }

        function closeModal() {
            document.getElementById('detail-modal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function closeModalOnBackdrop(e) {
            if (e.target === document.getElementById('detail-modal')) closeModal();
        }

        function escHtml(text) {
            const d = document.createElement('div');
            d.textContent = String(text ?? '');
            return d.innerHTML;
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    </script>

</x-app-layout>
