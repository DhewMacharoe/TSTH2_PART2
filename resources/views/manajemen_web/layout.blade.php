@extends('layouts.dasbor')

@section('title', 'Header & Footer - TSTH2 Web Management')

@section('header_title')
    <div>
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Header & Footer Website</h2>
        <p class="text-xs text-gray-500 font-medium">Pengaturan identitas navigasi atas dan kontak footer di seluruh halaman web publik.</p>
    </div>
@endsection

@section('content')
    <!-- Top Header Banner -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-primary-900 via-primary-800 to-primary-950 p-6 sm:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-accent/20 text-accent border border-accent/30 mb-3">
                <span class="material-symbols-rounded text-sm">settings</span> Pengaturan Tampilan Global
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Header & Footer Website</h1>
            <p class="text-white/70 text-sm mt-1 max-w-xl">Ubah nama dan subjudul di navigasi utama serta informasi alamat, email, dan deskripsi pada bagian bawah (footer).</p>
        </div>
        <div class="relative z-10 shrink-0">
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl text-xs font-bold backdrop-blur-sm transition-all hover:scale-105 shadow-sm">
                <span class="material-symbols-rounded text-sm">language</span> Pratinjau Web
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl mb-8 font-semibold border border-emerald-200/80 flex items-center gap-3 shadow-sm animate-fade-in">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0">
                <span class="material-symbols-rounded text-base">check</span>
            </div>
            <div class="text-sm">{{ session('success') }}</div>
        </div>
    @endif

    <form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-8">
            <!-- 1. KARTU NAVBAR HEADER -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center shadow-inner">
                        <span class="material-symbols-rounded text-xl">explore</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Navigasi Atas (Navbar Header)</h2>
                        <p class="text-xs text-gray-500">Teks identitas yang tampil di bagian paling atas setiap halaman pengunjung.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Nama Website / Judul Navbar</label>
                        <input type="text" name="navbar_title" 
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-semibold bg-gray-50/50 hover:bg-white" 
                            placeholder="Contoh: TSTH2"
                            value="{{ $konten['navbar_title']->value ?? '' }}">
                        <p class="text-[11px] text-gray-400 mt-1.5">Nama utama yang tampil di samping logo instansi.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Subjudul / Tagline Navbar</label>
                        <input type="text" name="navbar_subtitle" 
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-medium bg-gray-50/50 hover:bg-white" 
                            placeholder="Contoh: Pollung - Humbang Hasundutan"
                            value="{{ $konten['navbar_subtitle']->value ?? '' }}">
                        <p class="text-[11px] text-gray-400 mt-1.5">Teks pendukung kecil di bawah nama website.</p>
                    </div>
                </div>
            </div>

            <!-- 2. KARTU FOOTER & KONTAK -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center shadow-inner">
                        <span class="material-symbols-rounded text-xl">domain</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Bagian Bawah (Footer) & Kontak Resmi</h2>
                        <p class="text-xs text-gray-500">Teks informasi singkat, alamat kantor, dan alamat email resmi untuk dihubungi pengunjung.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Deskripsi Ringkas Footer</label>
                        <textarea name="footer_desc" rows="4"
                            class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white resize-none"
                            placeholder="Tuliskan rangkuman singkat tentang dedikasi riset dan layanan TSTH2...">{{ $konten['footer_desc']->value ?? '' }}</textarea>
                        <p class="text-[11px] text-gray-400 mt-1">Muncul di kolom pertama bagian footer website.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Alamat Lengkap Kantor / Fasilitas</label>
                        <textarea name="footer_address" rows="4"
                            class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white resize-none"
                            placeholder="Contoh: Desa Aek Nauli, Kec. Pollung, Kab. Humbang Hasundutan, Sumatera Utara...">{{ $konten['footer_address']->value ?? '' }}</textarea>
                        <p class="text-[11px] text-gray-400 mt-1">Alamat fisik instansi.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Alamat Email Resmi</label>
                        <input type="email" name="footer_email" 
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-medium bg-gray-50/50 hover:bg-white" 
                            placeholder="Contoh: info@tsth2.id"
                            value="{{ $konten['footer_email']->value ?? '' }}">
                        <p class="text-[11px] text-gray-400 mt-1.5">Email resmi yang dapat diklik oleh pengunjung untuk mengirim surat/pertanyaan.</p>
                    </div>
                </div>
            </div>

            <!-- STICKY ACTION BUTTON BAR -->
            <div class="sticky bottom-6 bg-white/90 backdrop-blur-xl p-4 sm:p-5 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-200/80 flex flex-col sm:flex-row justify-between items-center gap-4 z-30">
                <div class="flex items-center gap-2.5 text-xs text-gray-500">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Perubahan akan langsung tercermin pada seluruh halaman website publik.</span>
                </div>
                <button type="submit" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-900 to-primary-950 hover:from-black hover:to-primary-950 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-primary-900/20 transition-all hover:scale-[1.02] text-sm">
                    <span class="material-symbols-rounded text-sm">save</span> Simpan Header & Footer
                </button>
            </div>
        </div>
    </form>
@endsection
