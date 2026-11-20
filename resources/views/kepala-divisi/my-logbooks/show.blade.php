<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Logbook Saya
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Informasi lengkap laporan kegiatan yang diajukan ke Kepala Departemen.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a
                    href="{{ route('kepala_divisi.my_logbooks.index') }}"
                    class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium transition"
                >
                    Kembali
                </a>

                @if(in_array($logbook->status, ['pending', 'revision']))
                    <a
                        href="{{ route('kepala_divisi.my_logbooks.edit', $logbook) }}"
                        class="px-4 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold transition"
                    >
                        Edit Logbook
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- NOTIFIKASI SUKSES / ERROR --}}
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700 shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- HEADER STATUS & INFORMASI UMUM --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Logbook Kepala Divisi</span>
                        <h3 class="text-xl font-bold text-gray-900 mt-1">
                            {{ $logbook->date ? $logbook->date->format('d F Y') : '-' }}
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Waktu: {{ $logbook->date ? $logbook->date->format('H:i') : '' }} WIB · Dibuat {{ $logbook->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div>
                        @if($logbook->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                                Menunggu Review
                            </span>
                        @elseif($logbook->status === 'revision')
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Perlu Revisi
                            </span>
                        @elseif(in_array($logbook->status, ['approved', 'reviewed']))
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                Disetujui
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Kepala Divisi</p>
                        <p class="font-bold text-gray-800 mt-0.5">{{ $logbook->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Divisi</p>
                        <p class="font-semibold text-gray-800 mt-0.5">{{ $logbook->user->divisionRelation?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Departemen Peninjau</p>
                        <p class="font-semibold text-gray-800 mt-0.5">Kepala Dept. {{ $logbook->user->department?->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- CATATAN REVISI / HASIL REVIEW --}}
            @if($logbook->review_note)
                <div class="rounded-2xl border p-6 mb-6 shadow-sm {{ $logbook->status === 'revision' ? 'bg-red-50/70 border-red-200' : 'bg-green-50/70 border-green-200' }}">
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-xl {{ $logbook->status === 'revision' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            @if($logbook->status === 'revision')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold {{ $logbook->status === 'revision' ? 'text-red-900' : 'text-green-900' }}">
                                {{ $logbook->status === 'revision' ? 'Catatan Revisi dari Kepala Departemen:' : 'Catatan Review:' }}
                            </h4>
                            <p class="mt-1 text-sm leading-relaxed {{ $logbook->status === 'revision' ? 'text-red-800' : 'text-green-800' }} whitespace-pre-line">
                                {{ $logbook->review_note }}
                            </p>
                            @if($logbook->reviewer)
                                <p class="text-xs mt-2 text-gray-500">
                                    Direview oleh: <strong class="font-medium text-gray-700">{{ $logbook->reviewer->name }}</strong>
                                    @if($logbook->reviewed_at)
                                        pada {{ $logbook->reviewed_at->format('d M Y H:i') }} WIB
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- RINCIAN KEGIATAN --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700 mb-3">
                    Kegiatan yang Dilakukan
                </h3>
                <div class="text-gray-800 text-sm leading-relaxed whitespace-pre-line bg-gray-50/60 p-4 rounded-xl border border-gray-100">
                    {{ $logbook->activity }}
                </div>
            </div>

            {{-- KENDALA --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700 mb-3">
                    Kendala
                </h3>
                @if($logbook->obstacle)
                    <div class="text-gray-800 text-sm leading-relaxed whitespace-pre-line bg-red-50/40 p-4 rounded-xl border border-red-100 text-red-900">
                        {{ $logbook->obstacle }}
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic bg-gray-50 p-3 rounded-xl border border-gray-100">
                        Tidak ada kendala yang dilaporkan.
                    </p>
                @endif
            </div>

            {{-- DOKUMENTASI PEKERJAAN --}}
            @php
                $docImages = $logbook->documentation_images;
                $docLinks = $logbook->documentation_links;
            @endphp

            <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700 mb-4">
                    Dokumentasi Pekerjaan
                </h3>

                @if(!empty($docImages) || !empty($docLinks))
                    <div class="space-y-5">
                        {{-- TAUTAN GOOGLE DRIVE --}}
                        @if(!empty($docLinks))
                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Tautan Dokumen Online ({{ count($docLinks) }})
                                </label>
                                @foreach($docLinks as $linkItem)
                                    <div class="flex items-center justify-between p-3.5 rounded-xl border border-blue-200 bg-blue-50/60 hover:bg-blue-50 transition">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-xs">
                                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-blue-900 truncate font-mono">
                                                    {{ $linkItem }}
                                                </p>
                                                <p class="text-[11px] text-blue-600">Dokumen Pendukung #{{ $loop->iteration }}</p>
                                            </div>
                                        </div>

                                        <a
                                            href="{{ $linkItem }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-xs transition shrink-0 ml-3"
                                        >
                                            <span>Buka</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- FOTO / GAMBAR DOKUMENTASI --}}
                        @if(!empty($docImages))
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                    Foto Dokumentasi ({{ count($docImages) }})
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach($docImages as $imgItem)
                                        @php
                                            $imageUrl = asset('storage/' . $imgItem);
                                        @endphp
                                        <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-50 aspect-video sm:aspect-square flex items-center justify-center relative group">
                                            <img
                                                src="{{ $imageUrl }}"
                                                alt="Dokumentasi Pekerjaan"
                                                class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-300"
                                                onclick="openImageModal(this.src)"
                                            >
                                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center pointer-events-none">
                                                <span class="text-xs text-white font-medium bg-black/60 px-2.5 py-1 rounded-full">Perbesar</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                        Tidak ada foto atau tautan dokumentasi yang dilampirkan.
                    </p>
                @endif
            </div>

            {{-- RENCANA SELANJUTNYA --}}
            @if($logbook->next_plan)
                <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6 shadow-sm">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700 mb-3">
                        Rencana Selanjutnya
                    </h3>
                    <div class="text-gray-800 text-sm leading-relaxed whitespace-pre-line bg-gray-50/60 p-4 rounded-xl border border-gray-100">
                        {{ $logbook->next_plan }}
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- MODAL PERBESAR FOTO FULLSCREEN --}}
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

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeImageModal();
    });
    </script>
</x-app-layout>

