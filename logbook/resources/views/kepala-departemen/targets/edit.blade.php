<x-app-layout>
    
<x-slot name="header">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">
            Edit Target
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Perbarui target, project, dan divisi
            sesuai kebutuhan departemen.
        </p>
    </div>
</x-slot>


<div class="py-8">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200">

            {{-- ===================================================== --}}
            {{-- HEADER CARD --}}
            {{-- ===================================================== --}}

            <div class="px-6 py-5 border-b border-gray-200">

                <h3 class="text-lg font-semibold text-gray-800">
                    Informasi Target
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Pastikan Project dan Divisi sesuai dengan
                    target pekerjaan yang diperbarui.
                </p>

            </div>


            {{-- ===================================================== --}}
            {{-- FORM --}}
            {{-- ===================================================== --}}

            <form
                action="{{ route('kepala_departemen.targets.update', $target) }}"
                method="POST"
                class="p-6"
            >

                @csrf
                @method('PUT')


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

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- DEPARTEMEN --}}
                {{-- ================================================= --}}

                <div class="mb-6">

                    <label
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Departemen
                    </label>

                    <div class="px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg">

                        <p class="font-semibold text-gray-800">
                            {{ $target->department?->name ?? '-' }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Target tetap berada pada departemen ini.
                        </p>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PROJECT --}}
                {{-- ================================================= --}}

                <div class="mb-6">

                    <label
                        for="project_id"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Project
                        <span class="text-gray-400">(Opsional)</span>
                    </label>

                    <select
                        name="project_id"
                        id="project_id"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Tidak menggunakan Project
                        </option>

                        @foreach ($projects as $project)

                            <option
                                value="{{ $project->id }}"
                                {{ old('project_id', $target->project_id) == $project->id ? 'selected' : '' }}
                            >
                                {{ $project->name }}
                            </option>

                        @endforeach

                    </select>

                    <p class="text-xs text-gray-500 mt-1">
                        Project merupakan pekerjaan utama yang dapat
                        melibatkan beberapa divisi.
                    </p>

                    @error('project_id')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror


                    {{-- PROJECT INFORMATION --}}

                    <div
                        id="project-info"
                        class="hidden mt-4 p-4 rounded-xl bg-indigo-50 border border-indigo-100"
                    >

                        <div class="flex items-start gap-3">

                            <div class="flex-shrink-0">

                                <div class="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center">

                                    <svg
                                        class="w-5 h-5 text-indigo-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.414 4.414A2 2 0 0119 9v10a2 2 0 01-2 2z"
                                        />
                                    </svg>

                                </div>

                            </div>


                            <div class="flex-1">

                                <p class="text-sm font-semibold text-indigo-800">
                                    Project Terpilih
                                </p>

                                <p
                                    id="project-description"
                                    class="mt-1 text-sm text-indigo-700"
                                ></p>

                                <div class="mt-3">

                                    <p class="text-xs font-semibold text-indigo-800 uppercase tracking-wide">
                                        Divisi yang terlibat
                                    </p>

                                    <div
                                        id="project-divisions"
                                        class="flex flex-wrap gap-2 mt-2"
                                    ></div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- DIVISI --}}
                {{-- ================================================= --}}

                <div class="mb-6">

                    <label
                        for="division_id"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Divisi
                    </label>

                    <select
                        name="division_id"
                        id="division_id"
                        required
                        disabled
                        class="w-full rounded-lg border-gray-300 bg-gray-100 focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed"
                    >

                        <option value="">
                            Pilih Project terlebih dahulu
                        </option>

                    </select>

                    <p class="text-xs text-gray-500 mt-1">
                        Divisi hanya dapat dipilih dari divisi yang
                        terlibat dalam Project yang dipilih.
                    </p>

                    @error('division_id')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- TARGET --}}
                {{-- ================================================= --}}

                <div class="mb-6">

                    <label
                        for="title"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Target
                    </label>

                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $target->title) }}"
                        required
                        maxlength="255"
                        placeholder="Contoh: Pemeliharaan Fasilitas Gedung"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    <p class="text-xs text-gray-500 mt-1">
                        Jelaskan hasil atau ruang lingkup utama
                        yang ingin dicapai oleh divisi dalam Project.
                    </p>

                    @error('title')

                        <p class="mt-1 text-sm text-red-600">
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
                        rows="5"
                        placeholder="Jelaskan ruang lingkup, tujuan, atau hasil yang diharapkan."
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description', $target->description) }}</textarea>

                    @error('description')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- PERIODE --}}
                {{-- ================================================= --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    {{-- MULAI --}}

                    <div>

                        <label
                            for="period_start"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Mulai Periode
                        </label>

                        <input
                            type="date"
                            name="period_start"
                            id="period_start"
                            value="{{ old('period_start', $target->period_start?->format('Y-m-d')) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('period_start')

                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- AKHIR --}}

                    <div>

                        <label
                            for="period_end"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Akhir Periode
                        </label>

                        <input
                            type="date"
                            name="period_end"
                            id="period_end"
                            value="{{ old('period_end', $target->period_end?->format('Y-m-d')) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('period_end')

                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- STATUS --}}
                {{-- ================================================= --}}

                <div class="mb-6">

                    <label
                        for="status"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Status
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >

                        <option
                            value="active"
                            {{ old('status', $target->status) === 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="pending"
                            {{ old('status', $target->status) === 'pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="completed"
                            {{ old('status', $target->status) === 'completed' ? 'selected' : '' }}
                        >
                            Completed
                        </option>

                        <option
                            value="inactive"
                            {{ old('status', $target->status) === 'inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>

                    <p class="text-xs text-gray-500 mt-1">
                        Pilih status Target sesuai kondisi target saat ini.
                    </p>

                    @error('status')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- BUTTONS --}}
                {{-- ================================================= --}}

                <div class="flex items-center justify-end gap-3">

                    <a
                        href="{{ route('kepala_departemen.targets.index') }}"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================== --}}
{{-- JAVASCRIPT PROJECT → DIVISION --}}
{{-- =============================================================== --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const projectSelect = document.getElementById('project_id');
        const divisionSelect = document.getElementById('division_id');

        const projectInfo = document.getElementById('project-info');
        const projectDescription = document.getElementById('project-description');
        const projectDivisions = document.getElementById('project-divisions');

        const projects = @json($projectData);

        const initialProjectId = @json(
            old('project_id', $target->project_id)
        );

        let oldDivisionId = @json(
            old('division_id', $target->division_id)
        );

        function resetDivisionOptions(message = 'Pilih Divisi') {
            divisionSelect.innerHTML = '';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = message;

            divisionSelect.appendChild(placeholder);
        }

        function showAllDivisions() {
            resetDivisionOptions();

            divisionSelect.disabled = false;
            divisionSelect.classList.remove('bg-gray-100');

            @foreach ($divisions as $division)
                {
                    const option = document.createElement('option');

                    option.value = @json((string) $division->id);
                    option.textContent = @json($division->name);

                    if (
                        String(option.value) === String(oldDivisionId)
                    ) {
                        option.selected = true;
                    }

                    divisionSelect.appendChild(option);
                }
            @endforeach

            projectInfo.classList.add('hidden');
        }

        function showProjectDivisions(project) {
            const divisions = project.divisions || [];

            resetDivisionOptions(
                divisions.length > 0
                    ? 'Pilih Divisi'
                    : 'Project belum memiliki divisi'
            );

            divisionSelect.disabled = divisions.length === 0;

            divisionSelect.classList.toggle(
                'bg-gray-100',
                divisions.length === 0
            );

            divisions.forEach(function (division) {
                const option = document.createElement('option');

                option.value = String(division.id);
                option.textContent = division.name;

                if (
                    String(division.id) === String(oldDivisionId)
                ) {
                    option.selected = true;
                }

                divisionSelect.appendChild(option);
            });

            projectDescription.textContent =
                project.description || 'Tidak ada deskripsi project.';

            projectDivisions.innerHTML = '';

            if (divisions.length > 0) {
                divisions.forEach(function (division) {
                    const badge = document.createElement('span');

                    badge.className =
                        'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white text-indigo-700 border border-indigo-200';

                    badge.textContent = division.name;

                    projectDivisions.appendChild(badge);
                });
            } else {
                const message = document.createElement('span');

                message.className = 'text-xs text-red-600';
                message.textContent =
                    'Belum ada divisi yang terhubung dengan Project ini.';

                projectDivisions.appendChild(message);
            }

            projectInfo.classList.remove('hidden');
        }

        function updateDivisionOptions() {
            const projectId = projectSelect.value;

            if (!projectId) {
                showAllDivisions();
                return;
            }

            const project = projects.find(function (item) {
                return String(item.id) === String(projectId);
            });

            if (!project) {
                resetDivisionOptions('Project tidak ditemukan');

                divisionSelect.disabled = true;
                divisionSelect.classList.add('bg-gray-100');

                projectInfo.classList.add('hidden');

                return;
            }

            showProjectDivisions(project);
        }

        projectSelect.addEventListener('change', function () {
            // Jangan mempertahankan divisi dari Project sebelumnya.
            oldDivisionId = '';

            updateDivisionOptions();
        });

        // Tampilkan pilihan yang sesuai ketika halaman pertama dibuka.
        updateDivisionOptions();
    });
</script>

</x-app-layout>
