const fs = require('fs');
const path = require('path');

const basePath = 'e:/magang/tsth2-redesign';

function fixResearch() {
    const file = path.join(basePath, 'resources/views/pages/research.blade.php');
    let content = fs.readFileSync(file, 'utf8');

    // Find the start of the content area
    const searchStr = `<!-- Content Area -->
        <section style="padding: 5rem 0; background: var(--color-background);">
            <div class="container">`;
    const startIdx = content.indexOf(searchStr);
    
    if (startIdx !== -1) {
        const endIdx = content.indexOf('</section>', startIdx) + '</section>'.length;
        const newSection = `<!-- Content Area -->
        <section style="padding: 5rem 0; background: var(--color-background);">
            <div class="container">
                <div style="display: flex; flex-direction: column; gap: 4rem;">
                    @forelse($researches as $item)
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
                        <div style="border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-lg); order: {{ $loop->iteration % 2 == 0 ? 2 : 1 }};">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="lightbox-img" data-title="{{ $item->title }}" data-desc="{{ $item->description }}" style="width: 100%; height: 300px; object-fit: cover;">
                        </div>
                        <div style="order: {{ $loop->iteration % 2 == 0 ? 1 : 2 }};">
                            <h2 style="font-family: var(--font-heading); color: var(--color-primary-dark); margin-bottom: 1rem;">{{ $item->title }}</h2>
                            <p style="color: var(--color-text-muted); line-height: 1.8; margin-bottom: 1.5rem;">{{ $item->description }}</p>
                            <a href="#" style="color: var(--color-primary); font-weight: 600; text-decoration: none;">Pelajari Publikasi Kami →</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center" style="padding: 3rem; color: #94a3b8;">Belum ada data fasilitas/riset.</div>
                    @endforelse
                </div>
            </div>
        </section>`;
        
        content = content.substring(0, startIdx) + newSection + content.substring(endIdx);
        fs.writeFileSync(file, content);
    }
}

function fixNews() {
    const file = path.join(basePath, 'resources/views/pages/news.blade.php');
    let content = fs.readFileSync(file, 'utf8');

    const searchStr = `<!-- Content Area -->
        <section style="padding: 5rem 0; background: var(--color-background);">
            <div class="container">`;
    const startIdx = content.indexOf(searchStr);
    
    if (startIdx !== -1) {
        const endIdx = content.indexOf('</section>', startIdx) + '</section>'.length;
        const newSection = `<!-- Content Area -->
        <section style="padding: 5rem 0; background: var(--color-background);">
            <div class="container">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
                    @forelse($news as $item)
                    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s;">
                        <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}" alt="{{ $item->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                        <div style="padding: 1.5rem;">
                            <span style="font-size: 0.8rem; color: var(--color-primary); font-weight: 600; text-transform: uppercase;">{{ $item->category }}</span>
                            <h3 style="font-family: var(--font-heading); margin: 0.5rem 0; color: var(--color-text-dark);">{{ $item->title }}</h3>
                            <p style="color: var(--color-text-muted); font-size: 0.9rem; margin-bottom: 1rem;">{{ Str::limit($item->description, 100) }}</p>
                            <span style="font-size: 0.8rem; color: #94a3b8;">{{ $item->date }}</span>
                        </div>
                    </div>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #94a3b8;">Belum ada berita terbaru.</div>
                    @endforelse
                </div>
            </div>
        </section>`;
        
        content = content.substring(0, startIdx) + newSection + content.substring(endIdx);
        fs.writeFileSync(file, content);
    }
}

function fixGallery() {
    const file = path.join(basePath, 'resources/views/pages/gallery.blade.php');
    let content = fs.readFileSync(file, 'utf8');

    const searchStr = `<div style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center;">`;
    const startIdx = content.indexOf(searchStr);
    
    if (startIdx !== -1) {
        // Because gallery doesn't have an outer section tag with a clear end, we'll replace up to </main>
        const endIdx = content.indexOf('</main>', startIdx);
        const newSection = `<div style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center;">
            @forelse($galleries as $item)
            <div style="width: 320px; background: #fff; padding: 10px; border: 1px solid #ccc; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="lightbox-img" data-title="{{ $item->title }}" data-desc="{{ $item->description }}" style="width: 100%; height: auto; border-bottom: 2px solid #eee; margin-bottom: 10px; aspect-ratio: 16/10; object-fit: cover; cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                <h3 style="color: var(--color-primary-dark); font-size: 1.1rem; text-align: center; margin: 0;">{{ $item->title }}</h3>
                <p style="font-size: 0.9rem; color: #666; text-align: center; margin-top: 5px;">{{ $item->description }}</p>
            </div>
            @empty
            <div style="width: 100%; text-align: center; padding: 3rem; color: #94a3b8;">Belum ada foto galeri.</div>
            @endforelse
        </div>
    `;
        content = content.substring(0, startIdx) + newSection + content.substring(endIdx);
        fs.writeFileSync(file, content);
    }
}

try {
    fixResearch();
    fixNews();
    fixGallery();
    console.log('Fixed missing frontend view replacements!');
} catch (e) {
    console.error(e);
}
