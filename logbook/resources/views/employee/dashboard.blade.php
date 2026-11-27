<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800">
            Dashboard Karyawan
        </h2>

    </x-slot>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- STATISTIK --}}

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


                {{-- TOTAL LOGBOOK --}}

                <div class="bg-white shadow rounded-lg p-6">

                    <p class="text-gray-500">
                        Total Logbook Saya
                    </p>

                    <h3 class="text-3xl font-bold mt-2">

                        {{ $totalLogbooks }}

                    </h3>

                </div>


                {{-- PENDING --}}

                <div class="bg-white shadow rounded-lg p-6">

                    <p class="text-gray-500">
                        Menunggu Review
                    </p>

                    <h3 class="text-3xl font-bold mt-2">

                        {{ $pendingLogbooks }}

                    </h3>

                </div>


            {{-- APPROVED --}}

            <div class="bg-white shadow rounded-lg p-6">

                <p class="text-gray-500">
                    Sudah Disetujui
                </p>

                <h3 class="text-3xl font-bold mt-2">

                    {{ $approvedLogbooks }}

                </h3>

            </div>

            </div>


            {{-- LOGBOOK TERBARU --}}

            <div class="bg-white shadow rounded-lg p-6 mt-6">


                <div class="flex justify-between items-center mb-4">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Logbook Terbaru
                    </h3>

                    <a
                        href="{{ route('employee.logbooks.index') }}"
                        class="text-xs font-semibold text-[#054439] hover:text-[#032e26] hover:underline transition"
                    >
                        Lihat Semua
                    </a>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full">


                        <thead>

                            <tr class="border-b">

                                <th class="text-left p-3">

                                    Tanggal

                                </th>

                                <th class="text-left p-3">

                                    Kegiatan

                                </th>

                                <th class="text-left p-3">

                                    Status

                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($latestLogbooks as $logbook)

                                <tr class="border-b">

                                    <td class="p-3">

                                        {{ $logbook->date }}

                                    </td>


                                    <td class="p-3">

                                        {{ Str::limit(
                                            $logbook->activity,
                                            80
                                        ) }}

                                    </td>


                                    <td class="p-3">

            @if($logbook->status === 'pending')

                <span class="text-yellow-600">
                    Menunggu Review
                </span>

            @elseif($logbook->status === 'revision')

                <span class="text-red-600">
                    Perlu Revisi
                </span>

            @elseif($logbook->status === 'approved')

                <span class="text-green-600">
                    Disetujui
                </span>

            @else

                <span class="text-gray-600">
                    {{ ucfirst($logbook->status) }}
                </span>

            @endif
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="text-center p-5 text-gray-500"
                                    >

                                        Belum ada logbook.

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