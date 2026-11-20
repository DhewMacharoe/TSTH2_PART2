@extends('layouts.dasbor')

@section('title', 'Kelola Beranda - TSTH2 Web Management')

@section('header_title')
    <div>
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Halaman Beranda (Home)</h2>
        <p class="text-xs text-gray-500 font-medium">Kelola tampilan layar utama, profil instansi, sambutan direktur, dan visi misi.</p>
    </div>
@endsection

@section('content')
    <!-- Top Header Banner -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-primary-900 via-primary-800 to-primary-950 p-6 sm:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-accent/20 text-accent border border-accent/30 mb-3">
                <span class="material-symbols-rounded text-sm">home</span> Manajemen Halaman Beranda
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Pengaturan Halaman Beranda</h1>
            <p class="text-white/70 text-sm mt-1 max-w-xl">Ubah teks pembuka, latar belakang gambar hero, profil instansi, serta kutipan sambutan direktur dengan mudah.</p>
        </div>
        <div class="relative z-10 shrink-0">
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl text-xs font-bold backdrop-blur-sm transition-all hover:scale-105 shadow-sm">
                <span class="material-symbols-rounded text-sm">language</span> Pratinjau Beranda
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>

    @if (session('success'))
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
            <!-- 1. BAGIAN HERO (LAYAR PERTAMA) -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shadow-inner">
                        <span class="material-symbols-rounded text-xl">flag</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Layar Pembuka</h2>
                        <p class="text-xs text-gray-500">Teks besar dan pengantar yang pertama kali dilihat pengunjung saat membuka website.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Badge / Label Kecil Hero</label>
                        <input type="text" name="home_hero_badge"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-medium bg-gray-50/50 hover:bg-white" 
                            placeholder="Contoh: Kawasan Riset Unggulan Nasional"
                            value="{{ $konten['home_hero_badge']->value ?? '' }}">
                        <p class="text-[11px] text-gray-400 mt-1.5">Teks kecil melayang di atas judul utama.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Judul Utama Hero</label>
                        <input type="text" name="home_hero_title"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-semibold bg-gray-50/50 hover:bg-white" 
                            placeholder="Contoh: Taman Sains dan Teknologi Herbal dan Hortikultura"
                            value="{{ $konten['home_hero_title']->value ?? '' }}">
                        <p class="text-[11px] text-gray-400 mt-1.5">Kalimat utama yang paling mencolok di halaman beranda.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Deskripsi Lengkap Hero</label>
                        <textarea name="home_hero_desc" rows="3"
                            class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white resize-none"
                            placeholder="Tuliskan gambaran ringkas mengenai pusat riset herbal TSTH2...">{{ $konten['home_hero_desc']->value ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 2. BAGIAN TENTANG KAMI -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center shadow-inner">
                        <span class="material-symbols-rounded text-xl">account_balance</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Tentang TSTH2</h2>
                        <p class="text-xs text-gray-500">Informasi profil umum instansi yang ditampilkan di seksi kedua beranda.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Judul Bagian Tentang</label>
                        <input type="text" name="home_about_title"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-semibold bg-gray-50/50 hover:bg-white" 
                            placeholder="Contoh: Mengenal TSTH2 Pollung"
                            value="{{ $konten['home_about_title']->value ?? '' }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Deskripsi Profil Singkat</label>
                        <textarea name="home_about_desc" rows="5"
                            class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white resize-none"
                            placeholder="Jelaskan sejarah singkat, visi pengembangan, dan tujuan berdirinya TSTH2...">{{ $konten['home_about_desc']->value ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Gambar Ilustrasi / Latar Belakang</label>
                        <div class="p-4 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-white transition-colors">
                            <input type="file" name="home_about_bg" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary-900 cursor-pointer" accept="image/*">
                            @if (isset($konten['home_about_bg']) && $konten['home_about_bg']->image_path)
                                <div class="mt-4 flex items-center gap-3 p-2 bg-white rounded-xl border border-gray-100 shadow-sm">
                                    <img src="{{ asset('storage/' . $konten['home_about_bg']->image_path) }}" class="h-16 w-28 object-cover rounded-lg border border-gray-200">
                                    <div class="text-[11px] text-gray-500">
                                        <span class="font-bold text-gray-700 block">Gambar Aktif Saat Ini</span>
                                        Pilih file baru jika ingin mengganti.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. BAGIAN PENGANTAR PENELITIAN -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center shadow-inner">
                        <span class="material-symbols-rounded text-xl">biotech</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900"> Penelitian</h2>
                        <p class="text-xs text-gray-500">Teks pengantar untuk bagian preview riset yang ada di beranda.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Judul Seksi Riset</label>
                        <input type="text" name="home_penelitian_title"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-semibold bg-gray-50/50 hover:bg-white" 
                            placeholder="Contoh: Fasilitas Riset Terintegrasi"
                            value="{{ $konten['home_penelitian_title']->value ?? '' }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Deskripsi Singkat</label>
                        <textarea name="home_penelitian_desc" rows="3"
                            class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white resize-none"
                            placeholder="Dukung riset herbal dengan laboratorium bioteknologi, genomik, dan kebun ekstraksi...">{{ $konten['home_penelitian_desc']->value ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 4. BAGIAN SAMBUTAN DIREKTUR -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center shadow-inner">
                        <span class="material-symbols-rounded text-xl">person</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Sambutan & Profil Direktur / Pimpinan</h2>
                        <p class="text-xs text-gray-500">Pesan resmi, kutipan inspiratif, dan foto pimpinan instansi.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Nama Lengkap Direktur & Gelar</label>
                        <input type="text" name="home_director_name"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-semibold bg-gray-50/50 hover:bg-white" 
                            placeholder="Contoh: Dr. Ir. Direktur Utama, M.Sc."
                            value="{{ $konten['home_director_name']->value ?? '' }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Jabatan Resmi</label>
                        <input type="text" name="home_director_title"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-semibold bg-gray-50/50 hover:bg-white" 
                            placeholder="Contoh: Direktur Pengelola TSTH2"
                            value="{{ $konten['home_director_title']->value ?? '' }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Kutipan Singkat (Quote)</label>
                        <textarea name="home_director_quote" rows="4"
                            class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white italic resize-none"
                            placeholder="Contoh: &ldquo;Mewujudkan Indonesia sebagai pusat kemandirian sains herbal tropis dunia.&rdquo;">{{ $konten['home_director_quote']->value ?? '' }}</textarea>
                        <p class="text-[11px] text-gray-400 mt-1">Akan ditampilkan dengan gaya tipografi quote di beranda.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Deskripsi / Sambutan Lengkap</label>
                        <textarea name="home_director_desc" rows="4"
                            class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white resize-none"
                            placeholder="Tuliskan uraian sambutan atau pesan direktur kepada seluruh mitra dan publik...">{{ $konten['home_director_desc']->value ?? '' }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Foto Resmi Direktur</label>
                        <div class="p-5 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-white transition-colors flex flex-col sm:flex-row items-center gap-6">
                            @if (isset($konten['home_director']) && $konten['home_director']->image_path)
                                <div class="shrink-0 text-center">
                                    <img src="{{ asset('storage/' . $konten['home_director']->image_path) }}"
                                        class="h-32 w-24 object-cover object-top rounded-2xl shadow-md border-2 border-white ring-2 ring-primary/20">
                                    <span class="text-[10px] text-gray-400 mt-1.5 block font-semibold">Foto Saat Ini</span>
                                </div>
                            @endif
                            <div class="flex-1 text-center sm:text-left">
                                <input type="file" name="home_director" class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white hover:file:bg-black cursor-pointer" accept="image/*">
                                <p class="text-xs text-gray-400 mt-2">Disarankan menggunakan foto potret formal dengan resolusi yang jelas (rasio 3:4). Foto akan otomatis diposisikan terpusat pada wajah.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WAKIL DIREKTUR -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center shadow-inner">
                        <span class="material-symbols-rounded text-xl">person</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Profil Wakil Direktur</h2>
                        <p class="text-xs text-gray-500">Data wakil direktur / pimpinan kedua instansi.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Nama Lengkap Wakil Direktur &amp; Gelar</label>
                        <input type="text" name="home_vice_director_name"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-semibold bg-gray-50/50 hover:bg-white"
                            placeholder="Contoh: Dr. Ir. Wakil Direktur, M.Sc."
                            value="{{ optional($konten['home_vice_director_name'] ?? null)->value ?? '' }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Jabatan Resmi</label>
                        <input type="text" name="home_vice_director_title"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm font-semibold bg-gray-50/50 hover:bg-white"
                            placeholder="Contoh: Wakil Direktur TSTH2"
                            value="{{ optional($konten['home_vice_director_title'] ?? null)->value ?? '' }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Kutipan Singkat (Quote)</label>
                        <textarea name="home_vice_director_quote" rows="4"
                            class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white italic resize-none"
                            placeholder="Kutipan inspiratif wakil direktur...">{{ optional($konten['home_vice_director_quote'] ?? null)->value ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Deskripsi / Sambutan</label>
                        <textarea name="home_vice_director_desc" rows="4"
                            class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm leading-relaxed bg-gray-50/50 hover:bg-white resize-none"
                            placeholder="Uraian singkat profil wakil direktur...">{{ optional($konten['home_vice_director_desc'] ?? null)->value ?? '' }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Foto Resmi Wakil Direktur</label>
                        <div class="p-5 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-white transition-colors flex flex-col sm:flex-row items-center gap-6">
                            @if (isset($konten['home_vice_director']) && $konten['home_vice_director']->image_path)
                                <div class="shrink-0 text-center">
                                    <img src="{{ asset('storage/' . $konten['home_vice_director']->image_path) }}"
                                        class="h-32 w-24 object-cover object-top rounded-2xl shadow-md border-2 border-white ring-2 ring-primary/20">
                                    <span class="text-[10px] text-gray-400 mt-1.5 block font-semibold">Foto Saat Ini</span>
                                </div>
                            @endif
                            <div class="flex-1 text-center sm:text-left">
                                <input type="file" name="home_vice_director" class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white hover:file:bg-black cursor-pointer" accept="image/*">
                                <p class="text-xs text-gray-400 mt-2">Foto potret formal, rasio 3:4. Kosongkan jika tidak ingin mengubah foto.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. BAGIAN  KONTEN BERANDA (RISET, BERITA, & GALERI) -->
            @php
                $rawSelPenelitian = $konten['home_selected_penelitian']->value ?? '[]';
                $decodedPenelitian = json_decode($rawSelPenelitian, true);
                $selectedPenelitianIds = is_array($decodedPenelitian) ? array_map('intval', $decodedPenelitian) : [];

                $rawSelBerita = $konten['home_selected_berita']->value ?? '[]';
                $decodedBerita = json_decode($rawSelBerita, true);
                $selectedBeritaIds = is_array($decodedBerita) ? array_map('intval', $decodedBerita) : [];

                $rawSelGaleri = $konten['home_selected_galeri']->value ?? '[]';
                $decodedGaleri = json_decode($rawSelGaleri, true);
                $selectedGaleriIds = is_array($decodedGaleri) ? array_map('intval', $decodedGaleri) : [];

                $allPenelitianIds = ($allPenelitian ?? collect([]))->pluck('id')->all();
                $allBeritaIds = ($allBerita ?? collect([]))->pluck('id')->all();
                $allGaleriIds = ($allGaleri ?? collect([]))->pluck('id')->all();
            @endphp

            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow"
                 x-data="{
                     activeCurTab: 'penelitian',
                     selectedPenelitian: {{ json_encode($selectedPenelitianIds) }},
                     selectedBerita: {{ json_encode($selectedBeritaIds) }},
                     selectedGaleri: {{ json_encode($selectedGaleriIds) }},
                     allPenelitianIds: {{ json_encode($allPenelitianIds) }},
                     allBeritaIds: {{ json_encode($allBeritaIds) }},
                     allGaleriIds: {{ json_encode($allGaleriIds) }},
                     toggleSelect(field, id) {
                         let idx = this[field].indexOf(id);
                         if (idx > -1) {
                             this[field].splice(idx, 1);
                         } else {
                             if (this[field].length >= 6) {
                                 alert('Maksimal 6 item terpilih! Batalkan salah satu pilihan yang ada terlebih dahulu.');
                                 return;
                             }
                             this[field].push(id);
                         }
                     },
                     resetSelection(field) {
                         this[field] = [];
                     },
                     selectTop6(field, allIds) {
                         this[field] = allIds.slice(0, 6);
                     }
                 }">
                
                <input type="hidden" name="home_selected_penelitian" :value="JSON.stringify(selectedPenelitian)">
                <input type="hidden" name="home_selected_berita" :value="JSON.stringify(selectedBerita)">
                <input type="hidden" name="home_selected_galeri" :value="JSON.stringify(selectedGaleri)">

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-5 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shadow-inner">
                            <span class="material-symbols-rounded text-xl">view_carousel</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-lg font-extrabold text-gray-900">Konten Beranda Publik</h2>
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full">Maks. 6 Item per Bagian</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">Tentukan konten riset, berita, dan galeri yang ditampilkan di beranda depan. Klik kartu untuk memilih / membatalkan.</p>
                        </div>
                    </div>
                </div>

                <!-- Nav Tabs -->
                <div class="flex flex-wrap gap-2 p-1.5 bg-gray-100/80 rounded-2xl mb-6">
                    <button type="button" @click="activeCurTab = 'penelitian'"
                            :class="activeCurTab === 'penelitian' ? 'bg-white text-emerald-900 shadow-sm font-bold' : 'text-gray-600 hover:text-gray-900 font-semibold'"
                            class="flex-1 min-w-[140px] px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-rounded text-sm">science</span>
                        <span>Fasilitas & Riset</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold"
                              :class="selectedPenelitian.length > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-600'"
                              x-text="selectedPenelitian.length + ' / 6'"></span>
                    </button>

                    <button type="button" @click="activeCurTab = 'berita'"
                            :class="activeCurTab === 'berita' ? 'bg-white text-emerald-900 shadow-sm font-bold' : 'text-gray-600 hover:text-gray-900 font-semibold'"
                            class="flex-1 min-w-[140px] px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-rounded text-sm">newspaper</span>
                        <span>Berita & Artikel</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold"
                              :class="selectedBerita.length > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-600'"
                              x-text="selectedBerita.length + ' / 6'"></span>
                    </button>

                    <button type="button" @click="activeCurTab = 'galeri'"
                            :class="activeCurTab === 'galeri' ? 'bg-white text-emerald-900 shadow-sm font-bold' : 'text-gray-600 hover:text-gray-900 font-semibold'"
                            class="flex-1 min-w-[140px] px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-rounded text-sm">photo_library</span>
                        <span>Galeri Dokumentasi</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold"
                              :class="selectedGaleri.length > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-600'"
                              x-text="selectedGaleri.length + ' / 6'"></span>
                    </button>
                </div>

                <!-- TAB 1: PENELITIAN -->
                <div x-show="activeCurTab === 'penelitian'" class="space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100">
                        <div class="text-xs text-emerald-900">
                            <span class="font-bold">Info Riset di Beranda:</span>
                            <span x-show="selectedPenelitian.length === 0" class="text-emerald-700">Belum ada riset spesifik yang dipilih. Sistem otomatis menampilkan <strong>6 riset terbaru</strong>.</span>
                            <span x-show="selectedPenelitian.length > 0" class="text-emerald-700">Menampilkan <strong x-text="selectedPenelitian.length"></strong> riset terpilih di beranda.</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" @click="selectTop6('selectedPenelitian', allPenelitianIds)"
                                    class="px-3 py-1.5 bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold transition">
                                Pilih 6 Terbaru
                            </button>
                            <button type="button" @click="resetSelection('selectedPenelitian')"
                                    class="px-3 py-1.5 bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 rounded-lg text-xs font-semibold transition">
                                Kosongkan (Otomatis)
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[520px] overflow-y-auto p-1">
                        @forelse($allPenelitian ?? [] as $item)
                            <div @click="toggleSelect('selectedPenelitian', {{ $item->id }})"
                                 :class="selectedPenelitian.includes({{ $item->id }}) 
                                     ? 'border-2 border-emerald-500 bg-emerald-50/40 shadow-sm ring-2 ring-emerald-500/20' 
                                     : 'border border-gray-200 bg-white hover:border-gray-300'"
                                 class="cursor-pointer rounded-2xl p-3.5 transition-all flex flex-col justify-between group relative select-none">
                                <div>
                                    <div class="relative w-full h-32 rounded-xl overflow-hidden mb-3 bg-gray-100 border border-gray-100">
                                        <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute top-2 right-2">
                                            <span x-show="selectedPenelitian.includes({{ $item->id }})"
                                                  class="inline-flex items-center gap-1 bg-emerald-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow">
                                                <span class="material-symbols-rounded text-xs">check</span>
                                                <span x-text="'Tampil #' + (selectedPenelitian.indexOf({{ $item->id }}) + 1)"></span>
                                            </span>
                                            <span x-show="!selectedPenelitian.includes({{ $item->id }})"
                                                  class="inline-flex items-center gap-1 bg-black/60 backdrop-blur-sm text-white/90 text-[10px] font-semibold px-2 py-0.5 rounded-full opacity-80 group-hover:opacity-100">
                                                Pilih
                                            </span>
                                        </div>
                                        @if($item->code)
                                            <span class="absolute bottom-2 left-2 bg-black/70 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-md">
                                                {{ $item->code }}
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="text-xs font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2 mb-1">
                                        {{ $item->title }}
                                    </h4>
                                    <p class="text-[11px] text-gray-500 line-clamp-2 leading-relaxed">
                                        {{ $item->description }}
                                    </p>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400 font-medium">
                                    <span>{{ $item->field ?? $item->facility ?? 'Riset' }}</span>
                                    <span class="font-mono text-gray-300">ID: {{ $item->id }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-xs text-gray-400">
                                Belum ada data penelitian yang tersimpan.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- TAB 2: BERITA -->
                <div x-show="activeCurTab === 'berita'" class="space-y-4" style="display: none;">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-blue-50/50 p-4 rounded-2xl border border-blue-100">
                        <div class="text-xs text-blue-900">
                            <span class="font-bold">Info Berita di Beranda:</span>
                            <span x-show="selectedBerita.length === 0" class="text-blue-700">Belum ada berita spesifik yang dipilih. Sistem otomatis menampilkan <strong>6 berita terbaru</strong>.</span>
                            <span x-show="selectedBerita.length > 0" class="text-blue-700">Menampilkan <strong x-text="selectedBerita.length"></strong> berita terpilih di beranda.</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" @click="selectTop6('selectedBerita', allBeritaIds)"
                                    class="px-3 py-1.5 bg-white hover:bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold transition">
                                Pilih 6 Terbaru
                            </button>
                            <button type="button" @click="resetSelection('selectedBerita')"
                                    class="px-3 py-1.5 bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 rounded-lg text-xs font-semibold transition">
                                Kosongkan (Otomatis)
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[520px] overflow-y-auto p-1">
                        @forelse($allBerita ?? [] as $item)
                            <div @click="toggleSelect('selectedBerita', {{ $item->id }})"
                                 :class="selectedBerita.includes({{ $item->id }}) 
                                     ? 'border-2 border-emerald-500 bg-emerald-50/40 shadow-sm ring-2 ring-emerald-500/20' 
                                     : 'border border-gray-200 bg-white hover:border-gray-300'"
                                 class="cursor-pointer rounded-2xl p-3.5 transition-all flex flex-col justify-between group relative select-none">
                                <div>
                                    <div class="relative w-full h-32 rounded-xl overflow-hidden mb-3 bg-gray-100 border border-gray-100">
                                        <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute top-2 right-2">
                                            <span x-show="selectedBerita.includes({{ $item->id }})"
                                                  class="inline-flex items-center gap-1 bg-emerald-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow">
                                                <span class="material-symbols-rounded text-xs">check</span>
                                                <span x-text="'Tampil #' + (selectedBerita.indexOf({{ $item->id }}) + 1)"></span>
                                            </span>
                                            <span x-show="!selectedBerita.includes({{ $item->id }})"
                                                  class="inline-flex items-center gap-1 bg-black/60 backdrop-blur-sm text-white/90 text-[10px] font-semibold px-2 py-0.5 rounded-full opacity-80 group-hover:opacity-100">
                                                Pilih
                                            </span>
                                        </div>
                                        <span class="absolute bottom-2 left-2 bg-emerald-950/80 backdrop-blur-sm text-white text-[10px] font-semibold px-2 py-0.5 rounded-md">
                                            {{ $item->category ?? 'Berita' }}
                                        </span>
                                    </div>
                                    <h4 class="text-xs font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2 mb-1">
                                        {{ $item->title }}
                                    </h4>
                                    <p class="text-[11px] text-gray-500 line-clamp-2 leading-relaxed">
                                        {{ $item->description }}
                                    </p>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400 font-medium">
                                    <span>{{ $item->date ? date('d M Y', strtotime($item->date)) : '-' }}</span>
                                    <span class="font-mono text-gray-300">ID: {{ $item->id }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-xs text-gray-400">
                                Belum ada data berita yang tersimpan.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- TAB 3: GALERI -->
                <div x-show="activeCurTab === 'galeri'" class="space-y-4" style="display: none;">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-amber-50/50 p-4 rounded-2xl border border-amber-100">
                        <div class="text-xs text-amber-900">
                            <span class="font-bold">Info Galeri di Beranda:</span>
                            <span x-show="selectedGaleri.length === 0" class="text-amber-700">Belum ada galeri spesifik yang dipilih. Sistem otomatis menampilkan <strong>6 galeri terbaru</strong>.</span>
                            <span x-show="selectedGaleri.length > 0" class="text-amber-700">Menampilkan <strong x-text="selectedGaleri.length"></strong> galeri terpilih di beranda.</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" @click="selectTop6('selectedGaleri', allGaleriIds)"
                                    class="px-3 py-1.5 bg-white hover:bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold transition">
                                Pilih 6 Terbaru
                            </button>
                            <button type="button" @click="resetSelection('selectedGaleri')"
                                    class="px-3 py-1.5 bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 rounded-lg text-xs font-semibold transition">
                                Kosongkan (Otomatis)
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[520px] overflow-y-auto p-1">
                        @forelse($allGaleri ?? [] as $item)
                            @php
                                $photos = is_array($item->photos) ? $item->photos : [];
                                $coverPhoto = count($photos) > 0 ? asset('storage/' . $photos[0]) : asset('assets/default_hero.jpg');
                                $photoCount = count($photos);
                            @endphp
                            <div @click="toggleSelect('selectedGaleri', {{ $item->id }})"
                                 :class="selectedGaleri.includes({{ $item->id }}) 
                                     ? 'border-2 border-emerald-500 bg-emerald-50/40 shadow-sm ring-2 ring-emerald-500/20' 
                                     : 'border border-gray-200 bg-white hover:border-gray-300'"
                                 class="cursor-pointer rounded-2xl p-3.5 transition-all flex flex-col justify-between group relative select-none">
                                <div>
                                    <div class="relative w-full h-32 rounded-xl overflow-hidden mb-3 bg-gray-100 border border-gray-100">
                                        <img src="{{ $coverPhoto }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute top-2 right-2">
                                            <span x-show="selectedGaleri.includes({{ $item->id }})"
                                                  class="inline-flex items-center gap-1 bg-emerald-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow">
                                                <span class="material-symbols-rounded text-xs">check</span>
                                                <span x-text="'Tampil #' + (selectedGaleri.indexOf({{ $item->id }}) + 1)"></span>
                                            </span>
                                            <span x-show="!selectedGaleri.includes({{ $item->id }})"
                                                  class="inline-flex items-center gap-1 bg-black/60 backdrop-blur-sm text-white/90 text-[10px] font-semibold px-2 py-0.5 rounded-full opacity-80 group-hover:opacity-100">
                                                Pilih
                                            </span>
                                        </div>
                                        <span class="absolute bottom-2 left-2 bg-black/70 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1">
                                            <span class="material-symbols-rounded text-[11px]">photo_camera</span>
                                            {{ $photoCount }} Foto
                                        </span>
                                    </div>
                                    <h4 class="text-xs font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2 mb-1">
                                        {{ $item->title }}
                                    </h4>
                                    <p class="text-[11px] text-gray-500 line-clamp-2 leading-relaxed">
                                        {{ $item->description }}
                                    </p>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400 font-medium">
                                    <span>{{ $item->event_date ? date('d M Y', strtotime($item->event_date)) : '-' }}</span>
                                    <span class="font-mono text-gray-300">ID: {{ $item->id }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-xs text-gray-400">
                                Belum ada album galeri yang tersimpan.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- STICKY ACTION BUTTON BAR -->
            <div class="sticky bottom-6 bg-white/90 backdrop-blur-xl p-4 sm:p-5 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-200/80 flex flex-col sm:flex-row justify-between items-center gap-4 z-30">
                <div class="flex items-center gap-2.5 text-xs text-gray-500">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Pastikan data di atas sudah benar sebelum menyimpan.</span>
                </div>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-900 to-primary-950 hover:from-black hover:to-primary-950 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-primary-900/20 transition-all hover:scale-[1.02] text-sm">
                    <span class="material-symbols-rounded text-sm">save</span> Simpan Konten Beranda
                </button>
            </div>
        </div>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12 mb-12">
        <!-- 5. DAFTAR VISI MISI / POIN KEUNGGULAN -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 flex flex-col justify-between" x-data="{ open: false }">
            <div>
                <div class="flex justify-between items-start mb-6 border-b border-gray-100 pb-4">
                    <div>
                        <div class="flex items-center gap-1.5 text-primary font-bold text-sm mb-1">
                            <span class="material-symbols-rounded text-base">star</span> Poin Penting
                        </div>
                        <h2 class="text-lg font-extrabold text-gray-900">Daftar Poin Visi / Misi</h2>
                        <p class="text-xs text-gray-400">Poin-poin sorotan yang tampil di bagian profil Tentang Kami.</p>
                    </div>
                    <button @click="open = true" class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary-900 text-white font-bold py-2 px-3.5 rounded-xl text-xs shadow-sm transition">
                        <span>+</span> Tambah Poin
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50/70 border-y border-gray-100 text-gray-600 text-xs uppercase tracking-wider font-bold">
                                <th class="p-3.5 w-12 text-center">No</th>
                                <th class="p-3.5">Isi Poin</th>
                                <th class="p-3.5 text-right w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($aboutBullets as $index => $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-3.5 text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                                    <td class="p-3.5 font-medium text-gray-800 leading-relaxed">{{ $item->value ?? $item->content }}</td>
                                    <td class="p-3.5 text-right">
                                        <form action="{{ route('web.management.about.destroy', $item->id) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Hapus poin ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg font-bold text-xs transition">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-8 text-center text-gray-400 text-xs">Belum ada poin visi misi yang ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Tambah Poin -->
            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                style="display: none;">
                <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-lg shadow-2xl border border-gray-100 transform transition-all"
                    @click.away="open = false">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <h3 class="text-base font-extrabold text-gray-900">Tambah Poin Baru</h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-sm inline-flex items-center justify-center">
                            <span class="material-symbols-rounded text-base">close</span>
                        </button>
                    </div>
                    <form action="{{ route('web.management.about.store') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Teks Poin Visi / Misi</label>
                            <input type="text" name="content"
                                class="w-full p-3.5 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm"
                                placeholder="Contoh: Mengembangkan 100+ spesies tanaman obat endemik"
                                required>
                        </div>
                        <div class="flex justify-end gap-2.5">
                            <button type="button" @click="open = false"
                                class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl text-xs transition">Batal</button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-primary hover:bg-primary-900 text-white font-bold rounded-xl text-xs shadow-md transition">Simpan Poin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 6. DAFTAR GAMBAR LATAR HERO SLIDER -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 flex flex-col justify-between" x-data="{ open: false }">
            <div>
                <div class="flex justify-between items-start mb-6 border-b border-gray-100 pb-4">
                    <div>
                        <div class="flex items-center gap-1.5 text-primary font-bold text-sm mb-1">
                            <span class="material-symbols-rounded text-base">photo_library</span> Galeri Slider
                        </div>
                        <h2 class="text-lg font-extrabold text-gray-900">Foto Latar Belakang Hero</h2>
                        <p class="text-xs text-gray-400">Gambar yang bergantian tampil di latar layar pembuka beranda.</p>
                    </div>
                    <button @click="open = true" class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary-900 text-white font-bold py-2 px-3.5 rounded-xl text-xs shadow-sm transition">
                        <span>+</span> Unggah Foto
                    </button>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @forelse($homeBackgrounds ?? [] as $bg)
                        <div class="group relative rounded-2xl overflow-hidden border border-gray-200 shadow-sm aspect-video bg-gray-100">
                            <img src="{{ asset('storage/' . $bg->image_path) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2">
                                <form action="{{ route('web.management.home_bg.destroy', $bg->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus gambar background ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-8 text-center text-gray-400 text-xs">
                            Belum ada foto latar belakang hero yang diunggah.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Modal Tambah BG -->
            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                style="display: none;">
                <div class="bg-white rounded-3xl p-6 sm:p-8 w-full max-w-lg shadow-2xl border border-gray-100 transform transition-all"
                    @click.away="open = false">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <h3 class="text-base font-extrabold text-gray-900">Unggah Gambar Latar Hero Baru</h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-sm inline-flex items-center justify-center">
                            <span class="material-symbols-rounded text-base">close</span>
                        </button>
                    </div>
                    <form action="{{ route('web.management.home_bg.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Pilih Berkas Gambar</label>
                            <input type="file" name="image_path"
                                class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-primary text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white" required
                                accept="image/*">
                            <p class="text-[11px] text-gray-400 mt-2">Disarankan foto lanskap horizontal dengan resolusi minimal 1920x1080 piksel.</p>
                        </div>
                        <div class="flex justify-end gap-2.5">
                            <button type="button" @click="open = false"
                                class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl text-xs transition">Batal</button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-primary hover:bg-primary-900 text-white font-bold rounded-xl text-xs shadow-md transition">Unggah Gambar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
