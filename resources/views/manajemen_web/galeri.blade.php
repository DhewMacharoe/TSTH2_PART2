@extends('layouts.dasbor')

@section('title', 'Manajemen Web - GALLERY')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - GALERI DOKUMENTASI</h1>
        <p class="text-gray-500 font-medium">Kelola album foto galeri (Sekali unggah hingga 10 gambar per album).</p>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 font-bold border border-emerald-100 flex items-center gap-3">
            <span class="text-xl">✅</span>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-8 font-bold border border-red-100 space-y-1">
            @foreach ($errors->all() as $error)
                <p>⚠️ {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data">
        @csrf 
        @method('PUT')
        <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold mb-6 text-gray-900 border-b border-gray-100 pb-4">Konten Hero Galeri</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block font-bold mb-2">Judul Hero</label>
                    <input type="text" name="galeri_hero_title" class="w-full p-3 border-2 rounded-xl" value="{{ $konten['galeri_hero_title']->value ?? '' }}">
                </div>
                <div>
                    <label class="block font-bold mb-2">Deskripsi Hero</label>
                    <textarea name="galeri_hero_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $konten['galeri_hero_desc']->value ?? '' }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-gray-900 text-white font-bold py-3 px-8 rounded-xl">Simpan Konten Statis</button>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100" 
         x-data="{ 
            open: false, 
            editOpen: false, 
            detailOpen: false, 
            activeItem: { id: '', title: '', description: '', photos: [] },
            setItem(item) {
                this.activeItem = Object.assign({}, item);
                let p = this.activeItem.photos;
                if (typeof p === 'string') {
                    try { p = JSON.parse(p); } catch(e) { p = []; }
                }
                this.activeItem.photos = Array.isArray(p) ? p : [];
            }
         }">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 border-b border-gray-100 pb-4 gap-4">
            <h2 class="text-xl font-bold text-gray-900">Daftar Album Galeri</h2>
            <div class="flex gap-2 w-full md:w-auto">
                <form action="{{ route('web.management.galeri') }}" method="GET" class="flex flex-1 md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari album..." class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary text-sm">
                    <button type="submit" class="bg-gray-800 text-white px-3 py-2 rounded-lg ml-2 text-sm hover:bg-gray-900">Cari</button>
                </form>
                <button type="button" @click="open = true" class="bg-primary text-white font-bold py-2 px-4 rounded-lg text-sm shrink-0">+ Tambah Album Foto</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-200">
                        <th class="p-4 font-bold">Cover Album</th>
                        <th class="p-4 font-bold">Judul Album</th>
                        <th class="p-4 font-bold">Jumlah Foto</th>
                        <th class="p-4 font-bold">Deskripsi</th>
                        <th class="p-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($galeri) && count($galeri) > 0)
                        @foreach($galeri as $item)
                            @php
                                $photos = is_array($item->photos) ? $item->photos : (json_decode($item->photos, true) ?? []);
                                $cover = count($photos) > 0 ? $photos[0] : 'default.jpg';$photoCount = count($photos);$itemData = [
                                    'id' => $item->id,
                                    'title' => $item->title,
                                    'description' => $item->description,
                                    'photos' => $photos
                                ];
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                                <td class="p-4">
                                    <div class="relative w-20 h-16">
                                        <img src="{{ asset('storage/' . $cover) }}" class="w-20 h-16 object-cover rounded-lg shadow-sm border">
                                        @if($photoCount > 1)
                                            <span class="absolute -top-2 -right-2 bg-emerald-600 text-white text-[10px] font-extrabold px-1.5 py-0.5 rounded-full shadow">
                                                +{{ $photoCount - 1 }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 font-bold text-gray-800">{{ $item->title }}</td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-full">
                                        📷 {{ $photoCount }} Foto (Max 10)
                                    </span>
                                </td>
                                <td class="p-4 text-sm">{{ Str::limit($item->description, 50) }}</td>
                                <td class="p-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="setItem(@js($itemData)); detailOpen = true" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg font-bold text-sm">Detail</button>
                                        <button type="button" @click="setItem(@js($itemData)); editOpen = true" class="text-yellow-600 hover:bg-yellow-50 p-2 rounded-lg font-bold text-sm">Edit</button>
                                        <form action="{{ route('web.management.galeri.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus album ini beserta semua fotonya?');">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg font-bold text-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-400">Belum ada album galeri.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah Album -->
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="open = false">
                <h3 class="text-lg font-bold mb-4">Tambah Album Foto Galeri</h3>
                <form action="{{ route('web.management.galeri.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                        <div>
                            <label class="block font-bold mb-2">Judul Album / Dokumentasi</label>
                            <input type="text" name="title" class="w-full p-3 border-2 rounded-xl" placeholder="Contoh: Peresmian Gedung TSTH2" required>
                        </div>
                        <div>
                            <label class="block font-bold mb-2">Deskripsi (Opsional)</label>
                            <textarea name="description" class="w-full p-3 border-2 rounded-xl h-[90px]" placeholder="Penjelasan singkat mengenai dokumentasi kegiatan..."></textarea>
                        </div>
                        <div>
                            <label class="block font-bold mb-2">Upload Foto (Dapat pilih banyak foto sekaligus)</label>
                            <input type="file" name="images[]" class="w-full p-3 border-2 rounded-xl" accept="image/*" multiple required>
                            <p class="text-xs text-gray-500 mt-1 font-semibold">💡 Tekan Ctrl/Shift untuk memilih beberapa foto sekaligus. Maksimal 10 foto per album (Ukuran maks 20MB per foto).</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="open = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl">Upload Album Foto</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div x-show="editOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="editOpen = false">
                <h3 class="text-lg font-bold mb-4">Edit Album Foto Galeri</h3>
                <form :action="`/web-management/galeri/${activeItem.id}`" method="POST" enctype="multipart/form-data">
                    @csrf 
                    @method('PUT')
                    <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                        <div>
                            <label class="block font-bold mb-2">Judul Album</label>
                            <input type="text" name="title" x-model="activeItem.title" class="w-full p-3 border-2 rounded-xl" required>
                        </div>
                        <div>
                            <label class="block font-bold mb-2">Deskripsi (Opsional)</label>
                            <textarea name="description" x-model="activeItem.description" class="w-full p-3 border-2 rounded-xl h-[90px]"></textarea>
                        </div>
                        
                        <!-- Kelola / Hapus Foto Saat Ini -->
                        <div>
                            <label class="block font-bold mb-2">Foto Saat Ini (Klik tombol merah untuk membuang foto)</label>
                            
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                                <template x-for="(photo, index) in activeItem.photos" :key="index">
                                    <div class="flex flex-col border border-gray-200 rounded-xl p-2 bg-gray-50 shadow-sm">
                                        <div class="relative w-full h-24 rounded-lg overflow-hidden bg-gray-200 border mb-2">
                                            <img :src="`/storage/${photo}`" class="w-full h-full object-cover" alt="Foto">
                                        </div>
                                        <button type="button" 
                                            @click="if(confirm('Hapus foto ini dari album?')) { 
                                                fetch(`/web-management/galeri/${activeItem.id}/photo/${index}`, {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content'),
                                                        'Accept': 'application/json'
                                                    }
                                                }).then(res => { if(res.ok) location.reload(); });
                                            }" 
                                            class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1.5 px-2 rounded-lg transition text-center shrink-0">
                                            🗑️ Hapus Foto
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <div x-show="!activeItem.photos || activeItem.photos.length === 0" class="text-sm text-gray-400 italic mb-4">
                                Belum ada foto di album ini.
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold mb-2">Tambah Foto Baru (Opsional)</label>
                            <input type="file" name="images[]" class="w-full p-3 border-2 rounded-xl" accept="image/*" multiple>
                            <p class="text-xs text-gray-500 mt-1">Pilih foto tambahan jika ingin menambah ke album ini.</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="editOpen = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-white font-bold rounded-xl">Update Album</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Detail -->
        <div x-show="detailOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-2xl shadow-xl" @click.away="detailOpen = false">
                <h3 class="text-lg font-bold mb-2 border-b pb-2">Detail Album Galeri</h3>
                <div class="space-y-4 text-gray-700 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <div>
                        <strong class="block text-gray-900 text-lg" x-text="activeItem.title"></strong>
                        <p x-text="activeItem.description" class="whitespace-pre-wrap text-sm text-gray-600 mt-1"></p>
                    </div>
                    <div>
                        <strong class="block text-gray-900 mb-2">Daftar Foto dalam Album:</strong>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <template x-for="(photo, index) in activeItem.photos" :key="index">
                                <div class="relative border rounded-lg overflow-hidden shadow-sm h-32 bg-gray-100">
                                    <img :src="`/storage/${photo}`" class="w-full h-full object-cover block" alt="Foto">
                                    <template x-if="index === 0">
                                        <span class="absolute top-1 left-1 bg-emerald-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow z-10">
                                            Cover Utama
                                        </span>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div x-show="!activeItem.photos || activeItem.photos.length === 0" class="text-sm text-gray-400 italic">
                            Album ini tidak memiliki foto.
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" @click="detailOpen = false" class="px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection