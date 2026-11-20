<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800">
            Edit Logbook
        </h2>

    </x-slot>


    <div class="py-12">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <form
                    action="{{ route('employee.logbooks.update', $logbook) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf

                    @method('PUT')


                    <!-- TANGGAL -->

                    <div class="mb-4">

                        <label class="block mb-2">
                            Tanggal
                        </label>

                        <input
                            type="datetime-local"
                            name="date"
                            value="{{ old('date', $logbook->date->format('Y-m-d\TH:i')) }}"
                            class="w-full border-gray-300 rounded"
                        >

                        @error('date')

                            <p class="text-red-500 text-sm mt-1">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>


                    <!-- KEGIATAN -->

                    <div class="mb-4">

                        <label class="block mb-2">
                            Kegiatan
                        </label>

                        <textarea
                            name="activity"
                            rows="5"
                            class="w-full border-gray-300 rounded"
                        >{{ old('activity', $logbook->activity) }}</textarea>

                        @error('activity')

                            <p class="text-red-500 text-sm mt-1">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>

                    {{-- Progress Logbook (Hanya untuk pekerjaan ditugaskan) --}}
                    @if (($logbook->logbook_type ?? 'assigned') === 'assigned')
                    <div class="mb-6">
                        <label
                            for="progress"
                            class="block mb-2 text-sm font-medium text-gray-700"
                        >
                            Progress Pekerjaan
                        </label>

                        @php
                            $maxProgress = $logbook->task
                                ? (int) $logbook->task->target_progress
                                : 100;

                            $currentProgress = (int) old('progress', $logbook->progress ?? 0);
                            $currentProgress = min(max($currentProgress, 0), $maxProgress);
                        @endphp

                        <div class="flex items-center gap-4">
                            <input
                                type="range"
                                name="progress"
                                id="progress"
                                min="0"
                                max="{{ $maxProgress }}"
                                value="{{ $currentProgress }}"
                                required
                                class="w-full"
                                oninput="document.getElementById('progress_value').textContent = this.value + '%'"
                            >

                            <span
                                id="progress_value"
                                class="w-14 text-center font-semibold text-blue-600"
                            >
                                {{ $currentProgress }}%
                            </span>
                        </div>

                        <div class="flex justify-between mt-2">
                            <p class="text-xs text-gray-400">
                                Progress pekerjaan yang telah dicapai.
                            </p>

                            <p class="text-xs text-gray-400">
                                Maksimal {{ $maxProgress }}%
                            </p>
                        </div>

                        @error('progress')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    @endif

                    <!-- KENDALA -->

                    <div class="mb-4">

                        <label class="block mb-2">
                            Kendala
                        </label>

                        <textarea
                            name="obstacle"
                            rows="4"
                            class="w-full border-gray-300 rounded"
                        >{{ old('obstacle', $logbook->obstacle) }}</textarea>

                    </div>


                    {{-- DOKUMENTASI PEKERJAAN (GAMBAR & LINK DRIVE) --}}
                    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-xl p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <label class="block font-semibold text-gray-800 text-sm">
                                    Dokumentasi Pekerjaan
                                    <span class="text-sm font-normal text-gray-500">(Opsional)</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Unggah foto/gambar langsung dan/atau sertakan tautan Google Drive / dokumen online pendukung.
                                </p>
                            </div>
                        </div>

                        {{-- SUB-SECTION: FOTO DOKUMENTASI (BISA LEBIH DARI 1) --}}
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                1. Foto / Gambar Langsung
                            </label>

                            @php
                                $existingImages = $logbook->documentation_images;
                            @endphp

                            {{-- Hidden File Inputs & Hidden Remove Flag --}}
                            <input
                                type="file"
                                id="doc_image_input"
                                name="documentation_images[]"
                                accept="image/jpeg,image/png,image/webp"
                                multiple
                                class="hidden"
                            >

                            <input
                                type="file"
                                id="doc_camera_input"
                                accept="image/jpeg,image/png,image/webp"
                                capture="environment"
                                class="hidden"
                            >

                            <input
                                type="hidden"
                                name="remove_documentation_image"
                                id="remove_documentation_image"
                                value="0"
                            >

                            {{-- Area Upload (Kotak Dokumentasi Pekerjaan) --}}
                            <div
                                id="doc_upload_area"
                                onclick="document.getElementById('doc_image_input').click()"
                                class="relative border-2 border-dashed border-gray-300 rounded-xl p-5 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50/50 bg-white transition group"
                            >
                                {{-- Grid Preview Foto (menampilkan foto tersimpan atau foto lokal yang dipilih) --}}
                                <div
                                    id="doc_preview_grid"
                                    class="{{ empty($existingImages) ? 'hidden' : '' }} grid grid-cols-2 sm:grid-cols-3 gap-3 mb-3"
                                >
                                    @if (!empty($existingImages))
                                        @foreach ($existingImages as $imgItem)
                                            <div class="rounded-lg overflow-hidden border border-gray-200 bg-gray-50 h-28 sm:h-32 flex items-center justify-center">
                                                <img
                                                    src="{{ asset('storage/' . $imgItem) }}"
                                                    alt="Dokumentasi Tersimpan"
                                                    class="w-full h-full object-cover"
                                                >
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div id="doc_upload_placeholder" class="{{ !empty($existingImages) ? 'hidden' : '' }}">
                                    <svg
                                        class="w-10 h-10 mx-auto text-gray-300 group-hover:text-blue-500 transition mb-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>

                                    <p class="text-sm font-medium text-gray-600 group-hover:text-blue-600 transition">
                                        Klik untuk memilih foto dari galeri atau berkas
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Format: JPG, PNG, WebP (maks. 5 MB per foto)
                                    </p>
                                </div>

                                <p
                                    id="doc_file_name"
                                    class="{{ empty($existingImages) ? 'hidden' : '' }} text-xs text-gray-600 mt-2 font-medium"
                                >
                                    @if (!empty($existingImages))
                                        📎 {{ count($existingImages) }} foto tersimpan
                                    @endif
                                </p>
                            </div>

                            {{-- Tombol Aksi Foto --}}
                            <div class="flex gap-2 mt-2.5">
                                <button
                                    type="button"
                                    onclick="document.getElementById('doc_image_input').click()"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-medium rounded-lg transition"
                                >
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Galeri</span>
                                </button>

                                <button
                                    type="button"
                                    onclick="document.getElementById('doc_camera_input').click()"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition shadow-sm"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Kamera</span>
                                </button>

                                <button
                                    type="button"
                                    id="doc_remove_btn"
                                    onclick="removeDocPhotos()"
                                    class="{{ empty($existingImages) ? 'hidden' : '' }} px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg border border-red-200 transition"
                                    title="Hapus foto"
                                >
                                    Hapus
                                </button>
                            </div>

                            @error('documentation_images')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                            @error('documentation_images.*')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- PEMBATAS ATAU --}}
                        <div class="relative my-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-xs uppercase">
                                <span class="bg-gray-50 px-2 text-gray-400 font-semibold">DAN / ATAU</span>
                            </div>
                        </div>

                        {{-- SUB-SECTION: LINK GOOGLE DRIVE / DOKUMEN (BISA LEBIH DARI 1 BARIS) --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    2. Tautan Google Drive / Dokumen Pendukung
                                </label>
                            </div>

                            <p class="text-xs text-gray-500 mb-2">
                                Sertakan tautan Google Drive / dokumen pendukung (dapat lebih dari 1 tautan, masing-masing tautan pada 1 baris).
                            </p>

                            @php
                                $initialLinks = old('documentation_links');
                                if (!is_array($initialLinks) || empty($initialLinks)) {
                                    $initialLinks = $logbook->documentation_links;
                                    if (empty($initialLinks)) {
                                        $initialLinks = old('documentation_link') ? [old('documentation_link')] : ($logbook->documentation_link ? [$logbook->documentation_link] : ['']);
                                    }
                                }
                            @endphp

                            <div id="doc_links_container" class="space-y-2">
                                @foreach ($initialLinks as $linkVal)
                                    <div class="flex items-center gap-2 doc-link-row">
                                        <div class="relative flex-1 rounded-lg shadow-sm">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                            </div>
                                            <input
                                                type="url"
                                                name="documentation_links[]"
                                                value="{{ $linkVal }}"
                                                class="block w-full rounded-lg border-gray-300 pl-9 text-sm focus:border-blue-500 focus:ring-blue-500 bg-white"
                                                placeholder="https://drive.google.com/... atau tautan file/folder"
                                            >
                                        </div>

                                        <button
                                            type="button"
                                            onclick="removeDocLinkRow(this)"
                                            class="shrink-0 p-2 text-gray-400 hover:text-red-600 rounded-lg border border-gray-200 hover:bg-red-50 transition"
                                            title="Hapus baris tautan ini"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-2.5">
                                <button
                                    type="button"
                                    onclick="addDocLinkRow()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200 transition"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Tambah Baris Tautan</span>
                                </button>
                            </div>

                            @error('documentation_links')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                            @error('documentation_links.*')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- RENCANA SELANJUTNYA -->

                    <div class="mb-6">

                        <label class="block mb-2">
                            Rencana Selanjutnya
                        </label>

                        <textarea
                            name="next_plan"
                            rows="4"
                            class="w-full border-gray-300 rounded"
                        >{{ old('next_plan', $logbook->next_plan) }}</textarea>

                    </div>


                    <a
                        href="{{ route('employee.logbooks.index') }}"
                        class="bg-gray-500 text-white px-4 py-2 rounded"
                    >

                        Kembali

                    </a>


                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded"
                    >

                        Update Logbook

                    </button>


                </form>

            </div>

        </div>

    </div>



    {{-- JAVASCRIPT --}}
    <script>
    function showDocPreviews(files) {
        if (!files || files.length === 0) return;

        const grid = document.getElementById('doc_preview_grid');
        const placeholder = document.getElementById('doc_upload_placeholder');
        const fileName = document.getElementById('doc_file_name');
        const removeBtn = document.getElementById('doc_remove_btn');
        const removeInput = document.getElementById('remove_documentation_image');

        grid.innerHTML = '';
        let validFiles = [];

        Array.from(files).forEach((file) => {
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                return;
            }
            validFiles.push(file);

            const div = document.createElement('div');
            div.className = 'rounded-lg overflow-hidden border border-gray-200 bg-gray-50 h-28 sm:h-32 flex items-center justify-center';
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;
            img.className = 'w-full h-full object-cover';
            div.appendChild(img);
            grid.appendChild(div);
        });

        if (validFiles.length > 0) {
            grid.classList.remove('hidden');
            placeholder.classList.add('hidden');
            fileName.textContent = '📎 ' + validFiles.length + ' foto dipilih';
            fileName.classList.remove('hidden');
            removeBtn.classList.remove('hidden');
            if (removeInput) {
                removeInput.value = '0';
            }
        } else {
            removeDocPhotos();
            alert('Pastikan file foto berupa JPG, PNG, atau WebP dan ukuran maks. 5 MB.');
        }
    }

    function removeDocPhotos() {
        document.getElementById('doc_image_input').value = '';
        document.getElementById('doc_camera_input').value = '';

        const grid = document.getElementById('doc_preview_grid');
        grid.innerHTML = '';
        grid.classList.add('hidden');

        document.getElementById('doc_upload_placeholder').classList.remove('hidden');
        document.getElementById('doc_file_name').classList.add('hidden');
        document.getElementById('doc_remove_btn').classList.add('hidden');

        const removeInput = document.getElementById('remove_documentation_image');
        if (removeInput) {
            removeInput.value = '1';
        }
    }

    // Input galeri (bisa pilih lebih dari 1 file gambar dari lokal)
    document.getElementById('doc_image_input')
        .addEventListener('change', function(e) {
            if (e.target.files && e.target.files.length > 0) {
                showDocPreviews(e.target.files);
            }
        });

    // Input kamera
    document.getElementById('doc_camera_input')
        .addEventListener('change', function(e) {
            if (e.target.files && e.target.files.length > 0) {
                const dt = new DataTransfer();
                Array.from(e.target.files).forEach(f => dt.items.add(f));
                document.getElementById('doc_image_input').files = dt.files;
                showDocPreviews(e.target.files);
            }
        });

    // =========================================================================
    // PENGELOLAAN BARIS TAUTAN GOOGLE DRIVE (BISA LEBIH DARI 1 BARIS)
    // =========================================================================
    function addDocLinkRow(val = '') {
        const container = document.getElementById('doc_links_container');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 doc-link-row';
        row.innerHTML = `
            <div class="relative flex-1 rounded-lg shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <input
                    type="url"
                    name="documentation_links[]"
                    value="${val}"
                    class="block w-full rounded-lg border-gray-300 pl-9 text-sm focus:border-blue-500 focus:ring-blue-500 bg-white"
                    placeholder="https://drive.google.com/... atau tautan file/folder"
                >
            </div>
            <button
                type="button"
                onclick="removeDocLinkRow(this)"
                class="shrink-0 p-2 text-gray-400 hover:text-red-600 rounded-lg border border-gray-200 hover:bg-red-50 transition"
                title="Hapus baris tautan ini"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        `;
        container.appendChild(row);
        const input = row.querySelector('input');
        if (input) input.focus();
    }

    function removeDocLinkRow(btn) {
        const container = document.getElementById('doc_links_container');
        const rows = container.querySelectorAll('.doc-link-row');
        if (rows.length <= 1) {
            const input = rows[0].querySelector('input');
            if (input) input.value = '';
            return;
        }
        btn.closest('.doc-link-row').remove();
    }
    </script>
</x-app-layout>
