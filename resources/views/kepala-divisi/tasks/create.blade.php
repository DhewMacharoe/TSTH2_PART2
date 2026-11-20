<x-app-layout>

<x-slot name="header">
    <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Pekerjaan
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Berikan pekerjaan kepada karyawan berdasarkan Sub Target yang telah ditetapkan Kepala Departemen.
        </p>
    </div>
</x-slot>

<div class="py-8">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Error validasi --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">

                <p class="font-medium text-red-700">
                    Terdapat kesalahan pada data yang dimasukkan.
                </p>

                <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        <form
            action="{{ route('kepala_divisi.tasks.store') }}"
            method="POST"
        >

            @csrf

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                {{-- Informasi Organisasi --}}
                <div class="p-6 border-b border-gray-100">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Informasi Organisasi
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Pekerjaan akan diberikan dalam lingkup divisi Anda.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

                        {{-- Departemen --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Departemen
                            </label>

                            <input
                                type="text"
                                value="{{ $department->name }}"
                                readonly
                                class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-600"
                            >
                        </div>

                        {{-- Divisi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Divisi
                            </label>

                            <input
                                type="text"
                                value="{{ $division->name }}"
                                readonly
                                class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-600"
                            >
                        </div>

                    </div>

                </div>

                {{-- Jenis Pekerjaan --}}
                <div class="p-6 border-b border-gray-100">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Jenis Pekerjaan
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Tentukan jenis pekerjaan yang akan diberikan kepada karyawan.
                    </p>

                    <div class="mt-5">

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
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">
                                -- Pilih Jenis Pekerjaan --
                            </option>

                            <option
                                value="project"
                                {{ old('work_type') === 'project' ? 'selected' : '' }}
                            >
                                Kategori Proyek
                            </option>

                            <option
                                value="daily"
                                {{ old('work_type') === 'daily' ? 'selected' : '' }}
                            >
                                Daily / Rutinitas
                            </option>

                            <option
                                value="other"
                                {{ old('work_type') === 'other' ? 'selected' : '' }}
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

                    {{-- Jenis pekerjaan lainnya --}}
                    <div
                        id="work_type_other_wrapper"
                        class="mt-5 {{ old('work_type') === 'other' ? '' : 'hidden' }}"
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
                            value="{{ old('other_work_type') }}"
                            maxlength="255"
                            placeholder="Ketik jenis pekerjaan yang dilakukan..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
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

                </div>

                {{-- Sub Target --}}
                <div class="p-6 border-b border-gray-100">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Dasar Pekerjaan
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Pilih Sub Target yang menjadi dasar pekerjaan yang akan diberikan kepada karyawan.
                    </p>

                    <div class="mt-5">

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
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"

                        <option value="">
                            -- Pilih Sub Target --
                        </option>

                        @foreach($subTargets as $subTarget)

                            <option
                                value="{{ $subTarget->id }}"
                                data-weight="{{ $subTarget->percentage }}"
                                data-project="{{ $subTarget->target->project_name }}"
                                data-target="{{ $subTarget->target->title }}"
                                data-sub-target="{{ $subTarget->title }}"
                                {{ old('sub_target_id') == $subTarget->id ? 'selected' : '' }}
                            >
                                {{ $subTarget->title }}
                                ({{ $subTarget->percentage }}%)
                            </option>

                        @endforeach

                        </select>
                        @error('sub_target_id')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                        @if($subTargets->isEmpty())

                            <p class="mt-2 text-sm text-orange-600">
                                Belum ada Sub Target dari Target aktif pada departemen ini.
                            </p>

                        @else

                            <p class="mt-2 text-xs text-gray-400">
                                Sub Target berasal dari Target aktif yang telah ditetapkan Kepala Departemen.
                            </p>

                        @endif

                    </div>

                    {{-- Informasi Sub Target --}}
                    <div
                        id="subTargetInfo"
                        class="hidden mt-5 rounded-xl border border-blue-100 bg-blue-50 p-4"
                    >

                        <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">
                            Informasi Sub Target
                        </p>

                        <div class="mt-3 space-y-3">

                            <div>
                                <p class="text-xs text-gray-500">
                                    Target
                                </p>

                                <p
                                    id="selectedTarget"
                                    class="text-sm font-medium text-gray-800 mt-1"
                                ></p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">
                                    Sub Target
                                </p>

                                <p
                                    id="selectedSubTarget"
                                    class="text-sm font-medium text-gray-800 mt-1"
                                ></p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">
                                    Bobot Sub Target
                                </p>

                                <span
                                    id="selectedWeight"
                                    class="inline-flex items-center mt-1 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold"
                                >
                                </span>
                            </div>

                        </div>

                        <div class="mt-4 pt-4 border-t border-blue-100">

                        </div>

                    </div>

                </div>

                {{-- Detail Pekerjaan --}}
                <div class="p-6 border-b border-gray-100">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Detail Pekerjaan
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Tentukan karyawan dan pekerjaan yang akan dilaksanakan.
                    </p>

                    <div class="space-y-5 mt-5">

                        {{-- Karyawan --}}
                        <div>

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
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >

                                <option value="">
                                    -- Pilih Karyawan --
                                </option>

                                @foreach($employees as $employee)

                                    <option
                                        value="{{ $employee->id }}"
                                        {{ old('assigned_to') == $employee->id ? 'selected' : '' }}
                                    >
                                        {{ $employee->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('assigned_to')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                            @if($employees->isEmpty())

                                <p class="mt-2 text-sm text-orange-600">
                                    Belum ada karyawan pada divisi ini.
                                </p>

                            @else

                                <p class="mt-2 text-xs text-gray-400">
                                    Hanya karyawan dalam divisi Anda yang dapat dipilih.
                                </p>

                            @endif

                        </div>

                        {{-- Judul --}}
                        <div>

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
                                value="{{ old('title') }}"
                                required
                                maxlength="255"
                                placeholder="Contoh: Pengelolaan dokumen operasional"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >

                            @error('title')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Deskripsi --}}
                        <div>

                            <label
                                for="description"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Deskripsi Pekerjaan
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                placeholder="Jelaskan pekerjaan yang harus dilakukan oleh karyawan..."
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-6 py-5 bg-gray-50 flex items-center justify-between">

                    <a
                        href="{{ route('kepala_divisi.tasks.index') }}"
                        class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ $subTargets->isEmpty() || $employees->isEmpty() ? 'disabled' : '' }}
                    >
                        Berikan Pekerjaan
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<script>
    const workType = document.getElementById('work_type');
    const workTypeOtherWrapper = document.getElementById('work_type_other_wrapper');
    const workTypeOther = document.getElementById('other_work_type');

    function toggleWorkTypeOther() {
        if (workType.value === 'other') {
            workTypeOtherWrapper.classList.remove('hidden');
            workTypeOther.required = true;
        } else {
            workTypeOtherWrapper.classList.add('hidden');
            workTypeOther.required = false;
            workTypeOther.value = '';
        }
    }

    workType.addEventListener('change', toggleWorkTypeOther);

    toggleWorkTypeOther();


    const subTargetSelect = document.getElementById('sub_target_id');
    const subTargetInfo = document.getElementById('subTargetInfo');

    const selectedTarget = document.getElementById('selectedTarget');
    const selectedSubTarget = document.getElementById('selectedSubTarget');
    const selectedWeight = document.getElementById('selectedWeight');

    function updateSubTargetInfo() {

        const option =
            subTargetSelect.options[subTargetSelect.selectedIndex];

        if (!option || !option.value) {
            subTargetInfo.classList.add('hidden');

            selectedTarget.textContent = '';
            selectedSubTarget.textContent = '';
            selectedWeight.textContent = '';

            return;
        }

        const projectName = option.dataset.project || '-';
        const targetName = option.dataset.target || '-';
        const subTargetName = option.dataset.subTarget || '-';

        selectedTarget.textContent =
        projectName + targetName;

        selectedSubTarget.textContent =
        subTargetName;

        selectedWeight.textContent =
        (option.dataset.weight || '0') + '%';

        subTargetInfo.classList.remove('hidden');

    }

    subTargetSelect.addEventListener(
        'change',
        updateSubTargetInfo
    );

    updateSubTargetInfo();
</script>

</x-app-layout>
