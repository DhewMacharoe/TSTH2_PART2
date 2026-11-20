<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a
                href="{{ route('kepala_divisi.logbooks.index') }}"
                class="text-gray-500 hover:text-blue-600 transition"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
            </a>

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Logbook
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $logbook->logbook_type === 'independent' ? 'Periksa laporan pekerjaan mandiri karyawan.' : 'Periksa laporan pekerjaan dan progress karyawan.' }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3">
                    <p class="text-sm text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            {{-- Error --}}
            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3">
                    <p class="text-sm text-red-700">
                        {{ session('error') }}
                    </p>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3">
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Status --}}
            @php
                $statusClass = match ($logbook->status) {
                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                    'revision' => 'bg-red-50 text-red-700 border-red-200',
                    'approved' => 'bg-green-50 text-green-700 border-green-200',
                    'reviewed' => 'bg-green-50 text-green-700 border-green-200',
                    default => 'bg-gray-50 text-gray-600 border-gray-200',
                };

                $statusLabel = match ($logbook->status) {
                    'pending' => 'Menunggu Review',
                    'revision' => 'Perlu Revisi',
                    'approved' => 'Disetujui',
                    'reviewed' => 'Sudah Direview',
                    default => ucfirst($logbook->status ?? '-'),
                };
            @endphp


        {{-- Header Info --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                <div>
                    <p class="text-sm text-gray-500">
                        Logbook tanggal
                    </p>

                    <h1 class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $logbook->date?->format('d F Y') ?? '-' }}
                    </h1>

                    @if ($logbook->date)
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $logbook->date->format('H:i') }}
                        </p>
                    @endif
                </div>

                <span class="self-start inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium border {{ $statusClass }}">
                    {{ $statusLabel }}
                </span>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-6 pt-5 border-t border-gray-100">

                <div>
                    <p class="text-xs text-gray-500">Nama Karyawan</p>
                    <p class="text-sm font-medium text-gray-800 mt-1">
                        {{ $logbook->user->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Divisi</p>
                    <p class="text-sm text-gray-700 mt-1">
                        {{ $logbook->user->divisionRelation?->name ?? '-' }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Informasi Pekerjaan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Informasi Pekerjaan
            </h3>

            @if ($logbook->logbook_type === 'independent' || !$logbook->task)
                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-1">
                            Kegiatan yang Dilakukan
                        </p>

                        <p class="text-sm text-gray-600 whitespace-pre-line">
                            {{ $logbook->activity ?: 'Tidak ada deskripsi kegiatan.' }}
                        </p>
                    </div>

                    @if ($logbook->next_plan)
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700 mb-1">
                                Rencana Selanjutnya
                            </p>

                            <p class="text-sm text-gray-600 whitespace-pre-line">
                                {{ $logbook->next_plan }}
                            </p>
                        </div>
                    @endif
                </div>
            @else
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Nama Pekerjaan</p>
                        <p class="font-medium text-gray-800">
                            {{ $logbook->task->title ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Target Progres</p>
                        <p class="font-medium text-gray-800">
                            {{ $logbook->task->target_progress ?? 0 }}%
                        </p>
                    </div>
                </div>
            @endif
        </div>


        {{-- Progress (Hanya untuk pekerjaan ditugaskan) --}}
        @if (($logbook->logbook_type ?? 'assigned') === 'assigned' && $logbook->progress !== null)
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">

            <div class="flex items-center justify-between mb-3">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Progress Pekerjaan
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Progress yang dilaporkan oleh karyawan.
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
                        Progress dilaporkan:
                        {{ $logbook->progress }}%
                    </span>

                    <span>
                        Target:
                        {{ $logbook->task->target_progress }}%
                    </span>
                </div>

            @endif

        </div>
        @endif

{{-- DOKUMENTASI PEKERJAAN --}}
@php
    $docImages = $logbook->documentation_images;
    $docLinks = $logbook->documentation_links;
@endphp

@if (!empty($docImages) || !empty($docLinks))

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">

        <h3 class="font-semibold text-gray-800 mb-4">
            Dokumentasi Pekerjaan
        </h3>

        <div class="space-y-4">
            @if (!empty($docLinks))
                @foreach ($docLinks as $linkItem)
                    <div class="flex items-center justify-between p-4 rounded-xl border border-blue-200 bg-blue-50/70 hover:bg-blue-50 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-sm">
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

            @if (!empty($docImages))
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach ($docImages as $imgItem)
                        @php
                            $imageUrl = asset('storage/' . $imgItem);
                        @endphp

                        <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-50 aspect-video sm:aspect-square flex items-center justify-center relative group">
                            <img
                                src="{{ $imageUrl }}"
                                alt="Dokumentasi Pekerjaan"
                                class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition"
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
    </div>
@endif

        {{-- Catatan Review --}}

        @if ($logbook->review_note)

            <div
                class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6"
            >

                <div class="flex items-start gap-3">

                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-red-100 flex items-center justify-center">

                        <svg
                            class="w-5 h-5 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86l-7.5 13A2 2 0 004.53 20h14.94a2 2 0 001.74-3.14l-7.5-13a2 2 0 00-3.42 0z"
                            />
                        </svg>

                    </div>

                    <div class="flex-1">

                        <h3 class="font-semibold text-red-800">
                            Catatan Revisi
                        </h3>

                        <p class="text-sm text-red-700 mt-2 leading-6 whitespace-pre-line">
                            {{ $logbook->review_note }}
                        </p>

                        @if ($logbook->reviewer)

                            <div class="mt-4 pt-4 border-t border-red-200">

                                <p class="text-xs text-red-600">
                                    Diminta revisi oleh
                                </p>

                                <p class="text-sm font-medium text-red-800 mt-1">
                                    {{ $logbook->reviewer->name }}
                                </p>

                                @if ($logbook->reviewed_at)

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $logbook->reviewed_at->format('d M Y H:i') }}
                                    </p>

                                @endif

                            </div>

                        @endif

                        @if ($logbook->status === 'revision')

                            <div class="mt-4 rounded-lg bg-white/70 border border-red-200 px-4 py-3">

                                <p class="text-sm text-red-700">
                                    Logbook sedang menunggu perbaikan dan pengajuan ulang dari karyawan.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        @endif
{{-- Review Form --}}
@if ($logbook->status === 'pending')

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800">
                Review Logbook
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Tentukan apakah laporan pekerjaan dapat disetujui atau perlu diperbaiki.
            </p>
        </div>

        <form
            id="reviewForm"
            action="{{ route('kepala_divisi.logbooks.review', $logbook) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            {{-- Review Note --}}
            <div class="mb-6">

                <label
                    for="review_note"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Catatan Review
                </label>

                <textarea
                    name="review_note"
                    id="review_note"
                    rows="4"
                    placeholder="Berikan catatan atau masukan terhadap laporan..."
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                >{{ old('review_note') }}</textarea>

                <p class="text-xs text-gray-400 mt-2">
                    Catatan wajib diisi jika logbook dikembalikan untuk revisi.
                </p>

                @error('review_note')
                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Action --}}
            <div class="flex flex-col sm:flex-row justify-end gap-3">

                {{-- Request Revision --}}
                <button
                    type="button"
                    onclick="openRevisionModal()"
                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-red-300 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-medium transition"
                >
                    Minta Revisi
                </button>

                {{-- Approve --}}
                <button
                    type="button"
                    onclick="openApproveModal()"
                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition"
                >
                    Setujui Logbook
                </button>

            </div>

            {{-- Hidden Action --}}
            <input
                type="hidden"
                name="action"
                id="reviewAction"
                value=""
            >

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- APPROVE MODAL --}}
    {{-- ========================================================= --}}

    <div
        id="approveModal"
        class="fixed inset-0 z-50 hidden"
        aria-labelledby="approveModalTitle"
        role="dialog"
        aria-modal="true"
    >

        {{-- Backdrop --}}
        <div
            class="absolute inset-0 bg-black/40"
            onclick="closeApproveModal()"
        ></div>

        {{-- Modal --}}
        <div class="relative min-h-screen flex items-center justify-center px-4">

            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-200 p-6">

                <div class="flex items-start gap-4">

                    <div class="flex-shrink-0 w-11 h-11 rounded-full bg-green-100 flex items-center justify-center">

                        <svg
                            class="w-6 h-6 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                    </div>

                    <div class="flex-1">

                        <h3
                            id="approveModalTitle"
                            class="text-lg font-semibold text-gray-800"
                        >
                            Setujui Logbook?
                        </h3>

                        <p class="text-sm text-gray-500 mt-2 leading-6">
                            Setelah disetujui, laporan ini akan dianggap telah diverifikasi
                            oleh Kepala Divisi.
                        </p>

                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6">

                    <button
                        type="button"
                        onclick="closeApproveModal()"
                        class="px-4 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        onclick="submitReview('approve')"
                        class="px-4 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition"
                    >
                        Ya, Setujui
                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REVISION MODAL --}}
    {{-- ========================================================= --}}

    <div
        id="revisionModal"
        class="fixed inset-0 z-50 hidden"
        aria-labelledby="revisionModalTitle"
        role="dialog"
        aria-modal="true"
    >

        {{-- Backdrop --}}
        <div
            class="absolute inset-0 bg-black/40"
            onclick="closeRevisionModal()"
        ></div>

        {{-- Modal --}}
        <div class="relative min-h-screen flex items-center justify-center px-4">

            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-200 p-6">

                <div class="flex items-start gap-4">

                    <div class="flex-shrink-0 w-11 h-11 rounded-full bg-red-100 flex items-center justify-center">

                        <svg
                            class="w-6 h-6 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86l-7.5 13A2 2 0 004.53 20h14.94a2 2 0 001.74-3.14l-7.5-13a2 2 0 00-3.42 0z"
                            />
                        </svg>

                    </div>

                    <div class="flex-1">

                        <h3
                            id="revisionModalTitle"
                            class="text-lg font-semibold text-gray-800"
                        >
                            Minta Revisi?
                        </h3>

                        <p class="text-sm text-gray-500 mt-2 leading-6">
                            Logbook akan dikembalikan kepada karyawan untuk diperbaiki
                            berdasarkan catatan review yang diberikan.
                        </p>

                    </div>

                </div>

                {{-- Warning jika catatan kosong --}}
                <div
                    id="revisionNoteWarning"
                    class="hidden mt-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3"
                >
                    <p class="text-sm text-red-700">
                        Catatan revisi wajib diisi sebelum meminta revisi.
                    </p>
                </div>

                <div class="flex justify-end gap-3 mt-6">

                    <button
                        type="button"
                        onclick="closeRevisionModal()"
                        class="px-4 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        onclick="submitReview('revision')"
                        class="px-4 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition"
                    >
                        Ya, Minta Revisi
                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT REVIEW --}}
    {{-- ========================================================= --}}

    <script>
        function openApproveModal() {
            document
                .getElementById('approveModal')
                .classList
                .remove('hidden');
        }

        function closeApproveModal() {
            document
                .getElementById('approveModal')
                .classList
                .add('hidden');
        }

        function openRevisionModal() {
            const note = document
                .getElementById('review_note')
                .value
                .trim();

            const warning = document
                .getElementById('revisionNoteWarning');

            if (!note) {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }

            document
                .getElementById('revisionModal')
                .classList
                .remove('hidden');
        }

        function closeRevisionModal() {
            document
                .getElementById('revisionModal')
                .classList
                .add('hidden');

            document
                .getElementById('revisionNoteWarning')
                .classList
                .add('hidden');
        }

        function submitReview(action) {

            const form = document.getElementById('reviewForm');
            const actionInput = document.getElementById('reviewAction');
            const note = document
                .getElementById('review_note')
                .value
                .trim();

            if (action === 'revision' && !note) {

                document
                    .getElementById('revisionNoteWarning')
                    .classList
                    .remove('hidden');

                return;
            }

            actionInput.value = action;

            form.submit();
        }

        document.addEventListener('keydown', function (event) {

            if (event.key === 'Escape') {

                closeApproveModal();
                closeRevisionModal();

            }

        });
    </script>

@else

    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mb-6">

        <div class="flex items-center gap-3">

            <svg
                class="w-5 h-5 text-gray-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                />
            </svg>

            <p class="text-sm text-gray-600">
                Logbook ini sudah melalui proses review.
            </p>

        </div>

    </div>

@endif
        </div>
    </div>

</x-app-layout>