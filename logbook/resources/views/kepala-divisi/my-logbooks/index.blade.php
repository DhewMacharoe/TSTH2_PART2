<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800">

                Logbook Saya

            </h2>


            <a
                href="{{ route('kepala_divisi.my_logbooks.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
            >

                + Tambah Logbook

            </a>

        </div>

    </x-slot>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- SUCCESS MESSAGE --}}

            @if(session('success'))

                <div class="mb-4 bg-green-100 text-green-700 p-4 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            {{-- ERROR MESSAGE --}}

            @if(session('error'))

                <div class="mb-4 bg-red-100 text-red-700 p-4 rounded-lg">

                    {{ session('error') }}

                </div>

            @endif


            <div class="bg-white shadow rounded-lg p-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Logbook Saya</h3>
                        <p class="text-xs text-gray-500 mt-1">Catatan kegiatan kerja dan aktivitas harian Kepala Divisi.</p>
                    </div>
                    <a
                        href="{{ route('kepala_divisi.my_logbooks.create') }}"
                        class="inline-flex items-center gap-2 bg-[#054439] hover:bg-[#032e26] text-white px-4 py-2.5 rounded-xl font-semibold text-sm shadow-sm transition"
                    >
                        <span class="material-symbols-rounded" style="font-size:20px;">edit_note</span>
                        <span>Isi Logbook</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">


                    <thead>

                        <tr class="border-b bg-gray-50">

                            <th class="text-left p-3">
                                No
                            </th>

                            <th class="text-left p-3">
                                Tanggal
                            </th>

                            <th class="text-left p-3">
                                Kegiatan
                            </th>

                            <th class="text-left p-3">
                                Kendala
                            </th>

                            <th class="text-left p-3">
                                Status
                            </th>

                            <th class="text-center p-3">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($logbooks as $logbook)

                            <tr class="border-b hover:bg-gray-50">


                                <td class="p-3">

                                    {{ $loop->iteration }}

                                </td>


                                <td class="p-3">

                                    {{ $logbook->date ? $logbook->date->format('d-m-Y H:i') : '-' }}

                                </td>


                                <td class="p-3">

                                    {{ Str::limit(
                                        $logbook->activity,
                                        60
                                    ) }}

                                </td>


                                <td class="p-3">

                                    @if($logbook->obstacle)

                                        <span class="text-red-600">

                                            Ada Kendala

                                        </span>

                                    @else

                                        <span class="text-green-600">

                                            Tidak Ada

                                        </span>

                                    @endif

                                </td>


                <td class="p-3">
                    @if($logbook->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                            Menunggu Review
                        </span>

                    @elseif($logbook->status === 'revision')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            Perlu Revisi
                        </span>

                    @elseif($logbook->status === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Disetujui
                        </span>

                    @elseif($logbook->status === 'reviewed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Sudah Direview
                        </span>

                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ ucfirst($logbook->status) }}
                        </span>
                    @endif
                </td>

                                <td class="p-3 text-center">


                                    {{-- DETAIL --}}

                                    <a
                                        href="{{ route(
                                            'kepala_divisi.my_logbooks.show',
                                            $logbook
                                        ) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded"
                                    >

                                        Detail

                                    </a>


            {{-- EDIT DAN HAPUS UNTUK PENDING / REVISION --}}
            @if(in_array($logbook->status, ['pending', 'revision'], true))

                <a
                    href="{{ route(
                        'kepala_divisi.my_logbooks.edit',
                        $logbook
                    ) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
                >
                    {{ $logbook->status === 'revision' ? 'Perbaiki' : 'Edit' }}
                </a>

                <form
                    id="delete-form-{{ $logbook->id }}"
                    action="{{ route(
                        'kepala_divisi.my_logbooks.destroy',
                        $logbook
                    ) }}"
                    method="POST"
                    class="inline"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="button"
                        onclick="openDeleteModal({{ $logbook->id }})"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
                    >
                        Hapus
                    </button>
                </form>

            @endif

                                </td>


                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-12 px-6"
                                >
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-[#054439] flex items-center justify-center mb-3">
                                            <span class="material-symbols-rounded" style="font-size:26px;">assignment</span>
                                        </div>
                                        <p class="font-medium text-gray-700">Belum ada logbook.</p>
                                        <p class="text-xs text-gray-400 mt-1 max-w-sm">Anda belum mencatat kegiatan logbook hari ini. Mulai laporkan kegiatan divisi Anda sekarang.</p>
                                        <a
                                            href="{{ route('kepala_divisi.my_logbooks.create') }}"
                                            class="inline-flex items-center gap-1.5 mt-4 bg-[#054439] hover:bg-[#032e26] text-white px-4 py-2 rounded-lg text-xs font-semibold shadow-sm transition"
                                        >
                                            <span class="material-symbols-rounded" style="font-size:17px;">edit_note</span>
                                            <span>Isi Logbook Sekarang</span>
                                        </a>
                                    </div>
                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                </table>
                </div>


                <div class="mt-5">

                    {{ $logbooks->links() }}

                </div>


            </div>


        </div>

    </div>
    {{-- =========================================================== --}}
    {{-- DELETE CONFIRMATION MODAL --}}
    {{-- =========================================================== --}}

    <div
        id="deleteModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm"
    >

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">

            {{-- ICON --}}
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
            </div>

            {{-- TITLE --}}
            <h2 class="text-xl font-bold text-gray-800 text-center">
                Hapus Logbook?
            </h2>

            {{-- DESCRIPTION --}}
            <p class="text-gray-500 text-center mt-2 leading-relaxed">
                Apakah kamu yakin ingin menghapus logbook ini?
                <br>
                <span class="text-sm text-red-500 font-medium">
                    Tindakan ini tidak dapat dibatalkan.
                </span>
            </p>

            {{-- BUTTONS --}}
            <div class="flex justify-center gap-3 mt-6">

                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition"
                >
                    Batal
                </button>

                <button
                    type="button"
                    id="confirmDeleteBtn"
                    onclick="confirmDelete()"
                    class="px-5 py-2.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition"
                >
                    Ya, Hapus
                </button>

            </div>

        </div>

    </div>


    <script>

        let pendingDeleteFormId = null;

        function openDeleteModal(logbookId) {
            pendingDeleteFormId = 'delete-form-' + logbookId;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            pendingDeleteFormId = null;
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function confirmDelete() {
            if (pendingDeleteFormId) {
                document.getElementById(pendingDeleteFormId).submit();
            }
        }

        // Tutup modal saat klik di luar
        document.addEventListener('click', function (event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal && !modal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        });

        // Tutup modal saat tekan Escape
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });

    </script>


</x-app-layout>
