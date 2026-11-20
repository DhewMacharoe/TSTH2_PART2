<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Review Logbook
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Pantau dan periksa laporan pekerjaan dari Kepala Divisi di departemen Anda.
            </p>
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

            {{-- Ringkasan Statistik (Hanya Total Logbook, Menunggu Review, dan Perlu Revisi) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Total Logbook</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $totalLogbooks }}
                    </p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Menunggu Review</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">
                        {{ $pendingCount }}
                    </p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Perlu Revisi</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">
                        {{ $revisionCount }}
                    </p>
                </div>
            </div>

            {{-- Tabel Review Logbook Kepala Divisi --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-8">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-800">
                        Logbook Kepala Divisi
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Laporan pekerjaan dari Kepala Divisi di lingkungan Departemen {{ $department?->name ?? '-' }}.
                    </p>
                </div>

                @if ($logbooks->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="font-medium text-gray-700">
                            Belum ada logbook dari Kepala Divisi.
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Logbook yang diajukan oleh Kepala Divisi akan ditampilkan di sini.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">No</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Kepala Divisi</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Pekerjaan</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Tanggal</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Status</th>
                                    <th class="px-6 py-4 text-right font-semibold text-gray-600">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @foreach ($logbooks as $logbook)
                                    <tr class="hover:bg-gray-50 transition">
                                        {{-- No --}}
                                        <td class="px-6 py-4 font-medium text-gray-500">
                                            {{ $loop->iteration + ($logbooks->currentPage() - 1) * $logbooks->perPage() }}
                                        </td>

                                        {{-- Kepala Divisi --}}
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-800">
                                                {{ $logbook->user->name ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $logbook->user->divisionRelation?->name ?? ($logbook->user->email ?? '-') }}
                                            </div>
                                        </td>

                                        {{-- Pekerjaan --}}
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-800 line-clamp-2" title="{{ $logbook->activity }}">
                                                {{ $logbook->activity }}
                                            </div>
                                            @if ($logbook->obstacle)
                                                <div class="text-xs text-red-500 mt-1 truncate" title="{{ $logbook->obstacle }}">
                                                    Kendala: {{ $logbook->obstacle }}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Tanggal --}}
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

                                        {{-- Status --}}
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

                                        {{-- Aksi --}}
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            @if ($logbook->status === 'pending')
                                                <a href="{{ route('kepala_departemen.logbooks.show', $logbook) }}"
                                                   class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium transition">
                                                    Review
                                                </a>
                                            @elseif ($logbook->status === 'revision')
                                                <a href="{{ route('kepala_departemen.logbooks.show', $logbook) }}"
                                                   class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-medium transition">
                                                    Lihat Revisi
                                                </a>
                                            @else
                                                <a href="{{ route('kepala_departemen.logbooks.show', $logbook) }}"
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

                    @if ($logbooks->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            {{ $logbooks->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
