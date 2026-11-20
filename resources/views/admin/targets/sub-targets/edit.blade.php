<x-app-layout>

<x-slot name="header">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Sub Target
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Perbarui informasi Sub Target pada Target.
            </p>
        </div>

        <a
            href="{{ route('admin.targets.sub-targets.index', $target) }}"
            class="inline-flex items-center justify-center px-4 py-2.5
                   bg-gray-100 hover:bg-gray-200 text-gray-700
                   rounded-xl font-semibold text-sm transition"
        >
            Kembali
        </a>

    </div>

</x-slot>


<div class="py-8">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">


        {{-- ========================================================= --}}
        {{-- ALERT --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <p class="text-sm font-medium text-green-700">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-sm font-medium text-red-700">
                    {{ session('error') }}
                </p>
            </div>
        @endif


        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">

                <p class="text-sm font-semibold text-red-700 mb-2">
                    Terdapat kesalahan:
                </p>

                <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- INFORMASI PROJECT & SCOPE --}}
        {{-- ========================================================= --}}

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6">

            <div class="p-6">

                <div class="flex items-center gap-3 mb-5">

                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">

                        <svg
                            class="w-5 h-5 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 002-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>

                    </div>

                    <div>

                        <h3 class="text-base font-bold text-gray-800">
                            Informasi Target
                        </h3>

                        <p class="text-sm text-gray-500">
                            Sub Target ini berada di bawah Target berikut.
                        </p>

                    </div>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                    {{-- Project --}}
                    <div>

                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">
                            Project
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $target->project?->name ?? '-' }}
                        </p>

                    </div>


                    {{-- Target --}}
                    <div>

                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">
                            Target
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $target->title }}
                        </p>

                    </div>


                    {{-- Department --}}
                    <div>

                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">
                            Department
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $target->department?->name ?? '-' }}
                        </p>

                    </div>


                    {{-- Division --}}
                    <div>

                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">
                            Divisi
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $target->division?->name ?? '-' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- INFORMASI PERIODE TARGET --}}
        {{-- ========================================================= --}}

        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 mb-6">

            <div class="flex items-start gap-3">

                <div class="w-9 h-9 rounded-lg bg-white border border-gray-200
                            flex items-center justify-center flex-shrink-0">

                    <svg
                        class="w-5 h-5 text-gray-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>

                </div>

                <div>

                    <p class="text-sm font-bold text-gray-800">
                        Periode Target
                    </p>

                    <p class="text-sm text-gray-600 mt-1">

                        {{ $target->period_start?->format('d M Y') ?? '-' }}

                        -

                        {{ $target->period_end?->format('d M Y') ?? '-' }}

                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        Periode Sub Target tidak boleh berada di luar periode Target.
                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- INFORMASI BOBOT --}}
        {{-- ========================================================= --}}

        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">

                <div>

                    <p class="text-sm font-bold text-blue-900">
                        Alokasi Bobot Sub Target
                    </p>

                    <p class="mt-1 text-xs text-blue-700">
                        Bobot menentukan kontribusi Sub Target terhadap Target.
                    </p>

                </div>


                <div class="flex items-center gap-6">

                    <div>

                        <p class="text-xs text-blue-600">
                            Bobot Sub Target Lain
                        </p>

                        <p class="text-lg font-bold text-blue-900">
                            {{ $allocatedPercentage }}%
                        </p>

                    </div>


                    <div class="w-px h-10 bg-blue-200"></div>


                    <div>

                        <p class="text-xs text-blue-600">
                            Maksimal Bobot
                        </p>

                        <p class="text-lg font-bold text-blue-900">
                            {{ $remainingPercentage }}%
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FORM EDIT --}}
        {{-- ========================================================= --}}

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">

            <form
                method="POST"
                action="{{ route('admin.targets.sub-targets.update', [$target, $subTarget]) }}"
            >

                @csrf
                @method('PUT')


                <div class="p-6 sm:p-8">

                    <div class="space-y-6">


                        {{-- ================================================= --}}
                        {{-- JUDUL --}}
                        {{-- ================================================= --}}

                        <div>

                            <label
                                for="title"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Judul Sub Target
                            </label>

                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title', $subTarget->title) }}"
                                required
                                maxlength="255"
                                placeholder="Contoh: Penyelesaian tahap analisis data"
                                class="w-full rounded-xl border-gray-300
                                       focus:border-blue-500 focus:ring-blue-500
                                       text-sm"
                            >

                            @error('title')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- ================================================= --}}
                        {{-- DESKRIPSI --}}
                        {{-- ================================================= --}}

                        <div>

                            <label
                                for="description"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Deskripsi
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                placeholder="Jelaskan cakupan Sub Target..."
                                class="w-full rounded-xl border-gray-300
                                       focus:border-blue-500 focus:ring-blue-500
                                       text-sm"
                            >{{ old('description', $subTarget->description) }}</textarea>

                            @error('description')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- ================================================= --}}
                        {{-- PERIODE --}}
                        {{-- ================================================= --}}

                        <div>

                            <div class="flex items-center justify-between mb-2">

                                <label class="block text-sm font-semibold text-gray-700">
                                    Periode Sub Target
                                </label>

                                <span class="text-xs text-gray-400">
                                    Mengikuti periode Target
                                </span>

                            </div>


                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                {{-- Mulai --}}
                                <div>

                                    <label
                                        for="period_start"
                                        class="block text-xs font-medium text-gray-500 mb-2"
                                    >
                                        Tanggal Mulai
                                    </label>

                                    <input
                                        type="date"
                                        id="period_start"
                                        name="period_start"
                                        value="{{ old('period_start', optional($subTarget->period_start)->format('Y-m-d')) }}"
                                        min="{{ $target->period_start?->format('Y-m-d') }}"
                                        max="{{ $target->period_end?->format('Y-m-d') }}"
                                        class="w-full rounded-xl border-gray-300
                                               focus:border-blue-500 focus:ring-blue-500
                                               text-sm"
                                    >

                                    <p class="mt-1 text-xs text-gray-400">
                                        Tidak boleh sebelum periode Target.
                                    </p>

                                    @error('period_start')
                                        <p class="mt-2 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>


                                {{-- Selesai --}}
                                <div>

                                    <label
                                        for="period_end"
                                        class="block text-xs font-medium text-gray-500 mb-2"
                                    >
                                        Tanggal Selesai
                                    </label>

                                    <input
                                        type="date"
                                        id="period_end"
                                        name="period_end"
                                        value="{{ old('period_end', optional($subTarget->period_end)->format('Y-m-d')) }}"
                                        min="{{ $target->period_start?->format('Y-m-d') }}"
                                        max="{{ $target->period_end?->format('Y-m-d') }}"
                                        class="w-full rounded-xl border-gray-300
                                               focus:border-blue-500 focus:ring-blue-500
                                               text-sm"
                                    >

                                    <p class="mt-1 text-xs text-gray-400">
                                        Tidak boleh melewati periode Target.
                                    </p>

                                    @error('period_end')
                                        <p class="mt-2 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- BOBOT --}}
                        {{-- ================================================= --}}

                        <div>

                            @php

                                $currentPercentage = (int) old(
                                    'percentage',
                                    $subTarget->percentage
                                );

                                $maxPercentage = max(
                                    1,
                                    (int) $remainingPercentage
                                );

                                $sliderValue = min(
                                    $currentPercentage,
                                    $maxPercentage
                                );

                            @endphp


                            <div class="flex items-center justify-between mb-3">

                                <div>

                                    <label
                                        for="percentage"
                                        class="block text-sm font-semibold text-gray-700"
                                    >
                                        Bobot Sub Target
                                    </label>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Menentukan kontribusi Sub Target terhadap Target.
                                    </p>

                                </div>


                                <div class="text-right">

                                    <span
                                        id="percentageValue"
                                        class="text-2xl font-bold text-blue-600"
                                    >
                                        {{ $sliderValue }}%
                                    </span>

                                </div>

                            </div>


                            <input
                                type="range"
                                id="percentage"
                                name="percentage"
                                min="1"
                                max="{{ $maxPercentage }}"
                                value="{{ $sliderValue }}"
                                required
                                class="w-full h-2 bg-gray-200 rounded-lg
                                       appearance-none cursor-pointer accent-blue-600"
                            >


                            <div class="flex justify-between mt-2 text-xs text-gray-400">

                                <span>
                                    1%
                                </span>

                                <span>
                                    Maksimal {{ $remainingPercentage }}%
                                </span>

                            </div>


                            <div class="mt-4 p-4 bg-gray-50 rounded-xl">

                                <p class="text-xs text-gray-500 leading-relaxed">

                                    Bobot Sub Target ini:

                                    <span
                                        id="percentageDescription"
                                        class="font-bold text-gray-700"
                                    >
                                        {{ $sliderValue }}%
                                    </span>

                                    dari keseluruhan bobot Target.

                                    <span class="block mt-1">
                                        Total seluruh Sub Target dalam satu Target
                                        harus mencapai
                                        <span class="font-semibold text-gray-700">
                                            100%
                                        </span>.
                                    </span>

                                </p>

                            </div>


                            @error('percentage')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ========================================================= --}}
                {{-- BUTTON --}}
                {{-- ========================================================= --}}

                <div class="px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-200
                            flex flex-col-reverse sm:flex-row sm:justify-end gap-3">

                    <a
                        href="{{ route('admin.targets.sub-targets.index', $target) }}"
                        class="inline-flex items-center justify-center
                               px-5 py-2.5 rounded-xl
                               bg-white border border-gray-300
                               hover:bg-gray-100
                               text-gray-700 font-semibold text-sm transition"
                    >
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center
                               px-5 py-2.5 rounded-xl
                               bg-blue-600 hover:bg-blue-700
                               text-white font-semibold text-sm transition"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const percentage = document.getElementById('percentage');

        const percentageValue =
            document.getElementById('percentageValue');

        const percentageDescription =
            document.getElementById('percentageDescription');


        if (!percentage) {
            return;
        }


        function updatePercentage() {

            const value = percentage.value;

            if (percentageValue) {
                percentageValue.textContent = value + '%';
            }

            if (percentageDescription) {
                percentageDescription.textContent = value + '%';
            }

        }


        percentage.addEventListener(
            'input',
            updatePercentage
        );


        updatePercentage();

    });

</script>

</x-app-layout>
