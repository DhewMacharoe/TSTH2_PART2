<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="max-w-4xl mx-auto px-6">


            {{-- ===================================================== --}}
            {{-- HEADER --}}
            {{-- ===================================================== --}}

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Tambah Project
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Buat Project baru yang dapat melibatkan satu atau lebih divisi
                    dalam departemen yang sama.
                </p>
            </div>


            {{-- ===================================================== --}}
            {{-- FORM CARD --}}
            {{-- ===================================================== --}}

            <div class="bg-white rounded-xl shadow-sm p-6">

                {{-- ================================================= --}}
                {{-- VALIDATION ERROR --}}
                {{-- ================================================= --}}

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                        <p class="text-sm font-semibold text-red-700 mb-2">
                            Terdapat kesalahan pada form:
                        </p>
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                {{-- ===================================================== --}}
                {{-- FORM --}}
                {{-- ===================================================== --}}

                <form
                    method="POST"
                    action="{{ route('kepala_departemen.projects.store') }}"
                >

                    @csrf


                    {{-- ================================================= --}}
                    {{-- NAMA PROJECT --}}
                    {{-- ================================================= --}}

                    <div class="mb-5">

                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Nama Project
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            required
                            maxlength="255"
                            placeholder="Contoh: Pengelolaan Gedung 2026"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- DESKRIPSI --}}
                    {{-- ================================================= --}}

                    <div class="mb-5">

                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Deskripsi
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            placeholder="Jelaskan tujuan dan ruang lingkup project ini."
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- PERIODE --}}
                    {{-- ================================================= --}}

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

                        <div>
                            <label
                                for="start_date"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tanggal Mulai
                            </label>

                            <input
                                type="date"
                                name="start_date"
                                id="start_date"
                                value="{{ old('start_date') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="end_date"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tanggal Selesai
                            </label>

                            <input
                                type="date"
                                name="end_date"
                                id="end_date"
                                value="{{ old('end_date') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- DIVISI --}}
                    {{-- ================================================= --}}

                    <div class="mb-6">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Divisi yang Terlibat
                        </label>

                        <p class="text-xs text-gray-500 mb-3">
                            Pilih satu atau lebih divisi dalam departemen Anda yang akan
                            berkolaborasi pada Project ini. Setiap divisi dapat memiliki
                            Target masing-masing.
                        </p>

                        @if ($divisions->isEmpty())

                            <div class="p-4 rounded-lg bg-amber-50 border border-amber-200 text-sm text-amber-700">
                                Belum ada divisi yang tersedia.
                                Hubungi Admin untuk menambahkan divisi.
                            </div>

                        @else

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                @foreach ($divisions as $division)

                                    @php
                                        $oldDivisionIds = array_map(
                                            'intval',
                                            old('division_ids', [])
                                        );

                                        $isChecked = in_array(
                                            (int) $division->id,
                                            $oldDivisionIds,
                                            true
                                        );
                                    @endphp

                                    <label
                                        for="division_{{ $division->id }}"
                                        class="
                                            flex items-start gap-3 p-3 rounded-lg border cursor-pointer
                                            transition hover:bg-indigo-50
                                            {{ $isChecked
                                                ? 'border-indigo-400 bg-indigo-50'
                                                : 'border-gray-200 bg-white'
                                            }}
                                        "
                                    >
                                        <input
                                            type="checkbox"
                                            name="division_ids[]"
                                            id="division_{{ $division->id }}"
                                            value="{{ $division->id }}"
                                            {{ $isChecked ? 'checked' : '' }}
                                            class="mt-1 w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                        >

                                        <span class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-800">
                                                {{ $division->name }}
                                            </span>

                                            <span class="text-xs text-gray-500 mt-1">
                                                Department:
                                                {{ $division->department->name ?? 'Tanpa Department' }}
                                            </span>
                                        </span>

                                    </label>

                                @endforeach

                            </div>

                        @endif


                        @error('division_ids')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- BUTTON --}}
                    {{-- ================================================= --}}

                    <div class="flex items-center justify-end gap-3">

                        <a
                            href="{{ route('kepala_departemen.projects.index') }}"
                            class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            @disabled($divisions->isEmpty())
                            class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition"
                        >
                            Simpan Project
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
