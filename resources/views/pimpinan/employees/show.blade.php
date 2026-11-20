<x-app-layout>

```
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 mb-8">

            <div>
                <a
                    href="{{ route('pimpinan.employees.index') }}"
                    class="inline-flex items-center text-sm text-gray-500 hover:text-gray-800 transition mb-3"
                >
                    ← Kembali ke Monitoring Karyawan
                </a>

                <h1 class="text-2xl font-bold text-gray-800">
                    Detail Monitoring Karyawan
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Melihat pekerjaan dan aktivitas logbook karyawan.
                </p>
            </div>

        </div>


        {{-- Informasi Karyawan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ $user->name }}
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $user->email }}
                    </p>
                </div>


                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                    <div>
                        <p class="text-xs text-gray-400 mb-1">
                            Departemen
                        </p>

                        <p class="font-semibold text-gray-700">
                            {{ $user->department?->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 mb-1">
                            Divisi
                        </p>

                        <p class="font-semibold text-gray-700">
                            {{ $user->divisionRelation?->name ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- Statistik Pekerjaan --}}
        <div class="mb-6">

            <h2 class="text-lg font-bold text-gray-800 mb-4">
                Ringkasan Pekerjaan
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Total --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Total Pekerjaan
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $totalTasks }}
                    </p>
                </div>


                {{-- Selesai --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Selesai
                    </p>

                    <p class="text-3xl font-bold text-green-600 mt-2">
                        {{ $completedTasks }}
                    </p>
                </div>


                {{-- Berjalan --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Sedang Berjalan
                    </p>

                    <p class="text-3xl font-bold text-blue-600 mt-2">
                        {{ $inProgressTasks }}
                    </p>
                </div>


                {{-- Revisi --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Perlu Revisi
                    </p>

                    <p class="text-3xl font-bold text-orange-500 mt-2">
                        {{ $revisionTasks }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Statistik Logbook --}}
        <div class="mb-8">

            <h2 class="text-lg font-bold text-gray-800 mb-4">
                Ringkasan Logbook
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Total Logbook --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Total Logbook
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $totalLogbooks }}
                    </p>
                </div>


                {{-- Approved --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Disetujui
                    </p>

                    <p class="text-3xl font-bold text-green-600 mt-2">
                        {{ $approvedLogbooks }}
                    </p>
                </div>


                {{-- Revision --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Revisi
                    </p>

                    <p class="text-3xl font-bold text-orange-500 mt-2">
                        {{ $revisionLogbooks }}
                    </p>
                </div>


                {{-- Pending --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Menunggu Review
                    </p>

                    <p class="text-3xl font-bold text-yellow-500 mt-2">
                        {{ $pendingLogbooks }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Progress --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">

            <div class="flex items-center justify-between mb-3">

                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        Rata-rata Progress
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Berdasarkan seluruh logbook karyawan.
                    </p>
                </div>

                <span class="text-2xl font-bold text-green-600">
                    {{ $averageProgress }}%
                </span>

            </div>


            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">

                <div
                    class="h-full bg-green-500 rounded-full transition-all"
                    style="width: {{ min($averageProgress, 100) }}%"
                ></div>

            </div>

        </div>


        {{-- Daftar Pekerjaan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">

            <div class="px-6 py-5 border-b border-gray-100">

                <h2 class="text-lg font-bold text-gray-800">
                    Daftar Pekerjaan
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Pekerjaan yang diberikan kepada karyawan.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Pekerjaan
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Scope & Target
                            </th>

                            <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                Target
                            </th>

                            <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                Progress
                            </th>

                            <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse ($tasks as $task)

                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-4">

                                    <p class="font-semibold text-gray-800">
                                        {{ $task->title }}
                                    </p>

                                    @if ($task->description)
                                        <p class="text-xs text-gray-400 mt-1 max-w-md">
                                            {{ $task->description }}
                                        </p>
                                    @endif

                                </td>


                                <td class="px-6 py-4">

                                    <p class="font-medium text-gray-700">
                                        {{ $task->target?->title ?? '-' }}
                                    </p>

                                    @if ($task->target?->description)
                                        <p class="text-xs text-gray-400 mt-1 max-w-md">
                                            {{ $task->target->description }}
                                        </p>
                                    @endif

                                </td>


                                <td class="px-6 py-4 text-center">

                                    <span class="font-semibold text-gray-700">
                                        {{ $task->target_progress }}%
                                    </span>

                                </td>


                                <td class="px-6 py-4 text-center">

                                    <span class="font-semibold text-blue-600">
                                        {{ $task->verified_progress }}%
                                    </span>

                                </td>


                                <td class="px-6 py-4 text-center">

                                    @if ($task->status === 'completed')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Selesai
                                        </span>

                                    @elseif ($task->status === 'revision')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                            Revisi
                                        </span>

                                    @elseif ($task->status === 'in_progress')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                            Sedang Berjalan
                                        </span>

                                    @elseif ($task->status === 'assigned')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            Ditugaskan
                                        </span>

                                    @else

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                            {{ ucfirst($task->status) }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="px-6 py-10 text-center text-sm text-gray-400"
                                >
                                    Belum ada pekerjaan yang diberikan kepada karyawan.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

                {{-- Filter Periode --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">

                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        Filter Aktivitas Logbook
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Pilih periode untuk melihat aktivitas logbook karyawan.
                    </p>
                </div>


                <form
                    method="GET"
                    action="{{ route('pimpinan.employees.show', $user) }}"
                    class="flex flex-col sm:flex-row gap-3"
                >

                    {{-- Tahun --}}
                    <div>
                        <label
                            for="year"
                            class="block text-xs font-semibold text-gray-500 mb-1"
                        >
                            Tahun
                        </label>

                        <select
                            id="year"
                            name="year"
                            class="w-full sm:w-32 rounded-xl border-gray-200 text-sm focus:border-green-500 focus:ring-green-500"
                        >
                            @for ($year = now()->year; $year >= 2020; $year--)
                                <option
                                    value="{{ $year }}"
                                    @selected($selectedYear == $year)
                                >
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>


                    {{-- Bulan --}}
                    <div>
                        <label
                            for="month"
                            class="block text-xs font-semibold text-gray-500 mb-1"
                        >
                            Bulan
                        </label>

                        <select
                            id="month"
                            name="month"
                            class="w-full sm:w-40 rounded-xl border-gray-200 text-sm focus:border-green-500 focus:ring-green-500"
                        >

                            <option value="">
                                Semua Bulan
                            </option>

                            @php
                                $months = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember',
                                ];
                            @endphp

                            @foreach ($months as $number => $name)

                                <option
                                    value="{{ $number }}"
                                    @selected($selectedMonth == $number)
                                >
                                    {{ $name }}
                                </option>

                            @endforeach

                        </select>
                    </div>


                    {{-- Tombol --}}
                    <div class="flex gap-2">

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition"
                        >
                            Terapkan
                        </button>

                        <a
                            href="{{ route('pimpinan.employees.show', $user) }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition"
                        >
                            Reset
                        </a>

                    </div>

                </form>

            </div>

        </div>

        {{-- Aktivitas Logbook --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">

                <h2 class="text-lg font-bold text-gray-800">
                    Aktivitas Logbook
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Aktivitas logbook terbaru karyawan.
                </p>

            </div>


            <div class="divide-y divide-gray-100">

                @forelse ($logbookActivities as $logbook)

                    <div class="px-6 py-5">

                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                            <div class="flex-1">

                                <div class="flex flex-wrap items-center gap-2">

                                    <span class="text-sm font-semibold text-gray-800">
                                        {{ $logbook->date?->format('d M Y') ?? '-' }}
                                    </span>


                                    @if ($logbook->status === 'approved')

                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Disetujui
                                        </span>

                                    @elseif ($logbook->status === 'revision')

                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                            Revisi
                                        </span>

                                    @elseif ($logbook->status === 'pending')

                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            Menunggu Review
                                        </span>

                                    @else

                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                            {{ ucfirst($logbook->status) }}
                                        </span>

                                    @endif

                                </div>


                                @if ($logbook->task)

                                    <p class="text-xs text-blue-600 font-medium mt-2">
                                        Pekerjaan:
                                        {{ $logbook->task->title }}
                                    </p>

                                @endif


                                <p class="text-sm text-gray-700 mt-3 leading-relaxed">
                                    {{ $logbook->activity ?: '-' }}
                                </p>


                                @if ($logbook->obstacle)

                                    <div class="mt-3">

                                        <p class="text-xs font-semibold text-gray-500">
                                            Kendala
                                        </p>

                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $logbook->obstacle }}
                                        </p>

                                    </div>

                                @endif


                                @if ($logbook->next_plan)

                                    <div class="mt-3">

                                        <p class="text-xs font-semibold text-gray-500">
                                            Rencana Berikutnya
                                        </p>

                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $logbook->next_plan }}
                                        </p>

                                    </div>

                                @endif

                            </div>


                            <div class="shrink-0">

                                <div class="text-right">

                                    <p class="text-xs text-gray-400">
                                        Progress
                                    </p>

                                    <p class="text-2xl font-bold text-green-600">
                                        {{ $logbook->progress }}%
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-10 text-center text-sm text-gray-400">
                        Belum ada aktivitas logbook.
                    </div>

                @endforelse

            </div>

        </div>

    </div>
</div>
```

</x-app-layout>
