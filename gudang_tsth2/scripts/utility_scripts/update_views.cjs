const fs = require("fs");
const path = require("path");

const basePath = "e:/magang/tsth2-redesign";

// --- ADMIN VIEWS ---
function updateAdminHome() {
    const file = path.join(
        basePath,
        "resources/views/web_management/home.blade.php",
    );
    let content = fs.readFileSync(file, "utf8");

    // Remove static bg fields
    const staticBgs = `<div><label class="block font-bold mb-2">Background Hero 1</label><input type="file" name="home_hero_bg_1" class="w-full p-3 border-2 border-dashed rounded-xl" accept="image/*">
                @if(isset($contents['home_hero_bg_1']) && $contents['home_hero_bg_1']->image_path)<img src="{{ asset('storage/' . $contents['home_hero_bg_1']->image_path) }}" class="mt-2 h-20 rounded">@endif
            </div>
            <div><label class="block font-bold mb-2">Background Hero 2</label><input type="file" name="home_hero_bg_2" class="w-full p-3 border-2 border-dashed rounded-xl" accept="image/*">
                @if(isset($contents['home_hero_bg_2']) && $contents['home_hero_bg_2']->image_path)<img src="{{ asset('storage/' . $contents['home_hero_bg_2']->image_path) }}" class="mt-2 h-20 rounded">@endif
            </div>`;
    content = content.replace(staticBgs, "");

    // Add HomeBackground CRUD section
    const bgCrud = `
<!-- Daftar Background Hero -->
<div class="bg-white rounded-[2rem] p-8 md:p-10 mb-8 shadow-sm border border-gray-100" x-data="{ open: false }">
    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-xl font-bold text-gray-900">Daftar Background Hero</h2>
        <button @click="open = true" class="bg-primary text-white font-bold py-2 px-4 rounded-lg text-sm">+ Tambah Gambar</button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-200">
                    <th class="p-4 font-bold text-gray-600">Gambar</th>
                    <th class="p-4 font-bold text-gray-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($homeBackgrounds ?? [] as $bg)
                <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                    <td class="p-4">
                        <img src="{{ asset('storage/' . $bg->image_path) }}" class="w-32 h-16 object-cover rounded-lg shadow-sm">
                    </td>
                    <td class="p-4 text-right">
                        <form action="{{ route('web.management.home_bg.destroy', $bg->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus gambar ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg font-bold text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="2" class="p-6 text-center text-gray-400">Belum ada gambar background.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah BG -->
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl transform transition-all" @click.away="open = false">
            <h3 class="text-lg font-bold mb-4">Tambah Gambar Background Baru</h3>
            <form action="{{ route('web.management.home_bg.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block font-bold text-gray-700 mb-2">Pilih File Gambar</label>
                    <input type="file" name="image_path" class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-primary" required accept="image/*">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="open = false" class="px-5 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl shadow-md">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
`;
    // Insert after "Daftar Visi Misi"
    content = content.replace(
        "</div>\n@endsection",
        "</div>\n" + bgCrud + "\n@endsection",
    );
    fs.writeFileSync(file, content);
}

function updateAdminProgram() {
    const file = path.join(
        basePath,
        "resources/views/web_management/program.blade.php",
    );
    let content = fs.readFileSync(file, "utf8");

    // Remove icon table header and data
    content = content.replace(
        '<th class="p-4 font-bold text-gray-600">Ikon</th>',
        "",
    );
    content = content.replace(
        '<td class="p-4 text-2xl">{{ $item->icon }}</td>',
        "",
    );
    content = content.replace('<td colspan="4"', '<td colspan="3"');

    // Remove icon input
    const iconInput = `<div>
                        <label class="block font-bold text-gray-700 mb-2">Ikon (Emoji/Teks)</label>
                        <input type="text" name="icon" class="w-full p-3 border-2 rounded-xl" placeholder="Misal: 🌱">
                    </div>`;
    content = content.replace(iconInput, "");
    fs.writeFileSync(file, content);
}

function updateAdminResearch() {
    const file = path.join(
        basePath,
        "resources/views/web_management/research.blade.php",
    );
    let content = fs.readFileSync(file, "utf8");

    // Remove icon table header and data
    content = content.replace('<th class="p-4 font-bold">Ikon</th>', "");
    content = content.replace(
        '<td class="p-4 text-2xl">{{ $item->icon }}</td>',
        "",
    );
    content = content.replace('<td colspan="5"', '<td colspan="4"');

    // Remove icon input
    const iconInput = `<div><label class="block font-bold mb-2">Ikon (Emoji/Teks)</label><input type="text" name="icon" class="w-full p-3 border-2 rounded-xl"></div>`;
    content = content.replace(iconInput, "");

    // Make image_path required in the modal
    content = content.replace(
        'name="image_path" class="w-full p-3 border-2 rounded-xl" accept="image/*"',
        'name="image_path" class="w-full p-3 border-2 rounded-xl" accept="image/*" required',
    );
    fs.writeFileSync(file, content);
}

// --- FRONTEND VIEWS ---
function updateFrontendWelcome() {
    const file = path.join(basePath, "resources/views/welcome.blade.php");
    let content = fs.readFileSync(file, "utf8");

    // Dynamic Slider
    const oldSlider = `<div class="hero-slider">
            <div class="slide active">
                <img src="{{ isset($contents['home_hero_bg_1']) && $contents['home_hero_bg_1']->image_path ? asset('storage/' . $contents['home_hero_bg_1']->image_path) : '' }}" alt="Herbal Greenhouse">
                <div class="slide-overlay"></div>
            </div>
            <div class="slide">
                <img src="{{ isset($contents['home_hero_bg_2']) && $contents['home_hero_bg_2']->image_path ? asset('storage/' . $contents['home_hero_bg_2']->image_path) : '' }}" alt="Horticulture Landscape">
                <div class="slide-overlay"></div>
            </div>
        </div>`;
    const newSlider = `<div class="hero-slider">
            @forelse($homeBackgrounds ?? [] as $index => $bg)
            <div class="slide {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . $bg->image_path) }}" alt="TSTH2 Background">
                <div class="slide-overlay"></div>
            </div>
            @empty
            <div class="slide active">
                <img src="{{ asset('assets/default_hero.jpg') }}" alt="Default Background">
                <div class="slide-overlay"></div>
            </div>
            @endforelse
        </div>`;

    // Dynamic Slider dots
    const oldDots = `<div class="slider-controls">
            <div class="slider-dot active" data-slide="0"></div>
            <div class="slider-dot" data-slide="1"></div>
        </div>`;
    const newDots = `<div class="slider-controls">
            @foreach($homeBackgrounds ?? [] as $index => $bg)
            <div class="slider-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></div>
            @endforeach
        </div>`;

    content = content.replace(oldSlider, newSlider).replace(oldDots, newDots);

    // Remove icon from research
    const iconResearch = `<div class="card-icon" style="background: rgba(10, 102, 85, 0.1); color: var(--color-primary); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.5rem; margin-bottom: 1.5rem;">
                        {{ $item->icon }}
                    </div>`;
    content = content.replace(iconResearch, "");
    fs.writeFileSync(file, content);
}

function updateFrontendProgram() {
    const file = path.join(basePath, "resources/views/pages/program.blade.php");
    let content = fs.readFileSync(file, "utf8");

    // Remove icon
    content = content.replace(
        '<div style="height: 200px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 3rem;">{{ $item->icon }}</div>',
        "",
    );
    fs.writeFileSync(file, content);
}

function updateFrontendResearch() {
    const file = path.join(
        basePath,
        "resources/views/pages/research.blade.php",
    );
    let content = fs.readFileSync(file, "utf8");

    // Remove icon fallback
    const oldLogic = `@if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; border-radius: 12px; object-fit: cover; aspect-ratio: 16/9;">
                            @else
                            <div style="width: 100%; height: 300px; background: #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 4rem;">{{ $item->icon }}</div>
                            @endif`;
    const newLogic = `<img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; border-radius: 12px; object-fit: cover; aspect-ratio: 16/9;">`;

    content = content.replace(oldLogic, newLogic);
    fs.writeFileSync(file, content);
}

try {
    updateAdminHome();
    updateAdminProgram();
    updateAdminResearch();
    updateFrontendWelcome();
    updateFrontendProgram();
    updateFrontendResearch();
    console.log("All views updated.");
} catch (e) {
    console.error(e);
}
