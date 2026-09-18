@extends('layouts.dashboard')

@section('title', 'Manajemen Web - RESEARCH')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - RESEARCH</h1>
        <p class="text-gray-500 font-medium">Ubah daftar riset & fasilitas.</p>
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
            <h2 class="text-xl font-bold mb-6 text-gray-900 border-b border-gray-100 pb-4">Konten Hero Fasilitas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div><label class="block font-bold mb-2">Judul Hero</label><input type="text" name="research_hero_title"
                        class="w-full p-3 border-2 rounded-xl" value="{{ $contents['research_hero_title']->value ?? '' }}">
                </div>
                <div><label class="block font-bold mb-2">Deskripsi Hero</label>
                    <textarea name="research_hero_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['research_hero_desc']->value ?? '' }}</textarea>
                </div>
                <div class="md:col-span-2"><label class="block font-bold mb-2">Background Hero</label><input type="file"
                        name="research_hero_bg" class="w-full p-3 border-2 border-dashed rounded-xl" accept="image/*">
                    @if (isset($contents['research_hero_bg']) && $contents['research_hero_bg']->image_path)
                        <img src="{{ asset('storage/' . $contents['research_hero_bg']->image_path) }}"
                            class="mt-2 h-20 rounded">
                    @endif
                </div>
            </div>
            <div class="mt-6 flex justify-end"><button type="submit"
                    class="bg-gray-900 text-white font-bold py-3 px-8 rounded-xl">Simpan Konten Statis</button></div>
        </div>
    </form>

    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100" x-data="{ open: false }">
        <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
            <h2 class="text-xl font-bold text-gray-900">Daftar Fasilitas / Riset </h2>
            <button @click="open = true" class="bg-primary text-white font-bold py-2 px-4 rounded-lg text-sm">+ Tambah
                Riset</button>
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
                    @forelse($researches as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                            <td class="p-4">
                                @if ($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}"
                                        class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                @else
                                    -
                                @endif
                            </td>

                            <td class="p-4 font-bold text-gray-800">{{ $item->title }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ Str::limit($item->description, 50) }}</td>
                            <td class="p-4 text-right">
                                <form action="{{ route('web.management.researches.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus riset ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:bg-red-50 p-2 rounded-lg font-bold text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-400">Belum ada riset.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="open = false">
                <h3 class="text-lg font-bold mb-4">Tambah Riset Baru</h3>
                <form action="{{ route('web.management.researches.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">

                        <div><label class="block font-bold mb-2">Judul Riset</label><input type="text" name="title"
                                class="w-full p-3 border-2 rounded-xl" required></div>
                        <div><label class="block font-bold mb-2">Deskripsi</label>
                            <textarea name="description" class="w-full p-3 border-2 rounded-xl h-[100px]" required></textarea>
                        </div>
                        <div><label class="block font-bold mb-2">Gambar Detail</label><input type="file"
                                name="image_path" class="w-full p-3 border-2 rounded-xl" accept="image/*" required></div>
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
    </div>
@endsection
