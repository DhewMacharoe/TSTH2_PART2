<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Ringkasan Logbook Karyawan</h2></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6 mb-6"><form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input name="search" value="{{ request('search') }}" placeholder="Cari karyawan" class="border-gray-300 rounded-lg">
            <select name="division" class="border-gray-300 rounded-lg"><option value="">Semua Divisi</option>@foreach($divisions as $division)<option value="{{ $division }}" @selected(request('division') === $division)>{{ $division }}</option>@endforeach</select>
            <input type="month" name="month" value="{{ request('month') }}" class="border-gray-300 rounded-lg">
            <input type="number" name="year" value="{{ request('year') }}" placeholder="Tahun" class="border-gray-300 rounded-lg">
            <div class="md:col-span-4 flex gap-3"><button class="bg-blue-600 text-white px-4 py-2 rounded-lg">Filter</button><a href="{{ route('leader.logbooks.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Reset</a></div>
        </form></div>
        <div class="bg-white shadow rounded-lg overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="p-4 text-left">Karyawan</th><th class="p-4 text-left">Divisi</th><th class="p-4 text-center">Total Logbook</th><th class="p-4 text-center">Approved</th><th class="p-4 text-center">Rejected</th><th class="p-4 text-left">Progress</th><th class="p-4">Aksi</th></tr></thead><tbody>
        @forelse($employees as $employee)
            @php($progress = $employee->total_logbooks_count ? round(($employee->approved_logbooks_count / $employee->total_logbooks_count) * 100) : 0)
            <tr class="border-t"><td class="p-4"><div class="font-medium">{{ $employee->name }}</div><div class="text-sm text-gray-500">{{ $employee->email }}</div></td><td class="p-4">{{ $employee->division ?: '-' }}</td><td class="p-4 text-center font-semibold">{{ $employee->total_logbooks_count }}</td><td class="p-4 text-center text-green-600">{{ $employee->approved_logbooks_count }}</td><td class="p-4 text-center text-red-600">{{ $employee->rejected_logbooks_count }}</td><td class="p-4 min-w-40"><div class="h-2 bg-gray-200 rounded"><div class="h-2 bg-green-500 rounded" style="width: {{ $progress }}%"></div></div><span class="text-xs">{{ $progress }}%</span></td><td class="p-4 text-center"><a href="{{ route('leader.employees.logbooks', $employee) }}" class="bg-blue-600 text-white px-3 py-2 rounded-lg">Lihat {{ $employee->total_logbooks_count }} Logbook</a></td></tr>
        @empty <tr><td colspan="7" class="p-8 text-center text-gray-500">Belum ada logbook yang direview supervisor.</td></tr>@endforelse
        </tbody></table><div class="p-4">{{ $employees->links() }}</div></div>
    </div></div>
</x-app-layout>
