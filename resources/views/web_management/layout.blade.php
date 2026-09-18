@extends('layouts.dashboard')

@section('title', 'Manajemen Web - LAYOUT')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - LAYOUT</h1>
    <p class="text-gray-500 font-medium">Ubah teks dan gambar yang tampil di halaman pengunjung secara instan.</p>
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

    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div>
                <label class="block font-bold mb-2 text-gray-700">Navbar Title</label>
                <input type="text" name="navbar_title" class="w-full p-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-700 bg-gray-50 hover:bg-white" value="{{ $contents['navbar_title']->value ?? '' }}">
            </div>
            <div>
                <label class="block font-bold mb-2 text-gray-700">Navbar Subtitle</label>
                <input type="text" name="navbar_subtitle" class="w-full p-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-700 bg-gray-50 hover:bg-white" value="{{ $contents['navbar_subtitle']->value ?? '' }}">
            </div>
            <div>
                <label class="block font-bold mb-2 text-gray-700">Footer Desc</label>
                <textarea name="footer_desc" class="w-full h-[120px] p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-700 bg-gray-50 hover:bg-white leading-relaxed resize-none">{{ $contents['footer_desc']->value ?? '' }}</textarea>
            </div>
            <div>
                <label class="block font-bold mb-2 text-gray-700">Footer Address</label>
                <textarea name="footer_address" class="w-full h-[120px] p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-700 bg-gray-50 hover:bg-white leading-relaxed resize-none">{{ $contents['footer_address']->value ?? '' }}</textarea>
            </div>
            <div>
                <label class="block font-bold mb-2 text-gray-700">Footer Email</label>
                <input type="text" name="footer_email" class="w-full p-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-700 bg-gray-50 hover:bg-white" value="{{ $contents['footer_email']->value ?? '' }}">
            </div>
        </div>
    </div>

    <div class="sticky bottom-6 bg-white/95 backdrop-blur-xl p-5 rounded-[2rem] shadow-2xl shadow-gray-200/50 border border-gray-200 flex flex-col sm:flex-row justify-end items-center gap-4 z-30">
        <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white font-bold py-3.5 px-8 rounded-xl shadow-lg transition-transform hover:scale-105">
            💾 Simpan Perubahan
        </button>
    </div>
</form>
@endsection
