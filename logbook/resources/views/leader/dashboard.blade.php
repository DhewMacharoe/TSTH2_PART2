<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="max-w-7xl mx-auto px-6">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">
                    Dashboard Pimpinan
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Ringkasan pencapaian target dan monitoring pekerjaan karyawan.
                </p>
            </div>

            <section id="dashboardSection" @class(['space-y-6', 'hidden' => $activeSection !== 'dashboard'])>

            {{-- FILTER MONITORING SISTEM BRIMO --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">

                <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Filter Monitoring
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Tentukan periode dan unit kerja yang ingin dipantau.
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

                <form
                    method="GET"
                    action="{{ route('pimpinan.dashboard') }}"
                >
                    <input type="hidden" name="section" value="{{ $activeSection }}">

                    {{-- PILIHAN CEPAT RENTANG WAKTU (SISTEM BRIMO) --}}
                    <div class="mb-5">
                        <label class="block text-xs font-medium text-gray-600 mb-2">
                            Pilihan Cepat Rentang Waktu
                        </label>
                        <div class="flex flex-wrap items-center gap-2">
                            <button
                                type="button"
                                data-preset="7_days"
                                class="leader-filter-preset-pill px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === '7_days' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                            >
                                7 Hari Terakhir
                            </button>
                            <button
                                type="button"
                                data-preset="30_days"
                                class="leader-filter-preset-pill px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === '30_days' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                            >
                                30 Hari Terakhir
                            </button>
                            <button
                                type="button"
                                data-preset="this_month"
                                class="leader-filter-preset-pill px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === 'this_month' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                            >
                                Bulan Ini
                            </button>
                            <button
                                type="button"
                                data-preset="last_month"
                                class="leader-filter-preset-pill px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === 'last_month' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                            >
                                Bulan Sebelumnya
                            </button>
                            <button
                                type="button"
                                data-preset="this_year"
                                class="leader-filter-preset-pill px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === 'this_year' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                            >
                                Tahun Ini
                            </button>

                            {{-- MENU PILIH BULAN (BULAN DAN TAHUN BISA DIUBAH SEPERTI DI BRIMO) --}}
                            <div class="relative inline-block">
                                <button
                                    type="button"
                                    id="btnLeaderMonthPickerDropdown"
                                    data-preset="month_picker"
                                    class="leader-filter-preset-pill inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer {{ $preset === 'month_picker' ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-semibold' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium' }}"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span id="leaderMonthPickerBtnLabel">
                                        @php
                                            $mNames = [
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                                            ];
                                        @endphp
                                        {{ $preset === 'month_picker' ? 'Bulan: ' . ($mNames[$filterMonth] ?? 'Bulan ' . $filterMonth) . ' ' . $filterYear : 'Pilih Bulan' }}
                                    </span>
                                    <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                {{-- POPOVER PILIH BULAN & TAHUN BRIMO --}}
                                <div
                                    id="leaderMonthPickerPopover"
                                    class="hidden absolute left-0 top-full mt-2 z-30 w-72 rounded-2xl bg-white border border-gray-200 p-4 shadow-xl"
                                >
                                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100">
                                        <span class="text-xs font-bold text-gray-800">Pilih Bulan & Tahun</span>
                                        <div class="flex items-center gap-1.5">
                                            <label for="leaderMonthPickerYear" class="text-[11px] text-gray-500 font-medium">Tahun:</label>
                                            <select
                                                id="leaderMonthPickerYear"
                                                class="text-xs font-bold text-blue-700 rounded-lg border-gray-200 py-1 px-2 focus:ring-blue-500 focus:border-blue-500"
                                            >
                                                @foreach ($availableYears as $yr)
                                                    <option value="{{ $yr }}" @selected($filterYear == $yr)>{{ $yr }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- GRID 12 BULAN --}}
                                    <div class="grid grid-cols-3 gap-2">
                                        @php
                                            $shortMonthsList = [
                                                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                                5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                                                9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
                                            ];
                                        @endphp
                                        @foreach ($shortMonthsList as $mNum => $mShort)
                                            <button
                                                type="button"
                                                data-month="{{ $mNum }}"
                                                class="leader-month-picker-item py-2 px-2 text-center rounded-xl text-xs font-medium transition cursor-pointer {{ $preset === 'month_picker' && $filterMonth == $mNum ? 'bg-blue-600 text-white font-bold shadow-xs' : 'bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700' }}"
                                            >
                                                {{ $mShort }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="filter_month" id="leader_filter_month" value="{{ $filterMonth }}">
                        <input type="hidden" name="filter_year" id="leader_filter_year" value="{{ $filterYear }}">
                        <input type="hidden" name="preset" id="leader_filter_preset" value="{{ old('preset', $preset) }}">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        {{-- TANGGAL MULAI --}}
                        <div>
                            <label for="leader_start_date" class="block text-xs font-medium text-gray-600 mb-1">
                                Tanggal Mulai 
                            </label>
                            <input
                                type="date"
                                id="leader_start_date"
                                name="start_date"
                                value="{{ old('start_date', $startDate->format('Y-m-d')) }}"
                                required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        {{-- TANGGAL AKHIR --}}
                        <div>
                            <label for="leader_end_date" class="block text-xs font-medium text-gray-600 mb-1">
                                Tanggal Akhir 
                            </label>
                            <input
                                type="date"
                                id="leader_end_date"
                                name="end_date"
                                value="{{ old('end_date', $endDate->format('Y-m-d')) }}"
                                min="{{ old('start_date', $startDate->format('Y-m-d')) }}"
                                required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        {{-- DIVISI --}}
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
                                <option value="">Semua Divisi</option>
                                @foreach ($divisions as $division)
                                    <option
                                        value="{{ $division->id }}"
                                        @selected((string) ($divisionId ?? request('division_id')) === (string) $division->id)
                                    >
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- KARYAWAN --}}
                        <div>
                            <label for="employee_id" class="block text-xs font-medium text-gray-600 mb-1">
                                Karyawan
                            </label>
                            <select
                                id="employee_id"
                                name="employee_id"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Semua Karyawan</option>
                                @foreach ($chartEmployees as $chartEmployee)
                                    <option
                                        value="{{ $chartEmployee['id'] }}"
                                        data-division-id="{{ $chartEmployee['division_id'] }}"
                                        @selected((string) ($employeeId ?? request('employee_id')) === (string) $chartEmployee['id'])
                                    >
                                        {{ $chartEmployee['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- INDIKATOR FILTER AKTIF --}}
                    @php
                        $leaderActiveFilterBadges = [];
                        if ($preset === '7_days') $leaderActiveFilterBadges[] = 'Periode: 7 Hari Terakhir';
                        elseif ($preset === '30_days') $leaderActiveFilterBadges[] = 'Periode: 30 Hari Terakhir';
                        elseif ($preset === 'this_month') $leaderActiveFilterBadges[] = 'Periode: Bulan Ini';
                        elseif ($preset === 'last_month') $leaderActiveFilterBadges[] = 'Periode: Bulan Sebelumnya';
                        elseif ($preset === 'this_year') $leaderActiveFilterBadges[] = 'Periode: Tahun Ini';
                        elseif ($preset === 'month_picker') $leaderActiveFilterBadges[] = 'Bulan: ' . ($mNames[$filterMonth] ?? $filterMonth) . ' ' . $filterYear;
                        elseif ($preset === 'custom') $leaderActiveFilterBadges[] = 'Rentang: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');

                        if (!empty($divisionId)) {
                            $divObj = $divisions->firstWhere('id', $divisionId);
                            if ($divObj) $leaderActiveFilterBadges[] = 'Divisi: ' . $divObj->name;
                        }
                        if (!empty($employeeId)) {
                            $empObj = collect($chartEmployees)->firstWhere('id', $employeeId);
                            if ($empObj) $leaderActiveFilterBadges[] = 'Karyawan: ' . ($empObj['name'] ?? $empObj->name);
                        }
                    @endphp

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-5 pt-4 border-t border-gray-100">
                        <div class="flex flex-wrap items-center gap-2">
                            @if (!empty($leaderActiveFilterBadges))
                                <span class="text-xs font-semibold text-gray-500 mr-1">Filter Aktif:</span>
                                @foreach ($leaderActiveFilterBadges as $badge)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $badge }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-xs text-gray-400">Menampilkan seluruh data periode ini (default).</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a
                                href="{{ route('pimpinan.dashboard') }}"
                                class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-sm font-medium text-gray-600 hover:bg-gray-50 transition"
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

            </div>


            {{-- DATA RINGKAS --}}
            @php

                $monitoring = collect($employeeMonitoring);

                $totalEmployees = $monitoring->count();

                $lebihCepat = $monitoring
                    ->where('status', 'Lebih Cepat')
                    ->count();

                $sesuaiRencana = $monitoring
                    ->where('status', 'Sesuai Rencana')
                    ->count();

                $tertinggal = $monitoring
                    ->where('status', 'Tertinggal')
                    ->count();

                $belumAdaTask = $monitoring
                    ->where('status', 'Belum Ada Task')
                    ->count();

                $averagePlanning = $totalEmployees > 0
                    ? round($monitoring->avg('planning'))
                    : 0;

                $averageActual = $totalEmployees > 0
                    ? round($monitoring->avg('actual'))
                    : 0;

            @endphp


            {{-- SUMMARY --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6 items-stretch">

                {{-- PLANNING --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 text-center">

                    <p class="text-sm text-gray-500">
                        Rata-rata Planning
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-600">
                        {{ $averagePlanning }}%
                    </p>

                    <p class="mt-2 text-xs text-gray-400">
                        Progress yang seharusnya dicapai
                    </p>

                </div>


                {{-- AKTUAL --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 text-center">

                    <p class="text-sm text-gray-500">
                        Rata-rata Aktual
                    </p>

                    <p class="mt-2 text-3xl font-bold text-green-600">
                        {{ $averageActual }}%
                    </p>

                    <p class="mt-2 text-xs text-gray-400">
                        Progress yang sudah diverifikasi
                    </p>

                </div>

            </div>


            {{-- STATUS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6 items-stretch">

                <div class="bg-green-50 border border-green-100 rounded-2xl p-4 text-center">

                    <p class="text-sm text-green-600">
                        Lebih Cepat
                    </p>

                    <p class="mt-1 text-2xl font-bold text-green-700">
                        {{ $lebihCepat }}
                    </p>

                </div>


                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-center">

                    <p class="text-sm text-blue-600">
                        Sesuai Rencana
                    </p>

                    <p class="mt-1 text-2xl font-bold text-blue-700">
                        {{ $sesuaiRencana }}
                    </p>

                </div>


                <div class="bg-red-50 border border-red-100 rounded-2xl p-4 text-center">

                    <p class="text-sm text-red-600">
                        Tertinggal
                    </p>

                    <p class="mt-1 text-2xl font-bold text-red-700">
                        {{ $tertinggal }}
                    </p>

                </div>

            </div>



        {{-- ========================================================= --}}
        {{-- GRAFIK TARGET DAN TASK (BAR CHART VERTICAL & LINE DRILLDOWN) --}}
        {{-- ========================================================= --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        Progress Target dan Task
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Grafik batang Planning vs Aktual per task. Klik batang untuk melihat rincian progres dalam diagram garis.
                    </p>
                </div>

            </div>

            {{-- HEADER DRILLDOWN (LINE CHART) --}}
            <div id="chartDrilldownHeader" class="hidden flex flex-wrap items-center justify-between gap-3 p-4 mb-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3">
                    <button type="button" id="btnBackToMonthly"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition shadow-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </button>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-md bg-blue-600 text-white text-[11px] font-bold">Diagram Garis (Histori Progres)</span>
                            <p class="text-sm font-bold text-blue-950" id="drilldownTaskTitle">Rekam Jejak Task</p>
                        </div>
                        <p class="text-xs text-blue-700 mt-0.5" id="drilldownTaskSubtitle">Periode</p>
                    </div>
                </div>
                <div class="text-xs font-medium text-gray-500 bg-white px-3 py-1 rounded-lg border border-gray-200 shadow-xs">
                    {{ $groupingLabel }}
                </div>
            </div>

            {{-- PETUNJUK INTERAKSI BARCHART --}}
            <div id="chartBarHint" class="flex flex-wrap items-center justify-between gap-3 mb-3 p-3 bg-gray-50/80 rounded-xl border border-gray-100 text-xs">
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
                <p class="text-gray-500">
                    💡 <strong>Petunjuk:</strong> Klik salah satu batang untuk membuka <strong class="text-blue-600">diagram garis (line chart)</strong> histori progres disetujui.
                </p>
            </div>

            {{-- CONTAINER KANVAS BARCHART BULANAN --}}
            <div id="barChartWrapper" class="w-full overflow-x-auto">
                <div id="barChartInner" class="relative h-[440px] w-full min-w-full">
                    <canvas id="targetTaskBarChart"></canvas>
                </div>
            </div>

            {{-- CONTAINER KANVAS DIAGRAM GARIS MINGGUAN (DRILL-DOWN LINE CHART) --}}
            <div id="weeklyBarChartWrapper" class="hidden">
                <div class="relative h-[420px] w-full">
                    <canvas id="taskWeeklyBarChart"></canvas>
                </div>
                <div id="leaderWeeklySummaryGrid" class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mt-4 pt-4 border-t border-gray-100"></div>
            </div>

            <p id="chartEmptyState" class="hidden my-12 text-center text-sm text-gray-500">
                Belum ada data target atau task untuk filter yang dipilih.
            </p>

            {{-- KETERANGAN TASK RAPI & JELAS DENGAN BENTUK TABEL & PAGINATION (10 TASK PER HALAMAN) --}}
            <div id="leaderTaskCatalogWrapper" class="mt-6 border-t border-gray-100 pt-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">
                        Keterangan Task
                    </h3>
                    <span id="leaderTaskCatalogCount" class="text-xs text-gray-400"></span>
                </div>

                {{-- TABEL KETERANGAN TASK --}}
                <div class="overflow-x-auto rounded-xl border border-gray-200/80 bg-white">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50/75 border-b border-gray-200 text-xs font-semibold text-gray-700">
                            <tr>
                                <th scope="col" class="w-16 px-4 py-3.5 text-center">Nomor</th>
                                <th scope="col" class="px-4 py-3.5">Nama Target</th>
                                <th scope="col" class="px-4 py-3.5">Divisi</th>
                                <th scope="col" class="px-4 py-3.5">Karyawan</th>
                                <th scope="col" class="px-4 py-3.5">Status</th>
                            </tr>
                        </thead>
                        <tbody id="leaderTaskCatalogTbody" class="divide-y divide-gray-100">
                            {{-- Dinamis diisi via JavaScript berdasarkan task yang tampil di chart --}}
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION KETERANGAN TASK (10 TASK PER HALAMAN) --}}
                <div id="leaderTaskPaginationWrapper" class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-gray-100 text-xs text-gray-600">
                    <span id="leaderTaskPaginationInfo"></span>
                    <div id="leaderTaskPaginationButtons" class="flex items-center gap-1"></div>
                </div>
            </div>

            <div class="mt-4 p-4 rounded-xl bg-gray-50 border border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <p class="text-xs text-gray-600">
                    <strong>Catatan:</strong> Progres dinyatakan dalam persen (0 - 100%). Batang Planning (Biru) dan Aktual (Hijau) menyatu berdampingan tanpa gap.
                </p>
                <span class="text-xs text-gray-400">
                    {{ $groupingLabel }} · Periode {{ $startDate->format('d M Y') }} – {{ $endDate->format('d M Y') }}
                </span>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- EXECUTIVE TARGET MONITORING: DONUT GAUGE 2 SEGMEN & MULTI-DEPARTEMEN --}}
        {{-- ========================================================= --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
            {{-- Header Section & Multi-Department Selector --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-5 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-600 ring-4 ring-blue-100 animate-pulse flex-shrink-0"></span>
                        <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                            Progress Agregat Target (Single Nested Donut Chart)
                        </h2>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Grafik Donat Berlapis minimalis & profesional · Cincin Dalam (Bobot Target 5x20%) vs Cincin Luar (Progress Aktual) mewakili 100% target.
                    </p>
                </div>

                {{-- Filter Kontrol Pimpinan: Departemen & Divisi --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Filter Departemen --}}
                    <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-200">
                        <label for="leaderExecutiveDeptFilter" class="text-xs font-semibold text-gray-600 whitespace-nowrap">Departemen:</label>
                        <select
                            id="leaderExecutiveDeptFilter"
                            class="rounded-lg border-gray-300 text-xs py-1 px-2.5 focus:border-blue-500 focus:ring-blue-500 bg-white font-medium"
                        >
                            <option value="all">Semua Departemen ({{ count($departments ?? []) }})</option>
                            @foreach ($departments ?? [] as $dept)
                                <option value="{{ $dept->id }}">
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Divisi --}}
                    <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-200">
                        <label for="leaderExecutiveDivisionFilter" class="text-xs font-semibold text-gray-600 whitespace-nowrap">Divisi:</label>
                        <select
                            id="leaderExecutiveDivisionFilter"
                            class="rounded-lg border-gray-300 text-xs py-1 px-2.5 focus:border-blue-500 focus:ring-blue-500 bg-white font-medium max-w-[200px]"
                        >
                            <option value="all">Semua Divisi ({{ count($divisions ?? []) }})</option>
                            @foreach ($divisions ?? [] as $div)
                                <option value="{{ $div->id }}" data-dept-id="{{ $div->department_id }}">
                                    {{ $div->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <span id="leaderPieAggregateBadge" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
                        Total Sub Target: <span id="leaderPieSubTargetCount" class="ml-1 font-extrabold">-</span>
                    </span>
                </div>
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
                        <canvas id="leaderUnifiedPieChart"></canvas>
                        
                        {{-- CENTER HOLE: TOTAL PROGRESS AGREGAT (TEKS TEBAL & BESAR BISA DIBACA 1 DETIK) --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Total Progress Agregat</span>
                            <span id="leaderAggregatePercent" class="text-3xl sm:text-4xl font-black font-mono tracking-tight text-gray-900 my-0.5">
                                0%
                            </span>
                            <span id="leaderAggregateBadge" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold shadow-xs bg-gray-100 text-gray-700">
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
                        <span id="leaderDivisionScopeBadge" class="text-xs font-semibold px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                            Semua Divisi
                        </span>
                    </div>

                    <div id="leaderQuadrantCardList" class="space-y-3">
                        {{-- Diisi secara dinamis melalui JavaScript --}}
                    </div>
                </div>
            </div>

            {{-- DETIK 3: DETAIL EKSEKUSI SUB-TARGET (COMPACT STATUS CARDS 3-KOLOM) --}}
            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">
                            Detail Eksekusi Sub-Target
                        </h3>
                        <p class="text-xs text-gray-500">
                            Rincian status sub-target di luar lingkaran diagram dengan indikator kerja semantik.
                        </p>
                    </div>

                    {{-- PANDUAN SEMANTIK WARNA --}}
                    <div class="flex items-center gap-4 text-xs font-semibold">
                        <span class="inline-flex items-center gap-1.5 text-emerald-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Selesai (100%)
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-blue-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>Sedang Berjalan (&gt;0%)
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-gray-400">
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-300"></span>Belum Dimulai (0%)
                        </span>
                    </div>
                </div>

                {{-- GRID SUB TARGET CARDS (3 KOLOM PADA LAYAR BESAR) --}}
                <div id="leaderPieSubTargetList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5 max-h-[500px] overflow-y-auto pr-1">
                    {{-- Diisi secara dinamis oleh JavaScript sesuai filter departemen dan target --}}
                </div>
            </div>
        </div>

            </section>

            <section id="monitoringSection" @class(['space-y-6', 'hidden' => $activeSection !== 'monitoring'])>

            {{-- TABEL --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">

                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-lg font-bold text-gray-800">
                                Monitoring Pekerjaan Karyawan
                            </h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $totalEmployees }} karyawan
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            Planning dibandingkan dengan aktual serta capaian persentase target.
                        </p>
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
                                    Task
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

                                    {{-- KARYAWAN --}}
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


                                    {{-- TASK --}}
                                    <td class="px-5 py-5 text-center">

                                        <span class="inline-flex items-center justify-center min-w-8 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold">

                                            {{ $item['task_count'] }}

                                        </span>

                                    </td>


                                    {{-- PLANNING & AKTUAL (SEJAJAR ATAS BAWAH) --}}
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


                                    {{-- GAP --}}
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


                                    {{-- STATUS --}}
                                    <td class="px-5 py-5 text-center">

                                        @if ($item['status'] === 'Lebih Cepat')

                                            <span class="inline-flex px-3 py-1.5 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                                Lebih Cepat
                                            </span>

                                        @elseif ($item['status'] === 'Sesuai Rencana')

                                            <span class="inline-flex px-3 py-1.5 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                                Sesuai Rencana
                                            </span>

                                        @elseif ($item['status'] === 'Tertinggal')

                                            <span class="inline-flex px-3 py-1.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                                Tertinggal
                                            </span>

                                        @else

                                            <span class="inline-flex px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">
                                                Belum Ada Task
                                            </span>

                                        @endif

                                    {{-- ACTION RINGKASAN AI PER KARYAWAN --}}
                                    <td class="px-5 py-5 text-center">
                                        <button
                                            type="button"
                                            class="row-summary-ai-btn inline-flex items-center justify-center px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white text-xs font-semibold border border-blue-200 hover:border-blue-600 shadow-2xs hover:shadow-xs transition duration-150 transform hover:-translate-y-0.5 cursor-pointer group"
                                            data-employee-id="{{ $item['employee']->id }}"
                                            data-employee-name="{{ $item['employee']->name }}"
                                            title="Lihat Ringkasan AI untuk {{ $item['employee']->name }}"
                                        >
                                            <span>Lihat Ringkasan AI</span>
                                        </button>
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="px-5 py-12 text-center"
                                    >

                                        <p class="text-sm text-gray-400">
                                            Belum ada data pekerjaan karyawan.
                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- MODAL RINGKASAN AI LOGBOOK KARYAWAN --}}
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
                                            {{-- Rendered via JS: - Rekapitulasi Data Genomic --}}
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
                                            {{-- Rendered via JS: - Menyelesaikan dokumen persiapan pengembangan --}}
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
                                            {{-- Rendered via JS: - Beberapa kebutuhan masih perlu diverifikasi --}}
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

            </section>

    </div>
    </div>



{{-- CHART JS --}}
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
    /*
    |--------------------------------------------------------------------------
    | PALETTE WATERFILL & HELPER WARNA (SAMA RUMPUN: SELESAI PEKAT & SISA SAMAR)
    |--------------------------------------------------------------------------
    */
    const WATERFILL_PALETTES = [
        {
            name: 'Merah',
            bold: '#dc2626',
            pale: '#fecaca',
            paleSolid: '#fee2e2',
            border: '#b91c1c',
            text: '#991b1b'
        },
        {
            name: 'Kuning',
            bold: '#d97706',
            pale: '#fde68a',
            paleSolid: '#fef3c7',
            border: '#b45309',
            text: '#92400e'
        },
        {
            name: 'Hijau',
            bold: '#059669',
            pale: '#a7f3d0',
            paleSolid: '#d1fae5',
            border: '#047857',
            text: '#065f46'
        },
        {
            name: 'Biru',
            bold: '#2563eb',
            pale: '#bfdbfe',
            paleSolid: '#dbeafe',
            border: '#1d4ed8',
            text: '#1e40af'
        },
        {
            name: 'Ungu',
            bold: '#7c3aed',
            pale: '#ddd6fe',
            paleSolid: '#ede9fe',
            border: '#6d28d9',
            text: '#5b21b6'
        },
        {
            name: 'Cyan',
            bold: '#0891b2',
            pale: '#a5f3fc',
            paleSolid: '#cffafe',
            border: '#0e7490',
            text: '#155e75'
        },
        {
            name: 'Orange',
            bold: '#ea580c',
            pale: '#fed7aa',
            paleSolid: '#ffedd5',
            border: '#c2410c',
            text: '#9a3412'
        },
        {
            name: 'Pink',
            bold: '#db2777',
            pale: '#fbcfe8',
            paleSolid: '#fce7f3',
            border: '#be185d',
            text: '#9d174d'
        }
    ];

    const getWaterfillPalette = (index) => {
        return WATERFILL_PALETTES[index % WATERFILL_PALETTES.length];
    };

    /*
    |--------------------------------------------------------------------------
    | SINGLE NESTED DONUT CHART: EXECUTIVE TARGET MONITORING
    |--------------------------------------------------------------------------
    */
    const targetMonitoringList = @json($targetMonitoring ?? []);
    const pieCanvas = document.getElementById('leaderUnifiedPieChart');
    const deptFilterSelect = document.getElementById('leaderExecutiveDeptFilter');
    const divisionFilterSelect = document.getElementById('leaderExecutiveDivisionFilter');
    const quadrantContainer = document.getElementById('leaderQuadrantCardList');
    const divisionScopeBadge = document.getElementById('leaderDivisionScopeBadge');
    const subTargetListContainer = document.getElementById('leaderPieSubTargetList');
    const subTargetCountBadge = document.getElementById('leaderPieSubTargetCount');

    const aggregatePercentEl = document.getElementById('leaderAggregatePercent');
    const aggregateBadgeEl = document.getElementById('leaderAggregateBadge');

    let leaderPieChartInstance = null;

    // Palet 10 warna terintegrasi untuk sub target (5 awal + 5 tambahan baru)
    const leaderPaletteColors = [
        '#73605b', '#7B2CBF', '#008080', '#dda288', '#B3DBC0',
        '#EAB308', '#1D4ED8', '#65A30D', '#BE185D', '#475569'
    ];
    const leaderPaletteColorNames = [
        'Cokelat Keabu-abuan', 'Ungu', 'Teal', 'Cokelat Kemerahan', 'Mint',
        'Kuning Emas', 'Biru', 'Hijau Limau', 'Pink Magenta', 'Slate'
    ];

    const leaderThemeColorMap = {
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

    // Plugin Chart.js: Gambar garis penunjuk radial dan badge 'Progress: X%' pada Cincin Luar
    const singleNestedDonutLabelsPlugin = {
        id: 'singleNestedDonutLabels',
        afterDraw(chart) {
            const { ctx } = chart;
            const metaInner = chart.getDatasetMeta(1);
            const metaOuter = chart.getDatasetMeta(0);
            if (!metaInner || !metaInner.data || metaInner.data.length === 0) return;
            if (!metaOuter || !metaOuter.data) return;

            const pluginOpts = chart.config.options?.plugins?.nestedDonutLabels;
            const progressValues = pluginOpts?.progressValues || [];
            const colors = pluginOpts?.colors || leaderPaletteColors;
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

                const sliceColor = colors[i] || leaderPaletteColors[i % leaderPaletteColors.length];

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
                    color: leaderPaletteColors[idx],
                    colorName: leaderPaletteColorNames[idx]
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
                    color: leaderPaletteColors[idx],
                    colorName: leaderPaletteColorNames[idx]
                });
            }
        }

        return result;
    }

    function renderLeaderExecutiveGauge() {
        const selectedDeptId = deptFilterSelect ? deptFilterSelect.value : 'all';
        const selectedDivId = divisionFilterSelect ? divisionFilterSelect.value : 'all';

        // Filter target list berdasarkan departemen & divisi
        let filteredTargets = targetMonitoringList;
        if (selectedDeptId && selectedDeptId !== 'all') {
            filteredTargets = filteredTargets.filter(t => String(t.department_id) === String(selectedDeptId));
        }
        if (selectedDivId && selectedDivId !== 'all') {
            filteredTargets = filteredTargets.filter(t => String(t.division_id) === String(selectedDivId));
        }

        // Kumpulkan semua sub-target dari target yang telah difilter
        let subTargetItems = [];
        filteredTargets.forEach(target => {
            (target.sub_targets || []).forEach(st => {
                const percentage = Math.max(0, Number(st.percentage) || 0);
                const progress = Math.max(0, Math.min(100, Number(st.average_progress ?? st.progress) || 0));
                const contribution = (percentage * progress) / 100;

                subTargetItems.push({
                    sub_target_id: st.sub_target_id,
                    sub_target_name: st.sub_target_name,
                    target_id: target.target_id,
                    target_name: target.target_name,
                    department_id: target.department_id,
                    department_name: target.department_name || 'Departemen Umum',
                    division_id: target.division_id,
                    division_name: target.division_name || 'Divisi Umum',
                    percentage: percentage,
                    progress: progress,
                    contribution: contribution
                });
            });
        });

        // Update badge scope divisi
        if (divisionScopeBadge) {
            if (selectedDivId !== 'all') {
                const selOption = divisionFilterSelect?.options[divisionFilterSelect.selectedIndex];
                divisionScopeBadge.textContent = `Divisi: ${selOption?.text || '-'}`;
            } else if (selectedDeptId !== 'all') {
                const selOption = deptFilterSelect?.options[deptFilterSelect.selectedIndex];
                divisionScopeBadge.textContent = `Dept: ${selOption?.text || '-'}`;
            } else {
                divisionScopeBadge.textContent = 'Semua Divisi Perusahaan';
            }
        }

        if (subTargetCountBadge) {
            subTargetCountBadge.textContent = `${subTargetItems.length} Sub Target`;
        }

        // 1. HITUNG 5 IRISAN (CINCIN DALAM & CINCIN LUAR)
        const quadrants = calculateFourQuadrants(subTargetItems);

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

        // Total Progress Agregat: rata-rata capaian bobot irisan
        const totalAggregateProgress = subTargetItems.length > 0
            ? Number((quadrants.reduce((sum, q) => sum + (q.progress * (q.weight / 100)), 0)).toFixed(1))
            : 0;

        // Update Center Hole DOM
        if (aggregatePercentEl) {
            aggregatePercentEl.textContent = `${parseFloat(Number(totalAggregateProgress).toFixed(1))}%`;
        }
        if (aggregateBadgeEl) {
            let badgeClass = 'bg-blue-100 text-blue-800 border-blue-200';
            let badgeText = '● IN PROGRESS';
            if (totalAggregateProgress >= 75) {
                badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                badgeText = '● ON TRACK';
            } else if (totalAggregateProgress < 30) {
                badgeClass = 'bg-amber-100 text-amber-800 border-amber-200';
                badgeText = '● NEED PUSH';
            }
            aggregateBadgeEl.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border shadow-xs ${badgeClass}`;
            aggregateBadgeEl.textContent = badgeText;
        }

        // Render Single Nested Donut Chart (Chart.js)
        if (pieCanvas) {
            if (leaderPieChartInstance) {
                leaderPieChartInstance.destroy();
                leaderPieChartInstance = null;
            }

            leaderPieChartInstance = new Chart(pieCanvas, {
                type: 'doughnut',
                data: {
                    datasets: [
                        // DATASET 0: CINCIN LUAR (PROGRESS AKTUAL)
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
                                // Jangan tampilkan tooltip jika mengarahkan kursor ke area putih kosong
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
                                        return ` ${q.name} (${q.colorName}): Progress ${q.progress}% (Bobot ${q.weight}%)`;
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
                plugins: [singleNestedDonutLabelsPlugin]
            });
        }

        // 2. RENDER KARTU IRISAN DENGAN FORMAT 'Progress: X%'
        if (quadrantContainer) {
            quadrantContainer.innerHTML = '';
            quadrants.forEach((q, idx) => {
                const theme = leaderThemeColorMap[q.color] || leaderThemeColorMap['#73605b'];

                const cardEl = document.createElement('div');
                cardEl.className = 'p-3.5 rounded-xl border shadow-2xs transition-all hover:shadow-xs';
                cardEl.style.cssText = `${theme.borderStyle} ${theme.bgStyle}`;
                cardEl.innerHTML = `
                    <div class="flex items-center justify-between text-xs mb-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-3 h-3 rounded-full shrink-0" style="background-color: ${q.color}"></span>
                            <span class="font-bold text-gray-900 truncate">${q.name}: ${q.title}</span>
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
                quadrantContainer.appendChild(cardEl);
            });
        }

        // 3. DETAIL EKSEKUSI SUB-TARGET (COMPACT STATUS CARDS 3-KOLOM)
        if (subTargetListContainer) {
            subTargetListContainer.innerHTML = '';

            if (subTargetItems.length === 0) {
                subTargetListContainer.innerHTML = '<p class="text-xs text-gray-400 col-span-3 py-8 text-center">Belum ada sub target untuk filter ini.</p>';
            } else {
                subTargetItems.forEach(item => {
                    const isDone = item.progress >= 100;
                    const isZero = item.progress <= 0;

                    let statusClass = 'border-blue-200 bg-white hover:bg-blue-50/40';
                    let badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                    let badgeText = 'Berjalan';
                    let barColor = 'bg-blue-500';

                    if (isDone) {
                        statusClass = 'border-emerald-200 bg-emerald-50/20 hover:bg-emerald-50/40';
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
                    card.className = `rounded-2xl border p-3.5 transition shadow-xs hover:shadow flex flex-col justify-between ${statusClass}`;
                    card.title = item.sub_target_name;
                    card.innerHTML = `
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border ${badgeClass}">
                                    ${badgeText}
                                </span>
                                <span class="text-xs font-bold text-gray-700 font-mono">
                                    Bobot ${item.percentage}%
                                </span>
                            </div>

                            <h4 class="text-xs font-bold text-gray-900 mt-2 truncate" title="${item.sub_target_name}">
                                ${item.sub_target_name}
                            </h4>
                            <div class="flex items-center gap-1.5 text-[11px] text-gray-500 mt-1 truncate">
                                <span>${item.division_name}</span>
                                <span>·</span>
                                <span class="text-blue-600 font-medium">${item.department_name}</span>
                            </div>
                        </div>

                        <div class="mt-3 pt-2 border-t border-gray-100">
                            <div class="flex items-center justify-between text-[11px] font-semibold mb-1">
                                <span class="text-gray-500">Progres Aktual</span>
                                <span class="text-gray-900 font-mono">${formattedSubProgress}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden flex">
                                <div class="h-full rounded-full transition-all duration-300 ${barColor}" style="width: ${item.progress}%"></div>
                            </div>
                        </div>
                    `;
                    subTargetListContainer.appendChild(card);
                });
            }
        }
    }

    // Event listener filter departemen: sinkronisasi dropdown divisi
    deptFilterSelect?.addEventListener('change', function () {
        const deptId = this.value;
        if (divisionFilterSelect) {
            Array.from(divisionFilterSelect.options).forEach(opt => {
                if (opt.value === 'all') {
                    opt.style.display = '';
                } else {
                    const itemDept = opt.dataset.deptId;
                    opt.style.display = (deptId === 'all' || !itemDept || String(itemDept) === String(deptId)) ? '' : 'none';
                }
            });
            divisionFilterSelect.value = 'all';
        }
        renderLeaderExecutiveGauge();
    });

    divisionFilterSelect?.addEventListener('change', function () {
        renderLeaderExecutiveGauge();
    });

    renderLeaderExecutiveGauge();

    /*
    |--------------------------------------------------------------------------
    | ELEMEN DOM BARCHART & LINE CHART
    |--------------------------------------------------------------------------
    */
    const barCanvas = document.getElementById('targetTaskBarChart');
    const weeklyLineCanvas = document.getElementById('taskWeeklyBarChart');
    const barWrapper = document.getElementById('barChartWrapper');
    const barInner = document.getElementById('barChartInner');
    const weeklyBarWrapper = document.getElementById('weeklyBarChartWrapper');
    const weeklySummaryGrid = document.getElementById('leaderWeeklySummaryGrid');
    const emptyState = document.getElementById('chartEmptyState');
    const chartBarHint = document.getElementById('chartBarHint');
    const drilldownHeader = document.getElementById('chartDrilldownHeader');
    const btnBackToMonthly = document.getElementById('btnBackToMonthly');
    const drilldownTitle = document.getElementById('drilldownTaskTitle');
    const drilldownSubtitle = document.getElementById('drilldownTaskSubtitle');
    const taskCatalogTbody = document.getElementById('leaderTaskCatalogTbody') || document.getElementById('leaderTaskCatalogGrid');
    const taskCatalogGrid = taskCatalogTbody;
    const taskCatalogCount = document.getElementById('leaderTaskCatalogCount');
    const taskPaginationWrapper = document.getElementById('leaderTaskPaginationWrapper');
    const taskPaginationInfo = document.getElementById('leaderTaskPaginationInfo');
    const taskPaginationButtons = document.getElementById('leaderTaskPaginationButtons');

    // Filter Elements (Sistem BRImo)
    const divisionFilter = document.getElementById('division_id');
    const employeeFilter = document.getElementById('employee_id');
    const startInput = document.getElementById('leader_start_date');
    const endInput = document.getElementById('leader_end_date');
    const presetInput = document.getElementById('leader_filter_preset');
    const presetButtons = document.querySelectorAll('.leader-filter-preset-pill');

    const monthPickerBtn = document.getElementById('btnLeaderMonthPickerDropdown');
    const monthPickerPopover = document.getElementById('leaderMonthPickerPopover');
    const monthPickerYear = document.getElementById('leaderMonthPickerYear');
    const monthPickerItems = document.querySelectorAll('.leader-month-picker-item');
    const filterMonthInput = document.getElementById('leader_filter_month');
    const filterYearInput = document.getElementById('leader_filter_year');
    const monthPickerLabel = document.getElementById('leaderMonthPickerBtnLabel');

    if (!barCanvas || !weeklyLineCanvas) return;

    // Data dari Controller
    const monthlyTasks = @json($monthlyChartTasks ?? []);
    const chartEmployees = @json($chartEmployees ?? []);

    const monthNamesMap = {
        1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April',
        5: 'Mei', 6: 'Juni', 7: 'Juli', 8: 'Agustus',
        9: 'September', 10: 'Oktober', 11: 'November', 12: 'Desember'
    };

    let barChartInstance = null;
    let weeklyLineChartInstance = null;
    let currentRenderedBarItems = [];

    const formatDate = function (d) {
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const updatePresetPillStyles = function (activePreset) {
        presetButtons.forEach(btn => {
            const isMatch = btn.dataset.preset === activePreset;
            if (isMatch) {
                btn.className = 'leader-filter-preset-pill inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer bg-blue-600 text-white border border-blue-600 shadow-xs font-semibold';
            } else {
                btn.className = 'leader-filter-preset-pill inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs transition-colors cursor-pointer bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-medium';
            }
        });
        if (activePreset !== 'month_picker' && monthPickerLabel) {
            monthPickerLabel.textContent = 'Pilih Bulan';
        }
    };

    const applyPreset = function (type) {
        const today = new Date();
        let sDate, eDate;

        if (type === '7_days') {
            sDate = new Date();
            sDate.setDate(today.getDate() - 6);
            eDate = today;
        } else if (type === '30_days') {
            sDate = new Date();
            sDate.setDate(today.getDate() - 29);
            eDate = today;
        } else if (type === 'last_month') {
            sDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            eDate = new Date(today.getFullYear(), today.getMonth(), 0);
        } else if (type === 'this_year') {
            sDate = new Date(today.getFullYear(), 0, 1);
            eDate = new Date(today.getFullYear(), 11, 31);
        } else { // this_month
            sDate = new Date(today.getFullYear(), today.getMonth(), 1);
            eDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        }

        if (startInput) startInput.value = formatDate(sDate);
        if (endInput) {
            endInput.value = formatDate(eDate);
            endInput.min = startInput ? startInput.value : '';
        }
        if (presetInput) presetInput.value = type;
        updatePresetPillStyles(type);
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
            if (monthPickerPopover) {
                monthPickerPopover.classList.add('hidden');
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

                if (startInput) startInput.value = formatDate(start);
                if (endInput) {
                    endInput.value = formatDate(end);
                    endInput.min = startInput ? startInput.value : '';
                }

                if (presetInput) presetInput.value = 'month_picker';
                if (filterMonthInput) filterMonthInput.value = mNum;
                if (filterYearInput) filterYearInput.value = yr;

                if (monthPickerLabel) {
                    monthPickerLabel.textContent = 'Bulan: ' + (monthNamesMap[mNum] || mNum) + ' ' + yr;
                }

                monthPickerItems.forEach(mi => {
                    if (parseInt(mi.dataset.month) === mNum) {
                        mi.className = 'leader-month-picker-item py-2 px-2 text-center rounded-xl text-xs font-bold transition cursor-pointer bg-blue-600 text-white shadow-xs';
                    } else {
                        mi.className = 'leader-month-picker-item py-2 px-2 text-center rounded-xl text-xs font-medium transition cursor-pointer bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700';
                    }
                });

                updatePresetPillStyles('month_picker');
                monthPickerPopover.classList.add('hidden');
            });
        });
    }

    if (startInput) {
        startInput.addEventListener('change', function () {
            if (endInput) {
                endInput.min = this.value;
                if (endInput.value && endInput.value < this.value) {
                    endInput.value = this.value;
                }
            }
            if (presetInput) presetInput.value = 'custom';
            updatePresetPillStyles('custom');
        });
    }

    if (endInput) {
        endInput.addEventListener('change', function () {
            if (startInput && startInput.value && this.value < startInput.value) {
                startInput.value = this.value;
            }
            if (presetInput) presetInput.value = 'custom';
            updatePresetPillStyles('custom');
        });
    }

    function updateEmployeeOptions() {
        if (!employeeFilter) return;
        const selectedDivision = divisionFilter ? divisionFilter.value : '';
        const selectedEmployee = employeeFilter ? employeeFilter.value : '';
        const eligibleEmployees = chartEmployees.filter(emp =>
            !selectedDivision || String(emp.division_id) === selectedDivision
        );

        employeeFilter.replaceChildren(new Option('Semua Karyawan', ''));
        eligibleEmployees.forEach(emp => {
            employeeFilter.add(new Option(emp.name, emp.id));
        });

        employeeFilter.value = eligibleEmployees.some(emp => String(emp.id) === selectedEmployee)
            ? selectedEmployee
            : '';
    }

    divisionFilter?.addEventListener('change', function () {
        updateEmployeeOptions();
        updateChart();
    });

    employeeFilter?.addEventListener('change', function () {
        updateChart();
    });

    /*
    |--------------------------------------------------------------------------
    | RENDER KETERANGAN TASK DENGAN PAGINASI (10 TASK PER HALAMAN)
    |--------------------------------------------------------------------------
    */
    const TASKS_PER_PAGE = 10;
    let currentCatalogTasks = [];
    let currentCatalogPage = 1;

    function renderTaskCatalog(items) {
        if (!taskCatalogGrid) return;

        // Dapatkan task unik berdasarkan task_id
        const uniqueTasksMap = new Map();
        items.forEach(it => {
            if (!uniqueTasksMap.has(it.task_id)) {
                uniqueTasksMap.set(it.task_id, it);
            }
        });

        currentCatalogTasks = Array.from(uniqueTasksMap.values());
        currentCatalogPage = 1;
        renderCatalogPage(1);
    }

    function renderCatalogPage(page) {
        if (!taskCatalogTbody) return;
        taskCatalogTbody.innerHTML = '';

        const totalTasks = currentCatalogTasks.length;
        if (taskCatalogCount) {
            taskCatalogCount.textContent = totalTasks + ' Task Tampil';
        }

        const paginationWrapper = document.getElementById('leaderTaskPaginationWrapper');
        const paginationInfo = document.getElementById('leaderTaskPaginationInfo');
        const paginationButtons = document.getElementById('leaderTaskPaginationButtons');

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
            tr.className = 'leader-task-card group hover:bg-gray-50/80 transition cursor-pointer border-b border-gray-100 last:border-b-0';
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

            const subTargetTitle = task.sub_target_name && task.sub_target_name !== '-' ? task.sub_target_name : (task.scope_name && task.scope_name !== '-' ? task.scope_name : (task.task_name || '-'));
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
                        target : ${targetTitle}
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
                const bIndex = currentRenderedBarItems.findIndex(it => (cId && String(it.task_id) === cId) || (cNum && String(it.task_number) === cNum));
                if (bIndex !== -1 && barChartInstance) {
                    barChartInstance.setActiveElements([{ datasetIndex: 0, index: bIndex }]);
                    const meta = barChartInstance.getDatasetMeta(0);
                    if (meta && meta.data && meta.data[bIndex]) {
                        barChartInstance.tooltip.setActiveElements([{ datasetIndex: 0, index: bIndex }], {
                            x: meta.data[bIndex].x,
                            y: meta.data[bIndex].y
                        });
                    }
                    barChartInstance.update();
                }
            });
            tr.addEventListener('mouseleave', function () {
                if (barChartInstance) {
                    barChartInstance.setActiveElements([]);
                    barChartInstance.tooltip.setActiveElements([], {});
                    barChartInstance.update();
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
                        btn.onclick = () => renderCatalogPage(p);
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
                prevBtn.onclick = () => renderCatalogPage(currentCatalogPage - 1);
                paginationButtons.appendChild(prevBtn);

                // Tombol Next
                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.disabled = currentCatalogPage === totalPages;
                nextBtn.className = 'inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer ml-1';
                nextBtn.innerHTML = 'Next <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
                nextBtn.onclick = () => renderCatalogPage(currentCatalogPage + 1);
                paginationButtons.appendChild(nextBtn);
            }
        }
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
    | BARCHART PLANNING VS AKTUAL PER TASK
    |--------------------------------------------------------------------------
    */
    function renderBarChart(items) {
        // Gabungkan data berdasarkan task agar setiap task hanya punya satu batang progres terbaru
        const taskItemsMap = new Map();
        items.forEach(it => {
            const key = it.task_id || `${it.task_number}-${it.task_name}`;
            taskItemsMap.set(key, it);
        });
        const taskItems = Array.from(taskItemsMap.values());

        currentRenderedBarItems = taskItems;

        if (barChartInstance) {
            barChartInstance.destroy();
            barChartInstance = null;
        }

        renderTaskCatalog(taskItems);

        if (taskItems.length === 0) {
            emptyState.classList.remove('hidden');
            barWrapper.classList.add('hidden');
            chartBarHint.classList.add('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        barWrapper.classList.remove('hidden');
        chartBarHint.classList.remove('hidden');

        // Sesuaikan lebar scroll bila data banyak
        const minWidth = Math.max(taskItems.length * 85, barWrapper.clientWidth || 600);
        barInner.style.minWidth = minWidth + 'px';

        const labels = taskItems.map(it => 'Task ' + it.task_number);
        const planningData = taskItems.map(it => it.planning);
        const actualData = taskItems.map(it => it.actual);

        document.getElementById('leaderTaskChartPopup')?.remove();

        function highlightLeaderTaskCard(item) {
            if (!item) {
                document.querySelectorAll('.leader-task-card').forEach(c => c.classList.remove('task-card-glowing'));
                return;
            }
            const taskNum = String(item.task_number || '');
            const taskId = String(item.task_id || '');

            const matchingIndex = currentCatalogTasks.findIndex(t => (taskId && String(t.task_id) === taskId) || (taskNum && String(t.task_number) === taskNum));
            if (matchingIndex !== -1) {
                const targetPage = Math.floor(matchingIndex / TASKS_PER_PAGE) + 1;
                if (targetPage !== currentCatalogPage) {
                    renderCatalogPage(targetPage);
                }
                document.querySelectorAll('.leader-task-card').forEach(c => {
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
                document.querySelectorAll('.leader-task-card').forEach(c => c.classList.remove('task-card-glowing'));
            }
        }

        barChartInstance = new Chart(barCanvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Planning',
                        data: planningData,
                        backgroundColor: '#3b82f6',
                        borderColor: '#1e40af',
                        borderWidth: 1,
                        barPercentage: 1.0,
                        categoryPercentage: 0.65
                    },
                    {
                        label: 'Aktual',
                        data: actualData,
                        backgroundColor: '#10b981', // Hijau
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
                        const clickedItem = currentRenderedBarItems[index];
                        if (clickedItem) {
                            showWeeklyLineChart(clickedItem);
                        }
                    }
                },
                onHover: function (evt, activeElements) {
                    barCanvas.style.cursor = (activeElements && activeElements.length > 0) ? 'pointer' : 'default';
                    if (activeElements && activeElements.length > 0) {
                        const index = activeElements[0].index;
                        const item = currentRenderedBarItems[index];
                        highlightLeaderTaskCard(item);
                    } else {
                        highlightLeaderTaskCard(null);
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0,
                            font: { size: 11, weight: '500' },
                            color: '#4b5563'
                        },
                        grid: { display: false }
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
                    legend: { display: false },
                    tooltip: {
                        enabled: false
                    }
                }
            },
            plugins: [cube3dBarPlugin]
        });

        // Hilangkan glow saat kursor keluar kanvas
        barCanvas?.addEventListener('mouseleave', function () {
            document.querySelectorAll('.leader-task-card').forEach(c => c.classList.remove('task-card-glowing'));
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DIAGRAM GARIS RINCIAN (DRILL-DOWN LINE CHART)
    |--------------------------------------------------------------------------
    */
    function showWeeklyLineChart(item) {
        barWrapper.classList.add('hidden');
        chartBarHint.classList.add('hidden');
        drilldownHeader.classList.remove('hidden');
        weeklyBarWrapper.classList.remove('hidden');

        drilldownTitle.textContent = 'Task ' + item.task_number + ' · ' + item.task_name;
        drilldownSubtitle.textContent = 'Target: ' + item.target_name + ' | Divisi: ' + (item.division_name || '-') + ' | Pelaksana: ' + (item.employee_name || '-') + ' | Periode: ' + (item.period_full || item.period_name || item.month_name);

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

        if (weeklyLineChartInstance) {
            weeklyLineChartInstance.destroy();
            weeklyLineChartInstance = null;
        }

        weeklyLineChartInstance = new Chart(weeklyLineCanvas, {
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
    }

    btnBackToMonthly?.addEventListener('click', function () {
        drilldownHeader.classList.add('hidden');
        weeklyBarWrapper.classList.add('hidden');
        barWrapper.classList.remove('hidden');
        chartBarHint.classList.remove('hidden');
        if (weeklyLineChartInstance) {
            weeklyLineChartInstance.destroy();
            weeklyLineChartInstance = null;
        }
        if (barChartInstance) {
            barChartInstance.resize();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | UPDATE DATA & RENDER
    |--------------------------------------------------------------------------
    */
    function updateChart() {
        const selectedDivision = divisionFilter ? divisionFilter.value : '';
        const selectedEmployee = employeeFilter ? employeeFilter.value : '';

        // Kembalikan ke tampilan utama jika sedang drill-down
        drilldownHeader.classList.add('hidden');
        weeklyBarWrapper.classList.add('hidden');

        // Filter item chart berdasarkan divisi & karyawan
        const filteredItems = (monthlyTasks || []).filter(item => {
            if (selectedDivision && String(item.division_id) !== selectedDivision) return false;
            if (selectedEmployee && String(item.employee_id) !== selectedEmployee) return false;
            return true;
        });

        renderBarChart(filteredItems);
    }

    // Inisialisasi awal
    updateEmployeeOptions();
    updateChart();

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
            const response = await fetch(@json(route('pimpinan.dashboard.summarize')), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    start_date: startInput?.value || null,
                    end_date: endInput?.value || null,
                    preset: presetInput?.value || '30_days',
                    division_id: divisionFilter?.value || null,
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
