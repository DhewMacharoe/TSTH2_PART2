<x-app-layout>

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<x-slot name="header">
    <div class="flex justify-between items-center">

        <h2 class="font-semibold text-xl text-gray-800">
            Data User
        </h2>

        <a
            href="{{ route('admin.users.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition"
        >
            + Tambah User
        </a>

    </div>
</x-slot>


{{-- ========================================================= --}}
{{-- CONTENT --}}
{{-- ========================================================= --}}

<div class="py-12">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- ================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ================================================= --}}

        @if(session('success'))
            <div
                class="mb-4 bg-green-100 border border-green-200
                       text-green-700 px-4 py-3 rounded-lg"
            >
                {{ session('success') }}
            </div>
        @endif


        {{-- ================================================= --}}
        {{-- WARNING MESSAGE --}}
        {{-- ================================================= --}}

        @if(session('warning'))
            <div
                class="mb-4 bg-yellow-100 border border-yellow-200
                       text-yellow-700 px-4 py-3 rounded-lg"
            >
                {{ session('warning') }}
            </div>
        @endif


        {{-- ================================================= --}}
        {{-- ERROR MESSAGE --}}
        {{-- ================================================= --}}

        @if(session('error'))
            <div
                class="mb-4 bg-red-100 border border-red-200
                       text-red-700 px-4 py-3 rounded-lg"
            >
                {{ session('error') }}
            </div>
        @endif


        {{-- ================================================= --}}
        {{-- VALIDATION ERROR --}}
        {{-- ================================================= --}}

        @if($errors->any())
            <div
                class="mb-4 bg-red-50 border border-red-200
                       text-red-700 px-4 py-3 rounded-lg"
            >
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ================================================= --}}
        {{-- TABLE --}}
        {{-- ================================================= --}}

        <div class="bg-white shadow rounded-lg p-6 overflow-x-auto">

            <table class="w-full">

                {{-- ================================================= --}}
                {{-- TABLE HEADER --}}
                {{-- ================================================= --}}

                <thead>

                    <tr class="border-b">

                        <th class="text-left p-3">
                            No
                        </th>

                        <th class="text-left p-3">
                            Kode Karyawan
                        </th>

                        <th class="text-left p-3">
                            Nama
                        </th>

                        <th class="text-left p-3">
                            Email
                        </th>

                        <th class="text-left p-3">
                            Role
                        </th>

                        <th class="text-left p-3">
                            Department
                        </th>

                        <th class="text-left p-3">
                            Divisi
                        </th>

                        <th class="text-center p-3">
                            Aksi
                        </th>

                    </tr>

                </thead>


                {{-- ================================================= --}}
                {{-- TABLE BODY --}}
                {{-- ================================================= --}}

                <tbody>

                    @forelse($users as $user)

                        <tr class="border-b hover:bg-gray-50">

                            {{-- ===================================== --}}
                            {{-- NOMOR --}}
                            {{-- ===================================== --}}

                            <td class="p-3">
                                {{ $users->firstItem() + $loop->index }}
                            </td>


                            {{-- ===================================== --}}
                            {{-- KODE KARYAWAN --}}
                            {{-- ===================================== --}}

                            <td class="p-3">
                                @if($user->employee_code)
                                    <span
                                        class="inline-flex items-center px-2 py-1
                                               rounded font-mono text-xs font-semibold
                                               bg-blue-50 text-blue-700 border border-blue-200
                                               tracking-wider"
                                    >
                                        {{ $user->employee_code }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>


                            {{-- ===================================== --}}
                            {{-- NAMA --}}
                            {{-- ===================================== --}}

                            <td class="p-3 font-medium text-gray-800">
                                {{ $user->name }}
                            </td>


                            {{-- ===================================== --}}
                            {{-- EMAIL --}}
                            {{-- ===================================== --}}

                            <td class="p-3 text-gray-600">
                                {{ Str::before($user->email, '@') }}@gmail.com
                            </td>


                            {{-- ===================================== --}}
                            {{-- ROLE --}}
                            {{-- ===================================== --}}

                            <td class="p-3">

                                @switch($user->role)

                                    @case('admin')
                                        <span
                                            class="inline-flex items-center px-3 py-1
                                                   rounded-full text-xs font-medium
                                                   bg-purple-100 text-purple-700"
                                        >
                                            Admin
                                        </span>
                                        @break

                                    @case('pimpinan')
                                        <span
                                            class="inline-flex items-center px-3 py-1
                                                   rounded-full text-xs font-medium
                                                   bg-indigo-100 text-indigo-700"
                                        >
                                            Pimpinan
                                        </span>
                                        @break

                                    @case('kepala_departemen')
                                        <span
                                            class="inline-flex items-center px-3 py-1
                                                   rounded-full text-xs font-medium
                                                   bg-blue-100 text-blue-700"
                                        >
                                            Kepala Departemen
                                        </span>
                                        @break

                                    @case('kepala_divisi')
                                        <span
                                            class="inline-flex items-center px-3 py-1
                                                   rounded-full text-xs font-medium
                                                   bg-cyan-100 text-cyan-700"
                                        >
                                            Kepala Divisi
                                        </span>
                                        @break

                                    @case('employee')
                                        <span
                                            class="inline-flex items-center px-3 py-1
                                                   rounded-full text-xs font-medium
                                                   bg-green-100 text-green-700"
                                        >
                                            Employee
                                        </span>
                                        @break

                                    @default
                                        <span
                                            class="inline-flex items-center px-3 py-1
                                                   rounded-full text-xs font-medium
                                                   bg-gray-100 text-gray-700"
                                        >
                                            {{ $user->role }}
                                        </span>

                                @endswitch

                            </td>


                            {{-- ===================================== --}}
                            {{-- DEPARTMENT --}}
                            {{-- ===================================== --}}

                            <td class="p-3 text-gray-700">
                                {{ $user->department?->name ?? '-' }}
                            </td>


                            {{-- ===================================== --}}
                            {{-- DIVISION --}}
                            {{-- ===================================== --}}

                            <td class="p-3 text-gray-700">
                                {{ $user->divisionRelation?->name ?? '-' }}
                            </td>


                            {{-- ===================================== --}}
                            {{-- AKSI --}}
                            {{-- ===================================== --}}

                            <td class="p-3 text-center whitespace-nowrap">

                                {{-- EDIT --}}
                                <a
                                    href="{{ route('admin.users.edit', $user) }}"
                                    class="inline-block bg-yellow-500
                                           hover:bg-yellow-600
                                           text-white px-3 py-1
                                           rounded transition"
                                >
                                    Edit
                                </a>


                                {{-- DELETE --}}
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
                                            class="bg-red-600 hover:bg-red-700
                                                   text-white px-3 py-1
                                                   rounded transition"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>


                    @empty

                        {{-- ========================================= --}}
                        {{-- EMPTY DATA --}}
                        {{-- ========================================= --}}

                        <tr>

                            <td
                                colspan="8"
                                class="text-center p-8 text-gray-500"
                            >
                                Belum ada data user.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>


            {{-- ================================================= --}}
            {{-- PAGINATION --}}
            {{-- ================================================= --}}

            @if($users->hasPages())

                <div class="mt-5">
                    {{ $users->links() }}
                </div>

            @endif

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- DELETE CONFIRMATION MODAL --}}
{{-- ========================================================= --}}

<div
    id="deleteModal"
    class="fixed inset-0 z-50 hidden items-center
           justify-center bg-black/50 backdrop-blur-sm"
>

    <div
        class="bg-white rounded-2xl shadow-2xl
               w-full max-w-md mx-4 p-6"
    >

        {{-- ================================================= --}}
        {{-- ICON --}}
        {{-- ================================================= --}}

        <div class="flex justify-center mb-4">

            <div
                class="w-16 h-16 rounded-full bg-red-100
                       flex items-center justify-center"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-8 h-8 text-red-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v4m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 2.64h15.58a2 2 0 001.74-2.64l-7.82-14a2 2 0 00-3.42 0z"
                    />

                </svg>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- TITLE --}}
        {{-- ================================================= --}}

        <h2
            class="text-xl font-bold text-gray-800 text-center"
        >
            Hapus Akun?
        </h2>


        {{-- ================================================= --}}
        {{-- DESCRIPTION --}}
        {{-- ================================================= --}}

        <p
            class="text-gray-500 text-center mt-2 leading-relaxed"
        >

            Apakah kamu yakin ingin menghapus akun ini?

            <br>

            <span class="text-sm">
                Data akun yang dihapus tidak dapat dikembalikan.
            </span>

        </p>


        {{-- ================================================= --}}
        {{-- BUTTON --}}
        {{-- ================================================= --}}

        <div class="flex justify-center gap-3 mt-6">

            {{-- BATAL --}}
            <button
                type="button"
                onclick="closeDeleteModal()"
                class="px-5 py-2.5 rounded-lg
                       border border-gray-300
                       text-gray-700
                       hover:bg-gray-100
                       transition"
            >
                Batal
            </button>


            {{-- HAPUS --}}
            <button
                type="button"
                onclick="confirmDeleteUser()"
                class="px-5 py-2.5 rounded-lg
                       bg-red-600
                       text-white
                       hover:bg-red-700
                       transition"
            >
                Ya, Hapus
            </button>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- JAVASCRIPT DELETE MODAL --}}
{{-- ========================================================= --}}

<script>

    let deleteForm = null;


    // =========================================================
    // BUKA MODAL
    // =========================================================

    function openDeleteModal(button)
    {
        deleteForm = button.closest('.delete-user-form');

        const modal = document.getElementById('deleteModal');

        modal.classList.remove('hidden');

        modal.classList.add('flex');
    }


    // =========================================================
    // TUTUP MODAL
    // =========================================================

    function closeDeleteModal()
    {
        const modal = document.getElementById('deleteModal');

        modal.classList.add('hidden');

        modal.classList.remove('flex');

        deleteForm = null;
    }


    // =========================================================
    // KONFIRMASI DELETE
    // =========================================================

    function confirmDeleteUser()
    {
        if (deleteForm)
        {
            deleteForm.submit();
        }
    }


    // =========================================================
    // TUTUP KETIKA KLIK DI LUAR MODAL
    // =========================================================

    document.addEventListener('click', function(event)
    {
        const modal = document.getElementById('deleteModal');

        if (event.target === modal)
        {
            closeDeleteModal();
        }
    });


    // =========================================================
    // TUTUP DENGAN ESC
    // =========================================================

    document.addEventListener('keydown', function(event)
    {
        if (event.key === 'Escape')
        {
            closeDeleteModal();
        }
    });

</script>

</x-app-layout>
