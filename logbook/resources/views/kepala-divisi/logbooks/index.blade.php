<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Review Logbook Karyawan
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Pantau dan periksa laporan pekerjaan dari karyawan di divisi Anda.
                </p>
            </div>
            <a
                href="{{ route('kepala_divisi.my_logbooks.create') }}"
                class="inline-flex items-center gap-2 bg-[#054439] hover:bg-[#032e26] text-white px-4 py-2.5 rounded-xl font-semibold text-sm shadow-sm transition whitespace-nowrap"
            >
                <span class="material-symbols-rounded" style="font-size:20px;">edit_note</span>
                <span>Isi Logbook</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Ringkasan --}}
            @php
                $allLogbooks = $assignedLogbooks->concat($independentLogbooks);
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Total Logbook</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $allLogbooks->count() }}
                    </p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Menunggu Review</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">
                        {{ $allLogbooks->where('status', 'pending')->count() }}
                    </p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Perlu Revisi</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">
                        {{ $allLogbooks->where('status', 'revision')->count() }}
                    </p>
                </div>
            </div>

            {{-- Tabel Logbook dari Pekerjaan yang Ditugaskan --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-8">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-800">
                        Logbook dari Pekerjaan yang Ditugaskan
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Laporan karyawan yang terhubung dengan tugas dari Kepala Divisi.
                    </p>
                </div>

                @if ($assignedLogbooks->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="font-medium text-gray-700">
                            Belum ada logbook dari pekerjaan yang ditugaskan.
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Logbook yang terkait dengan tugas akan ditampilkan di sini.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Karyawan</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Pekerjaan</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Tanggal</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Progress</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Status</th>
                                    <th class="px-6 py-4 text-right font-semibold text-gray-600">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @foreach ($assignedLogbooks as $logbook)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-800">
                                                <span class="text-gray-500 mr-2">{{ $loop->iteration }}.</span>
                                                {{ $logbook->user->name ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $logbook->user->email ?? '-' }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-800">
                                                {{ $logbook->task->title ?? '-' }}
                                            </div>

                                            @if ($logbook->task?->target)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Scope &amp; Target:
                                                    {{ $logbook->task->target->title }}
                                                </div>
                                            @endif

                                            @if ($logbook->task?->subTarget)
                                                <div class="text-xs text-blue-600 mt-1">
                                                    Sub Target:
                                                    {{ $logbook->task->subTarget->title }}
                                                </div>
                                            @endif


                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-gray-700">
                                                {{ $logbook->date?->format('d M Y') ?? '-' }}
                                            </div>
                                            @if ($logbook->date)
                                                <div class="text-xs text-gray-400 mt-1">
                                                    {{ $logbook->date->format('H:i') }}
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 min-w-[170px]">
                                            @if (($logbook->logbook_type ?? 'assigned') === 'independent' || is_null($logbook->progress))
                                                <span class="text-xs text-gray-400 italic">Pekerjaan Mandiri</span>
                                            @else
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-xs text-gray-500">Dilaporkan</span>
                                                    <span class="text-sm font-semibold text-blue-600">
                                                        {{ $logbook->progress }}%
                                                    </span>
                                                </div>
                                                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                                    <div
                                                        class="h-full bg-blue-600 rounded-full"
                                                        style="width: {{ min(max((float) $logbook->progress, 0), 100) }}%"
                                                    ></div>
                                                </div>

                                                @if ($logbook->task?->subTarget)
                                                    <p class="text-xs text-gray-500 mt-2">
                                                        Bobot Sub Target:
                                                        {{ $logbook->task->subTarget->percentage }}%
                                                    </p>
                                                @endif
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            @php
                                                $statusClass = match ($logbook->status) {
                                                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                                    'revision' => 'bg-red-50 text-red-700 border-red-200',
                                                    'approved' => 'bg-green-50 text-green-700 border-green-200',
                                                    'reviewed' => 'bg-green-50 text-green-700 border-green-200',
                                                    default => 'bg-gray-50 text-gray-600 border-gray-200',
                                                };

                                                $statusLabel = match ($logbook->status) {
                                                    'pending' => 'Menunggu Review',
                                                    'revision' => 'Perlu Revisi',
                                                    'approved' => 'Disetujui',
                                                    'reviewed' => 'Sudah Direview',
                                                    default => ucfirst($logbook->status ?? '-'),
                                                };
                                            @endphp

                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            @if ($logbook->status === 'pending')
                                                <a href="{{ route('kepala_divisi.logbooks.show', $logbook) }}"
                                                   class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium transition">
                                                    Review
                                                </a>
                                            @elseif ($logbook->status === 'revision')
                                                <a href="{{ route('kepala_divisi.logbooks.show', $logbook) }}"
                                                   class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-medium transition">
                                                    Lihat Revisi
                                                </a>
                                            @else
                                                <a href="{{ route('kepala_divisi.logbooks.show', $logbook) }}"
                                                   class="inline-flex items-center px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg text-xs font-medium transition">
                                                    Lihat Detail
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Tabel Logbook Mandiri --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-800">
                        Logbook Mandiri
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Laporan aktivitas karyawan yang tidak terhubung dengan tugas tertentu.
                    </p>
                </div>

                @if ($independentLogbooks->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="font-medium text-gray-700">
                            Belum ada logbook mandiri.
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Logbook mandiri yang dikirim karyawan akan ditampilkan di sini.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Karyawan</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Aktivitas</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Tanggal</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Status</th>
                                    <th class="px-6 py-4 text-right font-semibold text-gray-600">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @foreach ($independentLogbooks as $logbook)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                             <div class="font-medium text-gray-800">
                                                 <span class="text-gray-500 mr-2">{{ $loop->iteration }}.</span>
                                                 {{ $logbook->user->name ?? '-' }}
                                             </div>
                                             <div class="text-xs text-gray-500 mt-1">
                                                 {{ $logbook->user->email ?? '-' }}
                                             </div>
                                         </td>

                                         <td class="px-6 py-4">
                                             <div class="font-medium text-gray-800">
                                                 {{ $logbook->activity ?? '-' }}
                                             </div>
                                             @if ($logbook->description)
                                                 <div class="text-xs text-gray-500 mt-1">
                                                     {{ \Illuminate\Support\Str::limit($logbook->description, 100) }}
                                                 </div>
                                             @endif
                                             <div class="flex items-center gap-2 mt-2">
                                                 <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-blue-50 text-blue-700 border border-blue-200">
                                                     Mandiri
                                                 </span>

                                             </div>
                                         </td>

                                         <td class="px-6 py-4 whitespace-nowrap">
                                             <div class="text-gray-700">
                                                 {{ $logbook->date?->format('d M Y') ?? '-' }}
                                             </div>
                                             @if ($logbook->date)
                                                 <div class="text-xs text-gray-400 mt-1">
                                                     {{ $logbook->date->format('H:i') }}
                                                 </div>
                                             @endif
                                         </td>

                                        <td class="px-6 py-4">
                                            @php
                                                $statusClass = match ($logbook->status) {
                                                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                                    'revision' => 'bg-red-50 text-red-700 border-red-200',
                                                    'approved' => 'bg-green-50 text-green-700 border-green-200',
                                                    'reviewed' => 'bg-green-50 text-green-700 border-green-200',
                                                    default => 'bg-gray-50 text-gray-600 border-gray-200',
                                                };

                                                $statusLabel = match ($logbook->status) {
                                                    'pending' => 'Menunggu Review',
                                                    'revision' => 'Perlu Revisi',
                                                    'approved' => 'Disetujui',
                                                    'reviewed' => 'Sudah Direview',
                                                    default => ucfirst($logbook->status ?? '-'),
                                                };
                                            @endphp

                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            @if ($logbook->status === 'pending')
                                                <a href="{{ route('kepala_divisi.logbooks.show', $logbook) }}"
                                                   class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium transition">
                                                    Review
                                                </a>
                                            @elseif ($logbook->status === 'revision')
                                                <a href="{{ route('kepala_divisi.logbooks.show', $logbook) }}"
                                                   class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-medium transition">
                                                    Lihat Revisi
                                                </a>
                                            @else
                                                <a href="{{ route('kepala_divisi.logbooks.show', $logbook) }}"
                                                   class="inline-flex items-center px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg text-xs font-medium transition">
                                                    Lihat Detail
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>