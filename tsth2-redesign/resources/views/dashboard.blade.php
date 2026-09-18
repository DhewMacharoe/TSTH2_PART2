@extends('layouts.dashboard')

@section('title', 'Dashboard Panel - TSTH2')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h1>
    <p class="text-gray-500 font-medium mt-2">Pilih menu navigasi <strong class="text-primary">di sebelah kiri (sidebar)</strong> sesuai dengan wewenang pekerjaan Anda.</p>
</div>

<div class="bg-white/80 backdrop-blur-xl rounded-[2rem] p-12 text-center shadow-sm border border-white mt-8 hover:shadow-lg transition-shadow duration-500">
    <div class="inline-flex items-center justify-center mb-6">
        <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo" class="h-24 md:h-32" style="transform: scale(1.3);">
    </div>
    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Sistem Informasi Manajemen Terpadu TSTH2</h2>
    <p class="text-gray-500 max-w-xl mx-auto leading-relaxed">Anda login sebagai <strong class="text-primary uppercase tracking-wider text-sm bg-primary/10 px-3 py-1 rounded-full">{{ str_replace('_', ' ', auth()->user()->role) }}</strong>. Akses menu telah disesuaikan secara otomatis berdasarkan hak akses Anda.</p>
</div>
@endsection
