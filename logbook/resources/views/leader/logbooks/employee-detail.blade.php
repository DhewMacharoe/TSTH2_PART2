<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Detail Logbook — {{ $user->name }}</h2>
            <a href="{{ route('leader.logbooks.index') }}"
               class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- KARTU STATISTIK --}}
            {{-- ============================================================ --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">

            {{-- Total --}}
            <div class="bg-white shadow-sm rounded-2xl p-5 border border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">
                    Total Logbook
                </p>
                <p class="text-3xl font-bold text-gray-800">
                    {{ $total }}
                </p>
            </div>

            {{-- Approved --}}
            <div class="bg-green-50 shadow-sm rounded-2xl p-5 border border-green-100">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">
                    Approved
                </p>
                <p class="text-3xl font-bold text-green-600">
                    {{ $approved }}
                </p>
            </div>

            {{-- Rejected --}}
            <div class="bg-red-50 shadow-sm rounded-2xl p-5 border border-red-100">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">
                    Rejected
                </p>
                <p class="text-3xl font-bold text-red-600">
                    {{ $rejected }}
                </p>
            </div>

        {{-- Approval Rate --}}
        @php
            $reviewedCount = $approved + $rejected;
            $approvalRate = $reviewedCount > 0
                ? round(($approved / $reviewedCount) * 100)
                : 0;
        @endphp

        <div class="bg-indigo-50 shadow-sm rounded-2xl p-5 border border-indigo-100">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">
                Approval Rate
            </p>

            <p class="text-3xl font-bold text-indigo-600">
                {{ $approvalRate }}%
            </p>

            <div class="mt-2 h-1.5 bg-indigo-200 rounded-full overflow-hidden">
                <div
                    class="h-full bg-indigo-500 rounded-full"
                    style="width: {{ $approvalRate }}%"
                ></div>
            </div>
        </div>
            {{-- Average Progress --}}
            <div class="bg-blue-50 shadow-sm rounded-2xl p-5 border border-blue-100">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">
                    Rata-rata Progress
                </p>

                <p class="text-3xl font-bold text-blue-600">
                    {{ $avgProgress }}%
                </p>

                <div class="mt-2 h-1.5 bg-blue-200 rounded-full overflow-hidden">
                    <div
                        class="h-full bg-blue-500 rounded-full"
                        style="width: {{ $avgProgress }}%"
                    ></div>
                </div>
            </div>

        </div>
            {{-- ============================================================ --}}
            {{-- PANEL RINGKASAN AI --}}
            {{-- ============================================================ --}}
        <div class="bg-gradient-to-br from-purple-50 via-white to-blue-50
                    border border-purple-200 rounded-2xl shadow-sm overflow-hidden">

            <div class="p-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">

                    {{-- Informasi --}}
                    <div class="flex items-center gap-4">

                        <div class="w-11 h-11 rounded-xl
                                    bg-gradient-to-br from-purple-500 to-blue-500
                                    flex items-center justify-center
                                    text-white text-xl shadow-sm flex-shrink-0">
                            ✦
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-800">
                                Ringkasan AI Karyawan
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Analisis keseluruhan berdasarkan
                                {{ $total }} logbook yang telah direview.
                            </p>
                        </div>

                    </div>


                    {{-- Tombol Ringkasan Bulanan --}}
                    <a
                        href="{{ route('leader.employees.monthly-summary', $user) }}"
                        class="inline-flex items-center justify-center gap-2
                            px-5 py-2.5
                            bg-white border border-indigo-300
                            hover:bg-indigo-50
                            text-indigo-600 text-sm font-semibold
                            rounded-xl shadow-sm transition
                            whitespace-nowrap"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Ringkasan Bulanan
                    </a>

                </div>

            </div>

            {{-- ============================================================ --}}
            {{-- TABEL LOGBOOK --}}
            {{-- ============================================================ --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-x-auto">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Daftar Logbook ({{ $total }})</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Periode</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kegiatan</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Dokumentasi</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Reviewer</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logbooks as $logbook)
                            <tr class="align-top hover:bg-gray-50 transition">
                                <td class="px-5 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ $logbook->date?->format('d M Y') }}<br>
                                    <span class="text-xs text-gray-400">{{ $logbook->date?->format('H:i') }}</span>
                                </td>
                                <td class="px-5 py-4 max-w-sm">
                                    <p class="text-sm text-gray-800 whitespace-pre-line line-clamp-4">{{ $logbook->activity }}</p>
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
                                                         onclick="openImageModal('{{ $imgSrc }}')">
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
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">✓ Approved</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">✗ Rejected</span>
                                    @endif
                                    @if($logbook->progress !== null)
                                        <div class="text-xs text-gray-400 mt-1">{{ $logbook->progress }}%</div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $logbook->reviewer?->name ?: '-' }}
                                    @if($logbook->review_note)
                                        <p class="text-xs text-gray-400 mt-1">{{ Str::limit($logbook->review_note, 60) }}</p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <p class="text-gray-400 text-sm">Belum ada logbook yang direview.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- MODAL PREVIEW GAMBAR (FULLSCREEN) --}}
    <div
        id="imageModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 p-4"
        onclick="closeImageModal()"
    >
        <button
            type="button"
            onclick="closeImageModal()"
            class="fixed top-4 right-4 z-50 w-10 h-10 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center text-2xl transition"
            aria-label="Tutup"
        >
            &times;
        </button>
        <img
            id="modalImage"
            src=""
            alt="Dokumentasi Pekerjaan"
            class="max-w-full max-h-screen object-contain"
            onclick="event.stopPropagation()"
        >
    </div>

    <script>
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
