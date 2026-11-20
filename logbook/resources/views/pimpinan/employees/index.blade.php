<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">
                    Monitoring Karyawan
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Memantau pekerjaan dan aktivitas logbook karyawan.
                </p>
            </div>


            {{-- Daftar Karyawan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Daftar Karyawan
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Data karyawan berdasarkan departemen dan divisi.
                    </p>
                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-gray-50">
                            <tr>

                                <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                    Karyawan
                                </th>

                                <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                    Departemen
                                </th>

                                <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                    Divisi
                                </th>

                                <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                    Pekerjaan
                                </th>

                                <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                    Logbook
                                </th>

                            </tr>
                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse ($employees as $employee)

                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Karyawan --}}
                                    <td class="px-6 py-4">

                                        <p class="font-semibold text-gray-800">
                                            {{ $employee->name }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $employee->email }}
                                        </p>

                                    </td>


                                    {{-- Departemen --}}
                                    <td class="px-6 py-4 text-gray-600">

                                        {{ $employee->department?->name ?? '-' }}

                                    </td>


                                    {{-- Divisi --}}
                                    <td class="px-6 py-4 text-gray-600">

                                        {{ $employee->divisionRelation?->name ?? '-' }}

                                    </td>


                                    {{-- Jumlah Pekerjaan --}}
                                    <td class="px-6 py-4 text-center">

                                        <span class="inline-flex items-center justify-center min-w-8 h-8 px-2 rounded-lg bg-blue-50 text-blue-700 font-semibold">

                                            {{ $employee->assigned_tasks_count }}

                                        </span>

                                    </td>


                                    {{-- Jumlah Logbook --}}
                                    <td class="px-6 py-4 text-center">

                                        <span class="inline-flex items-center justify-center min-w-8 h-8 px-2 rounded-lg bg-green-50 text-green-700 font-semibold">

                                            {{ $employee->logbooks_count }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="px-6 py-10 text-center text-sm text-gray-400"
                                    >
                                        Belum terdapat data karyawan.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>