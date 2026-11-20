<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="max-w-7xl mx-auto px-6">


            {{-- ===================================================== --}}
            {{-- HEADER --}}
            {{-- ===================================================== --}}

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Daftar Project
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                            Lihat Project yang Anda buat atau Project yang melibatkan divisi dalam departemen Anda.
                            Kolaborasi hanya dapat dilakukan antar divisi dalam departemen yang sama.
                    </p>
                </div>

                <a
                    href="{{ route('kepala_departemen.projects.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Project
                </a>

            </div>


            {{-- ===================================================== --}}
            {{-- FLASH MESSAGE --}}
            {{-- ===================================================== --}}

            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif


            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            @if ($projects->isEmpty())

                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <div class="w-14 h-14 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-1">Belum ada Project</h2>
                    <p class="text-sm text-gray-400 mb-5">
                        Buat Project pertama untuk mengelompokkan Scope &amp; Target dan memungkinkan kolaborasi antar divisi dalam departemen yang sama.
                    </p>
                    <a
                        href="{{ route('kepala_departemen.projects.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition"
                    >
                        Buat Project Sekarang
                    </a>
                </div>

            @else

                <div class="grid grid-cols-1 gap-5">

                    @foreach ($projects as $project)

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                                {{-- Info Utama --}}
                                <div class="flex-1 min-w-0">

                                    <div class="flex items-center gap-3 flex-wrap">
                                        <h2 class="text-base font-bold text-gray-800 truncate">
                                            {{ $project->name }}
                                        </h2>

                                        {{-- Status Badge --}}
                                        @php
                                            $statusClass = match($project->status) {
                                                'active'    => 'bg-emerald-100 text-emerald-700',
                                                'pending'   => 'bg-amber-100 text-amber-700',
                                                'completed' => 'bg-blue-100 text-blue-700',
                                                'inactive'  => 'bg-gray-100 text-gray-600',
                                                default     => 'bg-gray-100 text-gray-600',
                                            };
                                            $statusLabel = match($project->status) {
                                                'active'    => 'Aktif',
                                                'pending'   => 'Pending',
                                                'completed' => 'Selesai',
                                                'inactive'  => 'Tidak Aktif',
                                                default     => $project->status,
                                            };
                                        @endphp

                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>

                                    @if ($project->description)
                                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                            {{ $project->description }}
                                        </p>
                                    @endif

                                    {{-- Periode --}}
                                    @if ($project->start_date || $project->end_date)
                                        <div class="flex items-center gap-1.5 mt-2 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>
                                                {{ $project->start_date?->format('d M Y') ?? '-' }}
                                                &ndash;
                                                {{ $project->end_date?->format('d M Y') ?? '-' }}
                                            </span>
                                        </div>
                                    @endif

                                {{-- Divisi yang Terlibat --}}
                                @if ($project->divisions->isNotEmpty())
                                    <div class="mt-3">
                                        <p class="text-xs font-semibold text-gray-600 mb-2">
                                            Divisi yang Terlibat
                                        </p>

                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($project->divisions as $division)
                                                <div class="inline-flex flex-col px-3 py-2 rounded-lg bg-indigo-50 border border-indigo-100">
                                                    <span class="text-xs font-semibold text-indigo-700">
                                                        {{ $division->name }}
                                                    </span>

                                                    <span class="text-xs text-gray-500 mt-1">
                                                        {{ $division->department->name ?? 'Tanpa Department' }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                </div>

                    {{-- Stats & Actions --}}
                    <div class="flex items-center gap-5 shrink-0">

                        {{-- Jumlah Target --}}
                        <div class="text-center">
                            <p class="text-2xl font-bold text-indigo-600">
                                {{ $project->targets->count() }}
                            </p>

                            <p class="text-xs text-gray-500">
                                Target
                            </p>
                        </div>



        {{-- Actions --}}
        <div class="flex flex-row items-center justify-center gap-2 shrink-0 flex-wrap">

            {{-- Detail --}}
            <a
                href="{{ route('kepala_departemen.projects.show', $project) }}"
                class="inline-flex h-9 items-center justify-center gap-1.5 px-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-medium transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                    />
                </svg>
                Detail
            </a>

            @if ((int) $project->created_by === (int) auth()->id())

                {{-- Edit --}}
                <a
                    href="{{ route('kepala_departemen.projects.edit', $project) }}"
                    class="inline-flex h-9 items-center justify-center gap-1.5 px-3 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-xs font-medium transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                        />
                    </svg>
                    Edit
                </a>


            {{-- Hapus --}}
            <form
                action="{{ route('kepala_departemen.projects.destroy', $project) }}"
                method="POST"
                class="m-0 flex items-center"
                onsubmit="return confirmDeleteProject(event, '{{ addslashes($project->name) }}');"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="inline-flex h-9 items-center justify-center gap-1.5 px-3 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-xs font-medium transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"
                        />
                    </svg>
                    Hapus
                </button>
            </form>

            @endif

        </div>


                        </div>

                    </div>
                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $projects->links() }}
                </div>

            @endif

        </div>

    </div>

    {{-- Modal Konfirmasi Hapus Project --}}
<div
    id="deleteProjectModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/50 p-4"
>
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
            <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"
                />
            </svg>
        </div>

        <h2 class="text-lg font-bold text-center text-gray-800">
            Hapus Project?
        </h2>

        <p class="mt-2 text-sm text-center text-gray-500">
            Apakah kamu yakin ingin menghapus project
            <span id="deleteProjectName" class="font-semibold text-gray-800"></span>?
            Tindakan ini tidak dapat dibatalkan.
        </p>

        <div class="mt-6 flex justify-center gap-3">
            <button
                type="button"
                onclick="closeDeleteProjectModal()"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
            >
                Batal
            </button>

            <button
                type="button"
                id="confirmDeleteProjectButton"
                class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700 transition"
            >
                Ya, Hapus
            </button>
        </div>

    </div>
</div>

<script>
    let deleteProjectForm = null;

    function confirmDeleteProject(event, projectName) {
        event.preventDefault();

        deleteProjectForm = event.currentTarget;

        document.getElementById('deleteProjectName').textContent = projectName;

        const modal = document.getElementById('deleteProjectModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        return false;
    }

    function closeDeleteProjectModal() {
        const modal = document.getElementById('deleteProjectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        deleteProjectForm = null;
    }

    document.getElementById('confirmDeleteProjectButton').addEventListener('click', function () {
        if (deleteProjectForm) {
            deleteProjectForm.submit();
        }
    });
</script>

</x-app-layout>
