const fs = require('fs');
const path = require('path');

const basePath = 'e:/magang/tsth2-redesign';

function updateWelcome() {
    const file = path.join(basePath, 'resources/views/welcome.blade.php');
    let content = fs.readFileSync(file, 'utf8');

    // Update About section bullets
    const oldAboutBullets = `<ul style="list-style: none; display: flex; flex-direction: column; gap: 1rem; color: var(--color-text-dark); padding-top: 1rem;">
                        <li>✅ {{ $contents['home_about_bullet1']->value ?? '' }}</li>
                        <li>✅ {{ $contents['home_about_bullet2']->value ?? '' }}</li>
                        <li>✅ {{ $contents['home_about_bullet3']->value ?? '' }}</li>
                    </ul>`;
    const newAboutBullets = `<ul style="list-style: none; display: flex; flex-direction: column; gap: 1rem; color: var(--color-text-dark); padding-top: 1rem;">
                        @foreach($aboutBullets as $bullet)
                        <li>✅ {{ $bullet->content }}</li>
                        @endforeach
                    </ul>`;
    content = content.replace(oldAboutBullets, newAboutBullets);

    // Update Research section grid
    const oldResearchGridStart = `<div class="research-grid">`;
    const oldResearchGridEnd = `</div>
        </div>
    </section>`;

    // We'll replace everything between these markers with a foreach loop
    const newResearchGrid = `<div class="research-grid">
                @foreach($researches as $item)
                <div class="research-card chunk-card animate-on-scroll">
                    <div class="card-icon" style="background: rgba(10, 102, 85, 0.1); color: var(--color-primary); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.5rem; margin-bottom: 1.5rem;">
                        {{ $item->icon }}
                    </div>
                    <h3>{{ $item->title }}</h3>
                    <p>{{ Str::limit($item->description, 100) }}</p>
                    <button class="btn-lihat-detail" style="margin-top: 1rem; background: none; border: none; color: var(--color-primary); font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;" data-img-src="{{ $item->image_path ? asset('storage/' . $item->image_path) : '' }}" data-title="{{ $item->title }}" data-desc="{{ $item->description }}">Lihat Detail <span style="font-size: 1.2rem;">&rarr;</span></button>
                </div>
                @endforeach
            </div>
        </div>
    </section>`;

    // Find index of <div class="research-grid"> and the end of section
    const startIdx = content.indexOf(oldResearchGridStart);
    const endIdx = content.indexOf('</section>', startIdx) + '</section>'.length;
    
    if (startIdx !== -1 && endIdx !== -1) {
        content = content.substring(0, startIdx) + newResearchGrid + content.substring(endIdx);
    }

    fs.writeFileSync(file, content);
}

function updateProgram() {
    const file = path.join(basePath, 'resources/views/pages/program.blade.php');
    let content = fs.readFileSync(file, 'utf8');

    const oldGridStart = `<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">`;
    const oldGridEnd = `</div>
            </div>
        </section>`;
    
    const newGrid = `<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                    @foreach($programs as $item)
                    <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: transform 0.3s ease;">
                        <div style="height: 200px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 3rem;">{{ $item->icon }}</div>
                        <div style="padding: 2rem;">
                            <h3 style="font-family: var(--font-heading); margin-bottom: 1rem; color: var(--color-primary-dark);">{{ $item->title }}</h3>
                            <p style="color: var(--color-text-muted); line-height: 1.6;">{{ $item->description }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>`;

    const startIdx = content.indexOf(oldGridStart);
    const endIdx = content.indexOf('</section>', startIdx) + '</section>'.length;
    
    if (startIdx !== -1 && endIdx !== -1) {
        content = content.substring(0, startIdx) + newGrid + content.substring(endIdx);
    }

    fs.writeFileSync(file, content);
}

function updateResearch() {
    const file = path.join(basePath, 'resources/views/pages/research.blade.php');
    let content = fs.readFileSync(file, 'utf8');

    const oldGridStart = `<div style="display: grid; grid-template-columns: 1fr; gap: 4rem;">`;
    const newGrid = `<div style="display: grid; grid-template-columns: 1fr; gap: 4rem;">
                    @foreach($researches as $item)
                    <div style="display: flex; flex-direction: {{ $loop->iteration % 2 == 0 ? 'row-reverse' : 'row' }}; gap: 3rem; align-items: center; background: white; padding: 2rem; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                        <div style="flex: 1;">
                            @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; border-radius: 12px; object-fit: cover; aspect-ratio: 16/9;">
                            @else
                            <div style="width: 100%; height: 300px; background: #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 4rem;">{{ $item->icon }}</div>
                            @endif
                        </div>
                        <div style="flex: 1;">
                            <h3 style="font-size: 2rem; font-family: var(--font-heading); color: var(--color-primary-dark); margin-bottom: 1rem;">{{ $item->title }}</h3>
                            <p style="color: var(--color-text-muted); line-height: 1.8; font-size: 1.1rem;">
                                {{ $item->description }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>`;

    const startIdx = content.indexOf(oldGridStart);
    const endIdx = content.indexOf('</section>', startIdx) + '</section>'.length;
    
    if (startIdx !== -1 && endIdx !== -1) {
        content = content.substring(0, startIdx) + newGrid + content.substring(endIdx);
    }

    fs.writeFileSync(file, content);
}

function updateNews() {
    const file = path.join(basePath, 'resources/views/pages/news.blade.php');
    let content = fs.readFileSync(file, 'utf8');

    const oldGridStart = `<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2.5rem;">`;
    const newGrid = `<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2.5rem;">
                    @foreach($news as $item)
                    <article style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                        <div style="height: 250px; background-image: url('{{ $item->image_path ? asset('storage/' . $item->image_path) : '' }}'); background-size: cover; background-position: center;">
                        </div>
                        <div style="padding: 2rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <span style="background: var(--color-primary-light); color: var(--color-primary-dark); padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.875rem; font-weight: bold;">{{ $item->category }}</span>
                                <span style="color: var(--color-text-muted); font-size: 0.875rem;">{{ $item->date }}</span>
                            </div>
                            <h3 style="font-family: var(--font-heading); font-size: 1.5rem; margin-bottom: 1rem; color: var(--color-text-dark);">
                                {{ $item->title }}
                            </h3>
                            <p style="color: var(--color-text-muted); line-height: 1.6; margin-bottom: 1.5rem;">
                                {{ $item->description }}
                            </p>
                            <a href="#" style="color: var(--color-accent); font-weight: bold; text-decoration: none;">Baca Selengkapnya &rarr;</a>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
        </section>`;

    const startIdx = content.indexOf(oldGridStart);
    const endIdx = content.indexOf('</section>', startIdx) + '</section>'.length;
    
    if (startIdx !== -1 && endIdx !== -1) {
        content = content.substring(0, startIdx) + newGrid + content.substring(endIdx);
    }

    fs.writeFileSync(file, content);
}

function updateGallery() {
    const file = path.join(basePath, 'resources/views/pages/gallery.blade.php');
    let content = fs.readFileSync(file, 'utf8');

    const oldGridStart = `<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">`;
    const newGrid = `<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    @foreach($galleries as $item)
                    <div style="position: relative; border-radius: 12px; overflow: hidden; aspect-ratio: 1; group">
                        <div style="width: 100%; height: 100%; background-image: url('{{ $item->image_path ? asset('storage/' . $item->image_path) : '' }}'); background-size: cover; background-position: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"></div>
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); display: flex; flex-direction: column; justify-content: flex-end; padding: 1.5rem; opacity: 1; transition: opacity 0.3s ease; pointer-events: none;">
                            <h3 style="color: white; font-family: var(--font-heading); margin-bottom: 0.25rem;">{{ $item->title }}</h3>
                            <p style="color: rgba(255,255,255,0.8); font-size: 0.875rem;">{{ $item->description }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>`;

    const startIdx = content.indexOf(oldGridStart);
    const endIdx = content.indexOf('</section>', startIdx) + '</section>'.length;
    
    if (startIdx !== -1 && endIdx !== -1) {
        content = content.substring(0, startIdx) + newGrid + content.substring(endIdx);
    }

    fs.writeFileSync(file, content);
}

try {
    updateWelcome();
    updateProgram();
    updateResearch();
    updateNews();
    updateGallery();
    console.log('Frontend views rewritten successfully!');
} catch (e) {
    console.error(e);
}
