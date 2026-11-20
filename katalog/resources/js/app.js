import './bootstrap';

const copyPageUrl = async () => {
    const url = window.location.href;

    if (navigator.clipboard?.writeText) {
        await navigator.clipboard.writeText(url);
        return;
    }

    const textArea = document.createElement('textarea');
    textArea.value = url;
    textArea.setAttribute('readonly', '');
    textArea.style.position = 'fixed';
    textArea.style.opacity = '0';
    document.body.appendChild(textArea);
    textArea.select();

    try {
        if (!document.execCommand('copy')) {
            throw new Error('Penyalinan URL tidak didukung browser.');
        }
    } finally {
        textArea.remove();
    }
};

document.querySelectorAll('[data-share-button]').forEach((button) => {
    button.addEventListener('click', async () => {
        const title = button.dataset.shareTitle || document.title;

        try {
            if (navigator.share) {
                await navigator.share({ title, url: window.location.href });
                return;
            }

            await copyPageUrl();
            alert('Tautan halaman berhasil disalin.');
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            alert('Tautan tidak dapat dibagikan atau disalin. Silakan salin URL halaman ini secara manual.');
        }
    });
});
