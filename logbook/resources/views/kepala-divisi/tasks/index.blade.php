<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Pekerjaan Divisi
                </h2>

            <p class="text-sm text-gray-500 mt-1">
                Kelola pekerjaan yang diberikan kepada karyawan di divisi Anda.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a
                href="{{ route('kepala_divisi.my_logbooks.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#054439] hover:bg-[#032e26] text-white text-sm font-semibold rounded-xl transition shadow-sm"
            >
                <span class="material-symbols-rounded" style="font-size:19px;">edit_note</span>
                <span>Isi Logbook</span>
            </a>

            <a
                href="{{ route('kepala_divisi.tasks.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition"
            >
                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                Tambah Pekerjaan
            </a>
        </div>
    </div>
</x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Flash Success --}}
        @if (session('success'))
            <div
                class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700"
            >
                <svg
                    class="w-5 h-5 mt-0.5 flex-shrink-0"
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

                <p class="text-sm font-medium">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        {{-- Flash Error --}}
        @if (session('error'))
            <div
                class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700"
            >
                <svg
                    class="w-5 h-5 mt-0.5 flex-shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>

                <p class="text-sm font-medium">
                    {{ session('error') }}
                </p>
            </div>
        @endif

        {{-- Empty State --}}
        @if ($tasks->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">

                    <div class="flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 mb-5">
                        <svg
                            class="w-8 h-8 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586a1 1 0 01.707.293l3.414 3.414A1 1 0 0118 7.414V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800">
                        Belum Ada Pekerjaan
                    </h3>

                    <p class="mt-2 max-w-md text-sm text-gray-500">
                        Belum ada pekerjaan yang diberikan kepada karyawan.
                        Tambahkan pekerjaan untuk mulai memantau progres tugas.
                    </p>

                    <a
                        href="{{ route('kepala_divisi.tasks.create') }}"
                        class="mt-6 inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4"
                            />
                        </svg>

                        Tambah Pekerjaan
                    </a>
                </div>
            </div>

        @else

            {{-- Task Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                {{-- Table Header --}}
                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Daftar Pekerjaan
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Pekerjaan yang Anda berikan kepada karyawan.
                            </p>
                        </div>

                        <span class="inline-flex items-center w-fit px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 text-sm font-medium">
                            {{ $tasks->count() }} pekerjaan
                        </span>
                    </div>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Pekerjaan
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Project & Scope
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Karyawan
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Progress
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @foreach ($tasks as $task)

                                @php
                                    $statusLabel = match ($task->status) {
                                        'draft' => 'Draft',
                                        'assigned' => 'Ditugaskan',
                                        'in_progress' => 'Sedang Dikerjakan',
                                        'revision' => 'Perlu Revisi',
                                        'completed' => 'Selesai',
                                        default => ucfirst($task->status),
                                    };

                                    $statusClass = match ($task->status) {
                                        'draft' => 'bg-gray-100 text-gray-600',
                                        'assigned' => 'bg-blue-100 text-blue-700',
                                        'in_progress' => 'bg-yellow-100 text-yellow-700',
                                        'revision' => 'bg-red-100 text-red-700',
                                        'completed' => 'bg-green-100 text-green-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };

                                    $progress = $task->current_progress ?? 0;
                                @endphp

                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Nomor --}}
                                    <td class="px-6 py-5 text-center text-sm font-medium text-gray-500">
                                        {{ method_exists($tasks, 'firstItem') ? $tasks->firstItem() + $loop->index : $loop->iteration }}
                                    </td>

                                    {{-- Pekerjaan --}}
                                    <td class="px-6 py-5 align-top">
                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                {{ $task->title }}
                                            </p>

                                            <div class="mt-2">
                                                @php
                                                    $workTypeLabel = match($task->work_type){
                                                        'project' => 'Kategori Proyek',
                                                        'daily' => 'Daily/Rutinitas',
                                                        'other' => $task->other_work_type,
                                                        default => '-'
                                                    };
                                                @endphp

                                                <span class="inline-flex items-center px-2 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-medium">
                                                    {{ $workTypeLabel }}
                                                </span>
                                            </div>

                                            @if ($task->description)
                                                <p class="mt-1 text-sm text-gray-500 line-clamp-2 max-w-xs">
                                                    {{ $task->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Project & Scope --}}
                                    <td class="px-6 py-5 align-top">

                                        @if ($task->subTarget)

                                            <div class="space-y-1">

                                                <p class="text-xs text-gray-500 uppercase">
                                                    Project
                                                </p>

                                                <p class="text-sm font-semibold text-gray-800">
                                                    {{ $task->target?->project?->name ?? '-' }}
                                                </p>

                                                <p class="text-xs text-gray-500 uppercase mt-2">
                                                    Target
                                                </p>

                                                <p class="text-sm font-semibold text-gray-800">
                                                    {{ $task->target?->title ?? '-' }}
                                                </p>

                                                <p class="text-xs text-gray-500 uppercase mt-2">
                                                    Sub Target
                                                </p>

                                                <p class="text-sm text-blue-700 font-medium">
                                                    {{ $task->subTarget->title }}
                                                </p>

                                                <span class="inline-flex items-center px-2 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold">
                                                    Bobot Sub Target: {{ $task->subTarget->percentage }}%
                                                </span>

                                            </div>

                                        @else

                                            <span class="text-sm text-gray-400">
                                                Tidak tersedia
                                            </span>

                                        @endif

                                    </td>

                                    {{-- Karyawan --}}
                                    <td class="px-6 py-5 align-top">
                                        @if ($task->assignedTo)
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                                    <span class="text-sm font-semibold text-blue-700">
                                                        {{ strtoupper(substr($task->assignedTo->name, 0, 1)) }}
                                                    </span>
                                                </div>

                                                <div>
                                                    <p class="text-sm font-medium text-gray-800">
                                                        {{ $task->assignedTo->name }}
                                                    </p>

                                                    @if ($task->division)
                                                        <p class="text-xs text-gray-500">
                                                            {{ $task->division->name }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">
                                                Tidak tersedia
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Progress --}}
                                    <td class="px-6 py-5 align-top min-w-[180px]">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs text-gray-500">
                                                Progress Terverifikasi
                                            </span>

                                            <span class="text-sm font-semibold text-gray-800">
                                                {{ $progress }}%
                                            </span>
                                        </div>

                                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div
                                                class="h-full bg-blue-600 rounded-full transition-all"
                                                style="width: {{ min($progress, 100) }}%"
                                            ></div>
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-5 align-top">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex items-center justify-end gap-2">

                                            <a
                                                href="{{ route('kepala_divisi.tasks.show', $task) }}"
                                                class="px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                            >
                                                Detail
                                            </a>

                                            <a
                                                href="{{ route('kepala_divisi.tasks.edit', $task) }}"
                                                class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition"
                                            >
                                                Edit
                                            </a>

                                            <button
                                                type="button"
                                                onclick="openDeleteTaskModal(
                                                    '{{ $task->id }}',
                                                    @js($task->title)
                                                )"
                                                class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition"
                                            >
                                                Hapus
                                            </button>

                                        </div>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card --}}
                <div class="md:hidden divide-y divide-gray-100">

                    @foreach ($tasks as $task)

                        @php
                            $statusLabel = match ($task->status) {
                                'draft' => 'Draft',
                                'assigned' => 'Ditugaskan',
                                'in_progress' => 'Sedang Dikerjakan',
                                'revision' => 'Perlu Revisi',
                                'completed' => 'Selesai',
                                default => ucfirst($task->status),
                            };

                            $statusClass = match ($task->status) {
                                'draft' => 'bg-gray-100 text-gray-600',
                                'assigned' => 'bg-blue-100 text-blue-700',
                                'in_progress' => 'bg-yellow-100 text-yellow-700',
                                'revision' => 'bg-red-100 text-red-700',
                                'completed' => 'bg-green-100 text-green-700',
                                default => 'bg-gray-100 text-gray-600',
                            };

                            $progress = $task->current_progress ?? 0;
                        @endphp

                        <div class="p-5">

                            {{-- Title --}}
                            <div class="flex items-start justify-between gap-4">

                                <div class="min-w-0">

                                    {{-- Nomor Urut Pekerjaan --}}
                                    <span class="inline-flex items-center justify-center min-w-7 h-7 px-2 mb-2 rounded-lg bg-gray-100 text-gray-600 text-xs font-semibold">
                                        No. {{ method_exists($tasks, 'firstItem') ? $tasks->firstItem() + $loop->index : $loop->iteration }}
                                    </span>
                                    
                                    <h4 class="font-semibold text-gray-800 break-words">
                                        {{ $task->title }}
                                    </h4>

                                    <div class="mt-2">
                                        @php
                                            $workTypeLabel = match($task->work_type){
                                                'project' => 'Kategori Proyek',
                                                'daily' => 'Daily/Rutinitas',
                                                'other' => $task->other_work_type,
                                                default => '-'
                                            };
                                        @endphp

                                        <span class="inline-flex items-center px-2 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-medium">
                                            {{ $workTypeLabel }}
                                        </span>
                                    </div>

                                    @if ($task->description)
                                        <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                                            {{ $task->description }}
                                        </p>
                                    @endif
                                </div>

                                <span class="flex-shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>

                            </div>

                            {{-- Information --}}
                            <div class="mt-5 space-y-3">

                                {{-- Project & Scope --}}
                                <div>

                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                                        Project
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-700">
                                        {{ $task->target?->project?->name ?? '-' }}
                                    </p>

                                    <p class="mt-3 text-xs font-medium text-gray-400 uppercase tracking-wide">
                                        Target
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-700">
                                        {{ $task->target?->title ?? '-' }}
                                    </p>

                                    <p class="mt-3 text-xs font-medium text-gray-400 uppercase tracking-wide">
                                        Sub Target
                                    </p>

                                    <p class="mt-1 text-sm text-blue-700 font-medium">
                                        {{ $task->subTarget?->title ?? '-' }}
                                    </p>

                                    <span class="inline-flex items-center mt-2 px-2 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold">
                                        Bobot Sub Target: {{ $task->subTarget?->percentage ?? 0 }}%
                                    </span>

                                </div>

                                {{-- Employee --}}
                                <div>
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                                        Karyawan
                                    </p>

                                    @if ($task->assignedTo)
                                        <p class="mt-1 text-sm text-gray-700">
                                            {{ $task->assignedTo->name }}
                                        </p>
                                    @else
                                        <p class="mt-1 text-sm text-gray-400">
                                            Tidak tersedia
                                        </p>
                                    @endif
                                </div>

                                {{-- Progress --}}
                                <div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                                            Progress Terverifikasi
                                        </p>

                                        <span class="text-sm font-semibold text-gray-800">
                                            {{ $progress }}%
                                        </span>
                                    </div>

                                    <div class="mt-2 w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div
                                            class="h-full bg-blue-600 rounded-full"
                                            style="width: {{ min($progress, 100) }}%"
                                        ></div>
                                    </div>
                                </div>

                            </div>

                            {{-- Mobile Actions --}}
                            <div class="mt-5 flex items-center gap-2">

                                <a
                                    href="{{ route('kepala_divisi.tasks.show', $task) }}"
                                    class="flex-1 text-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition"
                                >
                                    Detail
                                </a>

                                <a
                                    href="{{ route('kepala_divisi.tasks.edit', $task) }}"
                                    class="flex-1 text-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition"
                                >
                                    Edit
                                </a>

                                <button
                                    type="button"
                                    onclick="openDeleteTaskModal(
                                        '{{ $task->id }}',
                                        @js($task->title)
                                    )"
                                    class="flex-1 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition"
                                >
                                    Hapus
                                </button>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div
    id="deleteTaskModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
>
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">

        {{-- Modal Header --}}
        <div class="px-6 py-5 border-b border-gray-200">

            <div class="flex items-center gap-3">

                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100">
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
                            d="M12 9v4m0 4h.01M10.29 3.86l-7.82 13a2 2 0 001.71 3h15.64a2 2 0 001.71-3l-7.82-13a2 2 0 00-3.42 0z"
                        />
                    </svg>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Hapus Pekerjaan?
                    </h3>

                    <p class="text-sm text-gray-500">
                        Konfirmasi penghapusan data
                    </p>
                </div>

            </div>

        </div>

        {{-- Modal Content --}}
        <div class="px-6 py-5">

            <p class="text-sm text-gray-600">
                Apakah Anda yakin ingin menghapus pekerjaan:
            </p>

            <p
                id="deleteTaskName"
                class="mt-2 font-semibold text-gray-800 break-words"
            ></p>

            <p class="mt-3 text-sm text-red-500">
                Data pekerjaan yang telah dihapus tidak dapat dikembalikan.
            </p>

        </div>

        {{-- Modal Footer --}}
        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">

            <button
                type="button"
                onclick="closeDeleteTaskModal()"
                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition"
            >
                Batal
            </button>

            <form
                id="deleteTaskForm"
                method="POST"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition"
                >
                    Hapus Pekerjaan
                </button>
            </form>

        </div>

    </div>
</div>

{{-- Delete Modal Script --}}
<script>
    function openDeleteTaskModal(taskId, taskName)
    {
        const modal =
            document.getElementById('deleteTaskModal');

        const taskNameElement =
            document.getElementById('deleteTaskName');

        const deleteForm =
            document.getElementById('deleteTaskForm');

        taskNameElement.textContent = taskName;

        deleteForm.action =
            "{{ url('/kepala-divisi/tasks') }}/" + taskId;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteTaskModal()
    {
        const modal =
            document.getElementById('deleteTaskModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');
    }

    document
        .getElementById('deleteTaskModal')
        .addEventListener('click', function (event)
        {
            if (event.target === this)
            {
                closeDeleteTaskModal();
            }
        });

    document.addEventListener('keydown', function (event)
    {
        if (event.key === 'Escape')
        {
            closeDeleteTaskModal();
        }
    });
</script>

</x-app-layout>
