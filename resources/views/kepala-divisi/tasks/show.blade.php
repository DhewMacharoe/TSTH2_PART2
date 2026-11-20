<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Detail Pekerjaan
                </h2>

            <p class="text-sm text-gray-500 mt-1">
                Detail pekerjaan, target yang terkait, serta perkembangan pekerjaan karyawan.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a
                href="{{ route('kepala_divisi.tasks.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl transition"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                Kembali
            </a>

            <a
                href="{{ route('kepala_divisi.tasks.edit', $task) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition"
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
                        d="M11 5H6a2 2 0 002-2v11a2 2 0 002 2h5m-1.5-9.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 7.5-7.5z"
                    />
                </svg>

                Edit
            </a>
        </div>
    </div>
</x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
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

        @php
            $progress = $task->current_progress ?? 0;

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
        @endphp

        {{-- Header Pekerjaan --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="px-6 py-6 border-b border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">

                    <div class="min-w-0">

                        <div class="flex items-center gap-3 flex-wrap">

                            <h1 class="text-2xl font-bold text-gray-800 break-words">
                                {{ $task->title }}
                            </h1>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>

                        </div>

                        <p class="mt-2 text-sm text-gray-500">
                            Dibuat pada {{ $task->created_at?->format('d M Y, H:i') }}
                        </p>

                    </div>

                    @if ($task->assignedTo)

                        <div class="flex items-center gap-3 lg:min-w-[220px]">

                            <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-base font-bold text-blue-700">
                                    {{ strtoupper(substr($task->assignedTo->name, 0, 1)) }}
                                </span>
                            </div>

                            <div>

                                <p class="text-xs text-gray-400">
                                    Ditugaskan kepada
                                </p>

                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $task->assignedTo->name }}
                                </p>

                                @if ($task->division)
                                    <p class="text-xs text-gray-500">
                                        {{ $task->division->name }}
                                    </p>
                                @endif

                            </div>

                        </div>

                    @endif

                </div>
            </div>

            {{-- Informasi Utama --}}
            <div class="px-6 py-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                    {{-- Project --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">

                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                            Project
                        </p>

                        @if ($task->target)

                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                {{ $task->target->project_name ?? '-' }}
                            </p>

                        @else

                            <p class="mt-2 text-sm text-gray-400">
                                Project tidak tersedia
                            </p>

                        @endif

                    </div>

                    {{-- Target --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">

                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                            Target
                        </p>

                        @if ($task->target)

                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                {{ $task->target->title }}
                            </p>

                        @else

                            <p class="mt-2 text-sm text-gray-400">
                                Target tidak tersedia
                            </p>

                        @endif

                    </div>

                    {{-- Sub Target --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">

                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                            Sub Target
                        </p>

                        @if ($task->subTarget)

                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                {{ $task->subTarget->title }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Bobot Sub Target:
                                <span class="font-semibold text-gray-700">
                                    {{ $task->subTarget->percentage }}%
                                </span>
                            </p>

                        @else

                            <p class="mt-2 text-sm text-gray-400">
                                Sub Target tidak tersedia
                            </p>

                        @endif

                    </div>

                    {{-- Progress --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">

                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                            Progress Saat Ini
                        </p>

                        <p class="mt-2 text-2xl font-bold text-blue-600">
                            {{ $progress }}%
                        </p>

                        <div class="mt-3 w-full h-2 bg-gray-200 rounded-full overflow-hidden">

                            <div
                                class="h-full bg-blue-600 rounded-full transition-all"
                                style="width: {{ min($progress, 100) }}%"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Detail Pekerjaan --}}
        <div class="mt-6 bg-white rounded-2xl border border-gray-200 shadow-sm">

            <div class="px-6 py-5 border-b border-gray-200">

                <h3 class="text-lg font-semibold text-gray-800">
                    Informasi Pekerjaan
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi pekerjaan yang diberikan kepada karyawan.
                </p>

            </div>

            <div class="px-6 py-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Jenis Pekerjaan --}}
                    <div>

                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                            Jenis Pekerjaan
                        </p>

                        @php
                            $workTypeLabel = match ($task->work_type) {
                                'project' => 'Kategori Proyek',
                                'daily' => 'Daily/Rutinitas',
                                'other' => $task->other_work_type,
                                default => '-',
                            };
                        @endphp

                        <p class="mt-2 text-sm font-semibold text-gray-800">
                            {{ $workTypeLabel }}
                        </p>

                    </div>

                    {{-- Karyawan --}}
                    <div>

                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                            Karyawan
                        </p>

                        @if ($task->assignedTo)

                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                {{ $task->assignedTo->name }}
                            </p>

                            @if ($task->division)
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $task->division->name }}
                                </p>
                            @endif

                        @else

                            <p class="mt-2 text-sm text-gray-400">
                                Tidak tersedia
                            </p>

                        @endif

                    </div>

                </div>

            </div>

        </div>

        {{-- Progress Section --}}
        <div class="mt-6 bg-white rounded-2xl border border-gray-200 shadow-sm">

            <div class="px-6 py-5 border-b border-gray-200">

                <h3 class="text-lg font-semibold text-gray-800">
                    Perkembangan Pekerjaan
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Perkembangan pekerjaan berdasarkan logbook yang telah disetujui.
                </p>

            </div>

            <div class="p-6">

                <div class="flex items-center justify-between mb-2">

                    <div>

                        <p class="text-sm font-semibold text-gray-700">
                            Progress Pekerjaan
                        </p>

                        <p class="text-xs text-gray-400 mt-0.5">
                            Progress terakhir dari logbook yang telah disetujui.
                        </p>

                    </div>

                    <span class="text-sm font-bold text-blue-600">
                        {{ $progress }}%
                    </span>

                </div>

                <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">

                    <div
                        class="h-full bg-blue-600 rounded-full transition-all"
                        style="width: {{ min($progress, 100) }}%"
                    ></div>

                </div>

                <div class="mt-4 rounded-xl bg-blue-50 border border-blue-100 px-4 py-3">

                    <p class="text-xs leading-5 text-blue-700">
                        Progress pekerjaan berada pada skala 0–100%.
                        Bobot Sub Target digunakan untuk menentukan kontribusi pekerjaan
                        terhadap Target dan bukan sebagai batas maksimal progress.
                    </p>

                </div>

            </div>

        </div>

        {{-- Description --}}
        <div class="mt-6 bg-white rounded-2xl border border-gray-200 shadow-sm">

            <div class="px-6 py-5 border-b border-gray-200">

                <h3 class="text-lg font-semibold text-gray-800">
                    Deskripsi Pekerjaan
                </h3>

            </div>

            <div class="px-6 py-5">

                @if ($task->description)

                    <p class="text-sm leading-7 text-gray-600 whitespace-pre-line">
                        {{ $task->description }}
                    </p>

                @else

                    <p class="text-sm text-gray-400">
                        Tidak ada deskripsi untuk pekerjaan ini.
                    </p>

                @endif

            </div>

        </div>

        {{-- Logbook History --}}
        <div class="mt-6 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-200">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

                    <div>

                        <h3 class="text-lg font-semibold text-gray-800">
                            Riwayat Logbook
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Riwayat laporan pekerjaan yang berkaitan dengan tugas ini.
                        </p>

                    </div>

                    <span class="inline-flex items-center w-fit px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 text-sm font-medium">
                        {{ $task->logbooks->count() }} laporan
                    </span>

                </div>

            </div>

            @if ($task->logbooks->isEmpty())

                <div class="px-6 py-12 text-center">

                    <div class="mx-auto w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">

                        <svg
                            class="w-6 h-6 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586a1 1 0 01.707.293l3.414 3.414A2 2 0 0118 7.414V19a2 2 0 01-2 2z"
                            />
                        </svg>

                    </div>

                    <p class="mt-4 text-sm font-medium text-gray-600">
                        Belum ada laporan logbook.
                    </p>

                    <p class="mt-1 text-sm text-gray-400">
                        Karyawan belum mengirimkan laporan untuk pekerjaan ini.
                    </p>

                </div>

            @else

                {{-- Desktop --}}
                <div class="hidden md:block overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-gray-50 border-b border-gray-200">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Aktivitas
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

                            @foreach ($task->logbooks->sortByDesc('date') as $logbook)

                                @php
                                    $logbookStatusLabel = match ($logbook->status) {
                                        'pending' => 'Menunggu Review',
                                        'revision' => 'Perlu Revisi',
                                        'approved' => 'Disetujui',
                                        'reviewed' => 'Sudah Direview',
                                        default => ucfirst($logbook->status),
                                    };

                                    $logbookStatusClass = match ($logbook->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'revision' => 'bg-red-100 text-red-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'reviewed' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp

                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-6 py-5 align-top">

                                        <p class="text-sm font-medium text-gray-700">
                                            {{ $logbook->date?->format('d M Y') }}
                                        </p>

                                    </td>

                                    <td class="px-6 py-5 align-top">

                                        <p class="text-sm text-gray-700 line-clamp-2 max-w-md">
                                            {{ $logbook->activity }}
                                        </p>

                                    </td>

                                    <td class="px-6 py-5 align-top">

                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-sm font-semibold">
                                            {{ $logbook->progress ?? 0 }}%
                                        </span>

                                    </td>

                                    <td class="px-6 py-5 align-top">

                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $logbookStatusClass }}">
                                            {{ $logbookStatusLabel }}
                                        </span>

                                    </td>

                                    <td class="px-6 py-5 align-top text-right">

                                        <a
                                            href="{{ route('kepala_divisi.logbooks.show', $logbook) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        >
                                            Lihat Detail
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Mobile --}}
                <div class="md:hidden divide-y divide-gray-100">

                    @foreach ($task->logbooks->sortByDesc('date') as $logbook)

                        @php
                            $logbookStatusLabel = match ($logbook->status) {
                                'pending' => 'Menunggu Review',
                                'revision' => 'Perlu Revisi',
                                'approved' => 'Disetujui',
                                'reviewed' => 'Sudah Direview',
                                default => ucfirst($logbook->status),
                            };

                            $logbookStatusClass = match ($logbook->status) {
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'revision' => 'bg-red-100 text-red-700',
                                'approved' => 'bg-green-100 text-green-700',
                                'reviewed' => 'bg-blue-100 text-blue-700',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp

                        <div class="p-5">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ $logbook->date?->format('d M Y') }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        Progress {{ $logbook->progress ?? 0 }}%
                                    </p>

                                </div>

                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $logbookStatusClass }}">
                                    {{ $logbookStatusLabel }}
                                </span>

                            </div>

                            <p class="mt-4 text-sm leading-6 text-gray-600 line-clamp-3">
                                {{ $logbook->activity }}
                            </p>

                            <a
                                href="{{ route('kepala_divisi.logbooks.show', $logbook) }}"
                                class="mt-4 inline-flex w-full items-center justify-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition"
                            >
                                Lihat Detail Logbook
                            </a>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

        {{-- Bottom Navigation --}}
        <div class="mt-6 flex justify-start">

            <a
                href="{{ route('kepala_divisi.tasks.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 rounded-xl transition"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                Kembali ke Daftar Pekerjaan
            </a>

        </div>

    </div>
</div>

</x-app-layout>
