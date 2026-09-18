@extends('layouts.dashboard')

@section('title', 'Manajemen Web - NEWS')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - BERITA</h1>
        <p class="text-gray-500 font-medium">Ubah daftar berita & artikel.</p>
    </div>

    @if (session('success'))
        <div
            class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 font-bold border border-emerald-100 flex items-center gap-3">
            <span class="text-xl">✅</span>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold mb-6 text-gray-900 border-b border-gray-100 pb-4">Konten Hero Berita</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div><label class="block font-bold mb-2">Judul Hero</label><input type="text" name="news_hero_title"
                        class="w-full p-3 border-2 rounded-xl" value="{{ $contents['news_hero_title']->value ?? '' }}">
                </div>
                <div><label class="block font-bold mb-2">Deskripsi Hero</label>
                    <textarea name="news_hero_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['news_hero_desc']->value ?? '' }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end"><button type="submit"
                    class="bg-gray-900 text-white font-bold py-3 px-8 rounded-xl">Simpan Konten Statis</button></div>
        </div>
    </form>

    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100" x-data="{ open: false, editOpen: false, detailOpen: false, activeItem: {} }">
        <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
            <h2 class="text-xl font-bold text-gray-900">Daftar Berita</h2>
            <button @click="open = true" class="bg-primary text-white font-bold py-2 px-4 rounded-lg text-sm">+ Tambah
                Berita</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-200">
                        <th class="p-4 font-bold">Gambar</th>
                        <th class="p-4 font-bold">Kategori</th>
                        <th class="p-4 font-bold">Judul</th>
                        <th class="p-4 font-bold">Tanggal</th>
                        <th class="p-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                            <td class="p-4">
                                @if ($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}"
                                        class="w-16 h-10 object-cover rounded shadow-sm">
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-4 font-bold text-accent">{{ $item->category }}</td>
                            <td class="p-4 font-bold text-gray-800">{{ $item->title }}</td>
                            <td class="p-4 text-sm">{{ $item->date }}</td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="activeItem = {{ json_encode($item) }}; detailOpen = true" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg font-bold text-sm">Detail</button>
                                    <button @click="activeItem = {{ json_encode($item) }}; editOpen = true" class="text-yellow-600 hover:bg-yellow-50 p-2 rounded-lg font-bold text-sm">Edit</button>
                                    <form action="{{ route('web.management.news.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus berita ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:bg-red-50 p-2 rounded-lg font-bold text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-400">Belum ada berita.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="open = false">
                <h3 class="text-lg font-bold mb-4">Tambah Berita Baru</h3>
                <form action="{{ route('web.management.news.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                        <div><label class="block font-bold mb-2">Kategori</label><input type="text" name="category"
                                class="w-full p-3 border-2 rounded-xl" required placeholder="Misal: Inovasi"></div>
                        <div><label class="block font-bold mb-2">Judul Berita</label><input type="text" name="title"
                                class="w-full p-3 border-2 rounded-xl" required></div>
                        <div><label class="block font-bold mb-2">Deskripsi</label>
                            <textarea name="description" class="w-full p-3 border-2 rounded-xl h-[100px]" required></textarea>
                        </div>
                        <div><label class="block font-bold mb-2">Tanggal (String)</label><input type="text"
                                name="date" class="w-full p-3 border-2 rounded-xl" placeholder="Misal: 12 Agustus 2026">
                        </div>
                        <div><label class="block font-bold mb-2">Gambar Cover</label><input type="file" name="image_path"
                                class="w-full p-3 border-2 rounded-xl" accept="image/*"></div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="open = false"
                            class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div x-show="editOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="editOpen = false">
                <h3 class="text-lg font-bold mb-4">Edit Berita</h3>
                <form :action="`/web-management/news/${activeItem.id}`" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                        <div><label class="block font-bold mb-2">Kategori</label><input type="text" name="category" x-model="activeItem.category" class="w-full p-3 border-2 rounded-xl" required></div>
                        <div><label class="block font-bold mb-2">Judul Berita</label><input type="text" name="title" x-model="activeItem.title" class="w-full p-3 border-2 rounded-xl" required></div>
                        <div><label class="block font-bold mb-2">Deskripsi</label>
                            <textarea name="description" x-model="activeItem.description" class="w-full p-3 border-2 rounded-xl h-[100px]" required></textarea>
                        </div>
                        <div><label class="block font-bold mb-2">Tanggal (String)</label><input type="text" name="date" x-model="activeItem.date" class="w-full p-3 border-2 rounded-xl"></div>
                        <div><label class="block font-bold mb-2">Gambar Cover (Opsional)</label><input type="file" name="image_path" class="w-full p-3 border-2 rounded-xl" accept="image/*"></div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="editOpen = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-white font-bold rounded-xl">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Detail -->
        <div x-show="detailOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="detailOpen = false">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Detail Berita</h3>
                <div class="space-y-4 text-gray-700 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <template x-if="activeItem.image_path">
                        <img :src="`/storage/${activeItem.image_path}`" class="w-full h-48 object-cover rounded-xl mb-4">
                    </template>
                    <div>
                        <strong class="block text-gray-900">Kategori:</strong>
                        <p x-text="activeItem.category"></p>
                    </div>
                    <div>
                        <strong class="block text-gray-900">Judul:</strong>
                        <p x-text="activeItem.title"></p>
                    </div>
                    <div>
                        <strong class="block text-gray-900">Tanggal:</strong>
                        <p x-text="activeItem.date"></p>
                    </div>
                    <div>
                        <strong class="block text-gray-900">Deskripsi:</strong>
                        <p x-text="activeItem.description" class="whitespace-pre-wrap"></p>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" @click="detailOpen = false" class="px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
