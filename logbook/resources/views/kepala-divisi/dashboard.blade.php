<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Kepala Divisi
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                {{ $department->name }} — {{ $division->name }}
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm p-6 mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Selamat datang, {{ $user->name }}
                    </h1>

                    <p class="text-gray-500 mt-2">
                        Kelola pekerjaan dan pantau perkembangan karyawan
                        pada divisi {{ $division->name }}.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a
                        href="{{ route('kepala_divisi.my_logbooks.create') }}"
                        class="inline-flex items-center gap-2 bg-[#054439] hover:bg-[#032e26] text-white px-4 py-2.5 rounded-xl font-semibold text-sm shadow-sm transition"
                    >
                        <span class="material-symbols-rounded" style="font-size:20px;">edit_note</span>
                        <span>Isi Logbook</span>
                    </a>

                    <a
                        href="{{ route('kepala_divisi.tasks.create') }}"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm shadow-sm transition"
                    >
                        <span class="material-symbols-rounded" style="font-size:20px;">add_task</span>
                        <span>Tugaskan Pekerjaan</span>
                    </a>
                </div>
            </div>

            {{-- Statistik interaktif --}}
            <div x-data="{ active: null }" class="space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">

                    {{-- Karyawan --}}
                    <button type="button"
                        @click="active = active === 'employees' ? null : 'employees'"
                        class="text-left bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition"
                        :class="active === 'employees' ? 'ring-2 ring-blue-500' : ''">
                        <p class="text-sm text-gray-500">Karyawan</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalEmployees }}</p>
                        <p class="text-xs text-gray-400 mt-1">Dalam divisi</p>
                    </button>

            {{-- Total Pekerjaan --}}
            <a href="{{ route('kepala_divisi.tasks.index') }}"
                class="block text-left bg-white rounded-xl shadow-sm p-6
                    hover:shadow-md hover:border-blue-300 border border-transparent
                    transition">

                <p class="text-sm text-gray-500">Total Pekerjaan</p>

                <p class="text-3xl font-bold text-gray-800 mt-2">
                    {{ $totalTasks }}
                </p>

            </a>

                    {{-- Ditugaskan --}}
                    <button type="button"
                        @click="active = active === 'assigned' ? null : 'assigned'"
                        class="text-left bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition"
                        :class="active === 'assigned' ? 'ring-2 ring-blue-500' : ''">
                        <p class="text-sm text-gray-500">Ditugaskan</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $assignedTasks }}</p>
                        <p class="text-xs text-gray-400 mt-1">Menunggu dikerjakan</p>
                    </button>

                    {{-- Sedang Berjalan --}}
                    <button type="button"
                        @click="active = active === 'inProgress' ? null : 'inProgress'"
                        class="text-left bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition"
                        :class="active === 'inProgress' ? 'ring-2 ring-yellow-500' : ''">
                        <p class="text-sm text-gray-500">Dalam Pengerjaan</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $inProgressTasks }}</p>
                        <p class="text-xs text-gray-400 mt-1">Dalam proses</p>
                    </button>

                    {{-- Revisi --}}
                    <button type="button"
                        @click="active = active === 'revision' ? null : 'revision'"
                        class="text-left bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition"
                        :class="active === 'revision' ? 'ring-2 ring-orange-500' : ''">
                        <p class="text-sm text-gray-500">Revisi</p>
                        <p class="text-3xl font-bold text-orange-600 mt-2">{{ $revisionTasks }}</p>
                        <p class="text-xs text-gray-400 mt-1">Perlu diperiksa</p>
                    </button>

                </div>

        {{-- Detail Karyawan --}}
        <div x-cloak x-show="active === 'employees'"
            class="bg-white rounded-xl shadow-sm p-6">

            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Daftar Karyawan Divisi
            </h3>

            @forelse ($employees as $employee)
                <div class="flex items-center gap-3 border-b border-gray-100 py-3 last:border-0">

                    {{-- Nomor dengan lingkaran hijau --}}
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-green-100 text-green-700 font-semibold text-sm shrink-0">
                        {{ $loop->iteration }}
                    </div>

                    {{-- Informasi karyawan --}}
                    <div class="min-w-0">
                        <p class="font-medium text-gray-800">
                            {{ $employee->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $employee->email }}
                        </p>
                    </div>

                </div>
            @empty
                <p class="text-sm text-gray-500">
                    Belum ada karyawan dalam divisi ini.
                </p>
            @endforelse

        </div>

                {{-- Detail Ditugaskan --}}
                <div x-cloak x-show="active === 'assigned'"
                    class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pekerjaan Ditugaskan</h3>

                    @forelse ($assignedTaskList as $task)
                        <div class="border-b border-gray-100 py-3 last:border-0">
                            <p class="font-medium text-gray-800">{{ $task->title }}</p>
                            <p class="text-sm text-gray-500">
                                Karyawan: {{ $task->assignedTo->name ?? 'Belum ditentukan' }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Tidak ada pekerjaan yang menunggu dikerjakan.</p>
                    @endforelse
                </div>

                {{-- Detail Sedang Berjalan --}}
                <div x-cloak x-show="active === 'inProgress'"
                    class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pekerjaan Dalam Pengerjaan</h3>

                    @forelse ($inProgressTaskList as $task)
                        <div class="border-b border-gray-100 py-3 last:border-0">
                            <p class="font-medium text-gray-800">{{ $task->title }}</p>
                            <p class="text-sm text-gray-500">
                                Karyawan: {{ $task->assignedTo->name ?? 'Belum ditentukan' }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Tidak ada pekerjaan yang sedang berjalan.</p>
                    @endforelse
                </div>

                {{-- Detail Revisi --}}
                <div x-cloak x-show="active === 'revision'"
                    class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pekerjaan Revisi</h3>

                    @forelse ($revisionTaskList as $task)
                        <div class="border-b border-gray-100 py-3 last:border-0">
                            <p class="font-medium text-gray-800">{{ $task->title }}</p>
                            <p class="text-sm text-gray-500">
                                Karyawan: {{ $task->assignedTo->name ?? 'Belum ditentukan' }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Tidak ada pekerjaan yang perlu direvisi.</p>
                    @endforelse
                </div>

            </div>



            <div class="bg-white rounded-xl shadow-sm p-6 mt-6">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Target Departemen
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Target yang menjadi acuan dalam penyusunan pekerjaan
                        pada divisi {{ $division->name }}.
                    </p>
                </div>

            </div>

            <div class="mt-5 space-y-4">

                @forelse ($targets as $target)

                    <div class="border border-gray-200 rounded-xl p-4">

                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0">

                                <h3 class="text-sm font-semibold text-gray-800">
                                    {{ $target->title }}
                                </h3>

                                @if ($target->description)

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $target->description }}
                                    </p>

                                @endif

                            </div>

                            <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Aktif
                            </span>

                        </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">


        <div class="bg-gray-50 rounded-lg px-4 py-3">

            <div class="flex items-center justify-between gap-3">
                <p class="text-xs text-gray-500">
                    Target Pencapaian
                </p>

                <p class="text-sm font-bold text-gray-800">
                    {{ $target->calculated_progress ?? 0 }}%
                </p>
            </div>

            <div
                class="w-full bg-gray-200 rounded-full h-2.5 mt-3 overflow-hidden"
                role="progressbar"
                aria-valuenow="{{ $target->calculated_progress ?? 0 }}"
                aria-valuemin="0"
                aria-valuemax="100"
                aria-label="Progres Target"
            >
                <div
                    class="bg-blue-600 h-2.5 rounded-full transition-all duration-500"
                    style="width: {{ $target->calculated_progress ?? 0 }}%"
                ></div>
            </div>

        </div>

                <div class="bg-gray-50 rounded-lg px-4 py-3">

                    <p class="text-xs text-gray-400">
                        Periode
                    </p>

                    <p class="text-sm font-semibold text-gray-800 mt-1">

                        @if ($target->period_start || $target->period_end)

                            {{ $target->period_start?->format('d M Y') ?? '-' }}
                            —
                            {{ $target->period_end?->format('d M Y') ?? '-' }}

                        @else

                            Tidak ditentukan

                        @endif

                    </p>

                </div>

            </div>

            {{-- Waktu Target dikirim --}}
            <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 text-gray-400 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"
                    />
                </svg>

                <span>
                    Dikirim pada
                    <span class="font-medium text-gray-600">
                        {{ $target->created_at?->timezone('Asia/Jakarta')->format('d M Y, H:i') ?? '-' }}
                    </span>
                    WIB
                </span>

            </div>
                    </div>

                @empty

                    <div class="py-8 text-center">

                        <p class="text-sm text-gray-500">
                            Belum ada Target aktif dari departemen.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

        {{-- =====================================================
             FILTER MONITORING DIVISI
        ====================================================== --}}
        <div class="mt-8 space-y-6">

            <form
                method="GET"
                action="{{ route('kepala_divisi.dashboard') }}"
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Filter Monitoring</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Pilih rentang waktu dan karyawan dalam divisi {{ $division->name }}.
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100 self-start sm:self-auto">
                        Mode Grafik: {{ $groupingLabel }}
                    </div>
                </div>

                @if (isset($errors) && $errors->any())
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

                    {{-- DIVISI HANYA BISA MEMANTAU DIVISI SENDIRI --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Divisi (Terkunci)
                        </label>
                        <div class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-sm font-semibold text-gray-700 flex items-center justify-between">
                            <span class="truncate">{{ $division->name }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 shrink-0">
                                Divisi Anda
                            </span>
                        </div>
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
                            <option value="">Semua Karyawan ({{ count($chartEmployees) }})</option>
                            @foreach ($chartEmployees as $chartEmployee)
                                <option
                                    value="{{ $chartEmployee->id }}"
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

                    $activeFilterBadges[] = 'Divisi: ' . $division->name;
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
                            href="{{ route('kepala_divisi.dashboard') }}"
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
            <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Progress Target dan Task</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Grafik batang Planning vs Aktual per task. Klik batang untuk melihat rincian progres dalam <strong class="text-blue-600">diagram garis (line chart)</strong>. Filter data khusus untuk divisi <span class="font-medium text-gray-700">{{ $division->name }}</span>.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100 self-start md:self-auto">
                        Lingkup Divisi: {{ $division->name }}
                    </span>
                </div>

                @if ($startDate && $endDate && $startDate->lte($endDate))
                    <div class="w-full min-w-0">
                        {{-- LEGEND BARCHART --}}
                        <div id="divisionBarLegend" class="flex flex-wrap items-center justify-between gap-3 mb-4 p-3 bg-gray-50/80 rounded-xl border border-gray-100 text-xs">
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
                        <div id="divisionDrilldownHeader" class="hidden flex flex-wrap items-center justify-between gap-3 p-4 mb-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl shadow-sm">
                            <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    id="divisionChartBack"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                    Kembali
                                </button>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span id="divisionDrilldownBadge" class="px-2 py-0.5 rounded-md bg-blue-600 text-white text-[11px] font-bold">Diagram Garis (Histori Progres)</span>
                                        <h3 id="divisionDrilldownTitle" class="text-sm font-bold text-blue-950"></h3>
                                    </div>
                                    <p id="divisionDrilldownSubtitle" class="text-xs text-blue-700 mt-1"></p>
                                </div>
                            </div>
                        </div>

                        {{-- DIAGRAM BATANG BULANAN --}}
                        <div id="divisionBarChartWrapper" class="w-full overflow-x-auto">
                            <div id="divisionBarChartInner" class="relative h-[440px] min-w-full">
                                <canvas id="divisionTaskBarChart"></canvas>
                            </div>
                        </div>

                        {{-- DIAGRAM GARIS HISTORI PROGRES (DRILLDOWN LINE CHART) --}}
                        <div id="divisionWeeklyLineWrapper" class="hidden">
                            <div class="relative h-[420px] w-full">
                                <canvas id="divisionTaskWeeklyLineChart"></canvas>
                            </div>
                            <div id="divisionWeeklySummaryGrid" class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mt-4 pt-4 border-t border-gray-100"></div>
                        </div>

                        <p id="divisionChartEmpty" class="hidden py-12 text-center text-sm text-gray-500">
                            Belum ada task untuk filter yang dipilih.
                        </p>

                        {{-- KETERANGAN TASK RAPI & JELAS DENGAN BENTUK TABEL & PAGINATION (10 TASK PER HALAMAN) --}}
                        <div id="divTaskCatalogWrapper" class="mt-6 border-t border-gray-100 pt-5">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Keterangan Task
                                </h3>
                                <span id="divTaskCatalogCount" class="text-xs text-gray-400"></span>
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
                                    <tbody id="divTaskCatalogTbody" class="divide-y divide-gray-100">
                                        {{-- Diisi secara dinamis via JavaScript sesuai task yang tampil di chart --}}
                                    </tbody>
                                </table>
                            </div>

                            {{-- PAGINATION KETERANGAN TASK (10 TASK PER HALAMAN) --}}
                            <div id="divTaskPaginationWrapper" class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-gray-100 text-xs text-gray-600">
                                <span id="divTaskPaginationInfo"></span>
                                <div id="divTaskPaginationButtons" class="flex items-center gap-1"></div>
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
            {{-- PROGRESS AGREGAT TARGET: SINGLE NESTED DONUT CHART --}}
            {{-- ========================================================= --}}
            <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                {{-- Header Section --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 pb-4 mb-5 border-b border-gray-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            <h2 class="text-lg font-bold text-gray-900">Progress Agregat Target (Single Nested Donut Chart)</h2>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            Grafik Donat Berlapis minimalis &amp; profesional · Cincin Dalam (Bobot Target 5x20%) vs Cincin Luar (Progress Aktual) mewakili 100% target divisi.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100 self-start md:self-auto">
                        Divisi: {{ $division->name }}
                    </span>
                </div>

                @if ($startDate && $endDate && $startDate->lte($endDate))
                    {{-- Filter Kontrol Kepala Divisi: Hanya Divisinya Sendiri --}}
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 bg-gray-50/80 p-3.5 rounded-2xl border border-gray-200/80">
                        <div class="flex flex-wrap items-center gap-4">
                            {{-- Divisi Tetap (Terkunci ke Divisi Sendiri) --}}
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-700 whitespace-nowrap">Divisi:</span>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs font-bold text-gray-800">
                                    {{ $division->name }}
                                </span>
                            </div>

                            {{-- Filter Karyawan dalam Divisi --}}
                            <div class="flex items-center gap-2">
                                <label for="divPieEmployeeFilter" class="text-xs font-bold text-gray-700 whitespace-nowrap">Karyawan:</label>
                                <select
                                    id="divPieEmployeeFilter"
                                    class="rounded-lg border-gray-300 text-xs py-1.5 px-3 focus:border-blue-500 focus:ring-blue-500 bg-white font-medium min-w-[220px]"
                                >
                                    <option value="all">Semua Karyawan ({{ count($chartEmployees ?? []) }})</option>
                                    @foreach ($chartEmployees ?? [] as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <span id="divSubTargetCountBadge" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
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
                                <canvas id="divisionContributionPie"></canvas>
                                
                                {{-- CENTER HOLE: TOTAL PROGRESS AGREGAT (TEKS TEBAL & BESAR BISA DIBACA 1 DETIK) --}}
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Total Progress Agregat</span>
                                    <span id="divAggregatePercent" class="text-3xl sm:text-4xl font-black font-mono tracking-tight text-gray-900 my-0.5">
                                        0%
                                    </span>
                                    <span id="divAggregateBadge" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold shadow-xs bg-gray-100 text-gray-700">
                                        ● MEMUAT
                                    </span>
                                </div>
                            </div>

                            {{-- Panduan Struktur Donut Berlapis --}}
                            <div class="w-full grid grid-cols-2 gap-3 pt-3 border-t border-gray-100 text-xs mt-2">
                                <div class="p-2.5 rounded-xl bg-gray-50/80 border border-gray-100 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: #008080;"></span>
                                    <span class="font-bold text-gray-800">Irisan Luar : Progress Aktual</span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-gray-50/80 border border-gray-100 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: #73605b;"></span>
                                    <span class="font-bold text-gray-800">Irisan Dalam : Bobot Target</span>
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

                            <div id="divQuadrantCardList" class="space-y-3">
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
                                    Rincian status sub-target dan pekerjaan karyawan di bawah lingkup divisi {{ $division->name }}.
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

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="divisionPieLegendList">
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

    </div>
</div>

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

    const barCanvas = document.getElementById('divisionTaskBarChart');
    const weeklyLineCanvas = document.getElementById('divisionTaskWeeklyLineChart');
    const pieCanvas = document.getElementById('divisionContributionPie');
    const barWrapper = document.getElementById('divisionBarChartWrapper');
    const barLegend = document.getElementById('divisionBarLegend');
    const weeklyLineWrapper = document.getElementById('divisionWeeklyLineWrapper');
    const weeklySummaryGrid = document.getElementById('divisionWeeklySummaryGrid');
    const barInner = document.getElementById('divisionBarChartInner');
    const emptyState = document.getElementById('divisionChartEmpty');
    const drilldownHeader = document.getElementById('divisionDrilldownHeader');
    const drilldownTitle = document.getElementById('divisionDrilldownTitle');
    const drilldownSubtitle = document.getElementById('divisionDrilldownSubtitle');
    const backButton = document.getElementById('divisionChartBack');
    const taskCatalogTbody = document.getElementById('divTaskCatalogTbody') || document.getElementById('divTaskCatalogGrid');
    const taskCatalogCount = document.getElementById('divTaskCatalogCount');
    const paginationWrapper = document.getElementById('divTaskPaginationWrapper');
    const paginationInfo = document.getElementById('divTaskPaginationInfo');
    const paginationButtons = document.getElementById('divTaskPaginationButtons');
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

    function renderDivTaskCatalog(items) {
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
        renderDivCatalogPage(1);
    }

    function renderDivCatalogPage(page) {
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
            tr.className = 'div-task-card group hover:bg-gray-50/80 transition cursor-pointer border-b border-gray-100 last:border-b-0';
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
                        btn.onclick = () => renderDivCatalogPage(p);
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
                prevBtn.onclick = () => renderDivCatalogPage(currentCatalogPage - 1);
                paginationButtons.appendChild(prevBtn);

                // Tombol Next
                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.disabled = currentCatalogPage === totalPages;
                nextBtn.className = 'inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer ml-1';
                nextBtn.innerHTML = 'Next <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
                nextBtn.onclick = () => renderDivCatalogPage(currentCatalogPage + 1);
                paginationButtons.appendChild(nextBtn);
            }
        }
    }

    function highlightDivTaskCard(item) {
        if (!item) {
            document.querySelectorAll('.div-task-card').forEach(c => c.classList.remove('task-card-glowing'));
            return;
        }
        const taskNum = String(item.task_number || '');
        const taskId = String(item.task_id || '');

        const matchingIndex = currentCatalogTasks.findIndex(t => (taskId && String(t.task_id) === taskId) || (taskNum && String(t.task_number) === taskNum));
        if (matchingIndex !== -1) {
            const targetPage = Math.floor(matchingIndex / TASKS_PER_PAGE) + 1;
            if (targetPage !== currentCatalogPage) {
                renderDivCatalogPage(targetPage);
            }
            document.querySelectorAll('.div-task-card').forEach(c => {
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
            document.querySelectorAll('.div-task-card').forEach(c => c.classList.remove('task-card-glowing'));
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
        drilldownSubtitle.textContent = 'Target: ' + (item.target_name || '-') + ' | Divisi: ' + (item.division_name || 'Divisi') + ' | Pelaksana: ' + (item.employee_name || '-') + ' | Periode: ' + (item.period_full || item.period_name || item.month_name || '-');

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

        renderDivTaskCatalog(taskItems);

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
                        highlightDivTaskCard(item);
                    } else {
                        highlightDivTaskCard(null);
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
            document.querySelectorAll('.div-task-card').forEach(c => c.classList.remove('task-card-glowing'));
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
    | SINGLE NESTED DONUT CHART: 5 IRISAN BOBOT STATIS VS PROGRES AKTUAL
    |--------------------------------------------------------------------------
    */
    let divNestedDonutChartInstance = null;
    const divEmpFilter = document.getElementById('divPieEmployeeFilter');
    const divSubTargetBadge = document.getElementById('divSubTargetCountBadge');
    const divQuadrantList = document.getElementById('divQuadrantCardList');
    const divLegendSubTargetList = document.getElementById('divisionPieLegendList');

    const divAggregatePercentEl = document.getElementById('divAggregatePercent');
    const divAggregateBadgeEl = document.getElementById('divAggregateBadge');
    
    // Palet 10 warna terintegrasi untuk sub target (5 awal + 5 tambahan baru)
    const divPaletteColors = [
        '#73605b', '#7B2CBF', '#008080', '#dda288', '#B3DBC0',
        '#EAB308', '#1D4ED8', '#65A30D', '#BE185D', '#475569'
    ];
    const divPaletteColorNames = [
        'Cokelat Keabu-abuan', 'Ungu', 'Teal', 'Cokelat Kemerahan', 'Mint',
        'Kuning Emas', 'Biru', 'Hijau Limau', 'Pink Magenta', 'Slate'
    ];

    const divThemeColorMap = {
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
    const singleNestedDonutLabelsPluginDiv = {
        id: 'nestedDonutLabelsDiv',
        afterDraw(chart) {
            const ctx = chart.ctx;
            const metaOuter = chart.getDatasetMeta(0);
            const metaInner = chart.getDatasetMeta(1);
            if (!metaOuter || !metaOuter.data || !metaInner || !metaInner.data || metaInner.data.length === 0) return;

            const pluginOpts = chart.config.options?.plugins?.nestedDonutLabelsDiv;
            const progressValues = pluginOpts?.progressValues || [];
            const colors = pluginOpts?.colors || divPaletteColors;
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

                const sliceColor = colors[i] || divPaletteColors[i % divPaletteColors.length];

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

    function calculateFiveQuadrants(items) {
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
                    color: divPaletteColors[idx],
                    colorName: divPaletteColorNames[idx]
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
                    color: divPaletteColors[idx],
                    colorName: divPaletteColorNames[idx]
                });
            }
        }

        return result;
    }

    function renderDivisionExecutiveGauge() {
        const selectedEmpId = divEmpFilter ? divEmpFilter.value : 'all';

        // Filter / kalkulasi berdasarkan Karyawan (divisi sudah khusus milik divisi sendiri)
        let itemsToRender = [];
        if (selectedEmpId && selectedEmpId !== 'all') {
            contributionItems.forEach(item => {
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
            itemsToRender = contributionItems.map(item => ({
                ...item,
                progress: Math.max(0, Math.min(100, Number(item.progress) || 0))
            }));
        }

        if (divSubTargetBadge) {
            divSubTargetBadge.textContent = `Total Sub Target: ${itemsToRender.length}`;
        }

        // 3. HITUNG 5 IRISAN
        const quadrants = calculateFiveQuadrants(itemsToRender);

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
        if (divAggregatePercentEl) {
            divAggregatePercentEl.textContent = `${parseFloat(Number(totalAggregateProgress).toFixed(1))}%`;
        }
        if (divAggregateBadgeEl) {
            let badgeClass = 'bg-blue-100 text-blue-800 border-blue-200';
            let badgeText = '● IN PROGRESS';
            if (totalAggregateProgress >= 75) {
                badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                badgeText = '● ON TRACK';
            } else if (totalAggregateProgress < 30) {
                badgeClass = 'bg-amber-100 text-amber-800 border-amber-200';
                badgeText = '● NEED PUSH';
            }
            divAggregateBadgeEl.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border shadow-xs ${badgeClass}`;
            divAggregateBadgeEl.textContent = badgeText;
        }

        // Render Single Nested Donut Chart (Chart.js)
        if (pieCanvas) {
            if (divNestedDonutChartInstance) {
                divNestedDonutChartInstance.destroy();
                divNestedDonutChartInstance = null;
            }

            divNestedDonutChartInstance = new Chart(pieCanvas, {
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
                        nestedDonutLabelsDiv: {
                            progressValues: progressValues,
                            colors: sliceColors
                        }
                    }
                },
                plugins: [singleNestedDonutLabelsPluginDiv]
            });
        }

        // 4. RENDER KARTU IRISAN DENGAN FORMAT 'Progress: X%'
        if (divQuadrantList) {
            divQuadrantList.innerHTML = '';
            quadrants.forEach((q, idx) => {
                const theme = divThemeColorMap[q.color] || divThemeColorMap['#73605b'];

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
                divQuadrantList.appendChild(cardEl);
            });
        }

        // 5. DETAIL EKSEKUSI SUB-TARGET
        if (divLegendSubTargetList) {
            divLegendSubTargetList.innerHTML = '';
            if (itemsToRender.length === 0) {
                divLegendSubTargetList.innerHTML = '<p class="text-xs text-gray-400 py-6 text-center col-span-full">Tidak ada sub target yang sesuai dengan filter.</p>';
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
                    divLegendSubTargetList.appendChild(card);
                });
            }
        }
    }

    divEmpFilter?.addEventListener('change', function () {
        renderDivisionExecutiveGauge();
    });

    // Inisialisasi awal
    renderBarChart();
    renderDivisionExecutiveGauge();
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

</x-app-layout>