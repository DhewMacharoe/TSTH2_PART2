<x-app-layout>

<x-slot name="header">

    <div class="flex justify-between items-center">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Logbook
        </h2>

        <a
            href="{{ route('employee.logbooks.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition"
        >
            Kembali
        </a>

    </div>

</x-slot>


<div class="py-12">

    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">


        {{-- NOTIFIKASI --}}

        @if(session('success'))

            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 p-4 rounded-lg">
                {{ session('success') }}
            </div>

        @endif


        {{-- INFORMASI LOGBOOK --}}

        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

            <h3 class="text-lg font-semibold text-gray-800 mb-5">
                Informasi Logbook
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Tanggal --}}
                <div>
                    <p class="text-sm text-gray-500 mb-1">
                        Tanggal
                    </p>
                    <p class="font-semibold text-gray-800">
                        {{ $logbook->date?->format('d-m-Y H:i') ?? $logbook->date }}
                    </p>
                </div>

                {{-- Tipe Logbook --}}
                <div>
                    <p class="text-sm text-gray-500 mb-1">
                        Tipe Logbook
                    </p>
                    @if ($logbook->logbook_type === 'independent')
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            Pekerjaan Mandiri
                        </span>
                    @else
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                            Pekerjaan Ditugaskan
                        </span>
                    @endif
                </div>

                {{-- Status --}}
                <div>
                    <p class="text-sm text-gray-500 mb-1">
                        Status
                    </p>

                    @if($logbook->status === 'pending')
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                            Menunggu Review
                        </span>
                    @elseif($logbook->status === 'revision')
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">
                            Perlu Revisi
                        </span>
                    @elseif($logbook->status === 'approved')
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                            Disetujui
                        </span>
                    @elseif($logbook->status === 'reviewed')
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                            Sudah Direview
                        </span>
                    @else
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                            {{ ucfirst($logbook->status) }}
                        </span>
                    @endif
                </div>

            </div>

            @if ($logbook->task)
                <div class="mt-6 pt-5 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Pekerjaan</p>
                        <p class="font-medium text-gray-800 mt-1">
                            {{ $logbook->task->title }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Target Progres</p>
                        <p class="font-medium text-gray-800 mt-1">
                            {{ $logbook->task->target_progress }}%
                        </p>
                    </div>
                </div>
            @endif

        </div>

        {{-- PROGRESS PEKERJAAN (HANYA PEKERJAAN DITUGASKAN) --}}
        @if (($logbook->logbook_type ?? 'assigned') === 'assigned' && $logbook->progress !== null)
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Progress Pekerjaan
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Progress yang dilaporkan pada logbook ini.
                        </p>
                    </div>
                    <span class="text-2xl font-bold text-blue-600">
                        {{ $logbook->progress }}%
                    </span>
                </div>

                <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div
                        class="h-full bg-blue-600 rounded-full transition-all"
                        style="width: {{ min(max((float) $logbook->progress, 0), 100) }}%"
                    ></div>
                </div>

                @if ($logbook->task)
                    <div class="flex justify-between mt-2 text-xs text-gray-500">
                        <span>
                            Progress dilaporkan: {{ $logbook->progress }}%
                        </span>
                        <span>
                            Target: {{ $logbook->task->target_progress }}%
                        </span>
                    </div>
                @endif
            </div>
        @endif


        {{-- KEGIATAN --}}

        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Kegiatan yang Dilakukan
            </h3>

            <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                {{ $logbook->activity }}
            </div>

        </div>


        {{-- KENDALA --}}

        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Kendala
            </h3>

            @if($logbook->obstacle)

                <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $logbook->obstacle }}
                </div>

            @else

                <p class="text-gray-500">
                    Tidak ada kendala.
                </p>

            @endif

        </div>


        {{-- DOKUMENTASI PEKERJAAN --}}
        @php
            $docImages = $logbook->documentation_images;
            $docLinks = $logbook->documentation_links;
        @endphp

        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Dokumentasi Pekerjaan
            </h3>

            @if (!empty($docImages) || !empty($docLinks))
                <div class="space-y-4">
                    {{-- LINK GOOGLE DRIVE / DOKUMEN --}}
                    @if (!empty($docLinks))
                        @foreach ($docLinks as $linkItem)
                            <div class="flex items-center justify-between p-4 rounded-xl border border-blue-200 bg-blue-50/70 hover:bg-blue-50 transition">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-sm">
                                        {{-- Drive / Cloud Icon --}}
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold uppercase tracking-wider text-blue-900">
                                            Tautan Dokumen Pendukung {{ count($docLinks) > 1 ? '#' . $loop->iteration : '' }}
                                        </p>
                                        <p class="text-sm text-blue-700 truncate font-mono mt-0.5">
                                            {{ $linkItem }}
                                        </p>
                                    </div>
                                </div>

                                <a
                                    href="{{ $linkItem }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition shrink-0 ml-4"
                                >
                                    <span>Buka Tautan</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        @endforeach
                    @endif

                    {{-- FOTO / GAMBAR LANGSUNG --}}
                    @if (!empty($docImages))
                        <div class="@if(count($docImages) === 1) max-w-2xl @else grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 @endif">
                            @foreach ($docImages as $imgItem)
                                @php
                                    $imageUrl = asset('storage/' . $imgItem);
                                @endphp

                                <div class="rounded-xl overflow-hidden border border-gray-200 bg-white">
                                    <img
                                        src="{{ $imageUrl }}"
                                        alt="Dokumentasi Pekerjaan"
                                        class="block w-full {{ count($docImages) === 1 ? 'max-h-[550px]' : 'h-40 sm:h-48' }} object-contain sm:object-cover mx-auto cursor-pointer hover:opacity-95 transition"
                                        onclick="openImageModal(this.src)"
                                    >
                                </div>
                            @endforeach
                        </div>

                        {{-- MODAL MEMPERBESAR FOTO (FULLSCREEN) --}}
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
                    @endif
                </div>
            @else
                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                    <p class="text-sm text-gray-500">
                        Belum ada dokumentasi (foto atau link tautan) pada logbook ini.
                    </p>
                </div>
            @endif
        </div>

        {{-- RENCANA SELANJUTNYA --}}

        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Rencana Selanjutnya
            </h3>

            @if($logbook->next_plan)

                <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $logbook->next_plan }}
                </div>

            @else

                <p class="text-gray-500">
                    Belum ada rencana selanjutnya.
                </p>

            @endif

        </div>


        {{-- HASIL REVIEW KEPALA DIVISI --}}

        @if($logbook->status === 'revision' || $logbook->status === 'approved')

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Hasil Review Kepala Divisi
                </h3>

                {{-- INFORMASI REVIEWER --}}

                @if($logbook->reviewer)

                    <div class="mb-5">

                        <p class="text-sm text-gray-500 mb-1">
                            Direview oleh
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $logbook->reviewer->name }}
                        </p>

                        @if($logbook->reviewed_at)

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $logbook->reviewed_at->format('d-m-Y H:i') }}
                            </p>

                        @endif

                    </div>

                @endif


                {{-- CATATAN REVIEW --}}

                @if($logbook->review_note)

                    <div
                        class="border border-red-200 bg-red-50 rounded-lg p-4"
                    >

                        <p class="font-semibold text-red-800 mb-2">
                            Catatan Revisi
                        </p>

                        <p class="text-red-700 whitespace-pre-line leading-relaxed">
                            {{ $logbook->review_note }}
                        </p>

                    </div>

                @else

                    <div
                        class="border border-green-200 bg-green-50 rounded-lg p-4"
                    >

                        <p class="font-semibold text-green-800">
                            Logbook telah disetujui oleh Kepala Divisi.
                        </p>

                    </div>

                @endif

            </div>

        @endif

    </div>

</div>

</x-app-layout>
