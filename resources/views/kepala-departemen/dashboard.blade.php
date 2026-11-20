<x-app-layout>

<div class="min-h-screen bg-gray-50 py-8" x-data="{ active: null }">

    <div class="max-w-7xl mx-auto px-6">

        {{-- =====================================================
             HEADER
        ====================================================== --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Dashboard Kepala Departemen
                </h1>

                <p class="text-gray-500 mt-1">
                    Monitoring kegiatan dan pencapaian
                    {{ $department->name }}
                </p>
            </div>

            <a
                href="{{ route('admin.targets.index') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition"
            >
                Kelola Target
            </a>

        </div>


        {{-- =====================================================
             SUMMARY CARDS
        ====================================================== --}}
<div class="grid grid-cols-2 md:grid-cols-6 gap-5 mb-6">

    {{-- TOTAL KARYAWAN --}}
<button
    type="button"
    @click="active = active === 'employees' ? null : 'employees'"
    :class="active === 'employees'
        ? 'ring-2 ring-blue-500 border-blue-500'
        : 'border-gray-200 hover:border-blue-300 hover:shadow-md'"
    class="col-span-1 md:col-span-2 text-left bg-white rounded-xl shadow-sm border p-6 transition"
>
    <p class="text-sm text-gray-500">Total Karyawan</p>

    <p class="text-3xl font-bold text-gray-800 mt-2">
        {{ $totalEmployees }}
    </p>

</button>

    {{-- KEPALA DIVISI --}}
    <button
        type="button"
        @click="active = active === 'heads' ? null : 'heads'"
        :class="active === 'heads'
            ? 'ring-2 ring-blue-500 border-blue-500'
            : 'border-gray-200 hover:border-blue-300 hover:shadow-md'"
        class="col-span-1 md:col-span-2 text-left bg-white rounded-xl shadow-sm border p-6 transition"
    >
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Kepala Divisi</p>
        </div>
        <p class="text-3xl font-bold text-gray-800 mt-2">
            {{ $totalKepalaDivisi }}
        </p>
    </button>

    {{-- TOTAL DIVISI --}}
    <button
        type="button"
        @click="active = active === 'divisions' ? null : 'divisions'"
        :class="active === 'divisions'
            ? 'ring-2 ring-blue-500 border-blue-500'
            : 'border-gray-200 hover:border-blue-300 hover:shadow-md'"
        class="col-span-2 md:col-span-2 text-left bg-white rounded-xl shadow-sm border p-6 transition"
    >
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Total Divisi</p>
        </div>
        <p class="text-3xl font-bold text-gray-800 mt-2">
            {{ $totalDivisions }}
        </p>
    </button>

    {{-- TARGET AKTIF --}}
    <button
        type="button"
        @click="active = active === 'targets' ? null : 'targets'"
        :class="active === 'targets'
            ? 'ring-2 ring-emerald-500 border-emerald-500'
            : 'border-gray-200 hover:border-emerald-300 hover:shadow-md'"
        class="col-span-2 md:col-span-3 text-left bg-white rounded-xl shadow-sm border p-6 transition"
    >
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Target Aktif</p>
        </div>
        <p class="text-3xl font-bold text-emerald-600 mt-2">
            {{ $totalTargets }}
        </p>
    </button>

    {{-- TOTAL LOGBOOK --}}
    <button
        type="button"
        @click="active = active === 'logbooks' ? null : 'logbooks'"
        :class="active === 'logbooks'
            ? 'ring-2 ring-blue-500 border-blue-500'
            : 'border-gray-200 hover:border-blue-300 hover:shadow-md'"
        class="col-span-2 md:col-span-3 text-left bg-white rounded-xl shadow-sm border p-6 transition"
    >
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Total Logbook</p>
        </div>
        <p class="text-3xl font-bold text-blue-600 mt-2">
            {{ $totalLogbooks }}
        </p>
    </button>

</div>

{{-- =====================================================
     PANEL DETAIL STATISTIK
====================================================== --}}
<section
    x-cloak
    x-show="active !== null"
    x-transition
    class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-8 flex flex-col overflow-hidden"
>
    {{-- HEADER UTAMA PANEL --}}
    <div class="bg-white px-6 py-4 sm:py-5 border-b border-gray-200 flex items-center justify-between shrink-0">
        <div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <h2 class="text-lg font-bold text-gray-900"
                    x-text="{
                        employees: 'Daftar Karyawan',
                        heads: 'Daftar Kepala Divisi',
                        divisions: 'Daftar Divisi',
                        targets: 'Daftar Target Aktif',
                        logbooks: 'Daftar Total Logbook'
                    }[active] || 'Detail Statistik'">
                </h2>

                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 shadow-xs"
                    x-text="{
                        employees: '{{ count($employees) }} Karyawan',
                        heads: '{{ count($kepalaDivisiList) }} Kepala Divisi',
                        divisions: '{{ count($divisions) }} Divisi',
                        targets: '{{ count($activeTargets) }} Target Aktif',
                        logbooks: '{{ $totalLogbooks }} Logbook'
                    }[active] || ''">
                </span>
            </div>

            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Informasi detail sesuai statistik yang dipilih.
            </p>
        </div>

        <button
            type="button"
            @click="active = null"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition"
            title="Tutup Panel"
        >
            <span>Tutup</span>
            <span class="font-bold text-sm">✕</span>
        </button>
    </div>

    {{-- KONTEN DETAIL STATISTIK DENGAN SCROLL MANDIRI & STICKY SUB-HEADER MENYATU FULL --}}
    <div class="max-h-[520px] overflow-y-auto overscroll-contain">

        {{-- DAFTAR KARYAWAN --}}
        <div x-show="active === 'employees'" class="w-full">
            {{-- STICKY SUB-HEADER KOLOM FULL WIDTH (MENYATU TANPA JARAK) --}}
            <div class="sticky top-0 z-20 w-full bg-gray-50 border-b border-gray-200 px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-4">
                    <span class="w-8 text-center">No</span>
                    <span>Karyawan</span>
                </div>
                <span>Divisi</span>
            </div>

            <div class="px-6 divide-y divide-gray-100">
                @forelse ($employees as $index => $employee)
                    <div class="py-3.5 hover:bg-gray-50/80 transition flex items-center gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">
                            {{ $index + 1 }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-semibold text-gray-900 text-sm">
                                    {{ $employee->name }}
                                </p>
                                @if(!empty($employee->employee_code))
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-mono font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $employee->employee_code }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $employee->email }}
                            </p>
                        </div>

                        <span class="text-xs font-medium text-gray-600 text-right bg-gray-100 px-2.5 py-1 rounded-lg shrink-0">
                            {{ $employee->divisionRelation?->name ?? 'Tanpa Divisi' }}
                        </span>
                    </div>
                @empty
                    <p class="py-12 text-center text-sm text-gray-500">
                        Belum ada data karyawan.
                    </p>
                @endforelse
            </div>
        </div>

        {{-- DAFTAR KEPALA DIVISI --}}
        <div x-show="active === 'heads'" class="w-full">
            {{-- STICKY SUB-HEADER KOLOM FULL WIDTH (MENYATU TANPA JARAK) --}}
            <div class="sticky top-0 z-20 w-full bg-gray-50 border-b border-gray-200 px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-4">
                    <span class="w-8 text-center">No</span>
                    <span>Kepala Divisi</span>
                </div>
                <span>Divisi Ditugaskan</span>
            </div>

            <div class="px-6 divide-y divide-gray-100">
                @forelse ($kepalaDivisiList as $index => $head)
                    <div class="py-3.5 hover:bg-gray-50/80 transition flex items-center gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">
                            {{ $index + 1 }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-semibold text-gray-900 text-sm">
                                    {{ $head->name }}
                                </p>
                                @if(!empty($head->employee_code))
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-mono font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $head->employee_code }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $head->email }}
                            </p>
                        </div>

                        <span class="text-xs font-medium text-gray-600 text-right bg-gray-100 px-2.5 py-1 rounded-lg shrink-0">
                            {{ $head->divisionRelation?->name ?? 'Tanpa Divisi' }}
                        </span>
                    </div>
                @empty
                    <p class="py-12 text-center text-sm text-gray-500">
                        Belum ada kepala divisi.
                    </p>
                @endforelse
            </div>
        </div>

        {{-- DAFTAR DIVISI --}}
        <div x-show="active === 'divisions'" class="w-full">
            {{-- STICKY SUB-HEADER KOLOM FULL WIDTH (MENYATU TANPA JARAK) --}}
            <div class="sticky top-0 z-20 w-full bg-gray-50 border-b border-gray-200 px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-4">
                    <span class="w-8 text-center">No</span>
                    <span>Nama Divisi</span>
                </div>
                <span>Total Karyawan</span>
            </div>

            <div class="px-6 divide-y divide-gray-100">
                @forelse ($divisions as $index => $division)
                    <div class="py-3.5 hover:bg-gray-50/80 transition flex items-center gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">
                            {{ $index + 1 }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm">
                                {{ $division->name }}
                            </p>
                        </div>

                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 shrink-0">
                            {{ $division->users_count ?? 0 }} karyawan
                        </span>
                    </div>
                @empty
                    <p class="py-12 text-center text-sm text-gray-500">
                        Belum ada divisi.
                    </p>
                @endforelse
            </div>
        </div>

        {{-- DAFTAR TARGET AKTIF --}}
        <div x-show="active === 'targets'" class="w-full">
            {{-- STICKY SUB-HEADER KOLOM FULL WIDTH (MENYATU TANPA JARAK) --}}
            <div class="sticky top-0 z-20 w-full bg-gray-50 border-b border-gray-200 px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-4">
                    <span class="w-8 text-center">No</span>
                    <span>Target</span>
                </div>
                <span>Divisi & Periode</span>
            </div>

            <div class="px-6 divide-y divide-gray-100">
                @forelse ($activeTargets as $index => $target)
                    <div class="py-4 hover:bg-gray-50/80 transition flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs mt-0.5">
                            {{ $index + 1 }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm">
                                {{ $target->title }}
                            </p>

                            @if ($target->project_name)
                                <p class="text-xs font-medium text-emerald-600 mt-1 inline-flex items-center gap-1 bg-emerald-50 px-2 py-0.5 rounded">
                                    Project: {{ $target->project_name }}
                                </p>
                            @endif

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-xs text-gray-500">
                                <span>Divisi: <strong class="text-gray-700">{{ $target->division?->name ?? 'Tanpa Divisi' }}</strong></span>
                                <span>•</span>
                                <span>Periode: {{ $target->period_start?->format('d M Y') ?? '-' }} – {{ $target->period_end?->format('d M Y') ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="py-12 text-center text-sm text-gray-500">
                        Belum ada  Target aktif.
                    </p>
                @endforelse
            </div>
        </div>

        {{-- DAFTAR TOTAL LOGBOOK --}}
        <div x-show="active === 'logbooks'" class="w-full">
            {{-- STICKY SUB-HEADER KOLOM FULL WIDTH (MENYATU TANPA JARAK) --}}
            <div class="sticky top-0 z-20 w-full bg-gray-50 border-b border-gray-200 px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-4">
                    <span class="w-8 text-center">No</span>
                    <span>Karyawan & Aktivitas Logbook</span>
                </div>
                <span>Tanggal & Progres</span>
            </div>

            <div class="px-6 divide-y divide-gray-100">
                @forelse ($allDepartmentLogbooks as $index => $logbook)
                    <div class="py-4 hover:bg-gray-50/80 transition flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-xs mt-0.5">
                            {{ $index + 1 }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-1">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="font-semibold text-gray-900 text-sm">
                                            {{ $logbook->user?->name ?? 'Karyawan' }}
                                        </p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600">
                                            {{ $logbook->user?->divisionRelation?->name ?? $logbook->task?->division?->name ?? 'Tanpa Divisi' }}
                                        </span>
                                    </div>
                                    @if ($logbook->task)
                                        <p class="text-xs text-gray-500 mt-1">
                                            Pekerjaan: <span class="font-medium text-gray-700">{{ $logbook->task->title }}</span>
                                            @if ($logbook->task->target)
                                                · Target: <span class="text-emerald-700 font-medium">{{ $logbook->task->target->title }}</span>
                                            @endif
                                        </p>
                                    @else
                                        <p class="text-xs text-gray-400 mt-1 italic">
                                            Logbook Mandiri
                                        </p>
                                    @endif
                                </div>

                                <span class="text-xs text-gray-500 font-medium shrink-0 bg-gray-100 px-2 py-0.5 rounded">
                                    {{ $logbook->date?->format('d M Y') ?? '-' }}
                                </span>
                            </div>

                            <p class="text-xs sm:text-sm text-gray-600 mt-2 bg-gray-50/70 p-2.5 rounded-lg border border-gray-100">
                                {{ $logbook->activity }}
                            </p>


                            @if($logbook->progress !== null)
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 max-w-xs h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ min(100, $logbook->progress) }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-blue-600">
                                    {{ $logbook->progress }}%
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="py-12 text-center text-sm text-gray-500">
                        Belum ada data logbook di departemen ini.
                    </p>
                @endforelse
            </div>
        </div>

    </div>
</section>

        {{-- =====================================================
             KARYAWAN PER DIVISI & PENCAPAIAN PER DIVISI
        ====================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch mb-6">

            {{-- KARYAWAN PER DIVISI --}}
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden h-full flex flex-col">

                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-base font-semibold text-gray-900">Karyawan per Divisi</h2>
                    <p class="text-sm text-gray-500 mt-1">Distribusi karyawan dalam departemen.</p>
                </div>

                <div class="p-6 flex-1">

                    @forelse ($divisionEmployees as $divisionEmployee)

                        <div class="flex items-center justify-between py-4
                            {{ !$loop->last ? 'border-b border-gray-100' : '' }}">

                            <div class="min-w-0">
                                <p class="font-medium text-gray-900">
                                    {{ $divisionEmployee->divisionRelation?->name ?? 'Tanpa Divisi' }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Dalam departemen {{ $department->name }}
                                </p>
                            </div>

                            <div class="text-right shrink-0">
                                <p class="text-2xl font-semibold text-gray-900">{{ $divisionEmployee->total }}</p>
                                <p class="text-xs text-gray-500">Karyawan</p>
                            </div>

                        </div>

                    @empty

                        <div class="min-h-[180px] flex items-center justify-center">
                            <p class="text-sm text-gray-500">Belum ada data karyawan per divisi.</p>
                        </div>

                    @endforelse

                </div>

            </section>


            {{-- PENCAPAIAN PEKERJAAN PER DIVISI --}}
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden h-full flex flex-col">

                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-base font-semibold text-gray-900">Pencapaian Pekerjaan per Divisi</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Pencapaian pekerjaan per divisi berdasarkan progress yang telah diverifikasi Kepala Divisi.
                    </p>
                </div>

                <div class="p-6 flex-1">

                    @forelse ($divisionTaskProgress as $divisionProgress)

                        <div class="py-4
                            {{ !$loop->last ? 'border-b border-gray-100' : '' }}">

                            <div class="flex items-center justify-between gap-4">

                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900">
                                        {{ $divisionProgress->division?->name ?? 'Tanpa Divisi' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $divisionProgress->total_tasks }} pekerjaan
                                    </p>
                                </div>

                                <span class="text-sm font-semibold text-emerald-600 shrink-0">
                                    {{ $divisionProgress->progress }}%
                                </span>

                            </div>

                            <div class="mt-3 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div
                                    class="h-full bg-emerald-500 rounded-full"
                                    style="width: {{ min(100, $divisionProgress->progress) }}%"
                                ></div>
                            </div>

                        </div>

                    @empty

                        <div class="min-h-[180px] flex items-center justify-center">
                            <p class="text-sm text-gray-500">Belum ada pekerjaan yang dapat dimonitor.</p>
                        </div>

                    @endforelse

                </div>

            </section>

        </div>



        {{-- =====================================================
            PROGRESS KESELURUHAN TARGET
        ====================================================== --}}

        @php
            // Rata-rata aktual seluruh Target aktif.
            $overallTargetProgress = round(
                (float) ($targetTaskMonitoring->avg('actual_progress') ?? 0),
                1
            );

            $overallTargetProgress = min(
                100,
                max(0, $overallTargetProgress)
            );

            $totalMonitoredTargets = $targetTaskMonitoring->count();

            $completedTargets = $targetTaskMonitoring
                ->filter(function ($item) {
                    return (float) ($item->actual_progress ?? 0) >= 100;
                })
                ->count();
        @endphp

        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">

            {{-- HEADER --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Progress Keseluruhan Target
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Ringkasan pencapaian seluruh Target
                        dalam departemen {{ $department->name }}.
                    </p>
                </div>

                <div class="text-left sm:text-right">
                    <p class="text-3xl font-bold text-emerald-600">
                        {{ $overallTargetProgress }}%
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Progress keseluruhan
                    </p>
                </div>
            </div>

            {{-- PROGRESS BAR --}}
            <div
                class="w-full h-4 bg-gray-100 rounded-full overflow-hidden"
                role="progressbar"
                aria-label="Progress keseluruhan Target"
                aria-valuemin="0"
                aria-valuemax="100"
                aria-valuenow="{{ $overallTargetProgress }}"
            >
                <div
                    class="h-full bg-emerald-500 rounded-full transition-all duration-700 ease-out"
                    style="width: {{ $overallTargetProgress }}%"
                ></div>
            </div>

            {{-- RINGKASAN --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mt-4">
                <span class="inline-flex items-center gap-2 text-xs font-medium text-emerald-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    {{ $department->name }}
                </span>

            </div>

            @if ($totalMonitoredTargets === 0)
                <p class="text-sm text-gray-400 mt-3">
                    Belum ada Target untuk dihitung.
                </p>
            @endif

        </section>

        {{-- FILTER DAN GRAFIK MONITORING DEPARTEMEN --}}
        <div class="space-y-6 mb-8">

            <form
                method="GET"
                action="{{ route('kepala_departemen.dashboard') }}"
                class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6"
            >
                <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Filter Monitoring</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Pilih rentang waktu, divisi, dan karyawan dalam departemen {{ $department->name }}.
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100 self-start sm:self-auto">
                        Mode Grafik: {{ $groupingLabel }}
                    </div>
                </div>

                @if ($errors->any())
                    <div role="alert" class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- PILIHAN CEPAT RENTANG WAKTU (SISTEM BRIMO) --}}
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-600 mb-2">
                        Pilihan Cepat Rentang Waktu
                    </label>
                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            data-preset="7_days"
                            class="filter-preset-pill px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === '7_days' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                        >
                            7 Hari Terakhir
                        </button>
                        <button
                            type="button"
                            data-preset="30_days"
                            class="filter-preset-pill px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === '30_days' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                        >
                            30 Hari Terakhir
                        </button>
                        <button
                            type="button"
                            data-preset="this_month"
                            class="filter-preset-pill px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === 'this_month' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                        >
                            Bulan Ini
                        </button>
                        <button
                            type="button"
                            data-preset="last_month"
                            class="filter-preset-pill px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === 'last_month' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                        >
                            Bulan Sebelumnya
                        </button>
                        <button
                            type="button"
                            data-preset="this_year"
                            class="filter-preset-pill px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === 'this_year' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                        >
                            Tahun Ini
                        </button>

                        {{-- MENU PILIH BULAN (BULAN DAN TAHUN BISA DIUBAH SEPERTI DI BRIMO) --}}
                        <div class="relative inline-block">
                            <button
                                type="button"
                                id="btnMonthPickerDropdown"
                                data-preset="month_picker"
                                class="filter-preset-pill inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === 'month_picker' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span id="monthPickerBtnLabel">
                                    {{ $preset === 'month_picker' ? 'Bulan: ' . ($monthNames[$filterMonth] ?? 'Bulan ' . $filterMonth) . ' ' . $filterYear : 'Pilih Bulan' }}
                                </span>
                                <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            {{-- POPOVER PILIH BULAN & TAHUN BRIMO --}}
                            <div
                                id="monthPickerPopover"
                                class="hidden absolute left-0 top-full mt-2 z-30 w-72 rounded-2xl bg-white border border-gray-200 p-4 shadow-xl"
                            >
                                <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100">
                                    <span class="text-xs font-bold text-gray-800">Pilih Bulan & Tahun</span>
                                    <div class="flex items-center gap-1.5">
                                        <label for="monthPickerYear" class="text-[11px] text-gray-500 font-medium">Tahun:</label>
                                        <select
                                            id="monthPickerYear"
                                            class="text-xs font-bold text-blue-700 rounded-lg border-gray-200 py-1 px-2 focus:ring-blue-500 focus:border-blue-500"
                                        >
                                            @foreach ($availableChartYears as $yr)
                                                <option value="{{ $yr }}" @selected($filterYear == $yr)>{{ $yr }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- GRID 12 BULAN --}}
                                <div class="grid grid-cols-3 gap-2">
                                    @php
                                        $shortMonths = [
                                            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                                            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
                                        ];
                                    @endphp
                                    @foreach ($shortMonths as $mNum => $mShort)
                                        <button
                                            type="button"
                                            data-month="{{ $mNum }}"
                                            class="month-picker-item py-2 px-2 text-center rounded-xl text-xs font-medium transition cursor-pointer {{ $preset === 'month_picker' && $filterMonth == $mNum ? 'bg-blue-600 text-white font-bold shadow-xs' : 'bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700' }}"
                                        >
                                            {{ $mShort }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="filter_month" id="filter_month" value="{{ $filterMonth }}">
                    <input type="hidden" name="filter_year" id="filter_year" value="{{ $filterYear }}">
                    <input type="hidden" name="preset" id="filter_preset" value="{{ old('preset', $preset) }}">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="filter_start_date" class="block text-xs font-medium text-gray-600 mb-1">
                            Tanggal Mulai 
                        </label>
                        <input
                            type="date"
                            id="filter_start_date"
                            name="start_date"
                            value="{{ old('start_date', $startDate->format('Y-m-d')) }}"
                            required
                            class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="filter_end_date" class="block text-xs font-medium text-gray-600 mb-1">
                            Tanggal Akhir 
                        </label>
                        <input
                            type="date"
                            id="filter_end_date"
                            name="end_date"
                            value="{{ old('end_date', $endDate->format('Y-m-d')) }}"
                            min="{{ old('start_date', $startDate->format('Y-m-d')) }}"
                            required
                            class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="chart_division" class="block text-xs font-medium text-gray-600 mb-1">
                            Divisi
                        </label>
                        <select
                            id="chart_division"
                            name="division_id"
                            class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Semua Divisi</option>
                            @foreach ($divisions as $division)
                                <option
                                    value="{{ $division->id }}"
                                    @selected((int) $selectedChartDivisionId === (int) $division->id)
                                >
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="chart_employee" class="block text-xs font-medium text-gray-600 mb-1">
                            Karyawan
                        </label>
                        <select
                            id="chart_employee"
                            name="employee_id"
                            class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Semua Karyawan</option>
                            @foreach ($chartEmployees as $chartEmployee)
                                <option
                                    value="{{ $chartEmployee->id }}"
                                    data-division-id="{{ $chartEmployee->division_id }}"
                                    @selected((int) $selectedChartEmployeeId === (int) $chartEmployee->id)
                                >
                                    {{ $chartEmployee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- INDIKATOR FILTER AKTIF --}}
                @php
                    $activeFilterBadges = [];
                    if ($preset === '7_days') $activeFilterBadges[] = 'Periode: 7 Hari Terakhir';
                    elseif ($preset === '30_days') $activeFilterBadges[] = 'Periode: 30 Hari Terakhir';
                    elseif ($preset === 'last_month') $activeFilterBadges[] = 'Periode: Bulan Sebelumnya';
                    elseif ($preset === 'this_year') $activeFilterBadges[] = 'Periode: Tahun Ini';
                    elseif ($preset === 'month_picker') $activeFilterBadges[] = 'Bulan: ' . ($monthNames[$filterMonth] ?? $filterMonth) . ' ' . $filterYear;
                    elseif ($preset === 'custom') $activeFilterBadges[] = 'Rentang: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');

                    if ($selectedChartDivisionId) {
                        $divObj = $divisions->firstWhere('id', $selectedChartDivisionId);
                        if ($divObj) $activeFilterBadges[] = 'Divisi: ' . $divObj->name;
                    }
                    if ($selectedChartEmployeeId) {
                        $empObj = $chartEmployees->firstWhere('id', $selectedChartEmployeeId);
                        if ($empObj) $activeFilterBadges[] = 'Karyawan: ' . $empObj->name;
                    }
                @endphp

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-5 pt-4 border-t border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        @if (!empty($activeFilterBadges))
                            <span class="text-xs font-semibold text-gray-500 mr-1">Filter Aktif:</span>
                            @foreach ($activeFilterBadges as $badge)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $badge }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-xs text-gray-400">Menampilkan periode bulan ini (default).</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a
                            href="{{ route('kepala_departemen.dashboard') }}"
                            class="px-4 py-2 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition"
                        >
                            Reset
                        </a>
                        <button
                            type="submit"
                            class="px-5 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition cursor-pointer"
                        >
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>

            {{-- =====================================================
                 PROGRESS TARGET DAN TASK (DIAGRAM BATANG & DRILLDOWN GARIS)
            ====================================================== --}}
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Progress Target dan Task</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Grafik batang Planning vs Aktual per task. Klik batang untuk melihat rincian progres dalam <strong class="text-blue-600">diagram garis (line chart)</strong>. Filter data khusus untuk divisi dalam departemen <span class="font-medium text-gray-700">{{ $department->name }}</span>.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100 self-start md:self-auto">
                        Lingkup Departemen: {{ $department->name }}
                    </span>
                </div>

                @if ($startDate && $endDate && $startDate->lte($endDate))
                    <div class="w-full min-w-0">
                        {{-- LEGEND BARCHART --}}
                        <div id="departmentBarLegend" class="flex flex-wrap items-center justify-between gap-3 mb-4 p-3 bg-gray-50/80 rounded-xl border border-gray-100 text-xs">
                            <div class="flex items-center gap-5">
                                <span class="inline-flex items-center gap-2 font-medium text-gray-700">
                                    <span class="w-3.5 h-3.5 rounded bg-blue-500 inline-block shadow-sm"></span>
                                    Planning (%)
                                </span>
                                <span class="inline-flex items-center gap-2 font-medium text-gray-700">
                                    <span class="w-3.5 h-3.5 rounded bg-emerald-500 inline-block shadow-sm"></span>
                                    Aktual (%)
                                </span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-gray-500 hidden sm:inline-flex items-center gap-1">
                                     <span>Klik batang untuk diagram garis histori progres</span>
                                </span>
                                <span class="text-xs text-gray-500 font-medium">{{ $startDate->format('d/m/Y') }} – {{ $endDate->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        {{-- HEADER DRILLDOWN LINE CHART --}}
                        <div id="departmentDrilldownHeader" class="hidden flex flex-wrap items-center justify-between gap-3 p-4 mb-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl shadow-sm">
                            <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    id="departmentChartBack"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                    Kembali
                                </button>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span id="departmentDrilldownBadge" class="px-2 py-0.5 rounded-md bg-blue-600 text-white text-[11px] font-bold">Diagram Garis (Histori Progres)</span>
                                        <h3 id="departmentDrilldownTitle" class="text-sm font-bold text-blue-950"></h3>
                                    </div>
                                    <p id="departmentDrilldownSubtitle" class="text-xs text-blue-700 mt-1"></p>
                                </div>
                            </div>
                        </div>

                        {{-- DIAGRAM BATANG BULANAN --}}
                        <div id="departmentBarChartWrapper" class="w-full overflow-x-auto">
                            <div id="departmentBarChartInner" class="relative h-[440px] min-w-full">
                                <canvas id="departmentTaskBarChart"></canvas>
                            </div>
                        </div>

                        {{-- DIAGRAM GARIS HISTORI PROGRES (DRILLDOWN LINE CHART) --}}
                        <div id="departmentWeeklyLineWrapper" class="hidden">
                            <div class="relative h-[420px] w-full">
                                <canvas id="departmentTaskWeeklyLineChart"></canvas>
                            </div>
                            <div id="departmentWeeklySummaryGrid" class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mt-4 pt-4 border-t border-gray-100"></div>
                        </div>

                        <p id="departmentChartEmpty" class="hidden py-12 text-center text-sm text-gray-500">
                            Belum ada task untuk filter yang dipilih.
                        </p>

                        {{-- KETERANGAN TASK RAPI & JELAS DENGAN BENTUK TABEL & PAGINATION (10 TASK PER HALAMAN) --}}
                        <div id="deptTaskCatalogWrapper" class="mt-6 border-t border-gray-100 pt-5">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Keterangan Task
                                </h3>
                                <span id="deptTaskCatalogCount" class="text-xs text-gray-400"></span>
                            </div>

                            {{-- TABEL KETERANGAN TASK --}}
                            <div class="overflow-x-auto rounded-xl border border-gray-200/80 bg-white">
                                <table class="w-full text-left text-sm text-gray-600">
                                    <thead class="bg-gray-50/75 border-b border-gray-200 text-xs font-semibold text-gray-700">
                                        <tr>
                                            <th scope="col" class="w-16 px-4 py-3.5 text-center">No</th>
                                            <th scope="col" class="px-4 py-3.5">Nama Target</th>
                                            <th scope="col" class="px-4 py-3.5">Divisi</th>
                                            <th scope="col" class="px-4 py-3.5">Karyawan</th>
                                            <th scope="col" class="px-4 py-3.5">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="deptTaskCatalogTbody" class="divide-y divide-gray-100">
                                        {{-- Diisi secara dinamis via JavaScript sesuai task yang tampil di chart --}}
                                    </tbody>
                                </table>
                            </div>

                            {{-- PAGINATION KETERANGAN TASK (10 TASK PER HALAMAN) --}}
                            <div id="deptTaskPaginationWrapper" class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-gray-100 text-xs text-gray-600">
                                <span id="deptTaskPaginationInfo"></span>
                                <div id="deptTaskPaginationButtons" class="flex items-center gap-1"></div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="py-12 text-center text-sm text-gray-500">
                        Pilih rentang tanggal yang valid untuk menampilkan grafik monitoring.
                    </p>
                @endif
            </section>

            {{-- ========================================================= --}}
            {{-- PROGRESS AGREGAT TARGET: DONUT GAUGE 2 SEGMEN & 3 DETIK GLANCE --}}
            {{-- ========================================================= --}}
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
                {{-- Header Section --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 pb-4 mb-5 border-b border-gray-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            <h2 class="text-lg font-bold text-gray-900">Progress Agregat Target (Single Nested Donut Chart)</h2>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            Grafik Donat Berlapis minimalis & profesional · Cincin Dalam (Bobot Target 5x20%) vs Cincin Luar (Progress Aktual) mewakili 100% target divisi.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100 self-start md:self-auto">
                        Departemen: {{ $department->name }}
                    </span>
                </div>

                @if ($startDate && $endDate && $startDate->lte($endDate))
                    {{-- Filter Kontrol Kepala Departemen: Divisi & Karyawan --}}
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 bg-gray-50/80 p-3.5 rounded-2xl border border-gray-200/80">
                        <div class="flex flex-wrap items-center gap-4">
                            {{-- Filter Divisi --}}
                            <div class="flex items-center gap-2">
                                <label for="deptPieDivisionFilter" class="text-xs font-bold text-gray-700 whitespace-nowrap">Divisi:</label>
                                <select
                                    id="deptPieDivisionFilter"
                                    class="rounded-lg border-gray-300 text-xs py-1.5 px-3 focus:border-blue-500 focus:ring-blue-500 bg-white font-medium min-w-[200px]"
                                >
                                    @foreach ($divisions ?? [] as $div)
                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Karyawan --}}
                            <div class="flex items-center gap-2">
                                <label for="deptPieEmployeeFilter" class="text-xs font-bold text-gray-700 whitespace-nowrap">Karyawan:</label>
                                <select
                                    id="deptPieEmployeeFilter"
                                    class="rounded-lg border-gray-300 text-xs py-1.5 px-3 focus:border-blue-500 focus:ring-blue-500 bg-white font-medium min-w-[220px]"
                                >
                                    @foreach ($employees ?? [] as $emp)
                                        <option value="{{ $emp->id }}" data-division-id="{{ $emp->division_id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <span id="deptSubTargetCountBadge" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
                            Total Sub Target: {{ count($pieContributionData) }}
                        </span>
                    </div>

                    {{-- SINGLE NESTED DONUT CHART & 5 IRISAN TARGET DETAIL --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 my-6 items-start">
                        {{-- SISI KIRI: DONUT CHART BERLAPIS DENGAN CENTER HOLE --}}
                        <div class="lg:col-span-6 flex flex-col items-center justify-center p-6 bg-white rounded-2xl border border-gray-200/90 shadow-2xs relative">
                            <div class="w-full flex items-center justify-between pb-3 mb-2 border-b border-gray-100 text-xs">
                                <span class="font-bold text-gray-800 uppercase tracking-wider">Lingkaran 100% Target Divisi</span>
                                <span class="text-[11px] font-semibold text-gray-400">Nested Donut (Bobot & Progres)</span>
                            </div>

                            {{-- Canvas Container --}}
                            <div class="relative w-72 h-72 sm:w-80 sm:h-80 flex items-center justify-center my-3">
                                <canvas id="departmentContributionPie"></canvas>
                                
                                {{-- CENTER HOLE: TOTAL PROGRESS AGREGAT (TEKS TEBAL & BESAR BISA DIBACA 1 DETIK) --}}
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Total Progress Agregat</span>
                                    <span id="deptAggregatePercent" class="text-3xl sm:text-4xl font-black font-mono tracking-tight text-gray-900 my-0.5">
                                        0%
                                    </span>
                                    <span id="deptAggregateBadge" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold shadow-xs bg-gray-100 text-gray-700">
                                        ● MEMUAT
                                    </span>
                                </div>
                            </div>

                            {{-- Panduan Struktur Donut Berlapis --}}
                            <div class="w-full grid grid-cols-2 gap-3 pt-3 border-t border-gray-100 text-xs mt-2">
                                <div class="p-2.5 rounded-xl bg-gray-50/80 border border-gray-100 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0"></span>
                                    <span class="font-bold text-gray-800">Bagian Luar : Progress </span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-gray-50/80 border border-gray-100 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0"></span>
                                    <span class="font-bold text-gray-800">Bagian Dalam : Sub Target</span>
                                </div>
                            </div>
                        </div>

                        {{-- SISI KANAN: 5 KARTU IRISAN KUADRAN BOBOT VS PROGRESS --}}
                        <div class="lg:col-span-6 flex flex-col gap-3">
                            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800">
                                        Rincian Irisan Sub Target (Bobot vs Progres)
                                    </h3>
                                </div>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                    Bobot 100%
                                </span>
                            </div>

                            <div id="deptQuadrantCardList" class="space-y-3">
                                {{-- Diisi secara dinamis melalui JavaScript --}}
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL EKSEKUSI SUB-TARGET --}}
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">
                                    Detail Eksekusi Sub-Target
                                </h3>
                                <p class="text-xs text-gray-500">
                                    Rincian status sub-target dan pekerjaan karyawan di bawah lingkup departemen.
                                </p>
                            </div>

                            {{-- PANDUAN SEMANTIK WARNA --}}
                            <div class="flex items-center gap-4 text-xs font-semibold">
                                <span class="inline-flex items-center gap-1.5 text-emerald-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Selesai (100%)
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-blue-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>Berjalan (&gt;0%)
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-gray-400">
                                    <span class="w-2.5 h-2.5 rounded-full bg-gray-300"></span>Belum Mulai (0%)
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="departmentPieLegendList">
                            {{-- Diisi secara dinamis melalui JavaScript --}}
                        </div>
                    </div>
                @else
                    <p class="py-12 text-center text-sm text-gray-500">
                        Pilih rentang tanggal yang valid untuk menampilkan grafik monitoring.
                    </p>
                @endif
            </section>
        </div>

        {{-- =====================================================
             MONITORING: TARGET + PEKERJAAN (pekerjaan di bawah tiap sub target)
        ====================================================== --}}

        {{-- Buat lookup targetTaskMonitoring berdasarkan id --}}
        @php
            $taskMonitoringMap = $targetTaskMonitoring->keyBy('id');
        @endphp

        @if ($department->targets->isNotEmpty())

            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col mb-8">

                {{-- STICKY HEADER UTAMA MONITORING TARGET --}}
                <div class="sticky top-0 z-20 bg-white/95 backdrop-blur px-6 py-4 sm:py-5 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3 shrink-0">
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-base sm:text-lg font-bold text-gray-900">Monitoring Target</h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $department->targets->count() }} Target
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Target departemen beserta pekerjaan yang dilaksanakan pada setiap sub target.
                        </p>
                    </div>
                </div>

                {{-- KONTEN SCROLL MANDIRI --}}
                <div class="p-6 max-h-[620px] overflow-y-auto overscroll-contain space-y-6">

                    @foreach ($department->targets as $target)

                        @php
                            $taskTarget  = $taskMonitoringMap->get($target->id);
                            $taskSubMap  = $taskTarget ? $taskTarget->subTargets->keyBy('id') : collect();
                            $totalBobot  = $target->subTargets->sum('percentage');
                        @endphp

                        <div class="relative bg-white rounded-2xl border border-slate-200/90 border-l-[8px] border-l-emerald-600 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">

                            {{-- ===========================================
                                 HEADER: TARGET (LEVEL 1 / TARGET UTAMA)
                            ============================================ --}}
                            <div class="px-5 sm:px-6 py-4 bg-gradient-to-r from-emerald-600 via-teal-700 to-slate-800 text-white flex flex-wrap items-center justify-between gap-3 shadow-xs">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 backdrop-blur-xs text-white border border-white/30 shadow-xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                        </svg>
                                         TARGET 
                                    </span>

                                    @if ($target->project_name)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-100 bg-white/10 border border-white/20 rounded-full px-3 py-1 backdrop-blur-xs">
                                            <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                            Project: {{ $target->project_name }}
                                        </span>
                                    @endif
                                </div>

                                @if ($taskTarget)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-emerald-100 font-medium hidden sm:inline">Progres Kumulatif:</span>
                                        <span class="text-xs sm:text-sm font-black text-emerald-950 bg-emerald-100 px-3 py-1 rounded-full border border-white/40 shadow-xs">
                                            {{ $taskTarget->actual_progress }}% Aktual
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-5 sm:p-6 bg-white">

                                <div class="min-w-0">
                                    <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight leading-snug">
                                        {{ $target->title }}
                                    </h3>

                                    <div class="flex flex-wrap items-center gap-2.5 mt-2.5">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/70">
                                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            Divisi: {{ $target->division?->name ?? 'Belum ditentukan' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Periode: {{ $target->period_start?->format('d M Y') ?? '-' }} – {{ $target->period_end?->format('d M Y') ?? '-' }}
                                        </span>
                                    </div>

                                    @if ($target->description)
                                        <p class="text-sm text-slate-600 mt-3 leading-relaxed">
                                            {{ $target->description }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Bar Progres Utama Target --}}
                                <div class="mt-4 pt-3.5 border-t border-slate-100">
                                    <div class="flex items-center justify-between text-xs mb-1.5">
                                        <span class="font-bold text-slate-700 flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Pencapaian Target Utama
                                        </span>
                                        <span class="font-extrabold text-emerald-700">{{ $taskTarget ? $taskTarget->actual_progress : 0 }}%</span>
                                    </div>
                                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden p-0.5">
                                        <div
                                            class="h-full bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 rounded-full transition-all duration-500"
                                            style="width: {{ min(100, $taskTarget ? $taskTarget->actual_progress : 0) }}%"
                                        ></div>
                                    </div>
                                </div>

                                {{-- Pembagian Sub Target (Accordion dengan Efek Kasta) --}}
                                @if ($target->subTargets->isNotEmpty())

                                    <div class="mt-6 pt-5 border-t border-slate-200">

                                        {{-- Toggle Header + Total Bobot di kanan --}}
                                        <button
                                            type="button"
                                            onclick="toggleSubTarget('subtarget-{{ $target->id }}')"
                                            class="w-full flex items-center justify-between gap-3 p-3.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-left transition-all duration-150 group cursor-pointer shadow-2xs"
                                        >
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs shadow-xs">
                                                    ↳
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-bold text-slate-800 uppercase tracking-wide">
                                                            Pembagian Sub Target
                                                        </span>
                                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">
                                                            {{ $target->subTargets->count() }} Sub Target
                                                        </span>
                                                    </div>
                                                    <p class="text-[11px] text-slate-500 mt-0.5">Kasta Level 2: Turunan dari Target Utama</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <div class="text-right hidden sm:block">
                                                    <span class="text-xs text-slate-500">
                                                        Total Bobot:
                                                        <span class="font-bold text-slate-900">{{ $totalBobot }}%</span>
                                                    </span>
                                                </div>

                                                @if ($totalBobot == 100)
                                                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full shadow-2xs">✓ 100% Lengkap</span>
                                                @else
                                                    <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full shadow-2xs">⚠ Belum 100%</span>
                                                @endif

                                                <div class="w-6 h-6 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-2xs group-hover:border-slate-300">
                                                    <svg
                                                        id="icon-subtarget-{{ $target->id }}"
                                                        class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200"
                                                        style="transform: rotate(180deg)"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </button>

                                        {{-- Collapsible Content: TREE CONNECTOR HIERARKI SUB TARGET --}}
                                        <div id="subtarget-{{ $target->id }}" class="mt-4">

                                            <div class="relative ml-2 sm:ml-5 pl-4 sm:pl-7 border-l-2 border-dashed border-indigo-300 space-y-5 my-2">

                                                @foreach ($target->subTargets as $subTarget)

                                                    @php
                                                        $subTaskData  = $taskSubMap->get($subTarget->id);
                                                        $subTasks     = $subTaskData?->tasks ?? collect();
                                                        $subTargetAvg = $subTasks->isNotEmpty() ? round($subTasks->avg('verified_progress'), 1) : 0;
                                                    @endphp

                                                    <div class="relative group/subcard">

                                                        {{-- Branch Connector Visual Node --}}
                                                        <div class="absolute -left-[21px] sm:-left-[33px] top-6 w-3 sm:w-6 h-0.5 bg-indigo-300"></div>
                                                        <div class="absolute -left-[25px] sm:-left-[37px] top-[20px] w-2.5 h-2.5 rounded-full bg-indigo-500 ring-4 ring-white shadow-xs"></div>

                                                        {{-- SUB TARGET CARD (LEVEL 2) --}}
                                                        <div class="rounded-xl border border-indigo-200/90 bg-gradient-to-br from-indigo-50/40 via-white to-slate-50/50 shadow-xs hover:shadow-md hover:border-indigo-400 transition-all duration-200 overflow-hidden border-l-[5px] border-l-indigo-600">

                                                            {{-- Sub Target Header --}}
                                                            <div class="px-4 sm:px-5 py-3.5 bg-gradient-to-r from-indigo-50/90 to-purple-50/40 border-b border-indigo-100 flex flex-wrap items-center justify-between gap-2.5">

                                                                <div class="min-w-0 flex-1">
                                                                    <div class="flex items-center gap-2 mb-1">
                                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-indigo-600 text-white shadow-2xs">
                                                                            SUB TARGET
                                                                        </span>
                                                                        <span class="text-xs font-semibold text-indigo-700 bg-indigo-100/70 border border-indigo-200 px-2 py-0.5 rounded">
                                                                            Bobot: {{ $subTarget->percentage }}%
                                                                        </span>
                                                                    </div>
                                                                    <h4 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">
                                                                        {{ $subTarget->title }}
                                                                    </h4>
                                                                    <p class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1">
                                                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                        {{ $subTarget->period_start?->format('d M Y') ?? '-' }} – {{ $subTarget->period_end?->format('d M Y') ?? '-' }}
                                                                    </p>
                                                                </div>

                                                                <div class="flex items-center gap-2 shrink-0">
                                                                    <div class="text-right">
                                                                        <span class="text-[10px] font-semibold text-slate-400 block uppercase">Progres Sub Target</span>
                                                                        <span class="text-xs sm:text-sm font-extrabold text-indigo-700 bg-white border border-indigo-200 px-3 py-1 rounded-full shadow-2xs inline-block">
                                                                            {{ $subTargetAvg }}%
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            {{-- Pekerjaan di bawah sub target (LEVEL 3) --}}
                                                            <div class="p-4 sm:p-5 bg-white/70">

                                                                <div class="flex items-center justify-between mb-3">
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wider">
                                                                            LEVEL 3 · PEKERJAAN
                                                                        </span>
                                                                        <span class="text-xs text-slate-500 font-medium">
                                                                            ({{ $subTasks->count() }} pekerjaan teknis)
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                @forelse ($subTasks as $task)

                                                                    <div class="rounded-xl bg-white border border-slate-200/80 hover:border-indigo-300 hover:shadow-xs p-3.5 mb-2.5 last:mb-0 transition-all duration-150">

                                                                        <div class="flex items-start justify-between gap-3">

                                                                            <div class="min-w-0 flex-1">
                                                                                <div class="flex items-center gap-2">
                                                                                    <span class="w-5 h-5 rounded-md bg-slate-100 text-slate-600 font-bold text-[10px] flex items-center justify-center shrink-0">
                                                                                        #
                                                                                    </span>
                                                                                    <p class="text-xs sm:text-sm font-bold text-slate-800 leading-snug">
                                                                                        {{ $task->title }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="flex flex-wrap items-center gap-2 mt-2 ml-7">
                                                                                    <span class="inline-flex items-center gap-1 text-[11px] text-slate-600 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-md">
                                                                                        Divisi: {{ $task->division?->name ?? 'Tanpa Divisi' }}
                                                                                    </span>
                                                                                    <span class="inline-flex items-center gap-1 text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md font-medium">
                                                                                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                                                        {{ $task->assignedTo?->name ?? 'Belum ditentukan' }}
                                                                                    </span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="text-right shrink-0">
                                                                                <span class="text-[10px] font-medium text-slate-400 block">Progres</span>
                                                                                <span class="text-xs sm:text-sm font-bold text-slate-800">
                                                                                    {{ $task->verified_progress ?? 0 }}%
                                                                                </span>
                                                                            </div>

                                                                        </div>

                                                                        {{-- Progress Bar Task --}}
                                                                        <div class="mt-2.5 ml-7 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                                            <div
                                                                                class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-500"
                                                                                style="width: {{ min(100, $task->verified_progress ?? 0) }}%"
                                                                            ></div>
                                                                        </div>

                                                                    </div>

                                                                @empty

                                                                    <div class="rounded-xl bg-slate-50 border border-dashed border-slate-200 p-4 text-center">
                                                                        <p class="text-xs sm:text-sm text-slate-400">
                                                                            Belum ada pekerjaan yang terhubung pada Sub Target ini.
                                                                        </p>
                                                                    </div>

                                                                @endforelse

                                                            </div>

                                                        </div>

                                                    </div>

                                                @endforeach

                                            </div>

                                        </div>

                                    </div>

                                @else
                                    <div class="mt-4 rounded-xl bg-slate-50 border border-dashed border-slate-200 p-4 text-center">
                                        <p class="text-xs sm:text-sm text-slate-400">Belum ada Sub Target pada Target ini.</p>
                                    </div>
                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            </section>

        @else

            <div class="mb-8 bg-white rounded-2xl border border-gray-200 shadow-sm p-10 text-center">
                <p class="text-sm text-gray-500">Belum ada Target aktif.</p>
            </div>

        @endif

        {{-- =====================================================
             MONITORING LOGBOOK
        ====================================================== --}}
        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col mb-8">

            {{-- STICKY HEADER UTAMA MONITORING LOGBOOK --}}
            <div class="sticky top-0 z-20 bg-white px-6 py-4 sm:py-5 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3 shrink-0 shadow-xs">
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-base sm:text-lg font-bold text-gray-900">Monitoring Logbook</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ count($logbookMonitoring) }} Logbook
                        </span>
                        <span class="text-xs text-gray-500 font-medium hidden sm:inline">
                            · {{ $startDate->format('d/m/Y') }} – {{ $endDate->format('d/m/Y') }}
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Perkembangan pekerjaan berdasarkan laporan logbook karyawan pada rentang waktu terpilih.
                    </p>
                </div>
            </div>

            {{-- KONTEN SCROLL MANDIRI LOGBOOK --}}
            <div class="p-6 max-h-[600px] overflow-y-auto overscroll-contain">

                @forelse ($logbookMonitoring as $logbook)

                    <div class="border border-gray-200 rounded-xl p-4 mb-4 last:mb-0 hover:border-blue-200 hover:shadow-xs transition">

                        {{-- Karyawan --}}
                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $logbook->user?->name ?? 'Karyawan' }}
                                </p>

                                @if ($logbook->task)
                                    <p class="text-xs text-gray-500 mt-1">
                                        Pekerjaan: {{ $logbook->task->title }}
                                    </p>
                                @endif
                            </div>

                            <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $logbook->monitoring_status === 'Sesuai Progress'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $logbook->monitoring_status }}
                            </span>

                        </div>


                        {{-- Target --}}
                        @if ($logbook->task?->target)

                            <div class="mt-4 rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">

                                @if ($logbook->task->target->project_name)
                                    <p class="text-xs text-emerald-600 font-semibold uppercase tracking-wide">Project</p>
                                    <p class="text-sm font-medium text-gray-700 mt-1">{{ $logbook->task->target->project_name }}</p>
                                @endif

                                <p class="text-xs text-gray-400 uppercase tracking-wide mt-3">Target</p>
                                <p class="mt-1 text-sm font-medium text-gray-700">{{ $logbook->task->target->title }}</p>

                                @if ($logbook->task->subTarget)
                                    <p class="text-xs text-blue-600 mt-2">
                                        Sub Target: {{ $logbook->task->subTarget->title }}
                                        · Bobot {{ $logbook->task->subTarget->percentage }}%
                                    </p>
                                @endif

                            </div>

                        @endif


                        {{-- Progress --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">

                            <div class="rounded-lg bg-gray-50 px-4 py-3">
                                <p class="text-xs text-gray-400">Progress Logbook</p>
                                <p class="mt-1 text-sm font-semibold text-gray-800">{{ $logbook->progress }}%</p>
                            </div>

                            <div class="rounded-lg bg-gray-50 px-4 py-3">
                                <p class="text-xs text-gray-400">Tanggal</p>
                                <p class="mt-1 text-sm font-semibold text-gray-800">
                                    {{ $logbook->date?->format('d M Y') ?? '-' }}
                                </p>
                            </div>

                        </div>


                        {{-- Progress Bar --}}
                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-medium text-gray-500">Progress pekerjaan</p>
                                <p class="text-xs font-semibold text-gray-700">{{ $logbook->progress }}%</p>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div
                                    class="h-full rounded-full bg-emerald-500"
                                    style="width: {{ min(100, $logbook->progress) }}%"
                                ></div>
                            </div>
                        </div>


                        {{-- Aktivitas --}}
                        <div class="mt-4">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Aktivitas</p>
                            <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $logbook->activity }}</p>
                        </div>

                    </div>

                @empty

                    <div class="min-h-[180px] flex items-center justify-center">
                        <p class="text-sm text-gray-500">
                            Belum ada logbook yang terhubung dengan pekerjaan.
                        </p>
                    </div>

                @endforelse

            </div>

        </section>

        {{-- TOMBOL KEMBALI KE ATAS (SCROLL TO TOP) CEPAT --}}
        <div
            x-data="{ showTopBtn: false }"
            @scroll.window="showTopBtn = (window.pageYOffset > 500)"
            class="fixed bottom-6 right-6 z-40"
        >
            <button
                x-cloak
                x-show="showTopBtn"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                type="button"
                @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-full shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 focus:outline-none"
                title="Kembali ke atas"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                <span>Ke Atas</span>
            </button>
        </div>

    </div>

</div>

@if ($startDate && $endDate)
<style>
@keyframes taskCardGlowPulse {
    0%, 100% {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.75), 0 12px 28px -4px rgba(16, 185, 129, 0.45);
        border-color: #10b981;
    }
    50% {
        box-shadow: 0 0 0 5px rgba(52, 211, 153, 0.95), 0 16px 36px -4px rgba(16, 185, 129, 0.65);
        border-color: #059669;
    }
}
.task-card-glowing {
    animation: taskCardGlowPulse 1.3s infinite ease-in-out !important;
    background-color: #f0fdf4 !important;
    transform: translateY(-6px) scale(1.025) !important;
    z-index: 25 !important;
    position: relative;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.2s ease, box-shadow 0.25s ease !important;
}
tr.task-card-glowing {
    animation: none !important;
    background-color: #ecfdf5 !important;
    transform: none !important;
}
tr.task-card-glowing td {
    background-color: #ecfdf5 !important;
}
tr.task-card-glowing td:first-child {
    box-shadow: inset 3px 0 0 #10b981;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const monthlyItems = @json($monthlyChartTasks);
    const contributionItems = @json($pieContributionData);

    // Gabungkan data berdasarkan task agar setiap task hanya punya satu batang progres terbaru
    const taskItemsMap = new Map();
    monthlyItems.forEach(item => {
        const key = item.task_id || `${item.task_number}-${item.task_name}`;
        taskItemsMap.set(key, item);
    });
    const taskItems = Array.from(taskItemsMap.values());

    const barCanvas = document.getElementById('departmentTaskBarChart');
    const weeklyLineCanvas = document.getElementById('departmentTaskWeeklyLineChart');
    const pieCanvas = document.getElementById('departmentContributionPie');
    const barWrapper = document.getElementById('departmentBarChartWrapper');
    const barLegend = document.getElementById('departmentBarLegend');
    const weeklyLineWrapper = document.getElementById('departmentWeeklyLineWrapper');
    const weeklySummaryGrid = document.getElementById('departmentWeeklySummaryGrid');
    const barInner = document.getElementById('departmentBarChartInner');
    const emptyState = document.getElementById('departmentChartEmpty');
    const drilldownHeader = document.getElementById('departmentDrilldownHeader');
    const drilldownTitle = document.getElementById('departmentDrilldownTitle');
    const drilldownSubtitle = document.getElementById('departmentDrilldownSubtitle');
    const backButton = document.getElementById('departmentChartBack');
    const taskCatalogTbody = document.getElementById('deptTaskCatalogTbody') || document.getElementById('deptTaskCatalogGrid');
    const taskCatalogCount = document.getElementById('deptTaskCatalogCount');
    const paginationWrapper = document.getElementById('deptTaskPaginationWrapper');
    const paginationInfo = document.getElementById('deptTaskPaginationInfo');
    const paginationButtons = document.getElementById('deptTaskPaginationButtons');
    let barChart = null;
    let weeklyLineChart = null;

    /*
    |--------------------------------------------------------------------------
    | RENDER KETERANGAN TASK DENGAN TABEL & PAGINASI (10 TASK PER HALAMAN)
    |--------------------------------------------------------------------------
    */
    const TASKS_PER_PAGE = 10;
    let currentCatalogTasks = [];
    let currentCatalogPage = 1;

    function renderDeptTaskCatalog(items) {
        if (!taskCatalogTbody) return;

        // Dapatkan task unik berdasarkan task_id / task_number
        const uniqueTasksMap = new Map();
        items.forEach(it => {
            const key = it.task_id || `${it.task_number}-${it.task_name}`;
            if (!uniqueTasksMap.has(key)) {
                uniqueTasksMap.set(key, it);
            }
        });

        currentCatalogTasks = Array.from(uniqueTasksMap.values());
        currentCatalogPage = 1;
        renderDeptCatalogPage(1);
    }

    function renderDeptCatalogPage(page) {
        if (!taskCatalogTbody) return;
        taskCatalogTbody.innerHTML = '';

        const totalTasks = currentCatalogTasks.length;
        if (taskCatalogCount) {
            taskCatalogCount.textContent = totalTasks + ' Task Tampil';
        }

        if (totalTasks === 0) {
            taskCatalogTbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-xs text-gray-400">Belum ada target pada filter yang dipilih.</td></tr>';
            if (paginationWrapper) paginationWrapper.classList.add('hidden');
            return;
        }

        const totalPages = Math.ceil(totalTasks / TASKS_PER_PAGE);
        currentCatalogPage = Math.max(1, Math.min(page, totalPages));

        const startIndex = (currentCatalogPage - 1) * TASKS_PER_PAGE;
        const endIndex = Math.min(startIndex + TASKS_PER_PAGE, totalTasks);
        const pageTasks = currentCatalogTasks.slice(startIndex, endIndex);

        const avatarColors = [
            { bg: 'bg-pink-100', text: 'text-pink-700' },
            { bg: 'bg-purple-100', text: 'text-purple-700' },
            { bg: 'bg-sky-100', text: 'text-sky-700' },
            { bg: 'bg-teal-100', text: 'text-teal-700' },
            { bg: 'bg-amber-100', text: 'text-amber-700' },
            { bg: 'bg-emerald-100', text: 'text-emerald-700' },
            { bg: 'bg-indigo-100', text: 'text-indigo-700' },
        ];

        pageTasks.forEach((task, index) => {
            const tr = document.createElement('tr');
            tr.className = 'dept-task-card group hover:bg-gray-50/80 transition cursor-pointer border-b border-gray-100 last:border-b-0';
            tr.dataset.taskNumber = task.task_number;
            tr.dataset.taskId = task.task_id || '';

            const charCode = (task.employee_name || 'A').charCodeAt(0);
            const color = avatarColors[charCode % avatarColors.length];
            const nameParts = (task.employee_name || 'B').trim().split(/\s+/);
            const initials = nameParts.length >= 2 
                ? (nameParts[0][0] + nameParts[1][0]).toUpperCase() 
                : nameParts[0].slice(0, 2).toUpperCase();

            // Status badge
            let statusBadge = '';
            const st = String(task.status || '').toLowerCase();
            const actual = Number(task.actual || 0);

            if (st === 'completed' || actual >= 100) {
                statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-100">Completed</span>';
            } else if (st === 'revision') {
                statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-600 border border-rose-100">Revision</span>';
            } else if (st === 'in_progress' || actual > 0) {
                statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600 border border-blue-100">In Progress</span>';
            } else if (st === 'draft') {
                statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">Draft</span>';
            } else {
                statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600 border border-blue-100">Sent</span>';
            }

            const subTargetTitle = task.sub_target_name && task.sub_target_name !== '-' ? task.sub_target_name : (task.task_name || '-');
            const targetTitle = task.target_name && task.target_name !== '-' ? task.target_name : '-';

            tr.innerHTML = `
                <td class="w-16 px-4 py-3.5 text-center text-xs font-medium text-gray-500 whitespace-nowrap">
                    ${startIndex + index + 1}
                </td>
                <td class="px-4 py-3.5">
                    <div class="font-semibold text-gray-900 text-sm">
                        ${subTargetTitle}
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5">
                        Target : ${targetTitle}
                    </div>
                </td>
                <td class="px-4 py-3.5 whitespace-nowrap text-xs text-gray-600 font-medium">
                    ${task.division_name || '-'}
                </td>
                <td class="px-4 py-3.5 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full ${color.bg} ${color.text} flex items-center justify-center text-[10px] font-semibold shrink-0">
                            ${initials}
                        </span>
                        <span class="text-xs font-medium text-gray-800">
                            ${task.employee_name || 'Belum ditentukan'}
                        </span>
                    </div>
                </td>
                <td class="px-4 py-3.5 whitespace-nowrap">
                    ${statusBadge}
                </td>
            `;

            tr.onclick = () => showWeeklyLineChart(task);

            // Sinkronisasi hover ke bar chart
            tr.addEventListener('mouseenter', function () {
                const cNum = String(task.task_number || '');
                const cId = String(task.task_id || '');
                const bIndex = taskItems.findIndex(it => (cId && String(it.task_id) === cId) || (cNum && String(it.task_number) === cNum));
                if (bIndex !== -1 && barChart) {
                    barChart.setActiveElements([{ datasetIndex: 0, index: bIndex }]);
                    const meta = barChart.getDatasetMeta(0);
                    if (meta && meta.data && meta.data[bIndex]) {
                        barChart.tooltip.setActiveElements([{ datasetIndex: 0, index: bIndex }], {
                            x: meta.data[bIndex].x,
                            y: meta.data[bIndex].y
                        });
                    }
                    barChart.update();
                }
            });
            tr.addEventListener('mouseleave', function () {
                if (barChart) {
                    barChart.setActiveElements([]);
                    barChart.tooltip.setActiveElements([], {});
                    barChart.update();
                }
            });

            taskCatalogTbody.appendChild(tr);
        });

        if (paginationWrapper && paginationInfo && paginationButtons) {
            if (totalPages <= 1) {
                paginationWrapper.classList.add('hidden');
            } else {
                paginationWrapper.classList.remove('hidden');
                paginationInfo.textContent = `${totalTasks} Documents`;

                paginationButtons.innerHTML = '';

                // Page numbers container
                const numWrap = document.createElement('div');
                numWrap.className = 'flex items-center gap-1';

                const pages = [];
                if (totalPages <= 7) {
                    for (let i = 1; i <= totalPages; i++) pages.push(i);
                } else {
                    if (currentCatalogPage <= 4) {
                        pages.push(1, 2, 3, 4, 5, '...', totalPages);
                    } else if (currentCatalogPage >= totalPages - 3) {
                        pages.push(1, '...', totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1, totalPages);
                    } else {
                        pages.push(1, '...', currentCatalogPage - 1, currentCatalogPage, currentCatalogPage + 1, '...', totalPages);
                    }
                }

                pages.forEach(p => {
                    if (p === '...') {
                        const dots = document.createElement('span');
                        dots.className = 'px-2 py-1 text-xs text-gray-400 font-semibold';
                        dots.textContent = '...';
                        numWrap.appendChild(dots);
                    } else {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = p === currentCatalogPage
                            ? 'w-7 h-7 flex items-center justify-center text-xs font-bold rounded-lg bg-blue-600 text-white shadow-xs cursor-pointer'
                            : 'w-7 h-7 flex items-center justify-center text-xs font-semibold rounded-lg text-gray-600 hover:bg-gray-100 transition cursor-pointer';
                        btn.textContent = p;
                        btn.onclick = () => renderDeptCatalogPage(p);
                        numWrap.appendChild(btn);
                    }
                });
                paginationButtons.appendChild(numWrap);

                // Tombol Previous
                const prevBtn = document.createElement('button');
                prevBtn.type = 'button';
                prevBtn.disabled = currentCatalogPage === 1;
                prevBtn.className = 'inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer ml-3';
                prevBtn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Previous';
                prevBtn.onclick = () => renderDeptCatalogPage(currentCatalogPage - 1);
                paginationButtons.appendChild(prevBtn);

                // Tombol Next
                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.disabled = currentCatalogPage === totalPages;
                nextBtn.className = 'inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer ml-1';
                nextBtn.innerHTML = 'Next <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
                nextBtn.onclick = () => renderDeptCatalogPage(currentCatalogPage + 1);
                paginationButtons.appendChild(nextBtn);
            }
        }
    }

    function highlightDeptTaskCard(item) {
        if (!item) {
            document.querySelectorAll('.dept-task-card').forEach(c => c.classList.remove('task-card-glowing'));
            return;
        }
        const taskNum = String(item.task_number || '');
        const taskId = String(item.task_id || '');

        const matchingIndex = currentCatalogTasks.findIndex(t => (taskId && String(t.task_id) === taskId) || (taskNum && String(t.task_number) === taskNum));
        if (matchingIndex !== -1) {
            const targetPage = Math.floor(matchingIndex / TASKS_PER_PAGE) + 1;
            if (targetPage !== currentCatalogPage) {
                renderDeptCatalogPage(targetPage);
            }
            document.querySelectorAll('.dept-task-card').forEach(c => {
                const cNum = String(c.dataset.taskNumber || '');
                const cId = String(c.dataset.taskId || '');
                if ((taskId && cId === taskId) || (taskNum && cNum === taskNum)) {
                    c.classList.add('task-card-glowing');
                    c.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } else {
                    c.classList.remove('task-card-glowing');
                }
            });
        } else {
            document.querySelectorAll('.dept-task-card').forEach(c => c.classList.remove('task-card-glowing'));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DIAGRAM GARIS RINCIAN (DRILL-DOWN LINE CHART)
    |--------------------------------------------------------------------------
    */
    function showWeeklyLineChart(item) {
        if (!item || !weeklyLineCanvas) return;

        barWrapper.classList.add('hidden');
        if (barLegend) barLegend.classList.add('hidden');
        drilldownHeader.classList.remove('hidden');
        weeklyLineWrapper.classList.remove('hidden');

        drilldownTitle.textContent = 'Task ' + item.task_number + ' · ' + item.task_name;
        drilldownSubtitle.textContent = 'Target: ' + (item.target_name || '-') + ' | Divisi: ' + (item.division_name || 'Departemen') + ' | Pelaksana: ' + (item.employee_name || '-') + ' | Periode: ' + (item.period_full || item.period_name || item.month_name || '-');

        const cleanDateLabel = lbl => String(lbl || '').replace(/\s*\([mM]\d+\)/g, '').replace(/\s*\([mM]gg\s*\d+[^)]*\)/g, '').trim();

        const weeklyData = item.drilldown || item.weekly || [];
        const labels = weeklyData.map(w => cleanDateLabel(w.label));
        const planningValues = weeklyData.map(w => w.planning);
        const actualValues = weeklyData.map(w => w.actual);

        // Render card ringkasan rincian periode
        if (weeklySummaryGrid) {
            weeklySummaryGrid.innerHTML = '';
            weeklyData.forEach(w => {
                const card = document.createElement('div');
                card.className = 'p-3 rounded-xl bg-gray-50 border border-gray-100 flex flex-col justify-between';
                card.innerHTML = `
                    <span class="text-xs font-bold text-gray-700">${cleanDateLabel(w.label)}</span>
                    <div class="mt-2 space-y-1 text-[11px]">
                        <div class="flex items-center justify-between text-blue-700">
                            <span>Planning:</span>
                            <span class="font-semibold">${w.planning}%</span>
                        </div>
                        <div class="flex items-center justify-between text-emerald-700">
                            <span>Aktual:</span>
                            <span class="font-semibold">${w.actual}%</span>
                        </div>
                    </div>
                `;
                weeklySummaryGrid.appendChild(card);
            });
        }

        if (weeklyLineChart) {
            weeklyLineChart.destroy();
            weeklyLineChart = null;
        }

        weeklyLineChart = new Chart(weeklyLineCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Planning (%)',
                        data: planningValues,
                        borderColor: '#2563eb', // Biru
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        borderWidth: 2.5,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.25,
                        fill: false
                    },
                    {
                        label: 'Aktual (%)',
                        data: actualValues,
                        borderColor: '#10b981', // Hijau
                        backgroundColor: 'rgba(16, 185, 129, 0.15)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        tension: 0.25,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: {
                        ticks: { color: '#4b5563', font: { weight: '600' } },
                        grid: { color: 'rgba(229, 231, 235, 0.5)' }
                    },
                    y: {
                        min: 0,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Progres (%)',
                            color: '#374151',
                            font: { weight: 'bold', size: 12 }
                        },
                        ticks: {
                            stepSize: 10,
                            callback: val => val + '%',
                            color: '#6b7280'
                        },
                        grid: { color: 'rgba(229, 231, 235, 0.7)' }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 16,
                            color: '#374151',
                            font: { weight: '600', size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            title: contexts => contexts.length ? cleanDateLabel(contexts[0].label) : '',
                            label: context => ' ' + context.dataset.label + ': ' + context.parsed.y + '%'
                        }
                    }
                }
            }
        });

        drilldownHeader?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /*
    |--------------------------------------------------------------------------
    | PLUGIN 3D CUBE BAR (KUBUS ISOMETRIK MENEMPEL)
    |--------------------------------------------------------------------------
    */
    const cube3dBarPlugin = {
        id: 'cube3dBarPlugin',
        beforeDatasetDraw(chart, args) {
            // Mencegah penggambaran bar 2D default
            return false;
        },
        afterDatasetsDraw(chart) {
            const ctx = chart.ctx;
            const meta0 = chart.getDatasetMeta(0);
            const meta1 = chart.getDatasetMeta(1);
            if (!meta0 || !meta0.data || meta0.hidden) return;

            const dataset0 = chart.data.datasets[0];
            const dataset1 = chart.data.datasets[1];
            const count = meta0.data.length;

            const blueFront  = '#3b82f6';
            const blueTop    = '#60a5fa';
            const blueSide   = '#1d4ed8';
            const blueStroke = '#1e40af';

            const greenFront  = '#10b981';
            const greenTop    = '#34d399';
            const greenSide   = '#059669';
            const greenStroke = '#047857';

            for (let i = 0; i < count; i++) {
                const bar0 = meta0.data[i];
                const bar1 = meta1 && meta1.data ? meta1.data[i] : null;
                if (!bar0) continue;

                const base = bar0.base;
                const rawVal0 = dataset0 ? dataset0.data[i] : 0;
                const val0 = Number(rawVal0 || 0);

                const y0 = Math.min(bar0.y, base);
                const h0 = Math.max(base - y0, 2);
                const actualTop0 = base - h0;

                const left0 = bar0.x - bar0.width / 2;
                const right0 = bar0.x + bar0.width / 2;

                if (!bar1) {
                    continue;
                }

                const rawVal1 = dataset1 ? dataset1.data[i] : 0;
                const val1 = Number(rawVal1 || 0);

                const y1 = Math.min(bar1.y, base);
                const h1 = Math.max(base - y1, 2);
                const actualTop1 = base - h1;

                const left1 = bar1.x - bar1.width / 2;
                const right1 = bar1.x + bar1.width / 2;

                // Garis temu presisi: bar hijau menempel tepat di sisi kanan bar biru pada bidang depan
                const midX = (right0 + left1) / 2;
                const left_b = left0;
                const right_b = midX;
                const left_g = midX;
                const right_g = right1;

                const w_b = right_b - left_b;
                const w_g = right_g - left_g;

                const dx = Math.max(5, Math.min(Math.round(w_b * 0.32), 10));
                const dy = -Math.round(dx * 0.65);

                ctx.save();
                ctx.lineWidth = 1;
                ctx.lineJoin = 'round';

                // ============================================
                // 1. BAR BIRU (PROGRESS / PLANNING) - SISI KIRI
                // ============================================
                // Wajah Depan Bar Biru
                ctx.fillStyle = blueFront;
                ctx.strokeStyle = blueStroke;
                ctx.beginPath();
                ctx.rect(left_b, actualTop0, w_b, h0);
                ctx.fill();
                ctx.stroke();

                // Atap Kubus Bar Biru
                ctx.fillStyle = blueTop;
                ctx.beginPath();
                ctx.moveTo(left_b, actualTop0);
                ctx.lineTo(left_b + dx, actualTop0 + dy);
                ctx.lineTo(right_b + dx, actualTop0 + dy);
                ctx.lineTo(right_b, actualTop0);
                ctx.closePath();
                ctx.fill();
                ctx.stroke();

                // Sisi Kanan Bar Biru yang terbuka HANYA di atas bar hijau (jika bar biru lebih tinggi)
                if (actualTop0 < actualTop1) {
                    ctx.fillStyle = blueSide;
                    ctx.beginPath();
                    ctx.moveTo(right_b, actualTop0);
                    ctx.lineTo(right_b + dx, actualTop0 + dy);
                    ctx.lineTo(right_b + dx, actualTop1 + dy);
                    ctx.lineTo(right_b, actualTop1);
                    ctx.closePath();
                    ctx.fill();
                    ctx.stroke();
                }

                // ============================================
                // 2. BAR HIJAU (AKTUAL) - NEMPEL DI SISI KANAN BAR BIRU
                // ============================================
                // Wajah Depan Bar Hijau (menempel langsung di sisi kanan bar biru, sejajar di depan)
                ctx.fillStyle = greenFront;
                ctx.strokeStyle = greenStroke;
                ctx.beginPath();
                ctx.rect(left_g, actualTop1, w_g, h1);
                ctx.fill();
                ctx.stroke();

                // Atap Kubus Bar Hijau
                ctx.fillStyle = greenTop;
                ctx.beginPath();
                ctx.moveTo(left_g, actualTop1);
                ctx.lineTo(left_g + dx, actualTop1 + dy);
                ctx.lineTo(right_g + dx, actualTop1 + dy);
                ctx.lineTo(right_g, actualTop1);
                ctx.closePath();
                ctx.fill();
                ctx.stroke();

                // Sisi Kiri Terbuka Bar Hijau jika bar hijau lebih tinggi dari bar biru
                if (actualTop1 < actualTop0) {
                    ctx.fillStyle = greenSide;
                    ctx.beginPath();
                    ctx.moveTo(left_g, actualTop1);
                    ctx.lineTo(left_g + dx, actualTop1 + dy);
                    ctx.lineTo(left_g + dx, actualTop0 + dy);
                    ctx.lineTo(left_g, actualTop0);
                    ctx.closePath();
                    ctx.fill();
                    ctx.stroke();
                }

                // Sisi Kanan Kubus Bar Hijau (sisi paling luar kanan)
                ctx.fillStyle = greenSide;
                ctx.beginPath();
                ctx.moveTo(right_g, actualTop1);
                ctx.lineTo(right_g + dx, actualTop1 + dy);
                ctx.lineTo(right_g + dx, base + dy);
                ctx.lineTo(right_g, base);
                ctx.closePath();
                ctx.fill();
                ctx.stroke();

                // ============================================
                // 3. PERSENTASE DI ATAS MASING-MASING KUBUS
                // ============================================
                ctx.font = 'bold 10px system-ui, -apple-system, sans-serif';
                ctx.fillStyle = '#1e293b';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'bottom';

                const textX0 = left_b + w_b / 2 + dx / 2;
                const textY0 = actualTop0 + dy - 3;
                const disp0 = Number.isInteger(val0) ? val0 + '%' : val0.toFixed(1) + '%';
                ctx.fillText(disp0, textX0, textY0);

                const textX1 = left_g + w_g / 2 + dx / 2;
                const textY1 = actualTop1 + dy - 3;
                const disp1 = Number.isInteger(val1) ? val1 + '%' : val1.toFixed(1) + '%';
                ctx.fillText(disp1, textX1, textY1);

                ctx.restore();
            }
        }
    };

    /*
    |--------------------------------------------------------------------------
    | DIAGRAM BATANG BULANAN
    |--------------------------------------------------------------------------
    */
    function renderBarChart() {
        if (!barCanvas || !barWrapper || !emptyState) return;

        renderDeptTaskCatalog(taskItems);

        if (taskItems.length === 0) {
            barWrapper.classList.add('hidden');
            if (barLegend) barLegend.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        barWrapper.classList.remove('hidden');
        if (barLegend) barLegend.classList.remove('hidden');

        barInner.style.minWidth = Math.max(
            taskItems.length * 85,
            barWrapper.clientWidth || 600
        ) + 'px';

        if (barChart) {
            barChart.destroy();
            barChart = null;
        }

        barChart = new Chart(barCanvas, {
            type: 'bar',
            data: {
                labels: taskItems.map(item => 'Task ' + item.task_number),
                datasets: [
                    {
                        label: 'Planning',
                        data: taskItems.map(item => item.planning),
                        backgroundColor: '#3b82f6',
                        borderColor: '#1e40af',
                        borderWidth: 1,
                        barPercentage: 1.0,
                        categoryPercentage: 0.65
                    },
                    {
                        label: 'Aktual',
                        data: taskItems.map(item => item.actual),
                        backgroundColor: '#10b981',
                        borderColor: '#047857',
                        borderWidth: 1,
                        barPercentage: 1.0,
                        categoryPercentage: 0.65
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 25,
                        right: 15
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'xy',
                    intersect: true
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                onClick: function (evt, activeElements) {
                    if (activeElements && activeElements.length > 0) {
                        const index = activeElements[0].index;
                        const clickedItem = taskItems[index];
                        if (clickedItem) {
                            showWeeklyLineChart(clickedItem);
                        }
                    }
                },
                onHover: function (evt, activeElements) {
                    barCanvas.style.cursor = (activeElements && activeElements.length > 0) ? 'pointer' : 'default';
                    if (activeElements && activeElements.length > 0) {
                        const index = activeElements[0].index;
                        const item = taskItems[index];
                        highlightDeptTaskCard(item);
                    } else {
                        highlightDeptTaskCard(null);
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0,
                            color: '#4b5563',
                            font: { size: 11, weight: '500' }
                        }
                    },
                    y: {
                        min: 0,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Progres (%)',
                            color: '#374151',
                            font: { weight: 'bold', size: 12 }
                        },
                        ticks: {
                            stepSize: 10,
                            callback: value => value + '%',
                            color: '#6b7280'
                        },
                        grid: { color: 'rgba(229, 231, 235, 0.7)' }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: false
                    }
                }
            },
            plugins: [cube3dBarPlugin]
        });

        barCanvas?.addEventListener('mouseleave', function () {
            document.querySelectorAll('.dept-task-card').forEach(c => c.classList.remove('task-card-glowing'));
        });

        backButton?.addEventListener('click', function () {
            if (weeklyLineChart) {
                weeklyLineChart.destroy();
                weeklyLineChart = null;
            }
            weeklyLineWrapper.classList.add('hidden');
            drilldownHeader.classList.add('hidden');
            barWrapper.classList.remove('hidden');
            if (barLegend) barLegend.classList.remove('hidden');
            if (barChart) barChart.resize();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SINGLE NESTED DONUT CHART: 4 KUADRAN BOBOT STATIS VS PROGRES AKTUAL
    |--------------------------------------------------------------------------
    */
    let deptNestedDonutChartInstance = null;
    const deptDivFilter = document.getElementById('deptPieDivisionFilter');
    const deptEmpFilter = document.getElementById('deptPieEmployeeFilter');
    const deptSubTargetBadge = document.getElementById('deptSubTargetCountBadge');
    const deptQuadrantList = document.getElementById('deptQuadrantCardList');
    const deptLegendSubTargetList = document.getElementById('departmentPieLegendList');

    const deptAggregatePercentEl = document.getElementById('deptAggregatePercent');
    const deptAggregateBadgeEl = document.getElementById('deptAggregateBadge');
    
    // Palet 10 warna terintegrasi untuk sub target (5 awal + 5 tambahan baru)
    const deptPaletteColors = [
        '#73605b', '#7B2CBF', '#008080', '#dda288', '#B3DBC0',
        '#EAB308', '#1D4ED8', '#65A30D', '#BE185D', '#475569'
    ];
    const deptPaletteColorNames = [
        'Cokelat Keabu-abuan', 'Ungu', 'Teal', 'Cokelat Kemerahan', 'Mint',
        'Kuning Emas', 'Biru', 'Hijau Limau', 'Pink Magenta', 'Slate'
    ];

    const deptThemeColorMap = {
        '#73605b': { borderStyle: 'border-color: rgba(115, 96, 91, 0.35);', bgStyle: 'background-color: rgba(115, 96, 91, 0.06);', textStyle: 'color: #73605b;', barStyle: 'background-color: #73605b;', pillStyle: 'background-color: rgba(115, 96, 91, 0.15); color: #52433f;' },
        '#7B2CBF': { borderStyle: 'border-color: rgba(123, 44, 191, 0.35);', bgStyle: 'background-color: rgba(123, 44, 191, 0.06);', textStyle: 'color: #7B2CBF;', barStyle: 'background-color: #7B2CBF;', pillStyle: 'background-color: rgba(123, 44, 191, 0.15); color: #5a189a;' },
        '#008080': { borderStyle: 'border-color: rgba(0, 128, 128, 0.35);', bgStyle: 'background-color: rgba(0, 128, 128, 0.06);', textStyle: 'color: #008080;', barStyle: 'background-color: #008080;', pillStyle: 'background-color: rgba(0, 128, 128, 0.15); color: #005a5a;' },
        '#dda288': { borderStyle: 'border-color: rgba(221, 162, 136, 0.45);', bgStyle: 'background-color: rgba(221, 162, 136, 0.1);', textStyle: 'color: #b25d3b;', barStyle: 'background-color: #dda288;', pillStyle: 'background-color: rgba(221, 162, 136, 0.25); color: #7c3a21;' },
        '#B3DBC0': { borderStyle: 'border-color: rgba(179, 219, 192, 0.9);', bgStyle: 'background-color: rgba(179, 219, 192, 0.18);', textStyle: 'color: #1a634e;', barStyle: 'background-color: #79c492;', pillStyle: 'background-color: rgba(179, 219, 192, 0.65); color: #134e3f;' },
        '#EAB308': { borderStyle: 'border-color: rgba(234, 179, 8, 0.45);', bgStyle: 'background-color: rgba(234, 179, 8, 0.08);', textStyle: 'color: #a16207;', barStyle: 'background-color: #EAB308;', pillStyle: 'background-color: rgba(234, 179, 8, 0.2); color: #854d0e;' },
        '#1D4ED8': { borderStyle: 'border-color: rgba(29, 78, 216, 0.35);', bgStyle: 'background-color: rgba(29, 78, 216, 0.06);', textStyle: 'color: #1D4ED8;', barStyle: 'background-color: #1D4ED8;', pillStyle: 'background-color: rgba(29, 78, 216, 0.15); color: #1e40af;' },
        '#65A30D': { borderStyle: 'border-color: rgba(101, 163, 13, 0.35);', bgStyle: 'background-color: rgba(101, 163, 13, 0.06);', textStyle: 'color: #4d7c0f;', barStyle: 'background-color: #65A30D;', pillStyle: 'background-color: rgba(101, 163, 13, 0.15); color: #3f6212;' },
        '#BE185D': { borderStyle: 'border-color: rgba(190, 24, 93, 0.35);', bgStyle: 'background-color: rgba(190, 24, 93, 0.06);', textStyle: 'color: #BE185D;', barStyle: 'background-color: #BE185D;', pillStyle: 'background-color: rgba(190, 24, 93, 0.15); color: #9d174d;' },
        '#475569': { borderStyle: 'border-color: rgba(71, 85, 105, 0.35);', bgStyle: 'background-color: rgba(71, 85, 105, 0.06);', textStyle: 'color: #475569;', barStyle: 'background-color: #475569;', pillStyle: 'background-color: rgba(71, 85, 105, 0.15); color: #334155;' }
    };

    // Plugin Chart.js kustom: panah penunjuk presisi & label flat solid tebal "Progress: X%"
    const singleNestedDonutLabelsPluginDept = {
        id: 'nestedDonutLabels',
        afterDraw(chart) {
            const ctx = chart.ctx;
            const metaOuter = chart.getDatasetMeta(0);
            const metaInner = chart.getDatasetMeta(1);
            if (!metaOuter || !metaOuter.data || !metaInner || !metaInner.data || metaInner.data.length === 0) return;

            const pluginOpts = chart.config.options?.plugins?.nestedDonutLabels;
            const progressValues = pluginOpts?.progressValues || [];
            const colors = pluginOpts?.colors || deptPaletteColors;
            const count = metaInner.data.length;

            ctx.save();
            for (let i = 0; i < count; i++) {
                const innerArc = metaInner.data[i];
                if (!innerArc) continue;

                const midAngle = (innerArc.startAngle + innerArc.endAngle) / 2;
                const cx = innerArc.x;
                const cy = innerArc.y;

                // Radius cincin luar
                const outerArc = metaOuter.data[i * 2] || innerArc;
                const outerRadius = outerArc.outerRadius || (innerArc.outerRadius * 1.25);

                // Garis penunjuk panah tebal
                const tickStart = outerRadius + 2;
                const tickEnd = outerRadius + 14;
                const x1 = cx + Math.cos(midAngle) * tickStart;
                const y1 = cy + Math.sin(midAngle) * tickStart;
                const x2 = cx + Math.cos(midAngle) * tickEnd;
                const y2 = cy + Math.sin(midAngle) * tickEnd;

                const sliceColor = colors[i] || deptPaletteColors[i % deptPaletteColors.length];

                // Garis penunjuk panah
                ctx.beginPath();
                ctx.strokeStyle = sliceColor;
                ctx.lineWidth = 2;
                ctx.moveTo(x2, y2);
                ctx.lineTo(x1, y1);
                ctx.stroke();

                // Kepala panah (arrowhead) menunjuk ke irisan donat
                const arrowAngle = Math.atan2(y1 - y2, x1 - x2);
                const headLen = 6;
                ctx.beginPath();
                ctx.moveTo(x1, y1);
                ctx.lineTo(x1 - headLen * Math.cos(arrowAngle - Math.PI / 6), y1 - headLen * Math.sin(arrowAngle - Math.PI / 6));
                ctx.lineTo(x1 - headLen * Math.cos(arrowAngle + Math.PI / 6), y1 - headLen * Math.sin(arrowAngle + Math.PI / 6));
                ctx.closePath();
                ctx.fillStyle = sliceColor;
                ctx.fill();

                // Format Label: 'Progress: X%'
                const pVal = progressValues[i] ?? 0;
                const text = `Progress: ${parseFloat(Number(pVal).toFixed(1))}%`;

                ctx.font = 'bold 11px ui-sans-serif, system-ui, -apple-system, sans-serif';
                const textMetrics = ctx.measureText(text);
                const boxPaddingX = 8;
                const boxHeight = 22;
                const boxWidth = textMetrics.width + (boxPaddingX * 2);

                let boxX;
                let boxY = y2 - boxHeight / 2;

                if (Math.cos(midAngle) >= 0) {
                    boxX = x2 + 5;
                } else {
                    boxX = x2 - boxWidth - 5;
                }

                // Clamping agar 100% selalu berada di dalam kanvas dan tidak terpotong
                boxX = Math.max(6, Math.min(chart.width - boxWidth - 6, boxX));
                boxY = Math.max(6, Math.min(chart.height - boxHeight - 6, boxY));

                // Hubungkan garis panah ke badge
                const targetEdgeX = Math.cos(midAngle) >= 0 ? boxX : boxX + boxWidth;
                const targetEdgeY = boxY + boxHeight / 2;
                ctx.beginPath();
                ctx.strokeStyle = sliceColor;
                ctx.lineWidth = 1.5;
                ctx.moveTo(x2, y2);
                ctx.lineTo(targetEdgeX, targetEdgeY);
                ctx.stroke();

                // Kotak badge solid flat
                ctx.fillStyle = sliceColor;
                ctx.beginPath();
                if (ctx.roundRect) {
                    ctx.roundRect(boxX, boxY, boxWidth, boxHeight, 5);
                } else {
                    ctx.rect(boxX, boxY, boxWidth, boxHeight);
                }
                ctx.fill();

                // Teks kontras
                ctx.fillStyle = (sliceColor === '#B3DBC0' || sliceColor === '#EAB308') ? '#1f2937' : '#ffffff';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = 'bold 11px ui-sans-serif, system-ui, -apple-system, sans-serif';
                ctx.fillText(text, boxX + boxWidth / 2, boxY + boxHeight / 2 + 0.5);
            }
            ctx.restore();
        }
    };

    function calculateFourQuadrants(items) {
        const defaultQuadrants = [
            { id: 1, name: 'Irisan 1', title: 'Target Irisan 1', progress: 0, weight: 20, color: '#73605b', colorName: 'Cokelat Keabu-abuan' },
            { id: 2, name: 'Irisan 2', title: 'Target Irisan 2', progress: 0, weight: 20, color: '#7B2CBF', colorName: 'Ungu' },
            { id: 3, name: 'Irisan 3', title: 'Target Irisan 3', progress: 0, weight: 20, color: '#008080', colorName: 'Teal' },
            { id: 4, name: 'Irisan 4', title: 'Target Irisan 4', progress: 0, weight: 20, color: '#dda288', colorName: 'Cokelat Kemerahan' },
            { id: 5, name: 'Irisan 5', title: 'Target Irisan 5', progress: 0, weight: 20, color: '#B3DBC0', colorName: 'Mint' },
        ];

        if (!items || items.length === 0) {
            return defaultQuadrants;
        }

        // Jika sub target <= 10: buat 1 irisan per sub target dengan warna unik dari palet 10 warna
        if (items.length <= 10) {
            const count = items.length;
            const baseWeight = Math.floor((100 / count) * 10) / 10;
            let allocatedWeight = 0;

            return items.map((item, idx) => {
                const title = item.sub_target_name || item.target_name || `Target ${idx + 1}`;
                const progress = Number((Number(item.progress) || 0).toFixed(1));
                const weight = idx === count - 1 ? Number((100 - allocatedWeight).toFixed(1)) : baseWeight;
                allocatedWeight += weight;

                return {
                    id: idx + 1,
                    name: `Irisan ${idx + 1}`,
                    title: title,
                    progress: progress,
                    weight: weight,
                    color: deptPaletteColors[idx],
                    colorName: deptPaletteColorNames[idx]
                };
            });
        }

        // Jika lebih dari 10 sub target, kelompokkan rata ke dalam 10 irisan
        const chunkSize = Math.ceil(items.length / 10);
        const result = [];
        let allocatedWeight = 0;
        const baseWeight = 10;

        for (let idx = 0; idx < 10; idx++) {
            const chunk = items.slice(idx * chunkSize, (idx + 1) * chunkSize);
            if (chunk.length > 0) {
                const sumProg = chunk.reduce((acc, c) => acc + (Number(c.progress) || 0), 0);
                const progress = Number((sumProg / chunk.length).toFixed(1));
                const firstTitle = chunk[0].sub_target_name || chunk[0].target_name || `Target ${idx + 1}`;
                const title = chunk.length > 1 ? `${firstTitle} (+${chunk.length - 1} lainnya)` : firstTitle;
                const weight = idx === 9 ? Number((100 - allocatedWeight).toFixed(1)) : baseWeight;
                allocatedWeight += weight;

                result.push({
                    id: idx + 1,
                    name: `Irisan ${idx + 1}`,
                    title: title,
                    progress: progress,
                    weight: weight,
                    color: deptPaletteColors[idx],
                    colorName: deptPaletteColorNames[idx]
                });
            }
        }

        return result;
    }

    function renderDepartmentExecutiveGauge() {
        const selectedDivId = deptDivFilter ? deptDivFilter.value : 'all';
        const selectedEmpId = deptEmpFilter ? deptEmpFilter.value : null;

        // 1. Filter sub-target berdasarkan Divisi
        let filteredItems = contributionItems;
        if (selectedDivId && selectedDivId !== 'all') {
            filteredItems = filteredItems.filter(item => String(item.division_id) === String(selectedDivId));
        }

        // 2. Filter / kalkulasi berdasarkan Karyawan
        let itemsToRender = [];
        if (selectedEmpId) {
            filteredItems.forEach(item => {
                const empTasks = (item.tasks || []).filter(t => String(t.employee_id) === String(selectedEmpId));
                if (empTasks.length > 0) {
                    const avgEmpProg = empTasks.reduce((acc, t) => acc + (Number(t.progress) || 0), 0) / empTasks.length;
                    itemsToRender.push({
                        ...item,
                        progress: Number(avgEmpProg.toFixed(1)),
                        tasks: empTasks
                    });
                }
            });
        } else {
            itemsToRender = filteredItems.map(item => ({
                ...item,
                progress: Math.max(0, Math.min(100, Number(item.progress) || 0))
            }));
        }

        if (deptSubTargetBadge) {
            deptSubTargetBadge.textContent = `Total Sub Target: ${itemsToRender.length}`;
        }

        // 3. HITUNG 5 IRISAN
        const quadrants = calculateFourQuadrants(itemsToRender);

        // Siapkan data dinamis cincin luar & cincin dalam berdasarkan quadrants
        const outerData = [];
        const outerBackgroundColors = [];
        const outerBorderColors = [];
        const innerData = [];
        const innerBackgroundColors = [];
        const progressValues = [];
        const sliceColors = [];

        quadrants.forEach((q) => {
            const p = Math.max(0, Math.min(100, q.progress));
            const actual = q.weight * (p / 100);
            const remaining = Math.max(0, q.weight - actual);

            outerData.push(actual, remaining);
            outerBackgroundColors.push(q.color, '#FFFFFF');
            outerBorderColors.push(q.color, '#FFFFFF');

            innerData.push(q.weight);
            innerBackgroundColors.push(q.color);
            progressValues.push(p);
            sliceColors.push(q.color);
        });

        // Total Progress Agregat (berdasarkan capaian bobot tiap irisan)
        const totalAggregateProgress = itemsToRender.length > 0
            ? Number((quadrants.reduce((sum, q) => sum + (q.progress * (q.weight / 100)), 0)).toFixed(1))
            : 0;

        // Update Center Hole DOM
        if (deptAggregatePercentEl) {
            deptAggregatePercentEl.textContent = `${parseFloat(Number(totalAggregateProgress).toFixed(1))}%`;
        }
        if (deptAggregateBadgeEl) {
            let badgeClass = 'bg-blue-100 text-blue-800 border-blue-200';
            let badgeText = '● IN PROGRESS';
            if (totalAggregateProgress >= 75) {
                badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                badgeText = '● ON TRACK';
            } else if (totalAggregateProgress < 30) {
                badgeClass = 'bg-amber-100 text-amber-800 border-amber-200';
                badgeText = '● NEED PUSH';
            }
            deptAggregateBadgeEl.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border shadow-xs ${badgeClass}`;
            deptAggregateBadgeEl.textContent = badgeText;
        }

        // Render Single Nested Donut Chart (Chart.js)
        if (pieCanvas) {
            if (deptNestedDonutChartInstance) {
                deptNestedDonutChartInstance.destroy();
                deptNestedDonutChartInstance = null;
            }

            deptNestedDonutChartInstance = new Chart(pieCanvas, {
                type: 'doughnut',
                data: {
                    datasets: [
                        // DATASET 0: CINCIN LUAR (PROGRESS AKTUAL)
                        // Bagian tercapai diisi warna solid sama dengan cincin dalam.
                        // Bagian belum tercapai DIBIARKAN PUTIH MURNI (#FFFFFF) menyatu dengan background.
                        {
                            label: 'Cincin Luar (Progress Aktual)',
                            data: outerData,
                            backgroundColor: outerBackgroundColors,
                            borderColor: outerBorderColors,
                            hoverBackgroundColor: outerBackgroundColors,
                            borderWidth: 0,
                            hoverOffset: 0,
                            weight: 1.15
                        },
                        // DATASET 1: CINCIN DALAM (BOBOT TARGET)
                        {
                            label: 'Cincin Dalam (Bobot Target)',
                            data: innerData,
                            backgroundColor: innerBackgroundColors,
                            hoverBackgroundColor: innerBackgroundColors,
                            borderColor: '#FFFFFF',
                            borderWidth: 1,
                            hoverOffset: 0,
                            weight: 0.95
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '64%',
                    animation: {
                        duration: 600,
                        easing: 'easeOutQuart'
                    },
                    layout: {
                        padding: { top: 18, bottom: 18, left: 38, right: 38 }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            padding: 10,
                            cornerRadius: 8,
                            filter: function (tooltipItem) {
                                // Sembunyikan tooltip pada area putih kosong
                                if (tooltipItem.datasetIndex === 0 && tooltipItem.dataIndex % 2 === 1) {
                                    return false;
                                }
                                return true;
                            },
                            callbacks: {
                                label: function (ctx) {
                                    if (ctx.datasetIndex === 0) {
                                        const qIndex = Math.floor(ctx.dataIndex / 2);
                                        const q = quadrants[qIndex];
                                        return ` ${q.name} (${q.colorName}): Progress ${parseFloat(Number(q.progress).toFixed(1))}% (Bobot ${q.weight}%)`;
                                    } else {
                                        const q = quadrants[ctx.dataIndex];
                                        return ` Bobot Target ${q.name} = ${q.weight}%`;
                                    }
                                }
                            }
                        },
                        nestedDonutLabels: {
                            progressValues: progressValues,
                            colors: sliceColors
                        }
                    }
                },
                plugins: [singleNestedDonutLabelsPluginDept]
            });
        }

        // 4. RENDER KARTU IRISAN DENGAN FORMAT 'Progress: X%'
        if (deptQuadrantList) {
            deptQuadrantList.innerHTML = '';
            quadrants.forEach((q, idx) => {
                const theme = deptThemeColorMap[q.color] || deptThemeColorMap['#73605b'];

                const cardEl = document.createElement('div');
                cardEl.className = 'p-3.5 rounded-xl border shadow-2xs transition-all hover:shadow-xs';
                cardEl.style.cssText = `${theme.borderStyle} ${theme.bgStyle}`;
                cardEl.innerHTML = `
                    <div class="flex items-center justify-between text-xs mb-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-3 h-3 rounded-full shrink-0" style="background-color: ${q.color}"></span>
                            <span class="font-bold text-gray-900 truncate" title="${q.title}">${q.name}: ${q.title}</span>
                        </div>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-md bg-white border border-gray-200 text-gray-700 shrink-0">
                            Bobot: ${q.weight}%
                        </span>
                    </div>

                    <div class="flex items-center justify-between mt-1 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-extrabold tracking-tight" style="${theme.textStyle}">Progress: ${parseFloat(Number(q.progress).toFixed(1))}%</span>
                            <span class="text-[10px] uppercase font-bold px-1.5 py-0.5 rounded" style="${theme.pillStyle}">
                                ${q.progress >= 100 ? 'Selesai' : (q.progress > 0 ? 'Berjalan' : 'Belum')}
                            </span>
                        </div>
                    </div>

                    <div class="w-full h-2 rounded-full bg-white/90 border border-gray-200/60 overflow-hidden flex">
                        <div class="h-full rounded-full transition-all duration-500" style="width: ${q.progress}%; ${theme.barStyle}"></div>
                    </div>
                `;
                deptQuadrantList.appendChild(cardEl);
            });
        }

        // 5. DETAIL EKSEKUSI SUB-TARGET
        if (deptLegendSubTargetList) {
            deptLegendSubTargetList.innerHTML = '';
            if (itemsToRender.length === 0) {
                deptLegendSubTargetList.innerHTML = '<p class="text-xs text-gray-400 py-6 text-center col-span-full">Tidak ada sub target yang sesuai dengan filter.</p>';
            } else {
                itemsToRender.forEach(item => {
                    const isDone = item.progress >= 100;
                    const isZero = item.progress <= 0;

                    let statusClass = 'border-blue-200 bg-white hover:border-blue-400';
                    let badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                    let badgeText = 'Berjalan';
                    let barColor = 'bg-blue-500';

                    if (isDone) {
                        statusClass = 'border-emerald-200 bg-emerald-50/20 hover:border-emerald-400';
                        badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        badgeText = 'Selesai';
                        barColor = 'bg-emerald-500';
                    } else if (isZero) {
                        statusClass = 'border-gray-200 bg-gray-50/40 hover:bg-gray-50';
                        badgeClass = 'bg-gray-100 text-gray-500 border-gray-200';
                        badgeText = 'Belum Mulai';
                        barColor = 'bg-gray-300';
                    }

                    const formattedSubProgress = parseFloat(Number(item.progress).toFixed(1));

                    const card = document.createElement('div');
                    card.className = `rounded-xl border p-2.5 transition flex flex-col justify-between ${statusClass}`;
                    card.innerHTML = `
                        <div>
                            <div class="flex items-center justify-between gap-1.5 mb-1">
                                <span class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded border ${badgeClass}">
                                    ${badgeText}
                                </span>
                                <span class="text-[11px] font-bold text-gray-700 font-mono shrink-0">
                                    Bobot ${item.percentage}%
                                </span>
                            </div>
                            <h4 class="text-xs font-bold text-gray-900 line-clamp-1" title="${item.sub_target_name}">
                                ${item.sub_target_name}
                            </h4>
                            <div class="flex items-center justify-between text-[10px] text-gray-500 mt-0.5">
                                <span class="truncate">${item.division_name}</span>
                                <span class="font-medium text-gray-700 font-mono">Progress: <strong>${formattedSubProgress}%</strong></span>
                            </div>
                            <div class="mt-1.5 h-1.5 bg-gray-100 rounded-full overflow-hidden flex">
                                <div class="h-full rounded-full transition-all duration-300 ${barColor}" style="width: ${item.progress}%"></div>
                            </div>
                        </div>
                    `;
                    deptLegendSubTargetList.appendChild(card);
                });
            }
        }
    }

    // Event listener cascading filter: saat Divisi berubah, perbarui opsi Karyawan
    deptDivFilter?.addEventListener('change', function () {
        const divId = this.value;
        if (deptEmpFilter) {
            Array.from(deptEmpFilter.options).forEach(opt => {
                if (opt.value === 'all') {
                    opt.hidden = false;
                    return;
                }
                const empDivId = opt.getAttribute('data-division-id');
                if (divId === 'all' || !empDivId || empDivId === divId) {
                    opt.hidden = false;
                } else {
                    opt.hidden = true;
                }
            });
            const firstVisibleEmployee = Array.from(deptEmpFilter.options)
                .find(opt => !opt.hidden);

            if (firstVisibleEmployee) {
                deptEmpFilter.value = firstVisibleEmployee.value;
            }
        }
        renderDepartmentExecutiveGauge();
    });

    deptEmpFilter?.addEventListener('change', function () {
        renderDepartmentExecutiveGauge();
    });

    // Inisialisasi awal
    renderBarChart();
    renderDepartmentExecutiveGauge();
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const startInput = document.getElementById('filter_start_date');
    const endInput = document.getElementById('filter_end_date');
    const presetInput = document.getElementById('filter_preset');
    const presetButtons = document.querySelectorAll('.filter-preset-pill');

    if (!startInput || !endInput) return;

    const formatDate = function (d) {
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const monthPickerBtn = document.getElementById('btnMonthPickerDropdown');
    const monthPickerPopover = document.getElementById('monthPickerPopover');
    const monthPickerYear = document.getElementById('monthPickerYear');
    const monthPickerItems = document.querySelectorAll('.month-picker-item');
    const filterMonthInput = document.getElementById('filter_month');
    const filterYearInput = document.getElementById('filter_year');
    const monthPickerLabel = document.getElementById('monthPickerBtnLabel');

    const monthNamesIndo = {
        1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April',
        5: 'Mei', 6: 'Juni', 7: 'Juli', 8: 'Agustus',
        9: 'September', 10: 'Oktober', 11: 'November', 12: 'Desember'
    };

    const updatePresetPillStyles = function (activePreset) {
        presetButtons.forEach(btn => {
            const isMatch = btn.dataset.preset === activePreset;
            if (isMatch) {
                btn.className = 'filter-preset-pill inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer bg-blue-600 text-white border border-blue-600 shadow-xs font-semibold';
            } else {
                btn.className = 'filter-preset-pill inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium';
            }
        });
        if (activePreset !== 'month_picker' && monthPickerLabel) {
            monthPickerLabel.textContent = 'Pilih Bulan';
        }
    };

    const applyPreset = function (type) {
        const now = new Date();
        let start = new Date();
        let end = new Date();

        if (type === '7_days') {
            start.setDate(now.getDate() - 6);
            end = now;
        } else if (type === '30_days') {
            start.setDate(now.getDate() - 29);
            end = now;
        } else if (type === 'last_month') {
            start = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            end = new Date(now.getFullYear(), now.getMonth(), 0);
        } else if (type === 'this_month') {
            start = new Date(now.getFullYear(), now.getMonth(), 1);
            end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        } else if (type === 'this_year') {
            start = new Date(now.getFullYear(), 0, 1);
            end = new Date(now.getFullYear(), 11, 31);
        }

        startInput.value = formatDate(start);
        endInput.value = formatDate(end);
        endInput.min = startInput.value;

        if (presetInput) presetInput.value = type;
        updatePresetPillStyles(type);
        if (monthPickerPopover) monthPickerPopover.classList.add('hidden');
    };

    presetButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            const type = this.dataset.preset;
            if (type === 'month_picker') {
                e.stopPropagation();
                if (monthPickerPopover) {
                    monthPickerPopover.classList.toggle('hidden');
                }
                return;
            }
            applyPreset(type);
        });
    });

    if (monthPickerPopover) {
        document.addEventListener('click', function (e) {
            if (!monthPickerPopover.contains(e.target) && !monthPickerBtn?.contains(e.target)) {
                monthPickerPopover.classList.add('hidden');
            }
        });

        monthPickerItems.forEach(item => {
            item.addEventListener('click', function () {
                const mNum = parseInt(this.dataset.month);
                const yr = parseInt(monthPickerYear ? monthPickerYear.value : (new Date()).getFullYear());

                const start = new Date(yr, mNum - 1, 1);
                const end = new Date(yr, mNum, 0);

                startInput.value = formatDate(start);
                endInput.value = formatDate(end);
                endInput.min = startInput.value;

                if (presetInput) presetInput.value = 'month_picker';
                if (filterMonthInput) filterMonthInput.value = mNum;
                if (filterYearInput) filterYearInput.value = yr;

                if (monthPickerLabel) {
                    monthPickerLabel.textContent = 'Bulan: ' + (monthNamesIndo[mNum] || mNum) + ' ' + yr;
                }

                monthPickerItems.forEach(mi => {
                    if (parseInt(mi.dataset.month) === mNum) {
                        mi.className = 'month-picker-item py-2 px-2 text-center rounded-xl text-xs font-bold transition cursor-pointer bg-blue-600 text-white shadow-xs';
                    } else {
                        mi.className = 'month-picker-item py-2 px-2 text-center rounded-xl text-xs font-medium transition cursor-pointer bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700';
                    }
                });

                updatePresetPillStyles('month_picker');
                monthPickerPopover.classList.add('hidden');
            });
        });
    }

    // Validasi interaktif & alihkan preset ke 'custom' bila tanggal diubah manual
    startInput.addEventListener('change', function () {
        endInput.min = this.value;
        if (endInput.value && endInput.value < this.value) {
            endInput.value = this.value;
        }
        if (presetInput) presetInput.value = 'custom';
        updatePresetPillStyles('custom');
    });

    endInput.addEventListener('change', function () {
        if (startInput.value && this.value < startInput.value) {
            startInput.value = this.value;
        }
        if (presetInput) presetInput.value = 'custom';
        updatePresetPillStyles('custom');
    });

});
</script>


{{-- =====================================================
     JAVASCRIPT: ACCORDION SUB TARGET
====================================================== --}}
<script>
function toggleSubTarget(id) {
    const content = document.getElementById(id);
    const icon    = document.getElementById('icon-' + id);

    if (!content) return;

    const isHidden = content.classList.contains('hidden');

    content.classList.toggle('hidden', !isHidden);

    if (icon) {
        icon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    }
}
</script>

<script>
(() => {
    const divisionFilter = document.getElementById('chart_division');
    const employeeFilter = document.getElementById('chart_employee');

    if (!divisionFilter || !employeeFilter) return;

    const filterEmployeesByDivision = function () {
        const selectedDivision = divisionFilter.value;

        Array.from(employeeFilter.options).forEach(function (option) {
            if (!option.value) return;

            option.hidden = selectedDivision !== ''
                && option.dataset.divisionId !== selectedDivision;
        });

        if (employeeFilter.selectedOptions[0]?.hidden) {
            employeeFilter.value = '';
        }
    };

    divisionFilter.addEventListener('change', filterEmployeesByDivision);
    filterEmployeesByDivision();
})();
</script>

</x-app-layout>
