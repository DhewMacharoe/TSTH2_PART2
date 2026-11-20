<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="max-w-7xl mx-auto px-6">

            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">

                <div>
                    <div class="flex items-center gap-3 flex-wrap">

                        <h1 class="text-2xl font-bold text-gray-800">
                            {{ $project->name }}
                        </h1>

                        @php
                            $statusClass = match ($project->status) {
                                'active' => 'bg-emerald-100 text-emerald-700',
                                'pending' => 'bg-amber-100 text-amber-700',
                                'completed' => 'bg-blue-100 text-blue-700',
                                'inactive' => 'bg-gray-100 text-gray-600',
                                default => 'bg-gray-100 text-gray-600',
                            };

                            $statusLabel = match ($project->status) {
                                'active' => 'Aktif',
                                'pending' => 'Pending',
                                'completed' => 'Selesai',
                                'inactive' => 'Tidak Aktif',
                                default => $project->status,
                            };
                        @endphp

                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>

                    </div>

                    <p class="text-sm text-gray-500 mt-1">
                        Detail project dan pembagian pekerjaan antar-divisi.
                    </p>
                </div>

                <div class="flex items-center gap-2">

                    <a
                        href="{{ route('kepala_departemen.projects.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 text-sm font-medium transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Kembali
                    </a>

                    {{-- Edit hanya untuk pembuat Project --}}

                    @if ((int) $project->created_by === (int) auth()->id())
        <a
            href="{{ route('kepala_departemen.projects.edit', $project) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Edit Project
        </a>

        <form
            action="{{ route('kepala_departemen.projects.destroy', $project) }}"
            method="POST"
            onsubmit="return confirm('Apakah kamu yakin ingin menghapus project ini? Target dan pekerjaan yang terhubung akan tetap dipertahankan, tetapi tidak lagi terhubung ke project ini.');"
        >
            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/>
                </svg>
                Hapus Project
            </button>
        </form>
    @endif

                </div>

            </div>


            {{-- INFORMASI PROJECT --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

                {{-- Deskripsi --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Deskripsi Project
                    </p>

                    <p class="text-sm text-gray-700 mt-2 leading-relaxed whitespace-pre-line">
                        {{ $project->description ?: 'Tidak ada deskripsi project.' }}
                    </p>

                </div>

                {{-- Periode --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Periode
                    </p>

                    <div class="mt-2 text-sm text-gray-700 flex items-center gap-2">

                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>

                        <span>
                            {{ $project->start_date?->format('d M Y') ?? '-' }}
                            &ndash;
                            {{ $project->end_date?->format('d M Y') ?? '-' }}
                        </span>

                    </div>

                    <div class="mt-4">
                        <p class="text-xs text-gray-500">
                            Jumlah divisi
                        </p>
                        <p class="text-xl font-bold text-indigo-600">
                            {{ $project->divisions->count() }}
                        </p>
                    </div>

                    <div class="mt-3">
                        <p class="text-xs text-gray-500">
                            Jumlah Target
                        </p>
                        <p class="text-xl font-bold text-indigo-600">
                            {{ $project->targets->count() }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- DIVISI TERLIBAT --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">

                <div class="flex items-center justify-between gap-3 mb-4">

                    <div>
                        <h2 class="text-base font-bold text-gray-800">
                            Divisi yang Terlibat
                        </h2>

                        <p class="text-xs text-gray-500 mt-1">
                            Setiap divisi dapat memiliki Target masing-masing.
                        </p>
                    </div>

                    <span class="text-sm font-semibold text-indigo-600">
                        {{ $project->divisions->count() }} Divisi
                    </span>

                </div>

                @if ($project->divisions->isEmpty())

                    <div class="p-4 rounded-lg bg-amber-50 border border-amber-200">
                        <p class="text-sm text-amber-700">
                            Belum ada divisi yang terhubung ke Project ini.
                        </p>
                    </div>

                @else

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">

                        @foreach ($project->divisions as $division)

                            <div class="flex items-start gap-3 p-4 rounded-lg bg-indigo-50 border border-indigo-100">

                                <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-indigo-800">
                                        {{ $division->name }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Department:
                                        {{ $division->department->name ?? 'Tanpa Department' }}
                                    </p>
                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- TARGET PER DIVISI --}}
            <div class="mb-6">

                <div class="mb-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        Target dalam Project
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Scope, Target, SubTarget, dan Task dikelola sesuai divisi masing-masing.
                    </p>
                </div>

                @if ($project->divisions->isEmpty())

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
                        <h3 class="text-sm font-semibold text-gray-700">
                            Belum ada divisi yang terhubung
                        </h3>

                        <p class="text-xs text-gray-400 mt-1">
                            Hubungkan divisi melalui fitur Edit Project.
                        </p>
                    </div>

                @else

                    <div class="space-y-5">

                        {{-- Setiap divisi tetap ditampilkan meskipun belum memiliki Target --}}
                        @foreach ($project->divisions as $division)

                            @php
                                $divisionTargets = $project->targets->where(
                                    'division_id',
                                    $division->id
                                );
                            @endphp

                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                                {{-- Header divisi --}}
                                <div class="px-5 py-4 bg-gray-50 border-b border-gray-100">

                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Divisi
                                            </p>

                                            <h3 class="text-base font-bold text-gray-800 mt-1">
                                                {{ $division->name }}
                                            </h3>

                                            <p class="text-xs text-gray-500 mt-1">
                                                Department:
                                                {{ $division->department->name ?? 'Tanpa Department' }}
                                            </p>
                                        </div>

                                        <span class="inline-flex self-start px-2.5 py-1 rounded-full bg-white border border-gray-200 text-xs font-medium text-gray-600">
                                            {{ $divisionTargets->count() }} Target
                                        </span>

                                    </div>

                                </div>


                                {{-- Daftar Target --}}
                                <div class="p-5">

                                    @if ($divisionTargets->isEmpty())

                                        <div class="p-4 rounded-lg bg-gray-50 border border-dashed border-gray-200">
                                            <p class="text-sm font-medium text-gray-600">
                                                Belum ada Scope &amp; Target untuk divisi ini.
                                            </p>

                                            <p class="text-xs text-gray-400 mt-1">
                                                Target akan muncul setelah dibuat dan dihubungkan ke Project ini.
                                            </p>
                                        </div>

                                    @else

                                        <div class="space-y-4">

                                            @foreach ($divisionTargets as $target)

                                                <div class="rounded-lg border border-gray-200 p-4">

                                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                                                        <div class="flex-1 min-w-0">

                                                            <div class="flex items-center gap-2 flex-wrap">

                                                                <h4 class="text-sm font-bold text-gray-800">
                                                                    {{ $target->title }}
                                                                </h4>

                                                                @php
                                                                    $targetStatusClass = match ($target->status) {
                                                                        'active' => 'bg-emerald-100 text-emerald-700',
                                                                        'completed' => 'bg-blue-100 text-blue-700',
                                                                        'inactive' => 'bg-gray-100 text-gray-600',
                                                                        default => 'bg-amber-100 text-amber-700',
                                                                    };
                                                                @endphp

                                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $targetStatusClass }}">
                                                                    {{ ucfirst($target->status) }}
                                                                </span>

                                                            </div>

                                                            @if ($target->description)
                                                                <p class="text-xs text-gray-500 mt-1 whitespace-pre-line">
                                                                    {{ $target->description }}
                                                                </p>
                                                            @endif

                                                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3 text-xs text-gray-500">

                                                                <span>
                                                                    Periode:
                                                                    {{ $target->period_start?->format('d M Y') ?? '-' }}
                                                                    &ndash;
                                                                    {{ $target->period_end?->format('d M Y') ?? '-' }}
                                                                </span>

                                                                <span>
                                                                    SubTarget:
                                                                    {{ $target->subTargets->count() }}
                                                                </span>

                                                                <span>
                                                                    Task:
                                                                    {{ $target->subTargets->sum(fn ($subTarget) => $subTarget->tasks->count()) }}
                                                                </span>

                                                            </div>

                                                        </div>


                                                        {{-- Detail Target --}}
                                                        <div class="shrink-0">

                                                            <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-gray-500 text-xs font-medium">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                </svg>
                                                                Target
                                                            </span>

                                                        </div>

                                                    </div>

                                                </div>

                                            @endforeach

                                        </div>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>
