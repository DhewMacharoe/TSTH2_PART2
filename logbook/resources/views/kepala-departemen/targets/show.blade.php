<x-app-layout>

{{-- =========================================================
     HEADER
========================================================== --}}

<x-slot name="header">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Target
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Informasi project, target, sub target, dan pekerjaan
                yang terkait dengan divisi.
            </p>

        </div>


        <div class="flex items-center gap-2">

            {{-- EDIT --}}

            <a
                href="{{ route('kepala_departemen.targets.edit', $target) }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition"
            >
                Edit Target
            </a>


            {{-- KEMBALI --}}

            <a
                href="{{ route('kepala_departemen.targets.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition"
            >
                Kembali
            </a>

        </div>

    </div>

</x-slot>


{{-- =========================================================
     CONTENT
========================================================== --}}

<div class="py-8">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">


        {{-- =================================================
             ALERT SUCCESS
        ================================================== --}}

        @if (session('success'))

            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">

                {{ session('success') }}

            </div>

        @endif


        {{-- =================================================
             ALERT ERROR
        ================================================== --}}

        @if (session('error'))

            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">

                {{ session('error') }}

            </div>

        @endif


        {{-- =================================================
             VALIDATION ERRORS
        ================================================== --}}

        @if ($errors->any())

            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">

                <p class="font-semibold mb-2">
                    Terdapat kesalahan:
                </p>

                <ul class="list-disc list-inside text-sm space-y-1">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
             HIERARCHY INFORMATION
        ========================================================== --}}

        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-100 rounded-xl p-6">

            <div class="flex flex-col lg:flex-row lg:items-center gap-4">

                {{-- PROJECT --}}

                <div class="flex-1">

                    <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wide">
                        Project
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-800">
                        {{ $target->project?->name ?? '-' }}
                    </p>

                </div>


                {{-- ARROW --}}

                <div class="hidden lg:block text-indigo-300 text-xl">
                    →
                </div>


                {{-- DIVISION --}}

                <div class="flex-1">

                    <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wide">
                        Divisi
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-800">
                        {{ $target->division?->name ?? '-' }}
                    </p>

                </div>


                {{-- ARROW --}}

                <div class="hidden lg:block text-indigo-300 text-xl">
                    →
                </div>


                {{-- TARGET --}}

                <div class="flex-1">

                    <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wide">
                        Target
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-800">
                        {{ $target->title }}
                    </p>

                </div>

            </div>

        </div>


        {{-- =================================================
             INFORMASI UTAMA TARGET
        ================================================== --}}

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-200">

                <h3 class="text-lg font-semibold text-gray-800">
                    Informasi Target
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Informasi dasar dari target yang telah dibuat.
                </p>

            </div>


            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">


                    {{-- PROJECT --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Project
                        </p>

                        <p class="mt-1 text-base font-semibold text-indigo-700">
                            {{ $target->project?->name ?? '-' }}
                        </p>

                    </div>


                    {{-- DEPARTMENT --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Departemen
                        </p>

                        <p class="mt-1 text-base font-semibold text-gray-800">
                            {{ $target->department?->name ?? '-' }}
                        </p>

                    </div>


                    {{-- DIVISION --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Divisi
                        </p>

                        <p class="mt-1 text-base font-semibold text-gray-800">
                            {{ $target->division?->name ?? '-' }}
                        </p>

                    </div>


                    {{-- STATUS --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Status
                        </p>

                        <div class="mt-2">

                            @php

                                $statusClasses = match ($target->status) {

                                    'active' =>
                                        'bg-green-100 text-green-700',

                                    'pending' =>
                                        'bg-yellow-100 text-yellow-700',

                                    'completed' =>
                                        'bg-blue-100 text-blue-700',

                                    'inactive' =>
                                        'bg-gray-100 text-gray-700',

                                    default =>
                                        'bg-gray-100 text-gray-700',

                                };

                            @endphp


                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}"
                            >

                                {{ ucfirst($target->status) }}

                            </span>

                        </div>

                    </div>


                    {{-- PERIODE MULAI --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Periode Mulai
                        </p>

                        <p class="mt-1 text-base font-semibold text-gray-800">
                            {{ $target->period_start?->format('d F Y') ?? '-' }}
                        </p>

                    </div>


                    {{-- PERIODE SELESAI --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Periode Selesai
                        </p>

                        <p class="mt-1 text-base font-semibold text-gray-800">
                            {{ $target->period_end?->format('d F Y') ?? '-' }}
                        </p>

                    </div>


                    {{-- PROGRESS TARGET --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Progress Target
                        </p>

                        <div class="mt-2">

                            <div class="flex items-center justify-between mb-1">

                                <span class="text-sm font-semibold text-gray-800">
                                    {{ $target->target_progress ?? 0 }}%
                                </span>

                            </div>


                            <div class="w-full bg-gray-200 rounded-full h-2.5">

                                <div
                                    class="bg-blue-600 h-2.5 rounded-full"
                                    style="width: {{ min(100, max(0, $target->target_progress ?? 0)) }}%"
                                ></div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- DESKRIPSI --}}

                <div class="mt-8 pt-6 border-t border-gray-200">

                    <p class="text-sm font-medium text-gray-500">
                        Deskripsi Target
                    </p>

                    <div class="mt-2 text-sm text-gray-700 leading-relaxed whitespace-pre-line">

                        {{ $target->description ?: 'Tidak ada deskripsi.' }}

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             INFORMASI PROJECT
        ========================================================== --}}

        @if ($target->project)

            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">

                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Informasi Project
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Informasi project yang menaungi Target ini.
                    </p>

                </div>


                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        {{-- NAMA PROJECT --}}

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Nama Project
                            </p>

                            <p class="mt-1 text-base font-semibold text-gray-800">
                                {{ $target->project->name }}
                            </p>

                        </div>


                        {{-- STATUS PROJECT --}}

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Status Project
                            </p>

                            <div class="mt-2">

                                @php

                                    $projectStatusClasses = match ($target->project->status) {

                                        'active' =>
                                            'bg-green-100 text-green-700',

                                        'pending' =>
                                            'bg-yellow-100 text-yellow-700',

                                        'completed' =>
                                            'bg-blue-100 text-blue-700',

                                        'inactive' =>
                                            'bg-gray-100 text-gray-700',

                                        default =>
                                            'bg-gray-100 text-gray-700',

                                    };

                                @endphp


                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $projectStatusClasses }}"
                                >

                                    {{ ucfirst($target->project->status) }}

                                </span>

                            </div>

                        </div>


                        {{-- PERIODE PROJECT --}}

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Periode Project
                            </p>

                            <p class="mt-1 text-sm text-gray-700">

                                {{ $target->project->start_date?->format('d F Y') ?? '-' }}

                                <span class="mx-1">
                                    s/d
                                </span>

                                {{ $target->project->end_date?->format('d F Y') ?? '-' }}

                            </p>

                        </div>


                        {{-- DIVISI TERLIBAT --}}

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Divisi yang Terlibat
                            </p>

                            <div class="mt-2 flex flex-wrap gap-2">

                                @forelse ($target->project->divisions as $division)

                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium"
                                    >

                                        {{ $division->name }}

                                    </span>

                                @empty

                                    <span class="text-sm text-gray-500">
                                        Belum ada divisi.
                                    </span>

                                @endforelse

                            </div>

                        </div>

                    </div>


                    {{-- DESKRIPSI PROJECT --}}

                    <div class="mt-6 pt-6 border-t border-gray-200">

                        <p class="text-sm font-medium text-gray-500">
                            Deskripsi Project
                        </p>

                        <div class="mt-2 text-sm text-gray-700 leading-relaxed whitespace-pre-line">

                            {{ $target->project->description ?: 'Tidak ada deskripsi.' }}

                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- =========================================================
             SUB TARGET
        ========================================================== --}}

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-200">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>

                        <h3 class="text-lg font-semibold text-gray-800">
                            Sub Target
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Rincian pekerjaan yang membentuk Target ini.
                        </p>

                    </div>


                    {{-- TAMBAH SUB TARGET --}}

                    <a
                        href="{{ route('kepala_departemen.targets.sub-targets.create', $target) }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition"
                    >
                        + Tambah Sub Target
                    </a>

                </div>

            </div>


            <div class="p-6">

                @php

                    $allocatedPercentage =
                        $target->subTargets->sum('percentage');

                    $remainingPercentage =
                        100 - $allocatedPercentage;

                @endphp


                {{-- RINGKASAN BOBOT --}}

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">


                    {{-- TOTAL --}}

                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">

                        <p class="text-sm text-blue-600 font-medium">
                            Total Bobot
                        </p>

                        <p class="text-2xl font-bold text-blue-700 mt-1">
                            {{ $allocatedPercentage }}%
                        </p>

                    </div>


                    {{-- SISA --}}

                    <div class="bg-green-50 border border-green-100 rounded-lg p-4">

                        <p class="text-sm text-green-600 font-medium">
                            Sisa Bobot
                        </p>

                        <p class="text-2xl font-bold text-green-700 mt-1">
                            {{ max(0, $remainingPercentage) }}%
                        </p>

                    </div>


                    {{-- STATUS --}}

                    <div
                        class="
                            rounded-lg p-4 border

                            @if ($allocatedPercentage == 100)
                                bg-green-50 border-green-100

                            @elseif ($allocatedPercentage > 100)
                                bg-red-50 border-red-100

                            @else
                                bg-yellow-50 border-yellow-100
                            @endif
                        "
                    >

                        <p
                            class="
                                text-sm font-medium

                                @if ($allocatedPercentage == 100)
                                    text-green-600

                                @elseif ($allocatedPercentage > 100)
                                    text-red-600

                                @else
                                    text-yellow-600
                                @endif
                            "
                        >
                            Status Alokasi
                        </p>


                        <p
                            class="
                                text-lg font-bold mt-1

                                @if ($allocatedPercentage == 100)
                                    text-green-700

                                @elseif ($allocatedPercentage > 100)
                                    text-red-700

                                @else
                                    text-yellow-700
                                @endif
                            "
                        >

                            @if ($allocatedPercentage == 100)

                                Lengkap

                            @elseif ($allocatedPercentage > 100)

                                Melebihi 100%

                            @else

                                Belum Lengkap

                            @endif

                        </p>

                    </div>

                </div>


                {{-- PROGRESS BOBOT --}}

                <div class="mb-6">

                    <div class="flex items-center justify-between mb-2">

                        <span class="text-sm font-medium text-gray-700">
                            Alokasi Bobot Sub Target
                        </span>

                        <span class="text-sm font-semibold text-gray-800">
                            {{ $allocatedPercentage }} / 100%
                        </span>

                    </div>


                    <div class="w-full bg-gray-200 rounded-full h-3">

                        <div
                            class="
                                h-3 rounded-full

                                @if ($allocatedPercentage > 100)
                                    bg-red-500

                                @elseif ($allocatedPercentage == 100)
                                    bg-green-500

                                @else
                                    bg-blue-500
                                @endif
                            "
                            style="width: {{ min(100, max(0, $allocatedPercentage)) }}%"
                        ></div>

                    </div>

                </div>


                {{-- LIST SUB TARGET --}}

                @forelse ($target->subTargets as $subTarget)

                    <div class="border border-gray-200 rounded-xl p-5 mb-4 last:mb-0">

                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">


                            <div class="flex-1">


                                {{-- JUDUL --}}

                                <div class="flex flex-wrap items-center gap-2">

                                    <h4 class="text-base font-semibold text-gray-800">
                                        {{ $subTarget->title }}
                                    </h4>


                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">

                                        {{ $subTarget->percentage }}%

                                    </span>

                                </div>


                                {{-- DESKRIPSI --}}

                                <p class="text-sm text-gray-600 mt-2 whitespace-pre-line">

                                    {{ $subTarget->description ?: 'Tidak ada deskripsi.' }}

                                </p>


                                {{-- PERIODE --}}

                                <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-500">

                                    <span>

                                        Mulai:

                                        <strong class="text-gray-700">

                                            {{ $subTarget->period_start?->format('d F Y') ?? '-' }}

                                        </strong>

                                    </span>


                                    <span>

                                        Selesai:

                                        <strong class="text-gray-700">

                                            {{ $subTarget->period_end?->format('d F Y') ?? '-' }}

                                        </strong>

                                    </span>

                                </div>


                                {{-- JUMLAH TASK --}}

                                <div class="mt-3 text-xs text-gray-500">

                                    {{ $subTarget->tasks->count() }}

                                    pekerjaan

                                </div>

                            </div>


                            {{-- ACTION --}}

                            <div class="flex items-center gap-2">

                                <a
                                    href="{{ route('kepala_departemen.targets.sub-targets.edit', [$target, $subTarget]) }}"
                                    class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ route('kepala_departemen.targets.sub-targets.destroy', [$target, $subTarget]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus Sub Target ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex items-center px-3 py-2 bg-red-50 border border-red-200 rounded-lg text-xs font-semibold text-red-700 hover:bg-red-100 transition"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-10">

                        <div class="text-gray-400 text-4xl mb-3">
                            📋
                        </div>

                        <p class="text-gray-600 font-medium">
                            Belum ada Sub Target
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Tambahkan Sub Target untuk membagi bobot target.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- =========================================================
             PEKERJAAN / TASK
        ========================================================== --}}

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-200">

                <h3 class="text-lg font-semibold text-gray-800">
                    Pekerjaan
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Pekerjaan yang dibuat berdasarkan Sub Target.
                </p>

            </div>


            <div class="p-6">

                @php

                    /*
                     * Task tidak memiliki target_id secara langsung.
                     *
                     * Relasi:
                     *
                     * Project
                     *   ↓
                     * Target
                     *   ↓
                     * Sub Target
                     *   ↓
                     * Task
                     *   ↓
                     * Karyawan
                     */

                    $tasks = $target->subTargets
                        ->flatMap(function ($subTarget) {

                            return $subTarget->tasks ?? collect();

                        });

                @endphp


                @forelse ($tasks as $task)

                    <div class="border border-gray-200 rounded-xl p-5 mb-4 last:mb-0">

                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">


                            {{-- =================================================
                                 INFORMASI TASK
                            ================================================== --}}

                            <div class="flex-1">


                                {{-- JUDUL + STATUS --}}

                                <div class="flex flex-wrap items-center gap-2">

                                    <h4 class="text-base font-semibold text-gray-800">

                                        {{ $task->title }}

                                    </h4>


                                    @php

                                        $taskStatusClasses = match ($task->status) {

                                            'assigned' =>
                                                'bg-blue-100 text-blue-700',

                                            'in_progress' =>
                                                'bg-yellow-100 text-yellow-700',

                                            'revision' =>
                                                'bg-red-100 text-red-700',

                                            'completed' =>
                                                'bg-green-100 text-green-700',

                                            'verified' =>
                                                'bg-purple-100 text-purple-700',

                                            default =>
                                                'bg-gray-100 text-gray-700',

                                        };

                                    @endphp


                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $taskStatusClasses }}"
                                    >

                                        {{ ucwords(str_replace('_', ' ', $task->status)) }}

                                    </span>

                                </div>


                                {{-- KONTEKS HIERARKI --}}

                                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">

                                    <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 font-medium">

                                        Project:
                                        {{ $target->project?->name ?? '-' }}

                                    </span>


                                    <span class="text-gray-400">
                                        →
                                    </span>


                                    <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-medium">

                                        Target:
                                        {{ $target->title }}

                                    </span>


                                    <span class="text-gray-400">
                                        →
                                    </span>


                                    <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 font-medium">

                                        Sub Target:
                                        {{ $task->subTarget?->title ?? '-' }}

                                    </span>

                                </div>


                                {{-- DESKRIPSI --}}

                                <p class="text-sm text-gray-600 mt-3 whitespace-pre-line">

                                    {{ $task->description ?: 'Tidak ada deskripsi.' }}

                                </p>


                                {{-- DETAIL TASK --}}

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 mt-4">


                                    {{-- DIVISI --}}

                                    <div>

                                        <p class="text-xs text-gray-500">
                                            Divisi
                                        </p>

                                        <p class="text-sm font-medium text-gray-800 mt-1">

                                            {{ $task->division?->name ?? $target->division?->name ?? '-' }}

                                        </p>

                                    </div>


                                    {{-- KARYAWAN --}}

                                    <div>

                                        <p class="text-xs text-gray-500">
                                            Ditugaskan Kepada
                                        </p>

                                        <p class="text-sm font-medium text-gray-800 mt-1">

                                            {{ $task->assignedTo?->name ?? '-' }}

                                        </p>

                                    </div>


                                    {{-- PROGRESS --}}

                                    <div class="sm:col-span-2">

                                        <p class="text-xs text-gray-500">
                                            Progress
                                        </p>

                                        @php

                                            $taskProgress =
                                                $task->verified_progress
                                                ?? $task->progress
                                                ?? 0;

                                            $taskProgress =
                                                min(
                                                    100,
                                                    max(
                                                        0,
                                                        $taskProgress
                                                    )
                                                );

                                        @endphp


                                        <div class="flex items-center gap-3 mt-2">

                                            <div class="flex-1 bg-gray-200 rounded-full h-2">

                                                <div
                                                    class="bg-blue-600 h-2 rounded-full"
                                                    style="width: {{ $taskProgress }}%"
                                                ></div>

                                            </div>


                                            <span class="text-sm font-semibold text-gray-800">

                                                {{ $taskProgress }}%

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 BOBOT SUB TARGET
                            ================================================== --}}

                            @if ($task->subTarget)

                                <div class="lg:w-40 bg-gray-50 rounded-lg p-4">

                                    <p class="text-xs text-gray-500">
                                        Bobot Sub Target
                                    </p>

                                    <p class="text-xl font-bold text-gray-800 mt-1">

                                        {{ $task->subTarget->percentage }}%

                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        dari Target
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="text-center py-10">

                        <div class="text-gray-400 text-4xl mb-3">
                            📝
                        </div>

                        <p class="text-gray-600 font-medium">
                            Belum ada pekerjaan
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Belum ada pekerjaan yang dibuat berdasarkan
                            Sub Target pada Target ini.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

</x-app-layout>
