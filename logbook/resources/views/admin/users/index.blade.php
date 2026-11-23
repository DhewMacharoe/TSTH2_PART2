<x-app-layout>

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<x-slot name="header">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h2 class="font-semibold text-xl text-gray-800">
                Manajemen User & Approval Role
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">
                Kelola hak akses, persetujuan pendaftar baru, dan pembagian divisi kerja.
            </p>
        </div>

        <a
            href="{{ route('admin.users.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition flex items-center gap-1.5"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah User
        </a>
    </div>
</x-slot>


{{-- ========================================================= --}}
{{-- CONTENT --}}
{{-- ========================================================= --}}

<div class="py-10">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

        {{-- ================================================= --}}
        {{-- FLASH MESSAGES --}}
        {{-- ================================================= --}}

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2.5 shadow-sm">
                <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2.5 shadow-sm">
                <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>{{ session('warning') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2.5 shadow-sm">
                <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ================================================= --}}
        {{-- ALERT BANNER: MENUNGGU PERSETUJUAN --}}
        {{-- ================================================= --}}

        @if($pendingCount > 0)
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-300 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-lg shadow-sm animate-pulse">
                        {{ $pendingCount }}
                    </div>
                    <div>
                        <h3 class="font-bold text-amber-900 text-sm sm:text-base">
                            Ada {{ $pendingCount }} Akun Pendaftar Baru Menunggu Persetujuan
                        </h3>
                        <p class="text-xs text-amber-700 mt-0.5">
                            Pendaftar baru otomatis terdaftar sebagai Karyawan (Pending). Klik tombol <strong>Setujui / Atur Role</strong> untuk mengaktifkan akun mereka.
                        </p>
                    </div>
                </div>

                <a
                    href="{{ route('admin.users.index', ['status' => 'pending']) }}"
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg shadow-sm transition shrink-0"
                >
                    Lihat Akun Pending &rarr;
                </a>
            </div>
        @endif


        {{-- ================================================= --}}
        {{-- FILTER TABS --}}
        {{-- ================================================= --}}

        <div class="flex items-center gap-2 border-b border-gray-200 pb-2 overflow-x-auto text-sm">
            <a
                href="{{ route('admin.users.index') }}"
                class="px-3.5 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ empty($statusFilter) ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}"
            >
                <span>Semua User</span>
                <span class="text-xs px-1.5 py-0.5 rounded-full {{ empty($statusFilter) ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                    {{ $totalUsers }}
                </span>
            </a>

            <a
                href="{{ route('admin.users.index', ['status' => 'pending']) }}"
                class="px-3.5 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ $statusFilter === 'pending' ? 'bg-amber-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}"
            >
                <span>Menunggu Approval</span>
                @if($pendingCount > 0)
                    <span class="text-xs px-1.5 py-0.5 rounded-full font-bold {{ $statusFilter === 'pending' ? 'bg-amber-700 text-white' : 'bg-amber-100 text-amber-800' }}">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>

            <a
                href="{{ route('admin.users.index', ['status' => 'approved']) }}"
                class="px-3.5 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ $statusFilter === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}"
            >
                <span>Sudah Disetujui</span>
                <span class="text-xs px-1.5 py-0.5 rounded-full {{ $statusFilter === 'approved' ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                    {{ $approvedCount }}
                </span>
            </a>
        </div>


        {{-- ================================================= --}}
        {{-- TABLE CARD --}}
        {{-- ================================================= --}}

        <div class="bg-white shadow rounded-2xl p-6 overflow-x-auto">

            <table class="w-full text-sm">

                <thead>
                    <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="text-left py-3 px-3">No</th>
                        <th class="text-left py-3 px-3">Kode Karyawan</th>
                        <th class="text-left py-3 px-3">Nama</th>
                        <th class="text-left py-3 px-3">Email</th>
                        <th class="text-left py-3 px-3">Role</th>
                        <th class="text-left py-3 px-3">Department</th>
                        <th class="text-left py-3 px-3">Divisi</th>
                        <th class="text-center py-3 px-3">Status</th>
                        <th class="text-center py-3 px-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($users as $user)

                        <tr class="hover:bg-blue-50/40 transition {{ $user->status === 'pending' ? 'bg-amber-50/30' : '' }}">

                            {{-- NO --}}
                            <td class="py-3.5 px-3 text-gray-500 font-mono text-xs">
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            {{-- KODE KARYAWAN --}}
                            <td class="py-3.5 px-3">
                                @if($user->employee_code)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded font-mono text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 tracking-wider">
                                        {{ $user->employee_code }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

                            {{-- NAMA --}}
                            <td class="py-3.5 px-3 font-semibold text-gray-900">
                                {{ $user->name }}
                            </td>

                            {{-- EMAIL --}}
                            <td class="py-3.5 px-3 text-gray-600 font-mono text-xs">
                                {{ $user->email }}
                            </td>

                            {{-- ROLE --}}
                            <td class="py-3.5 px-3">
                                @switch($user->role)
                                    @case('pic_gudang')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                            PIC Gudang (GA)
                                        </span>
                                        @break

                                    @case('pic_lab')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            PIC Lab (RdI)
                                        </span>
                                        @break

                                    @case('kepala_departemen')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                            Kepala Departemen
                                        </span>
                                        @break

                                    @case('kepala_divisi')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-800 border border-cyan-200">
                                            Kepala Divisi
                                        </span>
                                        @break

                                    @case('employee')
                                    @case('karyawan')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            Karyawan
                                        </span>
                                        @break

                                    @case('pimpinan')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                            Pimpinan
                                        </span>
                                        @break

                                    @case('admin')
                                    @case('web_admin')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                            Admin
                                        </span>
                                        @break

                                    @default
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            {{ $user->role }}
                                        </span>
                                @endswitch
                            </td>

                            {{-- DEPARTMENT --}}
                            <td class="py-3.5 px-3 text-gray-700">
                                {{ $user->department?->name ?? '-' }}
                            </td>

                            {{-- DIVISION --}}
                            <td class="py-3.5 px-3 text-gray-700">
                                {{ $user->divisionRelation?->name ?? '-' }}
                            </td>

                            {{-- STATUS APPROVAL --}}
                            <td class="py-3.5 px-3 text-center">
                                @if(($user->status ?? 'approved') === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Disetujui
                                    </span>
                                @elseif($user->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 animate-pulse">
                                        <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">

                                    {{-- TOMBOL CEPAT APPROVE (JIKA STATUS PENDING) --}}
                                    @if(($user->status ?? 'approved') === 'pending')
                                        <button
                                            type="button"
                                            onclick="openApproveModal({{ json_encode([
                                                'id' => $user->id,
                                                'name' => $user->name,
                                                'email' => $user->email,
                                                'role' => $user->role,
                                                'department_id' => $user->department_id,
                                                'division_id' => $user->division_id,
                                                'join_year' => $user->join_year ?? date('Y')
                                            ]) }})"
                                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm transition flex items-center gap-1"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Setujui
                                        </button>
                                    @endif

                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        class="bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1.5 rounded-lg border border-blue-200 transition"
                                    >
                                        Edit
                                    </a>

                                    {{-- HAPUS --}}
                                    @if($user->id !== auth()->id())
                                        <form
                                            action="{{ route('admin.users.destroy', $user) }}"
                                            method="POST"
                                            class="inline delete-user-form"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                onclick="openDeleteModal(this)"
                                                class="bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1.5 rounded-lg border border-red-200 transition"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="text-center py-12 text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Belum ada data pengguna ditemukan.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            @if($users->hasPages())
                <div class="mt-6 border-t border-gray-100 pt-4">
                    {{ $users->links() }}
                </div>
            @endif

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL CEPAT APPROVE & ATUR ROLE --}}
{{-- ========================================================= --}}

<div
    id="approveModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto"
>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative">
        <button
            type="button"
            onclick="closeApproveModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 text-lg">Persetujuan & Penetapan Role</h3>
                <p class="text-xs text-gray-500">Tentukan role dan divisi sebelum menyetujui akun.</p>
            </div>
        </div>

        <div class="mb-4 p-3 rounded-lg bg-gray-50 border border-gray-200 text-xs">
            <div class="font-semibold text-gray-800" id="modalUserName">-</div>
            <div class="text-gray-500 font-mono" id="modalUserEmail">-</div>
        </div>

        <form id="approveForm" method="POST" action="">
            @csrf

            {{-- ROLE --}}
            <div class="mb-4">
                <label for="modalRole" class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Role Pengguna <span class="text-red-500">*</span>
                </label>
                <select
                    name="role"
                    id="modalRole"
                    class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 text-sm font-medium"
                    required
                >
                    <option value="">-- Pilih Role --</option>
                    <optgroup label="PIC Gudang & Lab">
                        <option value="pic_gudang">PIC Gudang (General Affair)</option>
                        <option value="pic_lab">PIC Lab (Riset dan Inovasi)</option>
                    </optgroup>
                    <optgroup label="Struktural">
                        <option value="kepala_departemen">Kepala Departemen</option>
                        <option value="kepala_divisi">Kepala Divisi</option>
                    </optgroup>
                    <optgroup label="Operasional">
                        <option value="employee">Karyawan / Employee</option>
                    </optgroup>
                    <optgroup label="Sistem">
                        <option value="pimpinan">Pimpinan / Direktur</option>
                        <option value="admin">Administrator Logbook</option>
                    </optgroup>
                </select>
            </div>

            {{-- DEPARTMENT --}}
            <div class="mb-4" id="modalDeptContainer">
                <label for="modalDept" class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Department <span id="modalDeptRequired" class="text-red-500">*</span>
                </label>
                <select
                    name="department_id"
                    id="modalDept"
                    class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 text-sm"
                >
                    <option value="">Pilih Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- DIVISION --}}
            <div class="mb-4" id="modalDivContainer">
                <label for="modalDiv" class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Division <span id="modalDivRequired" class="text-red-500">*</span>
                </label>
                <select
                    name="division_id"
                    id="modalDiv"
                    class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 text-sm"
                >
                    <option value="">Pilih Division</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" data-department="{{ $division->department_id }}">
                            {{ $division->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TAHUN MASUK --}}
            <div class="mb-5" id="modalYearContainer">
                <label for="modalYear" class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Tahun Masuk (Kode Karyawan) <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    name="join_year"
                    id="modalYear"
                    value="{{ date('Y') }}"
                    min="2000"
                    max="{{ date('Y') + 1 }}"
                    class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 text-sm"
                >
            </div>

            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-800 mb-5 leading-relaxed">
                <strong>Info Keamanan Sesi:</strong> Menyimpan persetujuan ini akan otomatis me-logout akun pengguna jika sedang aktif login.
            </div>

            <div class="flex justify-end gap-2.5 pt-3 border-t border-gray-100">
                <button
                    type="button"
                    onclick="closeApproveModal()"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition"
                >
                    Setujui Akun Sekarang
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ========================================================= --}}
{{-- DELETE CONFIRMATION MODAL --}}
{{-- ========================================================= --}}

<div
    id="deleteModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4"
>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <div class="flex justify-center mb-4">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 2.64h15.58a2 2 0 001.74-2.64l-7.82-14a2 2 0 00-3.42 0z" />
                </svg>
            </div>
        </div>

        <h2 class="text-lg font-bold text-gray-800 text-center">Hapus Pengguna?</h2>
        <p class="text-gray-500 text-center mt-1.5 text-xs leading-relaxed">
            Apakah Anda yakin ingin menghapus akun ini? Data logbook dan aktivitas terkait mungkin terpengaruh.
        </p>

        <div class="flex justify-center gap-2.5 mt-5">
            <button
                type="button"
                onclick="closeDeleteModal()"
                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-xs font-medium hover:bg-gray-100 transition"
            >
                Batal
            </button>
            <button
                type="button"
                onclick="confirmDeleteUser()"
                class="px-5 py-2 rounded-lg bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition"
            >
                Ya, Hapus
            </button>
        </div>
    </div>
</div>


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>
    let deleteForm = null;

    // Delete Modal
    function openDeleteModal(button) {
        deleteForm = button.closest('.delete-user-form');
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        deleteForm = null;
    }

    function confirmDeleteUser() {
        if (deleteForm) {
            deleteForm.submit();
        }
    }

    // Approve Modal
    const approveModal = document.getElementById('approveModal');
    const approveForm = document.getElementById('approveForm');
    const modalRole = document.getElementById('modalRole');
    const modalDept = document.getElementById('modalDept');
    const modalDeptContainer = document.getElementById('modalDeptContainer');
    const modalDiv = document.getElementById('modalDiv');
    const modalDivContainer = document.getElementById('modalDivContainer');
    const modalYearContainer = document.getElementById('modalYearContainer');
    const modalYear = document.getElementById('modalYear');

    const modalDivOptions = Array.from(modalDiv.querySelectorAll('option'));

    function filterModalDivisions() {
        const selectedDept = modalDept.value;
        const currentDiv = modalDiv.value;
        modalDiv.innerHTML = '';
        const def = document.createElement('option');
        def.value = '';
        def.textContent = selectedDept ? 'Pilih Division' : 'Pilih Department Dahulu';
        modalDiv.appendChild(def);

        modalDivOptions.forEach(opt => {
            if (!opt.value) return;
            if (opt.dataset.department === selectedDept) {
                const clone = opt.cloneNode(true);
                if (clone.value === currentDiv) clone.selected = true;
                modalDiv.appendChild(clone);
            }
        });
    }

    function handleModalRoleChange() {
        const role = modalRole.value;

        if (role === 'pic_gudang' || role === 'pic_lab') {
            modalDeptContainer.classList.remove('hidden');
            if (role === 'pic_gudang') {
                Array.from(modalDept.options).forEach(opt => {
                    if (opt.text.toLowerCase().includes('general')) opt.selected = true;
                });
            } else if (role === 'pic_lab') {
                Array.from(modalDept.options).forEach(opt => {
                    if (opt.text.toLowerCase().includes('riset')) opt.selected = true;
                });
            }
            modalDivContainer.classList.add('hidden');
            modalDiv.value = '';
            modalYearContainer.classList.add('hidden');
            return;
        }

        if (role === 'kepala_departemen') {
            modalDeptContainer.classList.remove('hidden');
            modalDept.required = true;
            modalDivContainer.classList.add('hidden');
            modalDiv.value = '';
            modalYearContainer.classList.remove('hidden');
            modalYear.required = true;
            return;
        }

        if (role === 'kepala_divisi' || role === 'employee') {
            modalDeptContainer.classList.remove('hidden');
            modalDept.required = true;
            modalDivContainer.classList.remove('hidden');
            modalDiv.required = true;
            modalYearContainer.classList.remove('hidden');
            modalYear.required = true;
            filterModalDivisions();
            return;
        }

        // Admin & Pimpinan
        modalDeptContainer.classList.add('hidden');
        modalDivContainer.classList.add('hidden');
        modalYearContainer.classList.add('hidden');
    }

    modalRole.addEventListener('change', handleModalRoleChange);
    modalDept.addEventListener('change', filterModalDivisions);

    function openApproveModal(user) {
        document.getElementById('modalUserName').textContent = user.name;
        document.getElementById('modalUserEmail').textContent = user.email;
        approveForm.action = `/admin/users/${user.id}/approve`;

        modalRole.value = user.role || 'employee';
        modalDept.value = user.department_id || '';
        filterModalDivisions();
        modalDiv.value = user.division_id || '';
        modalYear.value = user.join_year || new Date().getFullYear();

        handleModalRoleChange();

        approveModal.classList.remove('hidden');
        approveModal.classList.add('flex');
    }

    function closeApproveModal() {
        approveModal.classList.add('hidden');
        approveModal.classList.remove('flex');
    }

    document.addEventListener('click', function(event) {
        if (event.target === approveModal) closeApproveModal();
        if (event.target === document.getElementById('deleteModal')) closeDeleteModal();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeApproveModal();
            closeDeleteModal();
        }
    });
</script>

</x-app-layout>
