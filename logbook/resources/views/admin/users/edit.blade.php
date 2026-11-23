<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                Edit & Approval User: {{ $user->name }}
            </h2>
            <a
                href="{{ route('admin.users.index') }}"
                class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1 transition"
            >
                &larr; Kembali ke Daftar User
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                {{-- NOTIFIKASI OTOMATIS LOGOUT --}}
                <div class="mb-5 p-3.5 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900 flex items-start gap-2.5 leading-relaxed">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <span class="font-bold">Mekanisme Keamanan Sesi:</span>
                        Menyimpan perubahan role atau status akun akan otomatis <strong>me-logout sesi pengguna ini</strong> jika sedang aktif login di perangkat mana pun. Pengguna harus login kembali untuk masuk ke dashboard barunya.
                    </div>
                </div>

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
                        <label for="name" class="block mb-2 font-medium text-gray-700">
                            Nama Lengkap <span class="text-red-500">*</span>
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
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-4">
                        <label for="email" class="block mb-2 font-medium text-gray-700">
                            Email <span class="text-red-500">*</span>
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
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- STATUS PERSETUJUAN (APPROVAL) --}}
                    <div class="mb-5 p-4 rounded-xl bg-amber-50/70 border border-amber-200">
                        <label for="status" class="block mb-1.5 font-bold text-gray-800">
                            Status Persetujuan Akun (Approval) <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-500 mb-2.5">
                            Ubah menjadi "Disetujui (Approved)" untuk mengaktifkan akun dan memberikan hak akses menu.
                        </p>

                        <select
                            name="status"
                            id="status"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 font-semibold"
                            required
                        >
                            <option value="approved" @selected(old('status', $user->status ?? 'approved') === 'approved') class="text-emerald-700 font-bold">
                                &#x2714; Disetujui (Approved) - Aktif
                            </option>
                            <option value="pending" @selected(old('status', $user->status) === 'pending') class="text-amber-700 font-semibold">
                                &#x23F3; Menunggu Persetujuan (Pending)
                            </option>
                            <option value="rejected" @selected(old('status', $user->status) === 'rejected') class="text-red-700 font-semibold">
                                &#x2716; Ditolak (Rejected)
                            </option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ROLE SELECTION (TERSTRUKTUR) --}}
                    <div class="mb-5 p-4 rounded-xl bg-gray-50 border border-gray-200">
                        <label for="role-select" class="block mb-1.5 font-bold text-gray-800">
                            Role Pengguna <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-500 mb-3">
                            Pilih jenis role: PIC (Gudang/Lab), Kepala Departemen, Kepala Divisi, atau Karyawan Lapangan.
                        </p>

                        <select
                            name="role"
                            id="role-select"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 font-medium"
                            required
                        >
                            <option value="">-- Pilih Role --</option>

                            <optgroup label="1. PIC Gudang & Lab (Warehouse)">
                                <option value="pic_gudang" @selected(old('role', $user->role) === 'pic_gudang')>
                                    PIC Gudang (General Affair)
                                </option>
                                <option value="pic_lab" @selected(old('role', $user->role) === 'pic_lab')>
                                    PIC Lab (Riset dan Inovasi)
                                </option>
                            </optgroup>

                            <optgroup label="2. Struktural Organisasi">
                                <option value="kepala_departemen" @selected(old('role', $user->role) === 'kepala_departemen')>
                                    Kepala Departemen
                                </option>
                                <option value="kepala_divisi" @selected(old('role', $user->role) === 'kepala_divisi')>
                                    Kepala Divisi
                                </option>
                            </optgroup>

                            <optgroup label="3. Operasional">
                                <option value="employee" @selected(old('role', $user->role) === 'employee')>
                                    Karyawan / Employee
                                </option>
                            </optgroup>

                            <optgroup label="4. Sistem & Eksekutif">
                                <option value="pimpinan" @selected(old('role', $user->role) === 'pimpinan')>
                                    Pimpinan / Direktur
                                </option>
                                <option value="admin" @selected(old('role', $user->role) === 'admin')>
                                    Admin Sistem Logbook
                                </option>
                            </optgroup>
                        </select>

                        {{-- Role Hint Badges --}}
                        <div id="role-hint" class="mt-2.5 hidden">
                            <div id="hint-pic-gudang" class="p-2.5 rounded-lg bg-amber-50 border border-amber-200 text-xs text-amber-900 hidden flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                <span><strong>PIC Gudang:</strong> Mengelola stok & gudang General Affair pada Warehouse (:8002).</span>
                            </div>
                            <div id="hint-pic-lab" class="p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 hidden flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                <span><strong>PIC Lab:</strong> Mengelola bahan & alat lab Riset dan Inovasi pada Warehouse (:8002).</span>
                            </div>
                            <div id="hint-kadep" class="p-2.5 rounded-lg bg-blue-50 border border-blue-200 text-xs text-blue-900 hidden flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                                <span><strong>Kepala Departemen:</strong> Membawahi seluruh divisi di departemen terpilih.</span>
                            </div>
                            <div id="hint-kadiv" class="p-2.5 rounded-lg bg-cyan-50 border border-cyan-200 text-xs text-cyan-900 hidden flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full bg-cyan-500 shrink-0"></span>
                                <span><strong>Kepala Divisi:</strong> Mengelola target, tugas divisi, dan review logbook anggotanya.</span>
                            </div>
                            <div id="hint-employee" class="p-2.5 rounded-lg bg-green-50 border border-green-200 text-xs text-green-900 hidden flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                                <span><strong>Karyawan:</strong> Mengerjakan tugas dan mencatat logbook harian.</span>
                            </div>
                        </div>

                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- DEPARTMENT --}}
                    <div class="mb-4" id="department-container">
                        <label for="department-select" class="block mb-2 font-medium text-gray-700">
                            Department
                            <span id="department-required" class="text-red-500 hidden">*</span>
                        </label>

                        <select
                            name="department_id"
                            id="department-select"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Pilih Department</option>
                            @foreach($departments as $department)
                                <option
                                    value="{{ $department->id }}"
                                    @selected((string) old('department_id', $user->department_id) === (string) $department->id)
                                >
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- DIVISION --}}
                    <div class="mb-6" id="division-container">
                        <label for="division-select" class="block mb-2 font-medium text-gray-700">
                            Division
                            <span id="division-required" class="text-red-500 hidden">*</span>
                        </label>

                        <select
                            name="division_id"
                            id="division-select"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Pilih Division</option>
                            @foreach($divisions as $division)
                                <option
                                    value="{{ $division->id }}"
                                    data-department="{{ $division->department_id }}"
                                    @selected((string) old('division_id', $user->division_id) === (string) $division->id)
                                >
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('division_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- TAHUN MASUK (JIKA BELUM ADA KODE KARYAWAN) --}}
                    @if(!$user->employee_code)
                        <div class="mb-6" id="join-year-container">
                            <label for="join_year" class="block mb-2 font-medium text-gray-700">
                                Tahun Masuk
                                <span class="text-red-500" id="join-year-required">*</span>
                                <span class="text-gray-400 text-sm font-normal">
                                    (untuk pembentukan kode karyawan otomatis)
                                </span>
                            </label>

                            <input
                                type="number"
                                name="join_year"
                                id="join_year"
                                value="{{ old('join_year', $user->join_year ?? date('Y')) }}"
                                min="2000"
                                max="{{ date('Y') + 1 }}"
                                class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                placeholder="{{ date('Y') }}"
                            >

                            <p class="text-gray-500 text-xs mt-1">
                                Format kode: <code class="bg-gray-100 px-1 rounded font-semibold text-blue-700">DDDKDYYNNN</code> (Kepala Dept) atau <code class="bg-gray-100 px-1 rounded font-semibold text-blue-700">DDDBBYYNNN</code> (Kepala Divisi & Employee).
                            </p>

                            @error('join_year')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    {{-- BUTTON --}}
                    <div class="flex gap-3 pt-4 border-t border-gray-100">
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition"
                        >
                            Simpan Perubahan & Setujui
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role-select');
    const departmentContainer = document.getElementById('department-container');
    const departmentSelect = document.getElementById('department-select');
    const departmentRequired = document.getElementById('department-required');

    const divisionContainer = document.getElementById('division-container');
    const divisionSelect = document.getElementById('division-select');
    const divisionRequired = document.getElementById('division-required');

    const joinYearContainer = document.getElementById('join-year-container');
    const joinYearInput = document.getElementById('join_year');
    const joinYearRequired = document.getElementById('join-year-required');

    const roleHint = document.getElementById('role-hint');
    const hints = {
        'pic_gudang': document.getElementById('hint-pic-gudang'),
        'pic_lab': document.getElementById('hint-pic-lab'),
        'kepala_departemen': document.getElementById('hint-kadep'),
        'kepala_divisi': document.getElementById('hint-kadiv'),
        'employee': document.getElementById('hint-employee'),
    };

    const divisionOptions = Array.from(divisionSelect.querySelectorAll('option'));

    function filterDivisions() {
        const selectedDepartment = departmentSelect.value;
        const currentDivision = "{{ old('division_id', $user->division_id) }}";
        let matched = false;

        divisionSelect.innerHTML = '';
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = selectedDepartment ? 'Pilih Division' : 'Pilih Department Dahulu';
        divisionSelect.appendChild(defaultOption);

        divisionOptions.forEach(option => {
            if (!option.value) return;
            if (option.dataset.department === selectedDepartment) {
                const clone = option.cloneNode(true);
                if (clone.value === currentDivision) {
                    clone.selected = true;
                    matched = true;
                }
                divisionSelect.appendChild(clone);
            }
        });

        if (!matched && divisionSelect.value !== '') {
            divisionSelect.value = '';
        }
    }

    function handleRoleChange() {
        const role = roleSelect.value;

        // Hint badges
        roleHint.classList.remove('hidden');
        Object.keys(hints).forEach(key => {
            if (hints[key]) hints[key].classList.add('hidden');
        });
        if (hints[role]) {
            hints[role].classList.remove('hidden');
        } else {
            roleHint.classList.add('hidden');
        }

        // PIC GUDANG & PIC LAB
        if (role === 'pic_gudang' || role === 'pic_lab') {
            departmentContainer.classList.remove('hidden');
            departmentRequired.classList.remove('hidden');
            departmentSelect.required = false;

            if (role === 'pic_gudang' && !departmentSelect.value) {
                Array.from(departmentSelect.options).forEach(opt => {
                    if (opt.text.toLowerCase().includes('general')) opt.selected = true;
                });
            } else if (role === 'pic_lab' && !departmentSelect.value) {
                Array.from(departmentSelect.options).forEach(opt => {
                    if (opt.text.toLowerCase().includes('riset')) opt.selected = true;
                });
            }

            divisionContainer.classList.add('hidden');
            divisionSelect.value = '';
            divisionSelect.required = false;

            if (joinYearContainer) {
                joinYearContainer.classList.add('hidden');
                if (joinYearInput) joinYearInput.required = false;
            }
            return;
        }

        // KEPALA DEPARTEMEN
        if (role === 'kepala_departemen') {
            departmentContainer.classList.remove('hidden');
            departmentRequired.classList.remove('hidden');
            departmentSelect.required = true;

            divisionContainer.classList.add('hidden');
            divisionSelect.value = '';
            divisionSelect.required = false;

            if (joinYearContainer) {
                joinYearContainer.classList.remove('hidden');
                if (joinYearRequired) joinYearRequired.classList.remove('hidden');
                if (joinYearInput) joinYearInput.required = true;
            }

            filterDivisions();
            return;
        }

        // KEPALA DIVISI & EMPLOYEE
        if (role === 'kepala_divisi' || role === 'employee') {
            departmentContainer.classList.remove('hidden');
            departmentRequired.classList.remove('hidden');
            departmentSelect.required = true;

            divisionContainer.classList.remove('hidden');
            divisionRequired.classList.remove('hidden');
            divisionSelect.required = true;

            if (joinYearContainer) {
                joinYearContainer.classList.remove('hidden');
                if (joinYearRequired) joinYearRequired.classList.remove('hidden');
                if (joinYearInput) joinYearInput.required = true;
            }

            filterDivisions();
            return;
        }

        // ADMIN & PIMPINAN
        departmentContainer.classList.add('hidden');
        departmentRequired.classList.add('hidden');
        departmentSelect.value = '';
        departmentSelect.required = false;

        divisionContainer.classList.add('hidden');
        divisionRequired.classList.add('hidden');
        divisionSelect.value = '';
        divisionSelect.required = false;

        if (joinYearContainer) {
            joinYearContainer.classList.add('hidden');
            if (joinYearInput) joinYearInput.required = false;
        }
    }

    roleSelect.addEventListener('change', handleRoleChange);
    departmentSelect.addEventListener('change', filterDivisions);

    // Initial run
    handleRoleChange();
});
</script>

</x-app-layout>
