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
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <span class="material-symbols-rounded text-2xl">inventory_2</span>
        </div>
        <span class="font-bold text-gray-700 text-sm">Data Barang</span>
    </a>
    <a href="{{ route('permintaan.index') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/30 transition-all group">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <span class="material-symbols-rounded text-2xl">shopping_cart</span>
        </div>
        <span class="font-bold text-gray-700 text-sm">Permintaan</span>
    </a>
    @if(in_array(auth()->user()->role, ['pic_gudang', 'agronom', 'pimpinan']))
    <a href="{{ route('catatan') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/30 transition-all group">
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <span class="material-symbols-rounded text-2xl">menu_book</span>
        </div>
        <span class="font-bold text-gray-700 text-sm">Logbook</span>
    </a>
    @endif
    <a href="{{ route('profil.edit') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/30 transition-all group">
        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <span class="material-symbols-rounded text-2xl">person</span>
        </div>
        <span class="font-bold text-gray-700 text-sm">Profil Saya</span>
    </a>
</div>
@endsection
