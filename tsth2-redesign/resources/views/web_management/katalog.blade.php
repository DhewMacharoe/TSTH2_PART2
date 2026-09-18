@extends('layouts.dashboard')

@section('title', 'Manajemen Web - Katalog')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">Katalog Tanaman</h1>
    <p class="text-gray-500 font-medium">Ubah pengaturan tampilan halaman Katalog di pengunjung publik.</p>
</div>

@if(session('success'))
<div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 font-bold border border-emerald-100 flex items-center gap-3">
    <span class="text-xl">✅</span>
    {{ session('success') }}
</div>
@endif

<form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Katalog -->
    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-8 text-gray-900 flex items-center gap-3 border-b border-gray-100 pb-4"><span class="text-primary text-2xl">📚</span> Bagian: Katalog</h2>
        
        <div class="space-y-6">
            <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 mb-6 flex gap-4">
                <div class="text-2xl mt-1">ℹ️</div>
                <div class="text-blue-900 text-sm font-medium leading-relaxed">
                    Pengaturan di bawah ini digunakan untuk mengatur Header/Teks Pengantar di halaman <strong>Katalog Tanaman</strong> publik.<br>
                    Untuk menambah atau menghapus produk/tanaman di dalam katalog, silakan gunakan menu <a href="{{ route('warehouse') }}" class="text-blue-700 font-bold underline">Inventory & Data Barang</a>.
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-bold mb-2 text-gray-700">Judul Header Katalog</label>
                    <input type="text" name="katalog_hero_title" class="w-full p-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-700 bg-gray-50 hover:bg-white" value="{{ $contents['katalog_hero_title']->value ?? '' }}" placeholder="Contoh: Katalog Koleksi Tanaman">
                </div>
                <div>
                    <label class="block font-bold mb-2 text-gray-700">Deskripsi Header Katalog</label>
                    <textarea name="katalog_hero_desc" class="w-full min-h-[120px] p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-700 bg-gray-50 hover:bg-white resize-none" placeholder="Contoh: Temukan berbagai koleksi tanaman herbal dan hortikultura dari pusat riset kami.">{{ $contents['katalog_hero_desc']->value ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Footer Actions -->
    <div class="sticky bottom-6 bg-white/95 backdrop-blur-xl p-5 rounded-[2rem] shadow-2xl shadow-gray-200/50 border border-gray-200 flex flex-col sm:flex-row justify-end items-center gap-4 z-30">
        <a href="{{ route('katalog') }}" target="_blank" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-white border-2 border-gray-200 text-gray-700 font-bold py-3.5 px-8 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all">
            👁️ Pratinjau Katalog
        </a>
        <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white font-bold py-3.5 px-8 rounded-xl shadow-lg transition-transform hover:scale-105">
            💾 Simpan Katalog
        </button>
    </div>
</form>
@endsection
