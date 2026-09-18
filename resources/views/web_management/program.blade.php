@extends('layouts.dashboard')

@section('title', 'Manajemen Web - PROGRAM')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - PROGRAM</h1>
        <p class="text-gray-500 font-medium">Ubah teks statis dan daftar program unggulan tanpa batas.</p>
    </div>

    @if (session('success'))
        <div
            class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 font-bold border border-emerald-100 flex items-center gap-3">
            <span class="text-xl">✅</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Konten Statis Hero -->
    <form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold mb-6 text-gray-900 border-b border-gray-100 pb-4">Konten Hero Program</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div><label class="block font-bold mb-2">Judul Hero</label><input type="text" name="program_hero_title"
                        class="w-full p-3 border-2 rounded-xl" value="{{ $contents['program_hero_title']->value ?? '' }}">
                </div>
                <div><label class="block font-bold mb-2">Deskripsi Hero</label>
                    <textarea name="program_hero_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['program_hero_desc']->value ?? '' }}</textarea>
                </div>
                <div class="md:col-span-2"><label class="block font-bold mb-2">Background Hero</label><input type="file"
                        name="program_hero_bg" class="w-full p-3 border-2 border-dashed rounded-xl" accept="image/*">
                    @if (isset($contents['program_hero_bg']) && $contents['program_hero_bg']->image_path)
                        <img src="{{ asset('storage/' . $contents['program_hero_bg']->image_path) }}"
                            class="mt-2 h-20 rounded">
                    @endif
                </div>
            </div>
            <div class="mt-6 flex justify-end"><button type="submit"
                    class="bg-gray-900 text-white font-bold py-3 px-8 rounded-xl">Simpan Konten Statis</button></div>
        </div>
    </form>

    <!-- Daftar Program -->
    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100" x-data="{ open: false, editOpen: false, detailOpen: false, activeItem: {} }">
        <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
            <h2 class="text-xl font-bold text-gray-900">Daftar Program</h2>
            <button @click="open = true" class="bg-primary text-white font-bold py-2 px-4 rounded-lg text-sm">+ Tambah
                Program</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-200">

                        <th class="p-4 font-bold text-gray-600">Judul</th>
                        <th class="p-4 font-bold text-gray-600">Deskripsi</th>
                        <th class="p-4 font-bold text-gray-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($programs as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50">

                            <td class="p-4 font-bold text-gray-800">{{ $item->title }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $item->description }}</td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="activeItem = {{ json_encode($item) }}; detailOpen = true" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg font-bold text-sm">Detail</button>
                                    <button @click="activeItem = {{ json_encode($item) }}; editOpen = true" class="text-yellow-600 hover:bg-yellow-50 p-2 rounded-lg font-bold text-sm">Edit</button>
                                    <form action="{{ route('web.management.programs.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus program ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:bg-red-50 p-2 rounded-lg font-bold text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-gray-400">Belum ada program.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah -->
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="open = false">
                <h3 class="text-lg font-bold mb-4">Tambah Program Baru</h3>
                <form action="{{ route('web.management.programs.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">Judul Program</label>
                            <input type="text" name="title" class="w-full p-3 border-2 rounded-xl" required>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" class="w-full p-3 border-2 rounded-xl h-[100px]" required></textarea>
                        </div>
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
                <h3 class="text-lg font-bold mb-4">Edit Program</h3>
                <form :action="`/web-management/programs/${activeItem.id}`" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">Judul Program</label>
                            <input type="text" name="title" x-model="activeItem.title" class="w-full p-3 border-2 rounded-xl" required>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" x-model="activeItem.description" class="w-full p-3 border-2 rounded-xl h-[100px]" required></textarea>
                        </div>
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
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Detail Program</h3>
                <div class="space-y-4 text-gray-700">
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
