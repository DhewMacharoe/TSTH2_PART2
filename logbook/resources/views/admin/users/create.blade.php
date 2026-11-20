<x-app-layout>

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800">
        Tambah User
    </h2>
</x-slot>


{{-- ========================================================= --}}
{{-- CONTENT --}}
{{-- ========================================================= --}}

<div class="py-12">

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded-lg p-6">

            <form
                action="{{ route('admin.users.store') }}"
                method="POST"
                autocomplete="off"
            >

                @csrf


                {{-- ================================================= --}}
                {{-- NAMA --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <label
                        for="name"
                        class="block mb-2 font-medium text-gray-700"
                    >
                        Nama
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Masukkan nama user"
                        required
                    >

                    @error('name')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- EMAIL --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <label
                        for="email"
                        class="block mb-2 font-medium text-gray-700"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        value=""
                        autocomplete="off"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Masukkan email user"
                        required
                    >

                    @error('email')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- PASSWORD --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <label
                        for="password"
                        class="block mb-2 font-medium text-gray-700"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        autocomplete="new-password"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Minimal 8 karakter"
                        required
                    >

                    @error('password')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- KONFIRMASI PASSWORD --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <label
                        for="password_confirmation"
                        class="block mb-2 font-medium text-gray-700"
                    >
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Ulangi password"
                        required
                    >

                </div>


                {{-- ================================================= --}}
                {{-- ROLE --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <label
                        for="role-select"
                        class="block mb-2 font-medium text-gray-700"
                    >
                        Role
                    </label>

                    <select
                        name="role"
                        id="role-select"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        required
                    >

                        <option value="">
                            Pilih Role
                        </option>

                        @foreach($roles as $role)

                            <option
                                value="{{ $role->slug }}"
                                @selected(old('role') === $role->slug)
                            >
                                {{ $role->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('role')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- DEPARTMENT --}}
                {{-- ================================================= --}}

                <div
                    class="mb-4"
                    id="department-container"
                >

                    <label
                        for="department-select"
                        class="block mb-2 font-medium text-gray-700"
                    >
                        Department
                        <span
                            id="department-required"
                            class="text-red-500 hidden"
                        >
                            *
                        </span>
                    </label>

                    <select
                        name="department_id"
                        id="department-select"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Pilih Department
                        </option>

                        @foreach($departments as $department)

                            <option
                                value="{{ $department->id }}"
                                @selected(
                                    (string) old('department_id') ===
                                    (string) $department->id
                                )
                            >
                                {{ $department->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('department_id')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- DIVISION --}}
                {{-- ================================================= --}}

                <div
                    class="mb-6"
                    id="division-container"
                >

                    <label
                        for="division-select"
                        class="block mb-2 font-medium text-gray-700"
                    >
                        Division
                        <span
                            id="division-required"
                            class="text-red-500 hidden"
                        >
                            *
                        </span>
                    </label>

                    <select
                        name="division_id"
                        id="division-select"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Pilih Division
                        </option>

                        @foreach($divisions as $division)

                            <option
                                value="{{ $division->id }}"
                                data-department="{{ $division->department_id }}"
                                @selected(
                                    (string) old('division_id') ===
                                    (string) $division->id
                                )
                            >
                                {{ $division->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('division_id')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- TAHUN MASUK --}}
                {{-- ================================================= --}}

                <div class="mb-6" id="join-year-container">

                    <label
                        for="join_year"
                        class="block mb-2 font-medium text-gray-700"
                    >
                        Tahun Masuk
                        <span class="text-red-500" id="join-year-required">*</span>
                        <span class="text-gray-400 text-sm font-normal">
                            (untuk pembentukan kode karyawan)
                        </span>
                    </label>

                    <input
                        type="number"
                        name="join_year"
                        id="join_year"
                        value="{{ old('join_year', date('Y')) }}"
                        min="2000"
                        max="{{ date('Y') + 1 }}"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        placeholder="{{ date('Y') }}"
                    >

                    <p class="text-gray-500 text-sm mt-1">
                        Format kode: <code class="bg-gray-100 px-1 rounded text-xs font-semibold">DDDKDYYNNN</code> (Kepala Dept) atau <code class="bg-gray-100 px-1 rounded text-xs font-semibold">DDDBBYYNNN</code> (Kepala Divisi & Employee).
                    </p>

                    @error('join_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- PREVIEW KODE KARYAWAN --}}
                {{-- ================================================= --}}

                <div
                    class="mb-6 p-4 rounded-lg bg-blue-50 border border-blue-200 hidden"
                    id="code-preview-container"
                >
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-semibold text-blue-900">
                            Preview Format Kode Unik Otomatis
                        </span>
                    </div>

                    <div class="mt-2 flex items-baseline gap-3 flex-wrap">
                        <span
                            id="code-preview-badge"
                            class="font-mono text-base font-bold bg-white px-3 py-1 rounded border border-blue-300 text-blue-700 tracking-wider shadow-sm"
                        >
                            -
                        </span>
                        <span class="text-xs text-blue-700" id="code-preview-desc">
                            Nomor urut (NNN) dimulai dari 001 akan dibuat otomatis oleh backend.
                        </span>
                    </div>
                </div>


                {{-- ================================================= --}}
                {{-- BUTTON --}}
                {{-- ================================================= --}}

                <div class="flex gap-3">

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition"
                    >
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition"
                    >
                        Simpan User
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

        const roleSelect =
            document.getElementById('role-select');

        const departmentContainer =
            document.getElementById('department-container');

        const departmentSelect =
            document.getElementById('department-select');

        const departmentRequired =
            document.getElementById('department-required');

        const divisionContainer =
            document.getElementById('division-container');

        const divisionSelect =
            document.getElementById('division-select');

        const divisionRequired =
            document.getElementById('division-required');

        const joinYearContainer =
            document.getElementById('join-year-container');

        const joinYearInput =
            document.getElementById('join_year');

        const joinYearRequired =
            document.getElementById('join-year-required');

        const codePreviewContainer =
            document.getElementById('code-preview-container');

        const codePreviewBadge =
            document.getElementById('code-preview-badge');

        const codePreviewDesc =
            document.getElementById('code-preview-desc');

        const divisionOptions =
            Array.from(
                divisionSelect.querySelectorAll('option')
            );


        // =====================================================
        // HELPER PREVIEW KODE
        // =====================================================

        function getDepartmentCode(name)
        {
            if (!name) return '';
            const n = name.toLowerCase();
            if (n.includes('general')) return '01G';
            if (n.includes('riset') || n.includes('hilirisasi riset')) return '02R';
            return '';
        }

        function getDivisionCode(name)
        {
            if (!name) return '';
            const n = name.toLowerCase();
            if (n.includes('administrasi')) return 'AD';
            if (n.includes('operasional')) return 'OP';
            if (n.includes('genomic')) return 'GE';
            if (n.includes('hilirisasi')) return 'HI';
            if (n.includes('gh') || n.includes('sh')) return 'GS';
            return '';
        }

        function updateCodePreview()
        {
            const role = roleSelect.value;
            const joinYear = (joinYearInput.value || '').trim();
            const yy = joinYear.length >= 2 ? joinYear.slice(-2) : 'YY';

            const deptOption = departmentSelect.options[departmentSelect.selectedIndex];
            const deptName = deptOption && deptOption.value ? deptOption.text : '';
            const ddd = getDepartmentCode(deptName) || 'DDD';

            if (role === 'kepala_departemen')
            {
                if (ddd !== 'DDD' && joinYear.length >= 4) {
                    codePreviewBadge.textContent = `${ddd}KD${yy}NNN`;
                    codePreviewDesc.textContent = `Kode unik Kepala Departemen ${deptName} (nomor urut NNN dibuat otomatis).`;
                    codePreviewContainer.classList.remove('hidden');
                } else {
                    codePreviewContainer.classList.add('hidden');
                }
                return;
            }

            if (role === 'kepala_divisi' || role === 'employee')
            {
                const divOption = divisionSelect.options[divisionSelect.selectedIndex];
                const divName = divOption && divOption.value ? divOption.text : '';
                const bb = getDivisionCode(divName) || 'BB';

                if (ddd !== 'DDD' && bb !== 'BB' && joinYear.length >= 4) {
                    codePreviewBadge.textContent = `${ddd}${bb}${yy}NNN`;
                    const roleTitle = role === 'kepala_divisi' ? 'Kepala Divisi' : 'Karyawan';
                    codePreviewDesc.textContent = `Kode unik ${roleTitle} ${divName} (nomor urut bersama NNN dibuat otomatis).`;
                    codePreviewContainer.classList.remove('hidden');
                } else {
                    codePreviewContainer.classList.add('hidden');
                }
                return;
            }

            codePreviewContainer.classList.add('hidden');
        }


        // =====================================================
        // UPDATE FORM BERDASARKAN ROLE
        // =====================================================

        function updateForm()
        {
            const role = roleSelect.value;


            // -------------------------------------------------
            // ADMIN, PIMPINAN, PIC GUDANG, PIC LAB
            // -------------------------------------------------

            if (
                role === 'admin' ||
                role === 'pimpinan' ||
                role === 'pic_gudang' ||
                role === 'pic_lab'
            ) {

                departmentContainer.style.display = 'none';
                divisionContainer.style.display = 'none';
                joinYearContainer.style.display = 'none';

                departmentSelect.value = '';
                divisionSelect.value = '';

                departmentSelect.required = false;
                divisionSelect.required = false;
                joinYearInput.required = false;

                departmentRequired.classList.add('hidden');
                divisionRequired.classList.add('hidden');
                joinYearRequired.classList.add('hidden');

                updateCodePreview();
                return;
            }


            // -------------------------------------------------
            // KEPALA DEPARTEMEN
            // -------------------------------------------------

            if (role === 'kepala_departemen')
            {

                departmentContainer.style.display = 'block';
                divisionContainer.style.display = 'none';
                joinYearContainer.style.display = 'block';

                departmentSelect.required = true;
                divisionSelect.required = false;
                joinYearInput.required = true;

                divisionSelect.value = '';

                departmentRequired.classList.remove('hidden');
                divisionRequired.classList.add('hidden');
                joinYearRequired.classList.remove('hidden');

                updateCodePreview();
                return;
            }


            // -------------------------------------------------
            // KEPALA DIVISI & EMPLOYEE
            // -------------------------------------------------

            if (
                role === 'kepala_divisi' ||
                role === 'employee'
            )
            {

                departmentContainer.style.display = 'block';
                divisionContainer.style.display = 'block';
                joinYearContainer.style.display = 'block';

                departmentSelect.required = true;
                divisionSelect.required = true;
                joinYearInput.required = true;

                departmentRequired.classList.remove('hidden');
                divisionRequired.classList.remove('hidden');
                joinYearRequired.classList.remove('hidden');

                filterDivisions();
                updateCodePreview();
                return;
            }


            // -------------------------------------------------
            // BELUM MEMILIH ROLE
            // -------------------------------------------------

            departmentContainer.style.display = 'none';
            divisionContainer.style.display = 'none';
            joinYearContainer.style.display = 'none';

            departmentSelect.value = '';
            divisionSelect.value = '';

            departmentSelect.required = false;
            divisionSelect.required = false;
            joinYearInput.required = false;

            departmentRequired.classList.add('hidden');
            divisionRequired.classList.add('hidden');
            joinYearRequired.classList.add('hidden');

            updateCodePreview();
        }


        // =====================================================
        // FILTER DIVISION BERDASARKAN DEPARTMENT
        // =====================================================

        function filterDivisions()
        {

            const departmentId =
                departmentSelect.value;


            divisionOptions.forEach(function (option)
            {

                // Option placeholder
                if (!option.value)
                {
                    option.style.display = 'block';
                    return;
                }


                const optionDepartment =
                    option.dataset.department;


                option.style.display =
                    optionDepartment === departmentId
                        ? 'block'
                        : 'none';

            });


            // -------------------------------------------------
            // CEK DIVISION YANG SEDANG DIPILIH
            // -------------------------------------------------

            const selectedOption =
                divisionSelect.options[
                    divisionSelect.selectedIndex
                ];


            if (
                selectedOption &&
                selectedOption.value &&
                selectedOption.dataset.department !== departmentId
            )
            {
                divisionSelect.value = '';
            }

        }


        // =====================================================
        // EVENT HANDLERS
        // =====================================================

        roleSelect.addEventListener('change', updateForm);

        departmentSelect.addEventListener('change', function () {
            divisionSelect.value = '';
            filterDivisions();
            updateCodePreview();
        });

        divisionSelect.addEventListener('change', updateCodePreview);

        joinYearInput.addEventListener('input', updateCodePreview);


        // =====================================================
        // INITIALIZE
        // =====================================================

        updateForm();

    });

</script>

</x-app-layout>
