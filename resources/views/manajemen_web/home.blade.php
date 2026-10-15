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
                <span>🏠</span> Manajemen Halaman Beranda
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Pengaturan Halaman Beranda</h1>
            <p class="text-white/70 text-sm mt-1 max-w-xl">Ubah teks pembuka, latar belakang gambar hero, profil instansi, serta kutipan sambutan direktur dengan mudah.</p>
        </div>
        <div class="relative z-10 shrink-0">
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl text-xs font-bold backdrop-blur-sm transition-all hover:scale-105 shadow-sm">
                <span>🌐</span> Pratinjau Beranda
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl mb-8 font-semibold border border-emerald-200/80 flex items-center gap-3 shadow-sm animate-fade-in">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0">✓</div>
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
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-extrabold text-xl shadow-inner">🎯</div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Bagian Hero (Layar Pembuka)</h2>
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
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-extrabold text-xl shadow-inner">🏛️</div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Tentang TSTH2 (Profil & Sekilas Mengenal)</h2>
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
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center font-extrabold text-xl shadow-inner">🔬</div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Pengantar Fasilitas & Penelitian di Beranda</h2>
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
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-extrabold text-xl shadow-inner">👤</div>
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

            <!-- STICKY ACTION BUTTON BAR -->
            <div class="sticky bottom-6 bg-white/90 backdrop-blur-xl p-4 sm:p-5 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-200/80 flex flex-col sm:flex-row justify-between items-center gap-4 z-30">
                <div class="flex items-center gap-2.5 text-xs text-gray-500">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Pastikan data di atas sudah benar sebelum menyimpan.</span>
                </div>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-900 to-primary-950 hover:from-black hover:to-primary-950 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-primary-900/20 transition-all hover:scale-[1.02] text-sm">
                    <span>💾</span> Simpan Konten Beranda
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
                        <div class="flex items-center gap-2 text-primary font-bold text-sm mb-1">
                            <span>✨</span> Poin Penting
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
                        <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-sm">✕</button>
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
                        <div class="flex items-center gap-2 text-primary font-bold text-sm mb-1">
                            <span>🖼️</span> Galeri Slider
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
                        <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-sm">✕</button>
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
