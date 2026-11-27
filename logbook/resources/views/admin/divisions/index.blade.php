<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800">
                    Manajemen Divisi
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Departemen: {{ $department->name }}
                </p>
            </div>

            <a
                href="{{ route('admin.departments.divisions.create', $department) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
            >
                + Tambah Divisi
            </a>

        </div>
    </x-slot>


    <div class="py-10">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">


            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))

                <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">

                    <svg
                        class="w-5 h-5 flex-shrink-0"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>

                    {{ session('success') }}

                </div>

            @endif


            {{-- ERROR MESSAGE --}}
            @if(session('error'))

                <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">

                    <svg
                        class="w-5 h-5 flex-shrink-0"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>

                    {{ session('error') }}

                </div>

            @endif


            {{-- VALIDATION ERROR --}}
            @if($errors->any())

                <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">

                    <svg
                        class="w-5 h-5 flex-shrink-0"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 012-16 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0l-1-9a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>

                    {{ $errors->first() }}

                </div>

            @endif


            {{-- DEPARTMENT INFORMATION --}}
            <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100">

                <div class="flex items-center gap-4">

                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">

                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                            />
                        </svg>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Departemen
                        </p>

                        <h3 class="text-xl font-semibold text-gray-800">
                            {{ $department->name }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Kelola Divisi yang berada di dalam Departemen ini.
                        </p>

                    </div>

                </div>

            </div>


            {{-- DIVISION TABLE --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-100">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Daftar Divisi
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Divisi yang terdaftar pada {{ $department->name }}.
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-gray-50 border-b border-gray-100">

                            <tr>

                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    No
                                </th>

                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Nama Divisi
                                </th>

                                <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Pengguna
                                </th>

                                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($divisions as $division)

                                <tr class="hover:bg-gray-50 transition">


                                    {{-- NO --}}
                                    <td class="px-6 py-4 text-sm text-gray-500">

                                        {{ $divisions->firstItem() + $loop->index }}

                                    </td>


                                    {{-- NAMA DIVISION --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">

                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                                    />
                                                </svg>

                                            </div>

                                            <span class="font-medium text-gray-800">
                                                {{ $division->name }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- USER COUNT --}}
                                    <td class="px-6 py-4 text-center">

                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full
                                            {{ $division->users_count > 0
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-gray-100 text-gray-500'
                                            }}
                                            text-sm font-semibold"
                                        >
                                            {{ $division->users_count }}
                                        </span>

                                    </td>


                                    {{-- AKSI --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center justify-end gap-2">


                                            {{-- EDIT --}}
                                            <a
                                                href="{{ route(
                                                    'admin.departments.divisions.edit',
                                                    [$department, $division]
                                                ) }}"
                                                class="inline-flex items-center px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50 border border-blue-200 rounded-lg transition"
                                            >
                                                Edit
                                            </a>


                                            {{-- DELETE --}}
                                            @if($division->users_count === 0)

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.departments.divisions.destroy',
                                                        [$department, $division]
                                                    ) }}"
                                                    onsubmit="return confirm('Hapus Divisi {{ addslashes($division->name) }}?')"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 border border-red-200 rounded-lg transition"
                                                    >

                                                        <svg
                                                            class="w-4 h-4"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                            />
                                                        </svg>

                                                        Hapus

                                                    </button>

                                                </form>

                                            @else

                                                <button
                                                    type="button"
                                                    disabled
                                                    title="Divisi masih digunakan oleh {{ $division->users_count }} user"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed"
                                                >

                                                    Hapus

                                                </button>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="4"
                                        class="px-6 py-12 text-center"
                                    >

                                        <svg
                                            class="w-10 h-10 mx-auto text-gray-300 mb-3"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011 1v5m-4 0h4"
                                        />

                                        <p class="text-gray-400 text-sm">
                                            Belum ada Divisi pada Departemen ini.
                                        </p>

                                        <a
                                            href="{{ route(
                                                'admin.departments.divisions.create',
                                                $department
                                            ) }}"
                                            class="inline-block mt-3 text-blue-600 hover:text-blue-700 font-medium text-sm"
                                        >
                                            + Tambahkan Divisi
                                        </a>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                @if($divisions->hasPages())

                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $divisions->links() }}
                    </div>

                @endif

            </div>


            {{-- BACK --}}
            <div>

            <div>
                <a
                    href="{{ route('admin.departments.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2
                        bg-blue-600 hover:bg-blue-700
                        text-white rounded-lg font-medium transition"
                >
                     Kembali ke Manajemen Departemen
                </a>
            </div>

            </div>


        </div>

    </div>

</x-app-layout>