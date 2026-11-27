<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="max-w-4xl mx-auto px-6">

            {{-- HEADER --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Tambah Target
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Tentukan Project, divisi, ruang lingkup, dan target
                    yang menjadi acuan pelaksanaan pekerjaan.
                </p>
            </div>


            {{-- FORM CARD --}}
            <div class="bg-white rounded-xl shadow-sm p-6">

                {{-- VALIDATION ERROR --}}
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


                {{-- FORM --}}
                <form
                    method="POST"
                    action="{{ route('kepala_departemen.targets.store') }}"
                >

                    @csrf


                    {{-- DEPARTMENT --}}
                    <div class="mb-5">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Department
                        </label>

                        <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-700">
                            @if ($departments->count() > 0)
                                {{ $departments->first()->name }}
                            @else
                                -
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 mt-1">
                            Department ditentukan secara otomatis berdasarkan
                            akun atau divisi yang digunakan.
                        </p>

                    </div>


                    {{-- PROJECT --}}
                    <div class="mb-5">

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
                                -- Tanpa Project --
                            </option>

                            @foreach ($projects as $project)

                                <option
                                    value="{{ $project->id }}"
                                    {{ old('project_id') == $project->id ? 'selected' : '' }}
                                >
                                    {{ $project->name }}
                                </option>

                            @endforeach

                        </select>

                        <p class="text-xs text-gray-500 mt-1">
                            Project digunakan apabila Target merupakan
                            bagian dari pekerjaan besar yang melibatkan beberapa
                            divisi. Jika pekerjaan hanya milik satu divisi,
                            Project dapat dikosongkan.
                        </p>

                        @error('project_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- PROJECT INFORMATION --}}
                    <div
                        id="project-info"
                        class="hidden mb-5 rounded-lg border border-indigo-100 bg-indigo-50 p-4"
                    >

                        <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide">
                            Project yang dipilih
                        </p>

                        <p
                            id="selected-project-name"
                            class="mt-1 text-sm font-semibold text-gray-800"
                        >
                            -
                        </p>

                        <div
                            id="selected-project-divisions"
                            class="mt-2 text-xs text-gray-600"
                        >
                        </div>

                    </div>


                    {{-- DIVISION --}}
                    <div class="mb-5">

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
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option value="">
                                -- Pilih Divisi --
                            </option>

                            @foreach ($divisions as $division)

                                <option
                                    value="{{ $division->id }}"
                                    {{ old('division_id') == $division->id ? 'selected' : '' }}
                                >
                                    {{ $division->name }}
                                </option>

                            @endforeach

                        </select>

                        <p class="text-xs text-gray-500 mt-1">
                            Jika menggunakan Project, hanya divisi yang
                            terlibat dalam Project tersebut yang dapat dipilih.
                        </p>

                        @error('division_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- TARGET --}}
                    <div class="mb-5">

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
                            value="{{ old('title') }}"
                            required
                            maxlength="255"
                            placeholder="Contoh: Analisis dan Validasi Genetik"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        <p class="text-xs text-gray-500 mt-1">
                            Target merupakan tanggung jawab utama
                            divisi dalam melaksanakan pekerjaan.
                        </p>

                        @error('title')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- DESCRIPTION --}}
                    <div class="mb-5">

                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Deskripsi
                            <span class="text-gray-400">(Opsional)</span>
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            placeholder="Jelaskan ruang lingkup, tujuan, atau hasil yang diharapkan."
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- PERIOD --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

                        {{-- START PERIOD --}}
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
                                value="{{ old('period_start') }}"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('period_start')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- END PERIOD --}}
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
                                value="{{ old('period_end') }}"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('period_end')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                    <p class="-mt-3 mb-5 text-xs text-gray-500">
                        Jika Project memiliki tanggal mulai atau selesai, tanggal tersebut akan
                        diisi otomatis dan dikunci. Tanggal yang belum ditentukan di Project
                        tetap dapat diisi di sini.
                    </p>


                    {{-- STATUS --}}
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
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option
                                value="active"
                                {{ old('status', 'active') === 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="pending"
                                {{ old('status') === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="completed"
                                {{ old('status') === 'completed' ? 'selected' : '' }}
                            >
                                Completed
                            </option>

                            <option
                                value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                        <p class="text-xs text-gray-500 mt-1">
                            Status awal Target dapat disesuaikan
                            dengan kondisi pekerjaan.
                        </p>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- BUTTON --}}
                    <div class="flex items-center justify-end gap-3">

                        <a
                            href="{{ route('kepala_departemen.targets.index') }}"
                            class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition"
                        >
                            Simpan Target
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- PROJECT → DIVISION AND PERIOD SCRIPT --}}

<script>
    const projects = @json($projectData);

    const projectSelect = document.getElementById('project_id');
    const divisionSelect = document.getElementById('division_id');
    const periodStartInput = document.getElementById('period_start');
    const periodEndInput = document.getElementById('period_end');

    const projectInfo = document.getElementById('project-info');
    const selectedProjectName =
        document.getElementById('selected-project-name');

    const selectedProjectDivisions =
        document.getElementById('selected-project-divisions');

    let oldDivisionId = @json(old('division_id', ''));

    function resetDivisionOptions(message = '-- Pilih Divisi --') {
        divisionSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = message;

        divisionSelect.appendChild(placeholder);
    }

    function showAllDivisions() {
        resetDivisionOptions();

        divisionSelect.disabled = false;

        @foreach ($divisions as $division)
            {
                const option = document.createElement('option');

                option.value = @json((string) $division->id);
                option.textContent = @json($division->name);

                if (String(option.value) === String(oldDivisionId)) {
                    option.selected = true;
                }

                divisionSelect.appendChild(option);
            }
        @endforeach

        projectInfo.classList.add('hidden');
    }

    function syncProjectPeriod(project) {
        const projectStartDate = project ? (project.start_date || '') : null;
        const projectEndDate = project ? (project.end_date || '') : null;

        if (project) {
            periodStartInput.value = projectStartDate;
            periodEndInput.value = projectEndDate;
        }

        periodStartInput.readOnly = Boolean(projectStartDate);
        periodEndInput.readOnly = Boolean(projectEndDate);

        periodStartInput.classList.toggle('bg-gray-100', periodStartInput.readOnly);
        periodEndInput.classList.toggle('bg-gray-100', periodEndInput.readOnly);
    }

    function showProjectDivisions(project) {
        syncProjectPeriod(project);
        resetDivisionOptions();

        const divisions = project.divisions || [];

        divisionSelect.disabled = divisions.length === 0;

        if (divisions.length === 0) {
            resetDivisionOptions('-- Belum ada divisi --');
        }

        divisions.forEach(function (division) {
            const option = document.createElement('option');

            option.value = String(division.id);
            option.textContent = division.name;

            if (String(division.id) === String(oldDivisionId)) {
                option.selected = true;
            }

            divisionSelect.appendChild(option);
        });

        selectedProjectName.textContent = project.name || '-';

        selectedProjectDivisions.textContent = divisions.length > 0
            ? 'Divisi terlibat: ' +
                divisions.map(function (division) {
                    return division.name;
                }).join(', ')
            : 'Belum ada divisi yang terlibat dalam Project ini.';

        projectInfo.classList.remove('hidden');
    }

    function updateDivisionOptions() {
        const projectId = projectSelect.value;

        if (!projectId) {
            syncProjectPeriod(null);
            showAllDivisions();
            return;
        }

        const project = projects.find(function (item) {
            return String(item.id) === String(projectId);
        });

        if (!project) {
            syncProjectPeriod(null);
            resetDivisionOptions('-- Divisi tidak tersedia --');
            divisionSelect.disabled = true;
            projectInfo.classList.add('hidden');
            return;
        }

        showProjectDivisions(project);
    }

    projectSelect.addEventListener('change', function () {
        oldDivisionId = '';
        updateDivisionOptions();
    });

    // Jalankan langsung setelah elemen form tersedia.
    updateDivisionOptions();
</script>

</x-app-layout>
