<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="max-w-7xl mx-auto px-6">

            {{-- ========================================================= --}}
            {{-- HEADER --}}
            {{-- ========================================================= --}}

            <div class="mb-8">

                <h1 class="text-2xl font-bold text-gray-800">
                    Dashboard Pimpinan
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Monitoring Planning dan Aktual pekerjaan karyawan.
                </p>

            </div>


            {{-- ========================================================= --}}
            {{-- FILTER --}}
            {{-- ========================================================= --}}

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">

                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Filter Monitoring
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Tentukan periode dan unit kerja yang ingin dipantau.
                        </p>

                    </div>


                    <form
                        method="GET"
                        action="{{ route('pimpinan.dashboard') }}"
                        class="w-full lg:w-auto"
                    >

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">

                            {{-- Tahun --}}
                            <div>

                                <label
                                    for="year"
                                    class="block text-xs font-medium text-gray-600 mb-1"
                                >
                                    Tahun
                                </label>

                                <select
                                    id="year"
                                    name="year"
                                    class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >

                                    @foreach ($availableYears as $year)

                                        <option
                                            value="{{ $year }}"
                                            @selected(
                                                (int) $selectedYear === (int) $year
                                            )
                                        >
                                            {{ $year }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Tanggal Monitoring --}}
                            <div>

                                <label
                                    for="as_of_date"
                                    class="block text-xs font-medium text-gray-600 mb-1"
                                >
                                    Tanggal Monitoring
                                </label>

                                <input
                                    type="date"
                                    id="as_of_date"
                                    name="as_of_date"
                                    value="{{ $asOfDate }}"
                                    class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >

                            </div>


                            {{-- Departemen --}}
                            <div>

                                <label
                                    for="department_id"
                                    class="block text-xs font-medium text-gray-600 mb-1"
                                >
                                    Departemen
                                </label>

                                <select
                                    id="department_id"
                                    name="department_id"
                                    class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >

                                    <option value="">
                                        Semua Departemen
                                    </option>

                                    @foreach ($departments as $department)

                                        <option
                                            value="{{ $department->id }}"
                                            @selected(
                                                (string) request('department_id') ===
                                                (string) $department->id
                                            )
                                        >
                                            {{ $department->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Divisi --}}
                            <div>

                                <label
                                    for="division_id"
                                    class="block text-xs font-medium text-gray-600 mb-1"
                                >
                                    Divisi
                                </label>

                                <select
                                    id="division_id"
                                    name="division_id"
                                    class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >

                                    <option value="">
                                        Semua Divisi
                                    </option>

                                    @foreach ($divisions as $division)

                                        <option
                                            value="{{ $division->id }}"
                                            @selected(
                                                (string) request('division_id') ===
                                                (string) $division->id
                                            )
                                        >
                                            {{ $division->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Project --}}
                            <div>

                                <label
                                    for="project_id"
                                    class="block text-xs font-medium text-gray-600 mb-1"
                                >
                                    Proyek
                                </label>

                                <select
                                    id="project_id"
                                    name="project_id"
                                    class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >

                                    <option value="">
                                        Semua Proyek
                                    </option>

                                    @foreach ($projects as $project)

                                        <option
                                            value="{{ $project->id }}"
                                            @selected(
                                                (string) request('project_id') ===
                                                (string) $project->id
                                            )
                                        >
                                            {{ $project->project_name ?? $project->title }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- Button --}}
                        <div class="flex justify-end gap-3 mt-4">

                            <a
                                href="{{ route('pimpinan.dashboard') }}"
                                class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-sm font-medium text-gray-600 hover:bg-gray-50 transition"
                            >
                                Reset
                            </a>

                            <button
                                type="submit"
                                class="px-5 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition"
                            >
                                Terapkan Filter
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- SUMMARY --}}
            {{-- ========================================================= --}}

            @php

                $totalEmployeesMonitoring =
                    count($employeeMonitoring);

                $lebihCepat =
                    collect($employeeMonitoring)
                        ->where('status', 'Lebih Cepat')
                        ->count();

                $sesuaiRencana =
                    collect($employeeMonitoring)
                        ->where('status', 'Sesuai Rencana')
                        ->count();

                $tertinggal =
                    collect($employeeMonitoring)
                        ->where('status', 'Tertinggal')
                        ->count();

                $averagePlanning =
                    $totalEmployeesMonitoring > 0
                        ? round(
                            collect($employeeMonitoring)
                                ->avg('planning')
                        )
                        : 0;

                $averageActual =
                    $totalEmployeesMonitoring > 0
                        ? round(
                            collect($employeeMonitoring)
                                ->avg('actual')
                        )
                        : 0;

            @endphp


            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

                {{-- Planning --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm text-gray-500">
                                Rata-rata Planning
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-800">
                                {{ $averagePlanning }}%
                            </p>

                        </div>

                        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">

                            <span class="text-blue-600 text-lg">
                                P
                            </span>

                        </div>

                    </div>

                    <p class="mt-3 text-xs text-gray-400">
                        Target progress yang seharusnya dicapai
                    </p>

                </div>


                {{-- Aktual --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm text-gray-500">
                                Rata-rata Aktual
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-800">
                                {{ $averageActual }}%
                            </p>

                        </div>

                        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center">

                            <span class="text-green-600 text-lg">
                                A
                            </span>

                        </div>

                    </div>

                    <p class="mt-3 text-xs text-gray-400">
                        Progress berdasarkan logbook yang disetujui
                    </p>

                </div>
                </div>

            {{-- ========================================================= --}}
            {{-- STATUS RINGKAS --}}
            {{-- ========================================================= --}}

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

                <div class="bg-green-50 border border-green-100 rounded-2xl p-4">

                    <p class="text-sm text-green-600">
                        Lebih Cepat
                    </p>

                    <p class="mt-1 text-2xl font-bold text-green-700">
                        {{ $lebihCepat }}
                    </p>

                </div>


                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">

                    <p class="text-sm text-blue-600">
                        Sesuai Rencana
                    </p>

                    <p class="mt-1 text-2xl font-bold text-blue-700">
                        {{ $sesuaiRencana }}
                    </p>

                </div>


                <div class="bg-red-50 border border-red-100 rounded-2xl p-4">

                    <p class="text-sm text-red-600">
                        Tertinggal
                    </p>

                    <p class="mt-1 text-2xl font-bold text-red-700">
                        {{ $tertinggal }}
                    </p>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- GRAFIK --}}
            {{-- ========================================================= --}}

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">

                <div class="mb-5">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Planning vs Aktual Karyawan
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Perbandingan progress yang direncanakan dengan realisasi
                        sampai tanggal {{ \Carbon\Carbon::parse($asOfDate)->format('d M Y') }}.
                    </p>

                </div>


                <div class="relative h-96">

                    <canvas id="employeeProgressChart"></canvas>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- TABEL UTAMA --}}
            {{-- ========================================================= --}}

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">

                <div class="p-6 border-b border-gray-100">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                        <div>

                            <h2 class="text-lg font-semibold text-gray-800">
                                Monitoring Pekerjaan Karyawan
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                Planning dibandingkan dengan aktual berdasarkan
                                logbook yang telah disetujui.
                            </p>

                        </div>


                        <div class="text-sm text-gray-500">

                            {{ $totalEmployeesMonitoring }}
                            karyawan

                        </div>

                    </div>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-gray-50">

                            <tr class="border-b border-gray-200">

                                <th class="px-5 py-4 text-left font-semibold text-gray-600">
                                    Karyawan
                                </th>

                                <th class="px-5 py-4 text-center font-semibold text-gray-600">
                                    Pekerjaan
                                </th>

                                <th class="px-5 py-4 text-left font-semibold text-gray-600">
                                    Planning & Aktual
                                </th>

                                <th class="px-5 py-4 text-center font-semibold text-gray-600">
                                    Gap
                                </th>

                                <th class="px-5 py-4 text-center font-semibold text-gray-600">
                                    Status
                                </th>

                                <th class="px-5 py-4 text-center font-semibold text-gray-600">
                                    Ringkasan AI
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($employeeMonitoring as $item)

                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

                                    {{-- Karyawan --}}
                                    <td class="px-5 py-5">

                                        <div class="flex items-center gap-3">

                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">

                                                <span class="font-semibold text-gray-600">

                                                    {{ strtoupper(
                                                        substr(
                                                            $item['employee']->name,
                                                            0,
                                                            1
                                                        )
                                                    ) }}

                                                </span>

                                            </div>


                                            <div>

                                                <p class="font-semibold text-gray-800">

                                                    {{ $item['employee']->name }}

                                                </p>

                                                <p class="text-xs text-gray-400">

                                                    {{ $item['employee']->email }}

                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Jumlah Task --}}
                                    <td class="px-5 py-5 text-center">

                                        <span class="inline-flex items-center justify-center
                                                     min-w-8 px-2.5 py-1
                                                     rounded-lg bg-gray-100
                                                     text-gray-700 font-semibold">

                                            {{ $item['task_count'] }}

                                        </span>

                                    </td>


                                    {{-- Planning & Aktual (Sejajar Atas Bawah) --}}
                                    <td class="px-5 py-5">

                                        <div class="min-w-44 space-y-2.5">

                                            {{-- Planning (Atas) --}}
                                            <div>

                                                <div class="flex items-center justify-between mb-1">

                                                    <span class="text-xs font-medium text-gray-500 flex items-center gap-1.5">
                                                        <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
                                                        Planning
                                                    </span>

                                                    <span class="text-xs font-bold text-blue-600">
                                                        {{ $item['planning'] }}%
                                                    </span>

                                                </div>

                                                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">

                                                    <div
                                                        class="h-full bg-blue-500 rounded-full transition-all duration-300"
                                                        style="width: {{ min($item['planning'], 100) }}%"
                                                    ></div>

                                                </div>

                                            </div>


                                            {{-- Aktual (Bawah) --}}
                                            <div>

                                                <div class="flex items-center justify-between mb-1">

                                                    <span class="text-xs font-medium text-gray-500 flex items-center gap-1.5">
                                                        <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                                                        Aktual
                                                    </span>

                                                    <span class="text-xs font-bold text-green-600">
                                                        {{ $item['actual'] }}%
                                                    </span>

                                                </div>

                                                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">

                                                    <div
                                                        class="h-full bg-green-500 rounded-full transition-all duration-300"
                                                        style="width: {{ min($item['actual'], 100) }}%"
                                                    ></div>

                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Gap --}}
                                    <td class="px-5 py-5 text-center">

                                        @if ($item['gap'] > 0)

                                            <span class="font-semibold text-green-600">

                                                +{{ $item['gap'] }}%

                                            </span>

                                        @elseif ($item['gap'] < 0)

                                            <span class="font-semibold text-red-600">

                                                {{ $item['gap'] }}%

                                            </span>

                                        @else

                                            <span class="font-semibold text-gray-600">
                                                0%
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td class="px-5 py-5 text-center">

                                        @if ($item['status'] === 'Lebih Cepat')

                                            <span class="inline-flex px-3 py-1.5 rounded-full
                                                         bg-green-100 text-green-700
                                                         text-xs font-semibold">

                                                Lebih Cepat

                                            </span>

                                        @elseif ($item['status'] === 'Sesuai Rencana')

                                            <span class="inline-flex px-3 py-1.5 rounded-full
                                                         bg-blue-100 text-blue-700
                                                         text-xs font-semibold">

                                                Sesuai Rencana

                                            </span>

                                        @elseif ($item['status'] === 'Tertinggal')

                                            <span class="inline-flex px-3 py-1.5 rounded-full
                                                         bg-red-100 text-red-700
                                                         text-xs font-semibold">

                                                Tertinggal

                                            </span>

                                        @endif

                                    </td>

                                    {{-- Action Ringkasan AI --}}
                                    <td class="px-5 py-5 text-center">
                                        <button
                                            type="button"
                                            class="row-summary-ai-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white text-xs font-semibold border border-blue-200 hover:border-blue-600 shadow-2xs hover:shadow-xs transition duration-150 transform hover:-translate-y-0.5 cursor-pointer group"
                                            data-employee-id="{{ $item['employee']->id }}"
                                            data-employee-name="{{ $item['employee']->name }}"
                                            title="Lihat Ringkasan AI untuk {{ $item['employee']->name }}"
                                        >
                                            <span>Lihat Ringkasan</span>
                                        </button>
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="px-5 py-12 text-center"
                                    >

                                        <div class="text-gray-400">

                                            <p class="text-sm">
                                                Belum ada data pekerjaan karyawan.
                                            </p>

                                            <p class="text-xs mt-1">
                                                Coba ubah filter departemen, divisi,
                                                proyek, atau tanggal monitoring.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- MODAL RINGKASAN LOGBOOK KARYAWAN --}}
            {{-- ========================================================= --}}
            <div
                id="employeeAiModal"
                class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-xs transition-opacity duration-200"
                aria-labelledby="modalTitle"
                role="dialog"
                aria-modal="true"
            >
                <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100"
                        onclick="event.stopPropagation()"
                    >
                        {{-- HEADER MODAL --}}
                        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-6 py-5 text-white">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white shadow-inner shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 id="modalTitle" class="text-lg font-bold tracking-tight text-white flex items-center gap-2">
                                            <span>Ringkasan Logbook Karyawan</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white/20 text-white border border-white/30">AI Summary</span>
                                        </h3>
                                        <p id="modalEmployeeSubtitle" class="text-xs text-blue-100 mt-0.5 font-medium">
                                            Memuat data logbook karyawan...
                                        </p>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    id="closeModalXBtn"
                                    class="rounded-lg p-1.5 text-white/80 hover:text-white hover:bg-white/10 transition cursor-pointer"
                                    title="Tutup Modal"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- BODY MODAL --}}
                        <div class="p-6 bg-gray-50/50 space-y-4 max-h-[75vh] overflow-y-auto">

                            {{-- SPINNER / LOADING STATE --}}
                            <div id="modalLoadingState" class="py-12 text-center space-y-3">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-3 border-blue-600 border-t-transparent"></div>
                                <p class="text-sm font-medium text-gray-700 animate-pulse">Menyusun ringkasan logbook karyawan...</p>
                                <p class="text-xs text-gray-400">Menganalisis sub target, kegiatan yang dilakukan, dan kendala</p>
                            </div>

                            {{-- ERROR STATE --}}
                            <div id="modalErrorState" class="hidden p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 space-y-2">
                                <div class="flex items-center gap-2 font-semibold text-sm">
                                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span>Gagal Memuat Ringkasan</span>
                                </div>
                                <p id="modalErrorMessage" class="text-xs text-red-600 pl-7"></p>
                                <div class="pl-7 pt-1">
                                    <button type="button" id="modalRetryBtn" class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition cursor-pointer">
                                        Coba Lagi
                                    </button>
                                </div>
                            </div>

                            {{-- CONTENT STATE: FORMAT TEPAT DENGAN EFEK VISUAL MODERN & TIDAK POLOSAN --}}
                            <div id="modalContentState" class="hidden">
                                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-b from-white via-slate-50/50 to-blue-50/20 p-6 sm:p-7 border border-slate-200/90 shadow-md space-y-5">
                                    
                                    {{-- Subtle ambient glow effect --}}
                                    <div class="absolute -top-16 -right-16 w-52 h-52 bg-gradient-to-br from-blue-400/15 via-indigo-400/15 to-purple-400/0 rounded-full blur-3xl pointer-events-none"></div>
                                    <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-gradient-to-tr from-emerald-400/10 via-teal-400/10 to-transparent rounded-full blur-2xl pointer-events-none"></div>

                                    {{-- NAMA & DIVISI (HEADER KARTU DENGAN AVATAR & GLOW) --}}
                                    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200/70">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 flex items-center justify-center text-white font-black text-lg shadow-md ring-4 ring-blue-500/10 shrink-0">
                                                <span id="summaryAvatarInitial">K</span>
                                            </div>
                                            <div>
                                                <div class="flex items-baseline gap-2">
                                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">nama :</span>
                                                    <span id="summaryNama" class="text-base font-extrabold text-slate-900 tracking-tight">-</span>
                                                </div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">divisi :</span>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/70 shadow-2xs">
                                                        <span id="summaryDivisi">-</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Live Status AI Pill --}}
                                        <div class="inline-flex items-center gap-1.5 self-start sm:self-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold shadow-2xs">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                            </span>
                                            <span>Teranalisis AI</span>
                                        </div>
                                    </div>

                                    {{-- SUB TARGET --}}
                                    <div class="relative rounded-2xl bg-gradient-to-r from-blue-50/70 via-indigo-50/30 to-white p-4 border border-blue-100 shadow-2xs space-y-3 hover:border-blue-200 transition-all duration-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center shadow-xs">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                </div>
                                                <span class="text-xs font-extrabold text-blue-950 uppercase tracking-wide">sub target :</span>
                                            </div>
                                            <span id="summarySubTargetCount" class="text-[10px] font-bold text-blue-700 bg-white px-2 py-0.5 rounded-full border border-blue-200 shadow-2xs">0 Target</span>
                                        </div>
                                        <ul id="summarySubTargetList" class="space-y-2 pl-0.5">
                                            {{-- Rendered via JS --}}
                                        </ul>
                                    </div>

                                    {{-- KEGIATAN YG DILAKUKAN --}}
                                    <div class="relative rounded-2xl bg-gradient-to-r from-slate-50 via-emerald-50/20 to-white p-4 border border-slate-200/80 shadow-2xs space-y-3 hover:border-emerald-200/80 transition-all duration-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center shadow-xs">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </div>
                                                <span class="text-xs font-extrabold text-slate-900 uppercase tracking-wide">kegiatan yg dilakukan :</span>
                                            </div>
                                            <span id="summaryKegiatanCount" class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 shadow-2xs">0 Kegiatan</span>
                                        </div>
                                        <ul id="summaryKegiatanList" class="space-y-2 pl-0.5">
                                            {{-- Rendered via JS --}}
                                        </ul>
                                    </div>

                                    {{-- KENDALA --}}
                                    <div id="summaryKendalaCard" class="relative rounded-2xl bg-gradient-to-r from-amber-50/60 via-orange-50/20 to-white p-4 border border-amber-200/80 shadow-2xs space-y-3 hover:border-amber-300 transition-all duration-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <div id="summaryKendalaIconBg" class="w-6 h-6 rounded-lg bg-amber-500 text-white flex items-center justify-center shadow-xs">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                </div>
                                                <span class="text-xs font-extrabold text-slate-900 uppercase tracking-wide">kendala :</span>
                                            </div>
                                            <span id="summaryKendalaBadge" class="text-[10px] font-bold px-2 py-0.5 rounded-full border shadow-2xs bg-amber-100 text-amber-800 border-amber-300">
                                                Perlu Perhatian
                                            </span>
                                        </div>
                                        <ul id="summaryKendalaList" class="space-y-2 pl-0.5">
                                            {{-- Rendered via JS --}}
                                        </ul>
                                    </div>

                                </div>
                            </div>

                        </div>

                        {{-- FOOTER MODAL DENGAN EFEK TOMBOL INTERAKTIF --}}
                        <div class="bg-white px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                <span>Ringkasan performa dan logbook karyawan</span>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                <button
                                    type="button"
                                    id="modalRefreshBtn"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition cursor-pointer"
                                    title="Perbarui ringkasan data"
                                >
                                    <svg id="modalRefreshIcon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    <span>Perbarui</span>
                                </button>

                                <button
                                    type="button"
                                    id="modalCopyBtn"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white border border-blue-200 hover:border-blue-600 text-xs font-semibold shadow-2xs hover:shadow-xs transition duration-150 cursor-pointer group"
                                    title="Salin teks ringkasan"
                                >
                                    <svg class="w-3.5 h-3.5 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <span id="modalCopyBtnText">Salin Teks</span>
                                </button>

                                <button
                                    type="button"
                                    id="closeModalBottomBtn"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-xs transition cursor-pointer"
                                >
                                    <span>Tutup</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CHART.JS --}}
    {{-- ========================================================= --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const employeeData = @json(
                collect($employeeMonitoring)
                    ->map(function ($item) {

                        return [
                            'name' => $item['employee']->name,
                            'planning' => $item['planning'],
                            'actual' => $item['actual'],
                        ];

                    })
                    ->values()
            );


            const canvas =
                document.getElementById('employeeProgressChart');


            if (!canvas) {
                return;
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

            const pimpinanChart = new Chart(canvas, {

                type: 'bar',

                data: {

                    labels: employeeData.map(
                        item => item.name
                    ),

                    datasets: [

                        {
                            label: 'Planning (%)',

                            data: employeeData.map(
                                item => item.planning
                            ),

                            backgroundColor: '#3b82f6',
                            borderColor: '#1e40af',
                            borderWidth: 1,
                            barPercentage: 1.0,
                            categoryPercentage: 0.65
                        },

                        {
                            label: 'Aktual (%)',

                            data: employeeData.map(
                                item => item.actual
                            ),

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
                        mode: 'index',
                        intersect: false
                    },

                    hover: {
                        mode: 'index',
                        intersect: false
                    },

                    scales: {

                        y: {

                            beginAtZero: true,

                            max: 100,

                            ticks: {

                                callback: function (value) {

                                    return value + '%';

                                }

                            }

                        }

                    },


                    plugins: {

                        legend: {

                            position: 'top'

                        },

                        tooltip: {
                            enabled: false
                        }

                    }

                },

                plugins: [cube3dBarPlugin]

            });

            /*
            |--------------------------------------------------------------------------
            | MODAL RINGKASAN LOGBOOK KARYAWAN (SPESIFIK PER KARYAWAN)
            |--------------------------------------------------------------------------
            */
            const employeeAiModal = document.getElementById('employeeAiModal');
            const modalEmployeeSubtitle = document.getElementById('modalEmployeeSubtitle');
            const modalLoadingState = document.getElementById('modalLoadingState');
            const modalErrorState = document.getElementById('modalErrorState');
            const modalErrorMessage = document.getElementById('modalErrorMessage');
            const modalRetryBtn = document.getElementById('modalRetryBtn');
            const modalContentState = document.getElementById('modalContentState');

            const summaryAvatarInitial = document.getElementById('summaryAvatarInitial');
            const summaryNama = document.getElementById('summaryNama');
            const summaryDivisi = document.getElementById('summaryDivisi');
            const summarySubTargetCount = document.getElementById('summarySubTargetCount');
            const summarySubTargetList = document.getElementById('summarySubTargetList');
            const summaryKegiatanCount = document.getElementById('summaryKegiatanCount');
            const summaryKegiatanList = document.getElementById('summaryKegiatanList');
            const summaryKendalaCard = document.getElementById('summaryKendalaCard');
            const summaryKendalaBadge = document.getElementById('summaryKendalaBadge');
            const summaryKendalaIconBg = document.getElementById('summaryKendalaIconBg');
            const summaryKendalaList = document.getElementById('summaryKendalaList');
            const modalCopyBtn = document.getElementById('modalCopyBtn');
            const modalCopyBtnText = document.getElementById('modalCopyBtnText');
            const modalRefreshBtn = document.getElementById('modalRefreshBtn');
            const modalRefreshIcon = document.getElementById('modalRefreshIcon');

            const closeModalXBtn = document.getElementById('closeModalXBtn');
            const closeModalBottomBtn = document.getElementById('closeModalBottomBtn');

            let activeModalEmployee = null;
            let activeSummaryText = '';

            function escapeHtml(str) {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function openEmployeeModal(employeeId, employeeName) {
                activeModalEmployee = { id: employeeId, name: employeeName };
                if (!employeeAiModal) return;

                if (summaryAvatarInitial) {
                    summaryAvatarInitial.textContent = (employeeName || 'K').trim().charAt(0).toUpperCase();
                }

                employeeAiModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                if (modalEmployeeSubtitle) {
                    modalEmployeeSubtitle.textContent = `Ringkasan Logbook: ${employeeName}`;
                }

                fetchEmployeeSummary(employeeId, false);
            }

            function closeEmployeeModal() {
                if (!employeeAiModal) return;
                employeeAiModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            closeModalXBtn?.addEventListener('click', closeEmployeeModal);
            closeModalBottomBtn?.addEventListener('click', closeEmployeeModal);
            employeeAiModal?.addEventListener('click', function(e) {
                if (e.target === employeeAiModal) {
                    closeEmployeeModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && employeeAiModal && !employeeAiModal.classList.contains('hidden')) {
                    closeEmployeeModal();
                }
            });

            modalRetryBtn?.addEventListener('click', function() {
                if (activeModalEmployee) {
                    fetchEmployeeSummary(activeModalEmployee.id, true);
                }
            });

            modalRefreshBtn?.addEventListener('click', function() {
                if (activeModalEmployee) {
                    modalRefreshIcon?.classList.add('animate-spin');
                    fetchEmployeeSummary(activeModalEmployee.id, true).finally(() => {
                        setTimeout(() => modalRefreshIcon?.classList.remove('animate-spin'), 600);
                    });
                }
            });

            modalCopyBtn?.addEventListener('click', async function() {
                if (!activeSummaryText) return;
                try {
                    await navigator.clipboard.writeText(activeSummaryText);
                    if (modalCopyBtnText) {
                        const origText = modalCopyBtnText.textContent;
                        modalCopyBtnText.textContent = 'Tersalin!';
                        modalCopyBtn.classList.remove('bg-blue-50', 'text-blue-700', 'border-blue-200');
                        modalCopyBtn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');
                        setTimeout(() => {
                            modalCopyBtnText.textContent = origText;
                            modalCopyBtn.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600');
                            modalCopyBtn.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-200');
                        }, 2200);
                    }
                } catch (err) {
                    console.error('Gagal menyalin:', err);
                }
            });

            async function fetchEmployeeSummary(employeeId, forceFresh = false) {
                if (!modalLoadingState || !modalContentState) return;

                modalLoadingState.classList.remove('hidden');
                modalErrorState.classList.add('hidden');
                modalContentState.classList.add('hidden');

                try {
                    const asOfDateInput = document.querySelector('input[name="as_of_date"]');
                    const divisionSelect = document.querySelector('select[name="division_id"]');

                    const response = await fetch(@json(route('pimpinan.dashboard.summarize')), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            end_date: asOfDateInput?.value || null,
                            division_id: divisionSelect?.value || null,
                            employee_id: employeeId,
                            fresh: forceFresh
                        })
                    });

                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Ringkasan gagal dibuat.');
                    }

                    modalLoadingState.classList.add('hidden');
                    modalContentState.classList.remove('hidden');

                    const report = data.report || {};
                    activeSummaryText = data.summary || '';

                    // Render Nama & Divisi & Avatar
                    const empName = report.nama || data.employee?.name || '-';
                    if (summaryNama) {
                        summaryNama.textContent = empName;
                    }
                    if (summaryAvatarInitial) {
                        summaryAvatarInitial.textContent = empName.trim().charAt(0).toUpperCase();
                    }
                    if (summaryDivisi) {
                        summaryDivisi.textContent = report.divisi || data.employee?.division || '-';
                    }

                    // Render Sub Target dengan Efek Kartu Beraksen
                    if (summarySubTargetList) {
                        const subTargets = report.sub_targets || [];
                        if (summarySubTargetCount) {
                            summarySubTargetCount.textContent = `${subTargets.length} Sub Target`;
                        }
                        if (subTargets.length === 0) {
                            summarySubTargetList.innerHTML = `<li class="text-xs text-slate-400 italic py-1">- Belum ada sub target yang ditugaskan.</li>`;
                        } else {
                            summarySubTargetList.innerHTML = subTargets.map(st => `
                                <li class="text-sm text-slate-800 flex items-start gap-2.5 p-2.5 rounded-xl bg-white/80 hover:bg-white border border-blue-100/70 hover:border-blue-300 shadow-2xs hover:shadow-xs transition-all duration-200 hover:translate-x-1 group">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-lg bg-blue-100 group-hover:bg-blue-600 text-blue-700 group-hover:text-white font-black text-xs select-none transition-colors shrink-0 shadow-2xs mt-0.5">-</span>
                                    <span class="font-semibold text-slate-800 leading-snug">${escapeHtml(st)}</span>
                                </li>
                            `).join('');
                        }
                    }

                    // Render Kegiatan yang dilakukan dengan Efek Kartu Interaktif
                    if (summaryKegiatanList) {
                        const kegiatan = report.kegiatan || [];
                        if (summaryKegiatanCount) {
                            summaryKegiatanCount.textContent = `${kegiatan.length} Kegiatan`;
                        }
                        if (kegiatan.length === 0) {
                            summaryKegiatanList.innerHTML = `<li class="text-xs text-slate-400 italic py-1">- Belum ada catatan aktivitas kegiatan.</li>`;
                        } else {
                            summaryKegiatanList.innerHTML = kegiatan.map(kg => `
                                <li class="text-sm text-slate-800 flex items-start gap-2.5 p-2.5 rounded-xl bg-white/80 hover:bg-white border border-slate-200/70 hover:border-emerald-300 shadow-2xs hover:shadow-xs transition-all duration-200 hover:translate-x-1 group">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-lg bg-emerald-100 group-hover:bg-emerald-600 text-emerald-700 group-hover:text-white font-black text-xs select-none transition-colors shrink-0 shadow-2xs mt-0.5">-</span>
                                    <span class="leading-relaxed font-normal text-slate-800">${escapeHtml(kg)}</span>
                                </li>
                            `).join('');
                        }
                    }

                    // Render Kendala dengan Efek Status Adaptif
                    if (summaryKendalaList) {
                        const kendala = report.kendala || [];
                        const isAllClear = kendala.length === 0 || kendala.every(kd => kd.toLowerCase().includes('tidak ada kendala'));

                        if (isAllClear) {
                            if (summaryKendalaCard) {
                                summaryKendalaCard.className = 'relative rounded-2xl bg-gradient-to-r from-emerald-50/70 via-teal-50/30 to-white p-4 border border-emerald-200 shadow-2xs space-y-3 hover:border-emerald-300 transition-all duration-200';
                            }
                            if (summaryKendalaIconBg) {
                                summaryKendalaIconBg.className = 'w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center shadow-xs';
                            }
                            if (summaryKendalaBadge) {
                                summaryKendalaBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full border shadow-2xs bg-emerald-100 text-emerald-800 border-emerald-300';
                                summaryKendalaBadge.textContent = 'Aman / Nihil';
                            }
                            summaryKendalaList.innerHTML = `
                                <li class="text-sm text-emerald-900 flex items-start gap-2.5 p-2.5 rounded-xl bg-emerald-50/80 border border-emerald-200 shadow-2xs">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-lg bg-emerald-600 text-white font-bold text-xs select-none shrink-0 mt-0.5">✓</span>
                                    <span class="font-medium leading-relaxed">Tidak ada kendala yang dilaporkan (seluruh progres pekerjaan berjalan aman dan lancar).</span>
                                </li>
                            `;
                        } else {
                            if (summaryKendalaCard) {
                                summaryKendalaCard.className = 'relative rounded-2xl bg-gradient-to-r from-amber-50/70 via-orange-50/30 to-white p-4 border border-amber-200/90 shadow-2xs space-y-3 hover:border-amber-300 transition-all duration-200';
                            }
                            if (summaryKendalaIconBg) {
                                summaryKendalaIconBg.className = 'w-6 h-6 rounded-lg bg-amber-500 text-white flex items-center justify-center shadow-xs';
                            }
                            if (summaryKendalaBadge) {
                                summaryKendalaBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full border shadow-2xs bg-amber-100 text-amber-800 border-amber-300';
                                summaryKendalaBadge.textContent = `${kendala.length} Kendala Dilaporkan`;
                            }
                            summaryKendalaList.innerHTML = kendala.map(kd => `
                                <li class="text-sm text-amber-950 flex items-start gap-2.5 p-2.5 rounded-xl bg-white/90 hover:bg-white border border-amber-200/80 hover:border-amber-400 shadow-2xs hover:shadow-xs transition-all duration-200 hover:translate-x-1 group">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-lg bg-amber-100 group-hover:bg-amber-600 text-amber-800 group-hover:text-white font-black text-xs select-none transition-colors shrink-0 shadow-2xs mt-0.5">-</span>
                                    <span class="leading-relaxed font-medium text-amber-950">${escapeHtml(kd)}</span>
                                </li>
                            `).join('');
                        }
                    }

                    if (modalEmployeeSubtitle && data.employee) {
                        modalEmployeeSubtitle.textContent = `${data.employee.name} (${data.employee.division || 'Tanpa Divisi'}) • Periode: ${data.source?.period || ''}`;
                    }

                } catch (err) {
                    modalLoadingState.classList.add('hidden');
                    modalErrorState.classList.remove('hidden');
                    modalErrorMessage.textContent = err.message || 'Terjadi kesalahan saat memuat ringkasan.';
                }
            }

            // Event listener tombol per baris karyawan (Lihat Ringkasan)
            document.querySelectorAll('.row-summary-ai-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const empId = this.dataset.employeeId;
                    const empName = this.dataset.employeeName;
                    openEmployeeModal(empId, empName);
                });
            });

        });

    </script>

</x-app-layout>