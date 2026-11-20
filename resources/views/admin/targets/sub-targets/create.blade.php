<x-app-layout>

<x-slot name="header">
    <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Sub Target
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Menentukan bagian pekerjaan dan bobot dari Target.
        </p>
    </div>
</x-slot>


<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ========================================================= --}}
        {{-- INFORMASI TARGET --}}
        {{-- ========================================================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Project
            </p>

            <h3 class="text-lg font-semibold text-gray-800 mt-1">
                {{ $target->project?->name ?? '-' }}
            </h3>

            @if($target->project?->description)
                <p class="text-sm text-gray-500 mt-2">
                    {{ $target->project->description }}
                </p>
            @endif


            <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mt-5">
                Target
            </p>

            <p class="text-xl font-semibold text-gray-800 mt-1">
                {{ $target->title }}
            </p>

            @if($target->description)
                <p class="text-sm text-gray-500 mt-2">
                    {{ $target->description }}
                </p>
            @endif


            <div class="flex flex-wrap gap-x-6 gap-y-2 mt-4">

                @if($target->department)
                    <p class="text-sm text-gray-500">
                        Department:
                        <span class="font-medium text-gray-700">
                            {{ $target->department->name }}
                        </span>
                    </p>
                @endif

                @if($target->division)
                    <p class="text-sm text-gray-500">
                        Divisi:
                        <span class="font-medium text-gray-700">
                            {{ $target->division->name }}
                        </span>
                    </p>
                @endif

            </div>


            {{-- ===================================================== --}}
            {{-- PERIODE TARGET --}}
            {{-- ===================================================== --}}
            <div class="mt-5 pt-5 border-t border-gray-100">

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Periode Target
                </p>

                <p class="text-sm text-gray-700 mt-1">

                    @if($target->period_start)
                        {{ $target->period_start->format('d M Y') }}
                    @else
                        -
                    @endif

                    <span class="mx-1 text-gray-400">—</span>

                    @if($target->period_end)
                        {{ $target->period_end->format('d M Y') }}
                    @else
                        -
                    @endif

                </p>

            </div>


            {{-- ===================================================== --}}
            {{-- RINGKASAN BOBOT --}}
            {{-- ===================================================== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-100">

                <div class="bg-blue-50 rounded-xl p-4">

                    <p class="text-xs text-blue-600">
                        Bobot Teralokasi
                    </p>

                    <p class="text-xl font-bold text-blue-700 mt-1">
                        {{ $allocatedPercentage }}%
                    </p>

                </div>


                <div
                    class="rounded-xl p-4
                        {{ $remainingPercentage > 0
                            ? 'bg-emerald-50'
                            : 'bg-gray-100' }}"
                >

                    <p
                        class="text-xs
                            {{ $remainingPercentage > 0
                                ? 'text-emerald-600'
                                : 'text-gray-500' }}"
                    >
                        Sisa Bobot
                    </p>

                    <p
                        class="text-xl font-bold mt-1
                            {{ $remainingPercentage > 0
                                ? 'text-emerald-700'
                                : 'text-gray-600' }}"
                    >
                        {{ $remainingPercentage }}%
                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            @if($errors->any())

                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">

                    <p class="text-sm font-medium text-red-700 mb-2">
                        Terdapat kesalahan:
                    </p>

                    <ul class="list-disc list-inside text-sm text-red-600">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                action="{{ route('admin.targets.sub-targets.store', $target) }}"
                method="POST"
            >

                @csrf


                {{-- ================================================= --}}
                {{-- NAMA SUB TARGET --}}
                {{-- ================================================= --}}
                <div class="mb-6">

                    <label
                        for="title"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Nama Sub Target
                    </label>

                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title') }}"
                        placeholder="Contoh: Pemeriksaan fasilitas gedung"
                        maxlength="255"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >

                    @error('title')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- DESKRIPSI --}}
                {{-- ================================================= --}}
                <div class="mb-6">

                    <label
                        for="description"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        placeholder="Jelaskan ruang lingkup Sub Target..."
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- PERIODE --}}
                {{-- ================================================= --}}
                <div class="mb-6">

                    <div class="flex items-center justify-between mb-3">

                        <label class="block text-sm font-medium text-gray-700">
                            Periode Sub Target
                        </label>

                        <span class="text-xs text-gray-400">
                            Harus berada dalam periode Target
                        </span>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Mulai --}}
                        <div>

                            <label
                                for="period_start"
                                class="block text-xs font-medium text-gray-600 mb-2"
                            >
                                Periode Mulai
                            </label>

                            <input
                                type="date"
                                name="period_start"
                                id="period_start"
                                value="{{ old('period_start', $target->period_start?->format('Y-m-d')) }}"
                                min="{{ $target->period_start?->format('Y-m-d') }}"
                                max="{{ $target->period_end?->format('Y-m-d') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            <p class="text-xs text-gray-400 mt-1">
                                Tidak boleh sebelum periode Target.
                            </p>

                            @error('period_start')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Selesai --}}
                        <div>

                            <label
                                for="period_end"
                                class="block text-xs font-medium text-gray-600 mb-2"
                            >
                                Periode Selesai
                            </label>

                            <input
                                type="date"
                                name="period_end"
                                id="period_end"
                                value="{{ old('period_end', $target->period_end?->format('Y-m-d')) }}"
                                min="{{ $target->period_start?->format('Y-m-d') }}"
                                max="{{ $target->period_end?->format('Y-m-d') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            <p class="text-xs text-gray-400 mt-1">
                                Tidak boleh melewati periode Target.
                            </p>

                            @error('period_end')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- BOBOT --}}
                {{-- ================================================= --}}
                <div class="mb-6">

                    <div class="flex items-center justify-between mb-2">

                        <label
                            for="percentage"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Bobot Sub Target
                        </label>

                        <span class="text-sm font-medium text-emerald-600">
                            Sisa {{ $remainingPercentage }}%
                        </span>

                    </div>


                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">

                        @if($remainingPercentage > 0)

                            <div class="flex items-center gap-4">

                                <input
                                    type="range"
                                    name="percentage"
                                    id="percentage"
                                    min="1"
                                    max="{{ $remainingPercentage }}"
                                    value="{{ old('percentage', min(10, $remainingPercentage)) }}"
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-emerald-600"
                                    oninput="document.getElementById('percentage_value').textContent = this.value + '%'"
                                >

                                <span
                                    id="percentage_value"
                                    class="w-16 text-center font-semibold text-emerald-600"
                                >
                                    {{ old('percentage', min(10, $remainingPercentage)) }}%
                                </span>

                            </div>


                            <div class="flex items-center justify-between mt-2">

                                <p class="text-xs text-gray-400">
                                    Minimal 1%
                                </p>

                                <p class="text-xs text-gray-400">
                                    Maksimal {{ $remainingPercentage }}%
                                </p>

                            </div>

                        @else

                            <div class="text-center py-3">

                                <p class="text-sm font-medium text-red-600">
                                    Bobot sudah mencapai 100%.
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    Tidak ada bobot yang tersisa.
                                </p>

                            </div>

                        @endif

                    </div>


                    <p class="text-xs text-gray-500 mt-2">
                        Bobot menunjukkan porsi Sub Target terhadap keseluruhan Target.
                    </p>

                    @error('percentage')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- TOMBOL --}}
                {{-- ================================================= --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">

                    <a
                        href="{{ route('admin.targets.sub-targets.index', $target) }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition"
                    >
                        Batal
                    </a>


                    @if($remainingPercentage > 0)

                        <button
                            type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition"
                        >
                            Simpan Sub Target
                        </button>

                    @else

                        <button
                            type="button"
                            disabled
                            class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed"
                        >
                            Bobot Sudah 100%
                        </button>

                    @endif

                </div>

            </form>

        </div>

    </div>
</div>

</x-app-layout>
