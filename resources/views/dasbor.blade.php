@extends('layouts.dasbor')

@section('title', 'Dashboard Panel - TSTH2')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h1>
    <p class="text-gray-500 font-medium mt-2">Ringkasan aktivitas dan sistem informasi manajemen terpadu TSTH2.</p>
</div>

<!-- Shortcuts -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('gudang') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/30 transition-all group">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">📦</div>
        <span class="font-bold text-gray-700 text-sm">Data Barang</span>
    </a>
    <a href="{{ route('permintaan.index') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/30 transition-all group">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">🛒</div>
        <span class="font-bold text-gray-700 text-sm">Permintaan</span>
    </a>
    @if(in_array(auth()->user()->role, ['pic_gudang', 'agronom', 'pimpinan']))
    <a href="{{ route('catatan') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/30 transition-all group">
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">📖</div>
        <span class="font-bold text-gray-700 text-sm">Logbook</span>
    </a>
    @endif
    <a href="{{ route('profil.edit') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/30 transition-all group">
        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">👤</div>
        <span class="font-bold text-gray-700 text-sm">Profil Saya</span>
    </a>
</div>

<!-- Stats and Charts -->
<div class="grid grid-cols-1 {{ auth()->user()->role !== 'web_admin' ? 'lg:grid-cols-2' : '' }} gap-6 mb-8">
    @if(auth()->user()->role !== 'web_admin')
    <!-- Storage Stats (disembunyikan untuk web_admin) -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Status Gudang</h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center flex flex-col justify-center">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Item</span>
                <strong class="text-3xl text-gray-900">{{ number_format($totalBarang) }}</strong>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center flex flex-col justify-center">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Stok Unit</span>
                <strong class="text-3xl text-primary">{{ number_format($totalStok) }}</strong>
            </div>
            @if(in_array(auth()->user()->role, ['pic_gudang']))
            <div class="p-4 bg-orange-50 rounded-xl border border-orange-100 text-center flex flex-col justify-center">
                <span class="block text-xs font-bold text-orange-500 uppercase tracking-wider mb-1">Stok Rusak</span>
                <strong class="text-3xl text-orange-600">{{ number_format($totalRusak ?? 0) }}</strong>
            </div>
            <div class="p-4 bg-red-50 rounded-xl border border-red-100 text-center flex flex-col justify-center">
                <span class="block text-xs font-bold text-red-500 uppercase tracking-wider mb-1">Stok Expired</span>
                <strong class="text-3xl text-red-600">{{ number_format($totalExpired ?? 0) }}</strong>
            </div>
            @endif
        </div>
        <div class="flex-1 relative min-h-[250px]">
            <canvas id="kategoriChart"></canvas>
        </div>
    </div>
    @endif

    <!-- Request Stats -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Status Permintaan</h3>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 text-center">
                <span class="block text-xs font-bold text-amber-600/70 uppercase tracking-wider mb-1">Menunggu</span>
                <strong class="text-3xl text-amber-600">{{ $permintaanStats['Menunggu Approval'] + $permintaanStats['Menunggu Pengambilan'] }}</strong>
            </div>
            <div class="p-4 bg-green-50 rounded-xl border border-green-100 text-center">
                <span class="block text-xs font-bold text-green-600/70 uppercase tracking-wider mb-1">Selesai</span>
                <strong class="text-3xl text-green-600">{{ $permintaanStats['Selesai'] }}</strong>
            </div>
        </div>
        <div class="flex-1 relative min-h-[250px]">
            <canvas id="permintaanChart"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(auth()->user()->role !== 'web_admin')
    // Kategori Chart (hanya untuk non web_admin)
    const katData = @json($kategoriStats);
    if(Object.keys(katData).length > 0) {
        new Chart(document.getElementById('kategoriChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(katData),
                datasets: [{
                    data: Object.values(katData),
                    backgroundColor: ['#054439', '#c09e5c', '#2ecc71', '#3498db', '#9b59b6', '#f1c40f'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                },
                cutout: '70%'
            }
        });
    }
    @endif

    // Request Chart
    const reqData = @json($permintaanStats);
    new Chart(document.getElementById('permintaanChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(reqData),
            datasets: [{
                label: 'Jumlah Permintaan',
                data: Object.values(reqData),
                backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#22c55e'],
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
});
</script>
@endsection
