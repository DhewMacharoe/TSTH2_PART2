<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <form
                    action="{{ route('admin.users.update', $user) }}"
                    method="POST"
                    autocomplete="off"
                >
                    @csrf
                    @method('PUT')

                    {{-- KODE KARYAWAN (READ-ONLY JIKA SUDAH ADA) --}}
                    @if($user->employee_code)
                        <div class="mb-5 p-4 rounded-lg bg-blue-50 border border-blue-200">
                            <label class="block text-xs font-semibold text-blue-900 uppercase tracking-wider mb-1">
                                Kode Unik Karyawan (Permanen)
                            </label>
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-base font-bold text-blue-800 bg-white px-3 py-1 rounded border border-blue-300 shadow-sm tracking-wider">
                                    {{ $user->employee_code }}
                                </span>
                                @if($user->join_year)
                                    <span class="text-xs text-blue-700">
                                        Tahun Masuk: <span class="font-semibold">{{ $user->join_year }}</span>
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-blue-600 mt-2">
                                Kode unik bersifat permanen dan tidak dapat diubah meskipun data pengguna, departemen, atau divisi diedit.
                            </p>
                        </div>
                    @endif

                    {{-- NAMA --}}
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
                            value="{{ old('name', $user->name) }}"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                            required
                        >

                        @error('name')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- EMAIL --}}
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
                            value="{{ old('email', $user->email) }}"
                            autocomplete="off"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                            required
                        >

                        @error('email')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- ROLE --}}
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
                            <option value="">Pilih Role</option>

                            @foreach($roles as $role)
                                <option
                                    value="{{ $role->slug }}"
                                    @selected(old('role', $user->role) === $role->slug)
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


                    {{-- DEPARTMENT --}}
                    <div
                        class="mb-4"
                        id="department-container"
                    >
                        <label
                            for="department_id"
                            class="block mb-2 font-medium text-gray-700"
                        >
                            Department
                            <span
                                id="department-required">
                            </span>
                        </label>

                        <select
                            name="department_id"
                            id="department_id"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Pilih Department</option>

                            @foreach($departments as $department)
                                <option
                                    value="{{ $department->id }}"
                                    @selected(
                                        old('department_id', $user->department_id)
                                        == $department->id
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


                    {{-- DIVISION --}}
                    <div
                        class="mb-6"
                        id="division-container"
                    >
                        <label
                            for="division_id"
                            class="block mb-2 font-medium text-gray-700"
                        >
                            Division
                            <span
                                id="division-required">
                            </span>
                        </label>

                        <select
                            name="division_id"
                            id="division_id"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Pilih Division</option>

                            @foreach($divisions as $division)
                                <option
                                    value="{{ $division->id }}"
                                    data-department="{{ $division->department_id }}"
                                    @selected(
                                        old('division_id', $user->division_id)
                                        == $division->id
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


                    {{-- BUTTON --}}
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
                            Update User
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>


    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const roleSelect = document.getElementById('role-select');

            const departmentContainer =
                document.getElementById('department-container');

            const departmentSelect =
                document.getElementById('department_id');

            const divisionContainer =
                document.getElementById('division-container');

            const divisionSelect =
                document.getElementById('division_id');

            const departmentRequired =
                document.getElementById('department-required');

            const divisionRequired =
                document.getElementById('division-required');


            /*
             * Simpan semua option Division
             * agar bisa difilter berdasarkan Department.
             */
            const divisionOptions =
                Array.from(divisionSelect.options).slice(1);


            function updateDivisionOptions() {

                const selectedDepartment =
                    departmentSelect.value;

                const currentDivision =
                    "{{ old('division_id', $user->division_id) }}";


                // Reset division
                divisionSelect.innerHTML =
                    '<option value="">Pilih Division</option>';


                divisionOptions.forEach(function (option) {

                    if (
                        selectedDepartment !== '' &&
                        option.dataset.department === selectedDepartment
                    ) {

                        const newOption =
                            option.cloneNode(true);

                        divisionSelect.appendChild(newOption);

                    }

                });


                /*
                 * Jika sebelumnya user sudah memiliki Division
                 * dan Division tersebut masih sesuai Department,
                 * pertahankan pilihan tersebut.
                 */
                if (currentDivision !== '') {

                    const matchingOption =
                        Array.from(divisionSelect.options)
                            .find(option =>
                                option.value === currentDivision
                            );

                    if (matchingOption) {
                        divisionSelect.value = currentDivision;
                    }

                }
            }


            function updateOrganizationFields() {

                const role = roleSelect.value;


                /*
                 * ADMIN, PIMPINAN, PIC GUDANG, PIC LAB
                 *
                 * Tidak memiliki Department maupun Division.
                 */
                if (
                    role === 'admin' ||
                    role === 'pimpinan' ||
                    role === 'pic_gudang' ||
                    role === 'pic_lab'
                ) {

                    departmentContainer.style.display = 'none';
                    divisionContainer.style.display = 'none';

                    departmentSelect.value = '';
                    divisionSelect.value = '';

                    departmentSelect.required = false;
                    divisionSelect.required = false;

                    departmentRequired.classList.add('hidden');
                    divisionRequired.classList.add('hidden');

                    return;
                }


                /*
                 * KEPALA DEPARTEMEN
                 *
                 * Memiliki Department,
                 * tetapi tidak memiliki Division.
                 */
                if (role === 'kepala_departemen') {

                    departmentContainer.style.display = 'block';
                    divisionContainer.style.display = 'none';

                    divisionSelect.value = '';

                    departmentSelect.required = true;
                    divisionSelect.required = false;

                    departmentRequired.classList.remove('hidden');
                    divisionRequired.classList.add('hidden');

                    return;
                }


                /*
                 * KEPALA DIVISI & EMPLOYEE
                 *
                 * Memiliki Department dan Division.
                 */
                if (
                    role === 'kepala_divisi' ||
                    role === 'employee'
                ) {

                    departmentContainer.style.display = 'block';
                    divisionContainer.style.display = 'block';

                    departmentSelect.required = true;
                    divisionSelect.required = true;

                    departmentRequired.classList.remove('hidden');
                    divisionRequired.classList.remove('hidden');

                    updateDivisionOptions();

                    return;
                }


                /*
                 * Belum memilih role.
                 */
                departmentContainer.style.display = 'none';
                divisionContainer.style.display = 'none';

                departmentSelect.value = '';
                divisionSelect.value = '';

                departmentSelect.required = false;
                divisionSelect.required = false;

                departmentRequired.classList.add('hidden');
                divisionRequired.classList.add('hidden');
            }


            /*
             * Ketika Department berubah,
             * Division harus mengikuti Department tersebut.
             */
            departmentSelect.addEventListener('change', function () {

                divisionSelect.value = '';

                updateDivisionOptions();

            });


            /*
             * Ketika Role berubah,
             * tampilkan field yang sesuai.
             */
            roleSelect.addEventListener('change', function () {

                updateOrganizationFields();

            });


            /*
             * Jalankan ketika halaman pertama kali dibuka.
             */
            updateOrganizationFields();

        });
    </script>

</x-app-layout>