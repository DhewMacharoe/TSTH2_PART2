<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Dashboard Supervisor</h2></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white shadow rounded-lg p-6"><p class="text-gray-500">Total Logbook</p><h3 class="text-3xl font-bold">{{ $totalLogbooks }}</h3></div>
            <div class="bg-yellow-50 shadow rounded-lg p-6"><p class="text-gray-500">Menunggu Review</p><h3 class="text-3xl font-bold">{{ $pendingLogbooks }}</h3></div>
            <div class="bg-green-50 shadow rounded-lg p-6"><p class="text-gray-500">Approved</p><h3 class="text-3xl font-bold">{{ $approvedLogbooks }}</h3></div>
            <div class="bg-red-50 shadow rounded-lg p-6"><p class="text-gray-500">Rejected</p><h3 class="text-3xl font-bold">{{ $rejectedLogbooks }}</h3></div>
        </div>
        <div class="bg-white shadow rounded-lg p-6 mt-6"><div class="flex justify-between mb-4"><h3 class="font-semibold">Logbook Terbaru</h3><a class="text-blue-600" href="{{ route('supervisor.logbooks.index') }}">Review semua</a></div>
            @foreach($latestLogbooks as $logbook)<div class="border-t py-3 flex justify-between"><div><p class="font-medium">{{ $logbook->user->name }}</p><p class="text-sm text-gray-500">{{ Str::limit($logbook->activity, 100) }}</p></div><span class="text-sm">{{ ucfirst($logbook->status) }}{{ $logbook->progress !== null ? ' - ' . $logbook->progress . '%' : '' }}</span></div>@endforeach
        </div>
    </div></div>
</x-app-layout>
