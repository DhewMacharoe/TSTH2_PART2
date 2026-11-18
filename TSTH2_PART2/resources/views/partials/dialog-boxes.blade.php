{{-- ═══════════════════════════════════════════════════════════
     GLOBAL ALERT BOXES & CONFIRMATION MODAL BOX (FIGMA STYLE)
     ═══════════════════════════════════════════════════════════ --}}

<!-- 1. FLASH MESSAGE BOXES (Session Alerts) -->
<div class="space-y-4 mb-6" id="global-flash-container">
    @if (session('success'))
        <div class="flex items-start justify-between gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-900 shadow-sm transition-all duration-300" role="alert" id="flash-success-box">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-base">check</span>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-emerald-950">Berhasil!</h4>
                    <p class="text-xs text-emerald-800 mt-0.5 leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('flash-success-box')?.remove()" class="text-emerald-700 hover:text-emerald-950 hover:bg-emerald-100 rounded-lg p-1.5 transition text-xs font-bold inline-flex items-center" aria-label="Tutup">
                <span class="material-symbols-rounded text-base">close</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="flex items-start justify-between gap-3 p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-900 shadow-sm transition-all duration-300" role="alert" id="flash-error-box">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-base">close</span>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-rose-950">Terjadi Kesalahan!</h4>
                    <p class="text-xs text-rose-800 mt-0.5 leading-relaxed">{{ session('error') }}</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('flash-error-box')?.remove()" class="text-rose-700 hover:text-rose-950 hover:bg-rose-100 rounded-lg p-1.5 transition text-xs font-bold inline-flex items-center" aria-label="Tutup">
                <span class="material-symbols-rounded text-base">close</span>
            </button>
        </div>
    @endif

    @if (session('warning'))
        <div class="flex items-start justify-between gap-3 p-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-900 shadow-sm transition-all duration-300" role="alert" id="flash-warning-box">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-base">priority_high</span>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-950">Peringatan!</h4>
                    <p class="text-xs text-amber-800 mt-0.5 leading-relaxed">{{ session('warning') }}</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('flash-warning-box')?.remove()" class="text-amber-700 hover:text-amber-950 hover:bg-amber-100 rounded-lg p-1.5 transition text-xs font-bold inline-flex items-center" aria-label="Tutup">
                <span class="material-symbols-rounded text-base">close</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="flex items-start justify-between gap-3 p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-900 shadow-sm transition-all duration-300" role="alert" id="flash-validation-box">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-base">priority_high</span>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-rose-950">Harap Periksa Formulir Anda</h4>
                    <p class="text-xs text-rose-800 mt-0.5">Terdapat beberapa kesalahan data yang perlu diperbaiki:</p>
                    <ul class="mt-2 list-disc list-inside text-xs text-rose-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('flash-validation-box')?.remove()" class="text-rose-700 hover:text-rose-950 hover:bg-rose-100 rounded-lg p-1.5 transition text-xs font-bold inline-flex items-center" aria-label="Tutup">
                <span class="material-symbols-rounded text-base">close</span>
            </button>
        </div>
    @endif
</div>

<!-- 2. GLOBAL MODAL BOX KONFIRMASI (FIGMA DIALOG) -->
<div id="custom-confirm-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-200">
    <div class="relative w-full max-w-md bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-gray-100 transform transition-all duration-200 scale-95 opacity-0" id="custom-confirm-card">
        <div class="flex items-start gap-4">
            <div id="confirm-icon-wrapper" class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200/80 flex items-center justify-center shrink-0 shadow-inner">
                <span class="material-symbols-rounded text-2xl">warning</span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 id="confirm-title" class="text-lg font-extrabold text-gray-900 tracking-tight">Konfirmasi Tindakan</h3>
                <p id="confirm-message" class="text-xs sm:text-sm text-gray-600 mt-1.5 leading-relaxed">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
            </div>
        </div>
        <div class="mt-6 flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5">
            <button type="button" id="confirm-cancel-btn" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold transition">
                Batal
            </button>
            <button type="button" id="confirm-accept-btn" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-primary-900 hover:bg-primary-800 text-white text-xs font-bold transition shadow-md">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

<!-- 3. GLOBAL MODAL BOX PERINGATAN / ALERT (MENGGANTIKAN ALERT BAWAAN) -->
<div id="custom-alert-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-200">
    <div class="relative w-full max-w-md bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-gray-100 transform transition-all duration-200 scale-95 opacity-0" id="custom-alert-card">
        <div class="flex items-start gap-4">
            <div id="alert-icon-wrapper" class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-900 border border-primary-200/80 flex items-center justify-center shrink-0 shadow-inner">
                <span class="material-symbols-rounded text-2xl">info</span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 id="alert-title" class="text-lg font-extrabold text-gray-900 tracking-tight">Pemberitahuan</h3>
                <p id="alert-message" class="text-xs sm:text-sm text-gray-600 mt-1.5 leading-relaxed"></p>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="button" id="alert-close-btn" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-primary-900 hover:bg-primary-800 text-white text-xs font-bold transition shadow-md">
                Mengerti
            </button>
        </div>
    </div>
</div>

<script>
    (function() {
        // Helper: Tampilkan Modal Konfirmasi Box
        window.showConfirmDialog = function(options) {
            const modal = document.getElementById('custom-confirm-modal');
            const card = document.getElementById('custom-confirm-card');
            const titleEl = document.getElementById('confirm-title');
            const msgEl = document.getElementById('confirm-message');
            const iconWrapper = document.getElementById('confirm-icon-wrapper');
            const acceptBtn = document.getElementById('confirm-accept-btn');
            const cancelBtn = document.getElementById('confirm-cancel-btn');

            if (!modal || !card) return;

            const isDelete = (options.title && options.title.toLowerCase().includes('hapus')) || 
                             (options.message && options.message.toLowerCase().includes('hapus'));

            titleEl.textContent = options.title || (isDelete ? 'Konfirmasi Hapus Data' : 'Konfirmasi Tindakan');
            msgEl.textContent = options.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            acceptBtn.textContent = options.confirmText || (isDelete ? 'Ya, Hapus' : 'Ya, Lanjutkan');
            cancelBtn.textContent = options.cancelText || 'Batal';

            if (isDelete) {
                acceptBtn.className = 'w-full sm:w-auto px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-md';
                iconWrapper.className = 'w-12 h-12 rounded-2xl bg-rose-50 text-rose-700 border border-rose-200/80 flex items-center justify-center shrink-0 shadow-inner';
                iconWrapper.innerHTML = '<span class="material-symbols-rounded text-2xl">delete</span>';
            } else {
                acceptBtn.className = 'w-full sm:w-auto px-5 py-2.5 rounded-xl bg-primary-900 hover:bg-primary-800 text-white text-xs font-bold transition shadow-md';
                iconWrapper.className = 'w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200/80 flex items-center justify-center shrink-0 shadow-inner';
                iconWrapper.innerHTML = '<span class="material-symbols-rounded text-2xl">warning</span>';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });

            function closeModal() {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    cleanup();
                }, 150);
            }

            function handleAccept() {
                closeModal();
                if (typeof options.onConfirm === 'function') {
                    options.onConfirm();
                }
            }

            function handleCancel() {
                closeModal();
                if (typeof options.onCancel === 'function') {
                    options.onCancel();
                }
            }

            function handleKeydown(e) {
                if (e.key === 'Escape') handleCancel();
            }

            function cleanup() {
                acceptBtn.removeEventListener('click', handleAccept);
                cancelBtn.removeEventListener('click', handleCancel);
                document.removeEventListener('keydown', handleKeydown);
            }

            acceptBtn.addEventListener('click', handleAccept);
            cancelBtn.addEventListener('click', handleCancel);
            document.addEventListener('keydown', handleKeydown);
        };

        // Helper: Tampilkan Modal Alert / Peringatan Box (Menggantikan alert bawaan)
        window.showAlertDialog = function(message, title, icon) {
            const modal = document.getElementById('custom-alert-modal');
            const card = document.getElementById('custom-alert-card');
            const titleEl = document.getElementById('alert-title');
            const msgEl = document.getElementById('alert-message');
            const iconWrapper = document.getElementById('alert-icon-wrapper');
            const closeBtn = document.getElementById('alert-close-btn');

            if (!modal || !card) return;

            titleEl.textContent = title || 'Pemberitahuan';
            msgEl.textContent = message || '';
            iconWrapper.innerHTML = icon || '<span class="material-symbols-rounded text-2xl">info</span>';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });

            function closeModal() {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    cleanup();
                }, 150);
            }

            function handleKeydown(e) {
                if (e.key === 'Escape') closeModal();
            }

            function cleanup() {
                closeBtn.removeEventListener('click', closeModal);
                document.removeEventListener('keydown', handleKeydown);
            }

            closeBtn.addEventListener('click', closeModal);
            document.addEventListener('keydown', handleKeydown);
        };

        // Ganti alert bawaan browser dengan modal box
        window.alert = function(message) {
            window.showAlertDialog(message, 'Pemberitahuan', '<span class="material-symbols-rounded text-2xl">warning</span>');
        };

        // Intercept semua form yang menggunakan confirm(...) atau data-confirm
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.dataset.confirmed === 'true') {
                form.dataset.confirmed = 'false';
                return;
            }

            let confirmMsg = form.getAttribute('data-confirm');
            const onsubmitAttr = form.getAttribute('onsubmit');

            if (!confirmMsg && onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                const match = onsubmitAttr.match(/confirm\s*\(\s*['"`](.*?)['"`]\s*\)/);
                confirmMsg = match && match[1] ? match[1] : 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                form.removeAttribute('onsubmit');
                form.dataset.confirm = confirmMsg;
            }

            if (confirmMsg) {
                e.preventDefault();
                e.stopImmediatePropagation();

                window.showConfirmDialog({
                    message: confirmMsg,
                    onConfirm: function() {
                        form.dataset.confirmed = 'true';
                        form.submit();
                    }
                });
            }
        }, true);

        // Terjemahkan pesan validasi bawaan HTML5 browser ke Bahasa Indonesia
        document.addEventListener('invalid', function(e) {
            const input = e.target;
            if (input.validity.valueMissing) {
                input.setCustomValidity('Bidang ini wajib diisi.');
            } else if (input.validity.typeMismatch && input.type === 'email') {
                input.setCustomValidity('Format alamat email tidak valid (contoh: nama@domain.com).');
            } else if (input.validity.typeMismatch && input.type === 'url') {
                input.setCustomValidity('Format tautan web tidak valid (contoh: https://contoh.com).');
            } else if (input.validity.tooShort) {
                input.setCustomValidity(`Harap masukkan minimal ${input.minLength} karakter.`);
            } else if (input.validity.tooLong) {
                input.setCustomValidity(`Maksimal ${input.maxLength} karakter yang diperbolehkan.`);
            } else if (input.validity.rangeUnderflow) {
                input.setCustomValidity(`Nilai minimal yang diperbolehkan adalah ${input.min}.`);
            } else if (input.validity.rangeOverflow) {
                input.setCustomValidity(`Nilai maksimal yang diperbolehkan adalah ${input.max}.`);
            }
        }, true);

        document.addEventListener('input', function(e) {
            e.target.setCustomValidity('');
        }, true);
    })();
</script>
