<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Review Logbook Karyawan</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- FLASH MESSAGE --}}
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- FILTER --}}
            <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100">
                <form method="GET" class="flex flex-col sm:flex-row gap-3">
                    <input
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama karyawan..."
                        class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    >
                    <select name="division" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">Semua Divisi</option>
                        @foreach($divisions as $div)
                            <option value="{{ $div }}" @selected(request('division') === $div)>{{ $div }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition">
                        Filter
                    </button>
                    @if(request('search') || request('division'))
                        <a href="{{ route('supervisor.logbooks.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition text-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- TABEL KARYAWAN --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Karyawan</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Pending</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Approved</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Rejected</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Progress</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($employees as $employee)
                            @php
                                $reviewed = $employee->approved_logbooks_count + $employee->rejected_logbooks_count;
                                $progress = $employee->total_logbooks_count > 0
                                    ? round(($employee->approved_logbooks_count / $employee->total_logbooks_count) * 100)
                                    : 0;
                                $progressColor = $progress >= 80 ? 'bg-green-500' : ($progress >= 50 ? 'bg-yellow-400' : 'bg-blue-500');
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-800">{{ $employee->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $employee->division ?: '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-gray-700">{{ $employee->total_logbooks_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($employee->pending_logbooks_count > 0)
                                        <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-2 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                            {{ $employee->pending_logbooks_count }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-2 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                        {{ $employee->approved_logbooks_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-2 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                        {{ $employee->rejected_logbooks_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 min-w-40">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-2 {{ $progressColor }} rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-600 w-9 text-right">{{ $progress }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a
                                        href="{{ route('supervisor.employees.logbooks', $employee) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        Review
                                        @if($employee->pending_logbooks_count > 0)
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-white text-blue-600 text-xs font-bold">
                                                {{ $employee->pending_logbooks_count }}
                                            </span>
                                        @endif
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <p class="text-gray-400 text-sm">Tidak ada karyawan ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-gray-100">{{ $employees->links() }}</div>
            </div>

        </div>
    </div>

</x-app-layout>
