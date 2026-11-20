<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Logbook
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Detail aktivitas karyawan
                </p>
            </div>

            <a
                href="{{ route('leader.logbooks.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition"
            >
                Kembali
            </a>

        </div>

    </x-slot>

    <div class="py-12">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif


            {{-- INFORMASI KARYAWAN --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Informasi Karyawan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <p class="text-sm text-gray-500 mb-1">
                            Nama Karyawan
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $logbook->user->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">
                            Divisi
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $logbook->user->division ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">
                            Tanggal
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $logbook->date->format('d-m-Y H:i') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">
                            Status
                        </p>

                        @if($logbook->status === 'pending')

                            <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                                Menunggu Review
                            </span>

                        @else

                            <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                Sudah Direview
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- KEGIATAN --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Kegiatan yang Dilakukan
                </h3>

                <div class="bg-gray-50 rounded-lg p-4">

                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $logbook->activity }}
                    </p>

                </div>

            </div>


            {{-- KENDALA --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Kendala
                </h3>

                @if($logbook->obstacle)

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">

                        <p class="text-red-700 leading-relaxed whitespace-pre-line">
                            {{ $logbook->obstacle }}
                        </p>

                    </div>

                @else

                    <p class="text-gray-500">
                        Tidak ada kendala.
                    </p>

                @endif

            </div>


            {{-- RENCANA SELANJUTNYA --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Rencana Selanjutnya
                </h3>

                @if($logbook->next_plan)

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">

                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $logbook->next_plan }}
                        </p>

                    </div>

                @else

                    <p class="text-gray-500">
                        Belum ada rencana selanjutnya.
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

                        {{-- FOTO LANGSUNG --}}
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
                            Belum ada dokumentasi (foto atau tautan) pada logbook ini.
                        </p>
                    </div>
                @endif
            </div>
                @else
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                        <p class="text-sm text-gray-500">
                            Belum ada dokumentasi (foto atau tautan) pada logbook ini.
                        </p>
                    </div>
                @endif
            </div>

            {{-- REVIEW --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <div class="flex justify-between items-center mb-5">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Review Logbook
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Tandai logbook sebagai sudah direview.
                        </p>
                    </div>

                    @if($logbook->status === 'pending')
                        <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg font-medium text-sm">
                            Menunggu Review
                        </span>
                    @else
                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg font-medium text-sm">
                            ✓ Sudah Direview
                        </span>
                    @endif

                </div>

            </div>


            {{-- KOMENTAR --}}
            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    💬 Komentar Pimpinan
                </h3>


                {{-- FORM KOMENTAR --}}
                <form
                    action="{{ route('leader.logbooks.comments.store', $logbook) }}"
                    method="POST"
                    class="mb-8"
                >

                    @csrf

                    <label
                        for="comment"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Tambahkan Komentar
                    </label>

                    <textarea
                        id="comment"
                        name="comment"
                        rows="4"
                        class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tulis komentar atau evaluasi untuk karyawan..."
                    >{{ old('comment') }}</textarea>

                    @error('comment')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                    <button
                        type="submit"
                        class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition"
                    >
                        Kirim Komentar
                    </button>

                </form>


                {{-- DAFTAR KOMENTAR --}}
                <div class="space-y-4">

                    @forelse($logbook->comments as $comment)

                        <div class="border border-gray-200 rounded-lg p-4">

                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-2">

                                <strong class="text-gray-800">
                                    {{ $comment->user->name ?? 'User' }}
                                </strong>

                                <span class="text-sm text-gray-500">
                                    {{ $comment->created_at->format('d/m/Y H:i') }}
                                </span>

                            </div>

                            <p class="text-gray-700 whitespace-pre-line leading-relaxed">
                                {{ $comment->comment }}
                            </p>

                        </div>

                    @empty

                        <div class="text-center py-6">

                            <p class="text-gray-500">
                                Belum ada komentar dari pimpinan.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</x-app-layout>