<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800">
            Dashboard Admin
        </h2>

    </x-slot>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">


                {{-- TOTAL USER --}}

                <div class="bg-white shadow rounded-lg p-6">

                    <p class="text-gray-500">
                        Total User
                    </p>

                    <h3 class="text-3xl font-bold mt-2">

                        {{ $totalUsers }}

                    </h3>

                </div>


                {{-- TOTAL KARYAWAN --}}

                <div class="bg-white shadow rounded-lg p-6">

                    <p class="text-gray-500">
                        Total Karyawan
                    </p>

                    <h3 class="text-3xl font-bold mt-2">

                        {{ $totalEmployees }}

                    </h3>

                </div>

                 {{-- KEPALA DEPARTEMEN --}}
        <div class="bg-white shadow rounded-lg p-6">

            <p class="text-gray-500">
                Kepala Departemen
            </p>

            <h3 class="text-3xl font-bold mt-2">
                {{ $totalDepartmentHeads }}
            </h3>

        </div>



            {{-- TOTAL DEPARTEMEN --}}
            <div class="bg-white shadow rounded-lg p-6">

                <p class="text-gray-500">
                    Total Departemen
                </p>

                <h3 class="text-3xl font-bold mt-2">
                    {{ $totalDepartments }}
                </h3>

            </div>

            {{-- TOTAL DIVISI --}}
            <div class="bg-white shadow rounded-lg p-6">

                <p class="text-gray-500">
                    Total Divisi
                </p>

                <h3 class="text-3xl font-bold mt-2">
                    {{ $totalDivisions }}
                </h3>

            </div>

            </div>


            {{-- MENU CEPAT --}}

            <div class="bg-white shadow rounded-lg p-6 mt-6">

            <h3 class="text-lg font-semibold mb-4">
                Menu Manajemen
            </h3>

<div class="flex flex-wrap gap-3">

    <a
        href="{{ route('admin.users.index') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
    >
        Kelola Pengguna
    </a>

</div>            </div>


        </div>

    </div>

</x-app-layout>