const fs = require('fs');
const path = require('path');

const viewsDir = 'e:/magang/tsth2-redesign/resources/views/web_management';

const components = {
    'home': [
        { title: 'Teks Badge Hero', name: 'home_hero_badge', type: 'text' },
        { title: 'Judul Utama (Hero)', name: 'home_hero_title', type: 'text' },
        { title: 'Deskripsi Utama (Hero)', name: 'home_hero_desc', type: 'textarea' },
        { title: 'Gambar Latar Hero 1', name: 'home_hero_bg_1', type: 'image' },
        { title: 'Gambar Latar Hero 2', name: 'home_hero_bg_2', type: 'image' },
        
        { title: 'Judul About', name: 'home_about_title', type: 'text' },
        { title: 'Deskripsi About', name: 'home_about_desc', type: 'textarea' },
        { title: 'Bullet About 1', name: 'home_about_bullet1', type: 'text' },
        { title: 'Bullet About 2', name: 'home_about_bullet2', type: 'text' },
        { title: 'Bullet About 3', name: 'home_about_bullet3', type: 'text' },
        { title: 'Gambar Latar About', name: 'home_about_bg', type: 'image' },

        { title: 'Judul Research', name: 'home_research_title', type: 'text' },
        { title: 'Deskripsi Research', name: 'home_research_desc', type: 'textarea' },
        
        { title: 'Research Item 1 Title', name: 'home_research_item1_title', type: 'text' },
        { title: 'Research Item 1 Desc', name: 'home_research_item1_desc', type: 'textarea' },
        { title: 'Research Item 1 Image', name: 'home_research_item1_img', type: 'image' },

        { title: 'Research Item 2 Title', name: 'home_research_item2_title', type: 'text' },
        { title: 'Research Item 2 Desc', name: 'home_research_item2_desc', type: 'textarea' },
        { title: 'Research Item 2 Image', name: 'home_research_item2_img', type: 'image' },

        { title: 'Research Item 3 Title', name: 'home_research_item3_title', type: 'text' },
        { title: 'Research Item 3 Desc', name: 'home_research_item3_desc', type: 'textarea' },
        { title: 'Research Item 3 Image', name: 'home_research_item3_img', type: 'image' },

        { title: 'Director Name', name: 'home_director_name', type: 'text' },
        { title: 'Director Title', name: 'home_director_title', type: 'text' },
        { title: 'Director Desc', name: 'home_director_desc', type: 'textarea' },
        { title: 'Director Quote', name: 'home_director_quote', type: 'textarea' },
        { title: 'Director Image', name: 'home_director', type: 'image' }
    ],
    'program': [
        { title: 'Program Hero Title', name: 'program_hero_title', type: 'text' },
        { title: 'Program Hero Desc', name: 'program_hero_desc', type: 'textarea' },
        { title: 'Program Hero Background', name: 'program_hero_bg', type: 'image' },

        { title: 'Program Item 1 Icon', name: 'program_item1_icon', type: 'text' },
        { title: 'Program Item 1 Title', name: 'program_item1_title', type: 'text' },
        { title: 'Program Item 1 Desc', name: 'program_item1_desc', type: 'textarea' },

        { title: 'Program Item 2 Icon', name: 'program_item2_icon', type: 'text' },
        { title: 'Program Item 2 Title', name: 'program_item2_title', type: 'text' },
        { title: 'Program Item 2 Desc', name: 'program_item2_desc', type: 'textarea' },

        { title: 'Program Item 3 Icon', name: 'program_item3_icon', type: 'text' },
        { title: 'Program Item 3 Title', name: 'program_item3_title', type: 'text' },
        { title: 'Program Item 3 Desc', name: 'program_item3_desc', type: 'textarea' }
    ],
    'research': [
        { title: 'Research Hero Title', name: 'research_hero_title', type: 'text' },
        { title: 'Research Hero Desc', name: 'research_hero_desc', type: 'textarea' },
        { title: 'Research Hero Background', name: 'research_hero_bg', type: 'image' },

        { title: 'Research Item 1 Title', name: 'research_item1_title', type: 'text' },
        { title: 'Research Item 1 Desc', name: 'research_item1_desc', type: 'textarea' },
        { title: 'Research Item 1 Image', name: 'research_item1_img', type: 'image' },

        { title: 'Research Item 2 Title', name: 'research_item2_title', type: 'text' },
        { title: 'Research Item 2 Desc', name: 'research_item2_desc', type: 'textarea' },
        { title: 'Research Item 2 Image', name: 'research_item2_img', type: 'image' }
    ],
    'news': [
        { title: 'News Hero Title', name: 'news_hero_title', type: 'text' },
        { title: 'News Hero Desc', name: 'news_hero_desc', type: 'textarea' },

        { title: 'News Item 1 Category', name: 'news_item1_category', type: 'text' },
        { title: 'News Item 1 Title', name: 'news_item1_title', type: 'text' },
        { title: 'News Item 1 Desc', name: 'news_item1_desc', type: 'textarea' },
        { title: 'News Item 1 Date', name: 'news_item1_date', type: 'text' },
        { title: 'News Item 1 Image', name: 'news_item1_img', type: 'image' },

        { title: 'News Item 2 Category', name: 'news_item2_category', type: 'text' },
        { title: 'News Item 2 Title', name: 'news_item2_title', type: 'text' },
        { title: 'News Item 2 Desc', name: 'news_item2_desc', type: 'textarea' },
        { title: 'News Item 2 Date', name: 'news_item2_date', type: 'text' },
        { title: 'News Item 2 Image', name: 'news_item2_img', type: 'image' }
    ],
    'gallery': [
        { title: 'Gallery Hero Title', name: 'gallery_hero_title', type: 'text' },
        { title: 'Gallery Hero Desc', name: 'gallery_hero_desc', type: 'textarea' },

        { title: 'Gallery Img 1 Title', name: 'gallery_img_1_title', type: 'text' },
        { title: 'Gallery Img 1 Desc', name: 'gallery_img_1_desc', type: 'textarea' },
        { title: 'Gallery Img 1 File', name: 'gallery_img_1', type: 'image' },

        { title: 'Gallery Img 2 Title', name: 'gallery_img_2_title', type: 'text' },
        { title: 'Gallery Img 2 Desc', name: 'gallery_img_2_desc', type: 'textarea' },
        { title: 'Gallery Img 2 File', name: 'gallery_img_2', type: 'image' },

        { title: 'Gallery Img 3 Title', name: 'gallery_img_3_title', type: 'text' },
        { title: 'Gallery Img 3 Desc', name: 'gallery_img_3_desc', type: 'textarea' },
        { title: 'Gallery Img 3 File', name: 'gallery_img_3', type: 'image' },

        { title: 'Gallery Img 4 Title', name: 'gallery_img_4_title', type: 'text' },
        { title: 'Gallery Img 4 Desc', name: 'gallery_img_4_desc', type: 'textarea' },
        { title: 'Gallery Img 4 File', name: 'gallery_img_4', type: 'image' }
    ],
    'layout': [
        { title: 'Navbar Title', name: 'navbar_title', type: 'text' },
        { title: 'Navbar Subtitle', name: 'navbar_subtitle', type: 'text' },
        { title: 'Footer Desc', name: 'footer_desc', type: 'textarea' },
        { title: 'Footer Address', name: 'footer_address', type: 'textarea' },
        { title: 'Footer Email', name: 'footer_email', type: 'text' }
    ]
};

const generateTemplate = (name, fields) => {
    let html = `@extends('layouts.dashboard')

@section('title', 'Manajemen Web - ${name.toUpperCase()}')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">CMS - ${name.toUpperCase()}</h1>
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
`;

    fields.forEach(f => {
        if (f.type === 'text') {
            html += `
            <div>
                <label class="block font-bold mb-2 text-gray-700">${f.title}</label>
                <input type="text" name="${f.name}" class="w-full p-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-700 bg-gray-50 hover:bg-white" value="{{ $contents['${f.name}']->value ?? '' }}">
            </div>`;
        } else if (f.type === 'textarea') {
            html += `
            <div>
                <label class="block font-bold mb-2 text-gray-700">${f.title}</label>
                <textarea name="${f.name}" class="w-full h-[120px] p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-700 bg-gray-50 hover:bg-white leading-relaxed resize-none">{{ $contents['${f.name}']->value ?? '' }}</textarea>
            </div>`;
        } else if (f.type === 'image') {
            html += `
            <div>
                <label class="block font-bold mb-2 text-gray-700">Ubah ${f.title} <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <input type="file" name="${f.name}" class="w-full p-3 border-2 border-gray-200 border-dashed rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-gray-600 bg-gray-50 hover:bg-white" accept="image/*">
                @if(isset($contents['${f.name}']) && $contents['${f.name}']->image_path)
                    <img src="{{ asset('storage/' . $contents['${f.name}']->image_path) }}" class="mt-2 h-20 rounded" alt="Current Image">
                @endif
            </div>`;
        }
    });

    html += `
        </div>
    </div>

    <div class="sticky bottom-6 bg-white/95 backdrop-blur-xl p-5 rounded-[2rem] shadow-2xl shadow-gray-200/50 border border-gray-200 flex flex-col sm:flex-row justify-end items-center gap-4 z-30">
        <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white font-bold py-3.5 px-8 rounded-xl shadow-lg transition-transform hover:scale-105">
            💾 Simpan Perubahan
        </button>
    </div>
</form>
@endsection
`;
    return html;
};

for (const [name, fields] of Object.entries(components)) {
    fs.writeFileSync(path.join(viewsDir, name + '.blade.php'), generateTemplate(name, fields));
}

console.log('Admin views generated successfully!');
