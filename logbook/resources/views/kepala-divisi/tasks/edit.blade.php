<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-3">

            <a
                href="{{ route('kepala_divisi.tasks.index') }}"
                class="text-gray-500 hover:text-blue-600 transition"
            >
                <svg
                    class="w-5 h-5"
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
            </a>

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Pekerjaan
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Perbarui informasi pekerjaan yang diberikan kepada karyawan.
                </p>
            </div>

        </div>
    </x-slot>


    <div class="py-8">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Validation Errors --}}
            @if ($errors->any())

                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3">

                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <div class="bg-white rounded-xl border border-gray-200 p-6">

                <form
                    action="{{ route('kepala_divisi.tasks.update', $task) }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')


                    {{-- Department --}}
                    <div class="mb-6">

                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Departemen
                        </label>

                        <input
                            type="text"
                            value="{{ $task->department?->name ?? $division->department?->name ?? '-' }}"
                            readonly
                            class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-600 text-sm"
                        >

                    </div>


                    {{-- Division --}}
                    <div class="mb-6">

                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Divisi
                        </label>

                        <input
                            type="text"
                            value="{{ $division->name ?? '-' }}"
                            readonly
                            class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-600 text-sm"
                        >

                    </div>


                    {{-- Jenis Pekerjaan --}}
                    <div class="mb-6">

                        <label
                            for="work_type"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Jenis Pekerjaan
                        </label>

                        <select
                            name="work_type"
                            id="work_type"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >
                            <option value="">
                                -- Pilih Jenis Pekerjaan --
                            </option>

                            <option
                                value="project"
                                @selected(old('work_type', $task->work_type) === 'project')
                            >
                                Kategori Proyek
                            </option>

                            <option
                                value="daily"
                                @selected(old('work_type', $task->work_type) === 'daily')
                            >
                                Daily / Rutinitas
                            </option>

                            <option
                                value="other"
                                @selected(old('work_type', $task->work_type) === 'other')
                            >
                                Other / Dadakan
                            </option>
                        </select>

                        @error('work_type')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

        {{-- Jenis Pekerjaan Lainnya --}}
        <div
            id="other_work_type_wrapper"
            class="mb-6 {{ old('work_type', $task->work_type) === 'other' ? '' : 'hidden' }}"
        >
            <label
                for="other_work_type"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Jenis Pekerjaan Lainnya
            </label>

            <input
                type="text"
                name="other_work_type"
                id="other_work_type"
                value="{{ old('other_work_type', $task->other_work_type) }}"
                maxlength="255"
                placeholder="Ketik jenis pekerjaan yang dilakukan..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
            >

            <p class="mt-2 text-xs text-gray-400">
                Digunakan untuk pekerjaan yang bersifat dadakan atau tidak termasuk dua kategori sebelumnya.
            </p>

            @error('other_work_type')
                <p class="mt-1 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>


        {{-- Sub Target --}}
        <div class="mb-6">

            <label
                for="sub_target_id"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Sub Target
            </label>

            <select
                name="sub_target_id"
                id="sub_target_id"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
            >

                <option value="">
                    -- Pilih Sub Target --
                </option>

                @foreach ($subTargets as $subTarget)

                    <option
                        value="{{ $subTarget->id }}"
                        @selected(old('sub_target_id', $task->sub_target_id) == $subTarget->id)
                    >
                        {{ $subTarget->target->title }}
                        → {{ $subTarget->title }}
                        (Bobot {{ $subTarget->percentage }}%)
                    </option>

                @endforeach

            </select>

            @error('sub_target_id')
                <p class="mt-1 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror

            @if ($subTargets->isEmpty())

                <p class="text-xs text-red-600 mt-2">
                    Belum ada Sub Target aktif untuk departemen ini.
                </p>

            @else

                <p class="text-xs text-gray-400 mt-2">
                    Sub Target ditetapkan oleh Kepala Departemen dan menjadi acuan pekerjaan.
                    Bobot Sub Target menunjukkan kontribusinya terhadap Target.
                </p>

            @endif

        </div>

                    {{-- Employee --}}
                    <div class="mb-6">

                        <label
                            for="assigned_to"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Karyawan
                        </label>

                        <select
                            name="assigned_to"
                            id="assigned_to"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >

                            <option value="">
                                Pilih karyawan
                            </option>

                            @foreach ($employees as $employee)

                                <option
                                    value="{{ $employee->id }}"
                                    @selected(old('assigned_to', $task->assigned_to) == $employee->id)
                                >
                                    {{ $employee->name }}
                                    — {{ $employee->email }}
                                </option>

                            @endforeach

                        </select>

                        @if ($employees->isEmpty())

                            <p class="text-xs text-red-600 mt-2">
                                Belum ada karyawan pada divisi ini.
                            </p>

                        @else

                            <p class="text-xs text-gray-400 mt-2">
                                Karyawan yang akan menerima pekerjaan ini.
                            </p>

                        @endif

                    </div>


                    {{-- Title --}}
                    <div class="mb-6">

                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Nama Pekerjaan
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title', $task->title) }}"
                            required
                            maxlength="255"
                            placeholder="Contoh: Membuat laporan hasil pengujian"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >

                    </div>


                    {{-- Description --}}
                    <div class="mb-6">

                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Deskripsi Pekerjaan
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="5"
                            placeholder="Jelaskan pekerjaan yang harus dilakukan oleh karyawan..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >{{ old('description', $task->description) }}</textarea>

                    </div>


            {{-- Status Pekerjaan --}}
            <div class="mb-8">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status Pekerjaan
                </label>

                @php
                    $statusLabel = match ($task->status) {
                        'draft' => 'Draft',
                        'assigned' => 'Ditugaskan',
                        'in_progress' => 'Sedang Dikerjakan',
                        'revision' => 'Perlu Revisi',
                        'completed' => 'Selesai',
                        default => ucfirst($task->status),
                    };

                    $statusClass = match ($task->status) {
                        'draft' => 'bg-gray-100 text-gray-700',
                        'assigned' => 'bg-blue-100 text-blue-700',
                        'in_progress' => 'bg-yellow-100 text-yellow-700',
                        'revision' => 'bg-orange-100 text-orange-700',
                        'completed' => 'bg-green-100 text-green-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp

                <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">

                    <span class="text-sm text-gray-500">
                        Status saat ini
                    </span>

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>

                </div>

                <p class="text-xs text-gray-400 mt-2">
                    Status pekerjaan diperbarui berdasarkan proses pelaksanaan dan review logbook.
                </p>

            </div>

                    {{-- Action --}}
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">

                        <a
                            href="{{ route('kepala_divisi.tasks.index') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition"
                        >
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

<script>
    const workType = document.getElementById('work_type');
    const otherWorkTypeWrapper = document.getElementById('other_work_type_wrapper');
    const otherWorkType = document.getElementById('other_work_type');

    function toggleOtherWorkType() {
        if (workType.value === 'other') {
            otherWorkTypeWrapper.classList.remove('hidden');
            otherWorkType.required = true;
        } else {
            otherWorkTypeWrapper.classList.add('hidden');
            otherWorkType.required = false;
            otherWorkType.value = '';
        }
    }

    workType.addEventListener('change', toggleOtherWorkType);

    toggleOtherWorkType();
</script>

</x-app-layout>