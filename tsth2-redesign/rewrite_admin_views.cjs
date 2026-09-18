const fs = require("fs");
const path = require("path");

const viewsDir = "e:/magang/tsth2-redesign/resources/views/web_management";

const components = {
    home: `
@extends('layouts.dashboard')

@section('title', 'Manajemen Web - HOME')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - HOME</h1>
        <p class="text-gray-500 font-medium">Ubah teks statis dan daftar Visi Misi.</p>
    </div>
</div>

@if(session('success'))
<div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 font-bold border border-emerald-100 flex items-center gap-3">
    <span class="text-xl">✅</span>
    {{ session('success') }}
</div>
@endif

<!-- Form Konten Statis -->
<form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-6 text-gray-900 border-b border-gray-100 pb-4">Konten Utama</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div><label class="block font-bold mb-2">Badge Hero</label><input type="text" name="home_hero_badge" class="w-full p-3 border-2 rounded-xl" value="{{ $contents['home_hero_badge']->value ?? '' }}"></div>
            <div><label class="block font-bold mb-2">Judul Hero</label><input type="text" name="home_hero_title" class="w-full p-3 border-2 rounded-xl" value="{{ $contents['home_hero_title']->value ?? '' }}"></div>
            <div><label class="block font-bold mb-2">Deskripsi Hero</label><textarea name="home_hero_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['home_hero_desc']->value ?? '' }}</textarea></div>
            <div><label class="block font-bold mb-2">Background Hero 1</label><input type="file" name="home_hero_bg_1" class="w-full p-3 border-2 border-dashed rounded-xl" accept="image/*">
                @if(isset($contents['home_hero_bg_1']) && $contents['home_hero_bg_1']->image_path)<img src="{{ asset('storage/' . $contents['home_hero_bg_1']->image_path) }}" class="mt-2 h-20 rounded">@endif
            </div>
            <div><label class="block font-bold mb-2">Background Hero 2</label><input type="file" name="home_hero_bg_2" class="w-full p-3 border-2 border-dashed rounded-xl" accept="image/*">
                @if(isset($contents['home_hero_bg_2']) && $contents['home_hero_bg_2']->image_path)<img src="{{ asset('storage/' . $contents['home_hero_bg_2']->image_path) }}" class="mt-2 h-20 rounded">@endif
            </div>

            <div><label class="block font-bold mb-2">Judul About</label><input type="text" name="home_about_title" class="w-full p-3 border-2 rounded-xl" value="{{ $contents['home_about_title']->value ?? '' }}"></div>
            <div><label class="block font-bold mb-2">Deskripsi About</label><textarea name="home_about_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['home_about_desc']->value ?? '' }}</textarea></div>
            <div><label class="block font-bold mb-2">Background About</label><input type="file" name="home_about_bg" class="w-full p-3 border-2 border-dashed rounded-xl" accept="image/*">
                @if(isset($contents['home_about_bg']) && $contents['home_about_bg']->image_path)<img src="{{ asset('storage/' . $contents['home_about_bg']->image_path) }}" class="mt-2 h-20 rounded">@endif
            </div>

            <div><label class="block font-bold mb-2">Judul Research</label><input type="text" name="home_research_title" class="w-full p-3 border-2 rounded-xl" value="{{ $contents['home_research_title']->value ?? '' }}"></div>
            <div><label class="block font-bold mb-2">Deskripsi Research</label><textarea name="home_research_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['home_research_desc']->value ?? '' }}</textarea></div>

            <div><label class="block font-bold mb-2">Nama Direktur</label><input type="text" name="home_director_name" class="w-full p-3 border-2 rounded-xl" value="{{ $contents['home_director_name']->value ?? '' }}"></div>
            <div><label class="block font-bold mb-2">Jabatan Direktur</label><input type="text" name="home_director_title" class="w-full p-3 border-2 rounded-xl" value="{{ $contents['home_director_title']->value ?? '' }}"></div>
            <div><label class="block font-bold mb-2">Deskripsi Direktur</label><textarea name="home_director_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['home_director_desc']->value ?? '' }}</textarea></div>
            <div><label class="block font-bold mb-2">Kutipan Direktur</label><textarea name="home_director_quote" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['home_director_quote']->value ?? '' }}</textarea></div>
            <div><label class="block font-bold mb-2">Foto Direktur</label><input type="file" name="home_director" class="w-full p-3 border-2 border-dashed rounded-xl" accept="image/*">
                @if(isset($contents['home_director']) && $contents['home_director']->image_path)<img src="{{ asset('storage/' . $contents['home_director']->image_path) }}" class="mt-2 h-20 rounded">@endif
            </div>
        </div>
        <div class="mt-6 flex justify-end"><button type="submit" class="bg-gray-900 text-white font-bold py-3 px-8 rounded-xl">Simpan Konten Statis</button></div>
    </div>
</form>

<!-- Daftar Visi Misi -->
<div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100" x-data="{ open: false }">
    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-xl font-bold text-gray-900">Daftar Poin Visi / Misi</h2>
        <button @click="open = true" class="bg-primary text-white font-bold py-2 px-4 rounded-lg text-sm">+ Tambah Poin</button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-200">
                    <th class="p-4 font-bold text-gray-600">No</th>
                    <th class="p-4 font-bold text-gray-600">Konten Poin</th>
                    <th class="p-4 font-bold text-gray-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aboutBullets as $index => $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                    <td class="p-4">{{ $index + 1 }}</td>
                    <td class="p-4 font-medium">{{ $item->content }}</td>
                    <td class="p-4 text-right">
                        <form action="{{ route('web.management.about.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus poin ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg font-bold text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="p-6 text-center text-gray-400">Belum ada poin visi misi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah -->
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl transform transition-all" @click.away="open = false">
            <h3 class="text-lg font-bold mb-4">Tambah Poin Baru</h3>
            <form action="{{ route('web.management.about.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block font-bold text-gray-700 mb-2">Teks Poin</label>
                    <input type="text" name="content" class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10" required>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="open = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
`,
    program: `
@extends('layouts.dashboard')

@section('title', 'Manajemen Web - PROGRAM')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - PROGRAM</h1>
    <p class="text-gray-500 font-medium">Ubah teks statis dan daftar program unggulan tanpa batas.</p>
</div>

@if(session('success'))
<div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 font-bold border border-emerald-100 flex items-center gap-3">
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
            <div><label class="block font-bold mb-2">Judul Hero</label><input type="text" name="program_hero_title" class="w-full p-3 border-2 rounded-xl" value="{{ $contents['program_hero_title']->value ?? '' }}"></div>
            <div><label class="block font-bold mb-2">Deskripsi Hero</label><textarea name="program_hero_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['program_hero_desc']->value ?? '' }}</textarea></div>
            <div class="md:col-span-2"><label class="block font-bold mb-2">Background Hero</label><input type="file" name="program_hero_bg" class="w-full p-3 border-2 border-dashed rounded-xl" accept="image/*">
                @if(isset($contents['program_hero_bg']) && $contents['program_hero_bg']->image_path)<img src="{{ asset('storage/' . $contents['program_hero_bg']->image_path) }}" class="mt-2 h-20 rounded">@endif
            </div>
        </div>
        <div class="mt-6 flex justify-end"><button type="submit" class="bg-gray-900 text-white font-bold py-3 px-8 rounded-xl">Simpan Konten Statis</button></div>
    </div>
</form>

<!-- Daftar Program -->
<div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100" x-data="{ open: false }">
    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-xl font-bold text-gray-900">Daftar Program</h2>
        <button @click="open = true" class="bg-primary text-white font-bold py-2 px-4 rounded-lg text-sm">+ Tambah Program</button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-200">
                    <th class="p-4 font-bold text-gray-600">Ikon</th>
                    <th class="p-4 font-bold text-gray-600">Judul</th>
                    <th class="p-4 font-bold text-gray-600">Deskripsi</th>
                    <th class="p-4 font-bold text-gray-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                    <td class="p-4 text-2xl">{{ $item->icon }}</td>
                    <td class="p-4 font-bold text-gray-800">{{ $item->title }}</td>
                    <td class="p-4 text-sm text-gray-600">{{ $item->description }}</td>
                    <td class="p-4 text-right">
                        <form action="{{ route('web.management.programs.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus program ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg font-bold text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-6 text-center text-gray-400">Belum ada program.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah -->
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="open = false">
            <h3 class="text-lg font-bold mb-4">Tambah Program Baru</h3>
            <form action="{{ route('web.management.programs.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Ikon (Emoji/Teks)</label>
                        <input type="text" name="icon" class="w-full p-3 border-2 rounded-xl" placeholder="Misal: 🌱">
                    </div>
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
                    <button type="button" @click="open = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
`,
    research: `
@extends('layouts.dashboard')

@section('title', 'Manajemen Web - RESEARCH')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - RESEARCH</h1>
    <p class="text-gray-500 font-medium">Ubah daftar riset & fasilitas.</p>
</div>

@if(session('success'))
<div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 font-bold border border-emerald-100 flex items-center gap-3">
    <span class="text-xl">✅</span>
    {{ session('success') }}
</div>
@endif

<form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-6 text-gray-900 border-b border-gray-100 pb-4">Konten Hero Fasilitas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div><label class="block font-bold mb-2">Judul Hero</label><input type="text" name="research_hero_title" class="w-full p-3 border-2 rounded-xl" value="{{ $contents['research_hero_title']->value ?? '' }}"></div>
            <div><label class="block font-bold mb-2">Deskripsi Hero</label><textarea name="research_hero_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['research_hero_desc']->value ?? '' }}</textarea></div>
            <div class="md:col-span-2"><label class="block font-bold mb-2">Background Hero</label><input type="file" name="research_hero_bg" class="w-full p-3 border-2 border-dashed rounded-xl" accept="image/*">
                @if(isset($contents['research_hero_bg']) && $contents['research_hero_bg']->image_path)<img src="{{ asset('storage/' . $contents['research_hero_bg']->image_path) }}" class="mt-2 h-20 rounded">@endif
            </div>
        </div>
        <div class="mt-6 flex justify-end"><button type="submit" class="bg-gray-900 text-white font-bold py-3 px-8 rounded-xl">Simpan Konten Statis</button></div>
    </div>
</form>

<div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100" x-data="{ open: false }">
    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-xl font-bold text-gray-900">Daftar Fasilitas / Riset.</h2>
        <button @click="open = true" class="bg-primary text-white font-bold py-2 px-4 rounded-lg text-sm">+ Tambah Riset</button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-200">
                    <th class="p-4 font-bold">Gambar</th>
                    <th class="p-4 font-bold">Ikon</th>
                    <th class="p-4 font-bold">Judul</th>
                    <th class="p-4 font-bold">Deskripsi</th>
                    <th class="p-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($researches as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                    <td class="p-4">
                        @if($item->image_path) <img src="{{ asset('storage/'.$item->image_path) }}" class="w-16 h-16 object-cover rounded-lg shadow-sm"> @else - @endif
                    </td>
                    <td class="p-4 text-2xl">{{ $item->icon }}</td>
                    <td class="p-4 font-bold text-gray-800">{{ $item->title }}</td>
                    <td class="p-4 text-sm text-gray-600">{{ Str::limit($item->description, 50) }}</td>
                    <td class="p-4 text-right">
                        <form action="{{ route('web.management.researches.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus riset ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg font-bold text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-6 text-center text-gray-400">Belum ada riset.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="open = false">
            <h3 class="text-lg font-bold mb-4">Tambah Riset Baru</h3>
            <form action="{{ route('web.management.researches.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                    <div><label class="block font-bold mb-2">Ikon (Emoji/Teks)</label><input type="text" name="icon" class="w-full p-3 border-2 rounded-xl"></div>
                    <div><label class="block font-bold mb-2">Judul Riset</label><input type="text" name="title" class="w-full p-3 border-2 rounded-xl" required></div>
                    <div><label class="block font-bold mb-2">Deskripsi</label><textarea name="description" class="w-full p-3 border-2 rounded-xl h-[100px]" required></textarea></div>
                    <div><label class="block font-bold mb-2">Gambar Detail</label><input type="file" name="image_path" class="w-full p-3 border-2 rounded-xl" accept="image/*"></div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="open = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
`,
    news: `
@extends('layouts.dashboard')

@section('title', 'Manajemen Web - NEWS')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - BERITA</h1>
    <p class="text-gray-500 font-medium">Ubah daftar berita & artikel.</p>
</div>

@if(session('success'))
<div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 font-bold border border-emerald-100 flex items-center gap-3">
    <span class="text-xl">✅</span>
    {{ session('success') }}
</div>
@endif

<form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-6 text-gray-900 border-b border-gray-100 pb-4">Konten Hero Berita</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div><label class="block font-bold mb-2">Judul Hero</label><input type="text" name="news_hero_title" class="w-full p-3 border-2 rounded-xl" value="{{ $contents['news_hero_title']->value ?? '' }}"></div>
            <div><label class="block font-bold mb-2">Deskripsi Hero</label><textarea name="news_hero_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['news_hero_desc']->value ?? '' }}</textarea></div>
        </div>
        <div class="mt-6 flex justify-end"><button type="submit" class="bg-gray-900 text-white font-bold py-3 px-8 rounded-xl">Simpan Konten Statis</button></div>
    </div>
</form>

<div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100" x-data="{ open: false }">
    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-xl font-bold text-gray-900">Daftar Berita</h2>
        <button @click="open = true" class="bg-primary text-white font-bold py-2 px-4 rounded-lg text-sm">+ Tambah Berita</button>
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
                        @if($item->image_path) <img src="{{ asset('storage/'.$item->image_path) }}" class="w-16 h-10 object-cover rounded shadow-sm"> @else - @endif
                    </td>
                    <td class="p-4 font-bold text-accent">{{ $item->category }}</td>
                    <td class="p-4 font-bold text-gray-800">{{ $item->title }}</td>
                    <td class="p-4 text-sm">{{ $item->date }}</td>
                    <td class="p-4 text-right">
                        <form action="{{ route('web.management.news.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus berita ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg font-bold text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-6 text-center text-gray-400">Belum ada berita.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="open = false">
            <h3 class="text-lg font-bold mb-4">Tambah Berita Baru</h3>
            <form action="{{ route('web.management.news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                    <div><label class="block font-bold mb-2">Kategori</label><input type="text" name="category" class="w-full p-3 border-2 rounded-xl" required placeholder="Misal: Inovasi"></div>
                    <div><label class="block font-bold mb-2">Judul Berita</label><input type="text" name="title" class="w-full p-3 border-2 rounded-xl" required></div>
                    <div><label class="block font-bold mb-2">Deskripsi</label><textarea name="description" class="w-full p-3 border-2 rounded-xl h-[100px]" required></textarea></div>
                    <div><label class="block font-bold mb-2">Tanggal (String)</label><input type="text" name="date" class="w-full p-3 border-2 rounded-xl" placeholder="Misal: 12 Agustus 2026"></div>
                    <div><label class="block font-bold mb-2">Gambar Cover</label><input type="file" name="image_path" class="w-full p-3 border-2 rounded-xl" accept="image/*"></div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="open = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
`,
    gallery: `
@extends('layouts.dashboard')

@section('title', 'Manajemen Web - GALLERY')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - GALERI</h1>
    <p class="text-gray-500 font-medium">Ubah daftar galeri foto tanpa batas.</p>
</div>

@if(session('success'))
<div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 font-bold border border-emerald-100 flex items-center gap-3">
    <span class="text-xl">✅</span>
    {{ session('success') }}
</div>
@endif

<form action="{{ route('web.management.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-6 text-gray-900 border-b border-gray-100 pb-4">Konten Hero Galeri</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div><label class="block font-bold mb-2">Judul Hero</label><input type="text" name="gallery_hero_title" class="w-full p-3 border-2 rounded-xl" value="{{ $contents['gallery_hero_title']->value ?? '' }}"></div>
            <div><label class="block font-bold mb-2">Deskripsi Hero</label><textarea name="gallery_hero_desc" class="w-full h-[120px] p-3 border-2 rounded-xl">{{ $contents['gallery_hero_desc']->value ?? '' }}</textarea></div>
        </div>
        <div class="mt-6 flex justify-end"><button type="submit" class="bg-gray-900 text-white font-bold py-3 px-8 rounded-xl">Simpan Konten Statis</button></div>
    </div>
</form>

<div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100" x-data="{ open: false }">
    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-xl font-bold text-gray-900">Daftar Foto Galeri</h2>
        <button @click="open = true" class="bg-primary text-white font-bold py-2 px-4 rounded-lg text-sm">+ Tambah Foto</button>
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
                        <img src="{{ asset('storage/'.$item->image_path) }}" class="w-20 h-16 object-cover rounded-lg shadow-sm">
                    </td>
                    <td class="p-4 font-bold text-gray-800">{{ $item->title }}</td>
                    <td class="p-4 text-sm">{{ $item->description }}</td>
                    <td class="p-4 text-right">
                        <form action="{{ route('web.management.galleries.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg font-bold text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-6 text-center text-gray-400">Belum ada foto galeri.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="open = false">
            <h3 class="text-lg font-bold mb-4">Tambah Foto Galeri</h3>
            <form action="{{ route('web.management.galleries.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                    <div><label class="block font-bold mb-2">Judul Foto</label><input type="text" name="title" class="w-full p-3 border-2 rounded-xl" required></div>
                    <div><label class="block font-bold mb-2">Deskripsi (Opsional)</label><textarea name="description" class="w-full p-3 border-2 rounded-xl h-[100px]"></textarea></div>
                    <div><label class="block font-bold mb-2">File Foto</label><input type="file" name="image_path" class="w-full p-3 border-2 rounded-xl" accept="image/*" required></div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="open = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl">Upload Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
`,
};

for (const [name, html] of Object.entries(components)) {
    fs.writeFileSync(path.join(viewsDir, name + ".blade.php"), html);
}

console.log("Admin views rewritten to support dynamic CRUD lists.");
