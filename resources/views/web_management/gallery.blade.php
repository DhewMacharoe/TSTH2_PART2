@extends('layouts.dashboard')

@section('title', 'Manajemen Web - GALLERY')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - GALERI</h1>
        <p class="text-gray-500 font-medium">Ubah daftar galeri foto tanpa batas.</p>
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
            <h2 class="text-xl font-bold mb-6 text-gray-900 border-b border-gray-100 pb-4">Konten Hero Galeri</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div><label class="block font-bold mb-2">Judul Hero</label><input type="text" name="gallery_hero_title"
                        class="w-full p-3 border-2 rounded-xl" value="{{ $contents['gallery_hero_title']->value ?? '' }}">
                </div>
                <div><label class="block font-bold mb-2">Deskripsi Hero</label>
                    <textarea name="gallery_hero_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['gallery_hero_desc']->value ?? '' }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end"><button type="submit"
                    class="bg-gray-900 text-white font-bold py-3 px-8 rounded-xl">Simpan Konten Statis</button></div>
        </div>
    </form>

    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100" x-data="{ open: false, editOpen: false, detailOpen: false, activeItem: {} }">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 border-b border-gray-100 pb-4 gap-4">
            <h2 class="text-xl font-bold text-gray-900">Daftar Foto Galeri</h2>
            <div class="flex gap-2 w-full md:w-auto">
                <form action="{{ route('web.management.gallery') }}" method="GET" class="flex flex-1 md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari foto..." class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary text-sm">
                    <button type="submit" class="bg-gray-800 text-white px-3 py-2 rounded-lg ml-2 text-sm hover:bg-gray-900">Cari</button>
                </form>
                <button @click="open = true" class="bg-primary text-white font-bold py-2 px-4 rounded-lg text-sm shrink-0">+ Tambah Foto</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-200">
                        <th class="p-4 font-bold">Gambar</th>
                        <th class="p-4 font-bold">Judul</th>
                        <th class="p-4 font-bold">Deskripsi</th>
                        <th class="p-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($galleries as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                            <td class="p-4">
                                <img src="{{ asset('storage/' . $item->image_path) }}"
                                    class="w-20 h-16 object-cover rounded-lg shadow-sm">
                            </td>
                            <td class="p-4 font-bold text-gray-800">{{ $item->title }}</td>
                            <td class="p-4 text-sm">{{ $item->description }}</td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="activeItem = {{ json_encode($item) }}; detailOpen = true" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg font-bold text-sm">Detail</button>
                                    <button @click="activeItem = {{ json_encode($item) }}; editOpen = true" class="text-yellow-600 hover:bg-yellow-50 p-2 rounded-lg font-bold text-sm">Edit</button>
                                    <form action="{{ route('web.management.galleries.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus foto ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:bg-red-50 p-2 rounded-lg font-bold text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-400">Belum ada foto galeri.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="open = false">
                <h3 class="text-lg font-bold mb-4">Tambah Foto Galeri</h3>
                <form action="{{ route('web.management.galleries.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                        <div><label class="block font-bold mb-2">Judul Foto</label><input type="text" name="title"
                                class="w-full p-3 border-2 rounded-xl" required></div>
                        <div><label class="block font-bold mb-2">Deskripsi (Opsional)</label>
                            <textarea name="description" class="w-full p-3 border-2 rounded-xl h-[100px]"></textarea>
                        </div>
                        <div><label class="block font-bold mb-2">File Foto</label><input type="file" name="image_path"
                                class="w-full p-3 border-2 rounded-xl" accept="image/*" required></div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="open = false"
                            class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl">Upload
                            Foto</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div x-show="editOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="editOpen = false">
                <h3 class="text-lg font-bold mb-4">Edit Foto Galeri</h3>
                <form :action="`/web-management/galleries/${activeItem.id}`" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                        <div><label class="block font-bold mb-2">Judul Foto</label><input type="text" name="title" x-model="activeItem.title" class="w-full p-3 border-2 rounded-xl" required></div>
                        <div><label class="block font-bold mb-2">Deskripsi (Opsional)</label>
                            <textarea name="description" x-model="activeItem.description" class="w-full p-3 border-2 rounded-xl h-[100px]"></textarea>
                        </div>
                        <div><label class="block font-bold mb-2">File Foto (Opsional)</label><input type="file" name="image_path" class="w-full p-3 border-2 rounded-xl" accept="image/*"></div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="editOpen = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-white font-bold rounded-xl">Update Foto</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Detail -->
        <div x-show="detailOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="detailOpen = false">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Detail Galeri</h3>
                <div class="space-y-4 text-gray-700 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <template x-if="activeItem.image_path">
                        <img :src="`/storage/${activeItem.image_path}`" class="w-full h-48 object-cover rounded-xl mb-4">
                    </template>
                    <div>
                        <strong class="block text-gray-900">Judul:</strong>
                        <p x-text="activeItem.title"></p>
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
