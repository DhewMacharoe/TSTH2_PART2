// Global Lightbox — only images marked with .lightbox-img

function openGlobalLightbox(src, title, desc) {
    const lightbox = document.getElementById('global-lightbox');
    if (!lightbox) return;

    document.getElementById('gl-img').src = src;
    document.getElementById('gl-title').textContent = title || '';
    document.getElementById('gl-desc').textContent = desc || '';

    lightbox.style.display = 'flex';
    void lightbox.offsetWidth;
    lightbox.style.opacity = '1';
    document.body.style.overflow = 'hidden';
}

function closeGlobalLightbox() {
    const lightbox = document.getElementById('global-lightbox');
    if (!lightbox) return;

    lightbox.style.opacity = '0';
    document.body.style.overflow = 'auto';
    setTimeout(() => {
        lightbox.style.display = 'none';
    }, 300);
}

document.addEventListener('DOMContentLoaded', () => {
    if (!document.getElementById('global-lightbox')) {
        const lightboxHTML = `
        <div id="global-lightbox" style="display: none; position: fixed; z-index: 999999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.85); flex-direction: column; justify-content: center; align-items: center; padding: 2rem; opacity: 0; transition: opacity 0.3s ease; backdrop-filter: blur(8px);">
            <span class="gl-close" style="position: absolute; top: 20px; right: 35px; color: #f1f1f1; font-size: 40px; font-weight: bold; cursor: pointer; transition: color 0.3s; z-index: 2;" onmouseover="this.style.color='#e74c3c'" onmouseout="this.style.color='#f1f1f1'">&times;</span>
            <img id="gl-img" style="max-width: 90%; max-height: 70vh; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.6); object-fit: contain; z-index: 1;">
            <div id="gl-caption" style="margin-top: 20px; text-align: center; color: white; max-width: 800px; padding: 20px; background: rgba(255,255,255,0.05); border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); z-index: 1;">
                <h3 id="gl-title" style="font-size: 1.5rem; margin-bottom: 8px; font-weight: bold; font-family: inherit;"></h3>
                <p id="gl-desc" style="font-size: 1rem; color: #ddd; margin: 0; font-family: inherit;"></p>
            </div>
        </div>
        `;
        document.body.insertAdjacentHTML('beforeend', lightboxHTML);
    }

    document.querySelector('.gl-close').addEventListener('click', closeGlobalLightbox);

    document.getElementById('global-lightbox').addEventListener('click', function(e) {
        if (e.target === this) closeGlobalLightbox();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeGlobalLightbox();
    });

    document.querySelectorAll('img.lightbox-img').forEach((img) => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const title = img.getAttribute('data-title') || img.getAttribute('alt') || '';
            const desc = img.getAttribute('data-desc') || '';
            openGlobalLightbox(img.src, title, desc);
        });
    });

    document.querySelectorAll('.btn-lihat-detail').forEach((btn) => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const src = btn.getAttribute('data-img-src') || '';
            const title = btn.getAttribute('data-title') || '';
            const desc = btn.getAttribute('data-desc') || '';
            if (src) {
                openGlobalLightbox(src, title, desc);
            }
        });
    });
});
