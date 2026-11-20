    <x-app-layout>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- ================= HEADER ================= --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <a
                        href="{{ route('leader.employees.logbooks', ['user' => $user->id]) }}"
                        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 transition"
                    >
                        ← Kembali ke Detail Karyawan
                    </a>

                    <h1 class="text-2xl font-bold text-gray-900 mt-3">
                        Ringkasan AI Karyawan
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Analisis berdasarkan seluruh logbook yang telah direview.
                    </p>
                </div>
            </div>


            {{-- ================= INFORMASI KARYAWAN ================= --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6 shadow-sm">

                <div class="flex items-center gap-4">

                    <div
                        class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg"
                    >
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-900">
                            {{ $user->name }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            {{ $user->division ?? 'Tidak ada divisi' }}
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            Periode:
                            {{ $periodStart }}
                            -
                            {{ $periodEnd }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- ================= STATISTIK ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

                {{-- Total --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-sm text-gray-500">
                        Total Logbook
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $total }}
                    </p>
                </div>


                {{-- Approved --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-sm text-gray-500">
                        Approved
                    </p>

                    <p class="text-2xl font-bold text-green-600 mt-1">
                        {{ $approved }}
                    </p>
                </div>


                {{-- Rejected --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-sm text-gray-500">
                        Rejected
                    </p>

                    <p class="text-2xl font-bold text-red-600 mt-1">
                        {{ $rejected }}
                    </p>
                </div>


                {{-- Progress --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-sm text-gray-500">
                        Rata-rata Progress
                    </p>

                    <p class="text-2xl font-bold text-indigo-600 mt-1">
                        {{ $avgProgress }}%
                    </p>
                </div>

            </div>


            {{-- ================= AI SUMMARY ================= --}}
            <div class="bg-white border border-purple-200 rounded-2xl overflow-hidden shadow-sm">

                {{-- AI HEADER --}}
                <div class="px-6 py-5 bg-gradient-to-r from-purple-50 to-indigo-50">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div class="flex items-center gap-4">

                            <div
                                class="w-11 h-11 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-xl shadow-sm"
                            >
                                ✦
                            </div>

                            <div>
                                <h2 class="text-lg font-bold text-gray-900">
                                    Ringkasan AI
                                </h2>

                                <p class="text-sm text-gray-500 mt-1">
                                    Analisis keseluruhan dari
                                    {{ $total }}
                                    logbook yang telah direview.
                                </p>
                            </div>

                        </div>


                        {{-- BUTTON GENERATE --}}
                        <button
                            type="button"
                            id="generate-ai-btn"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            ✦

                            <span id="generate-ai-text">
                                {{ $summary ? 'Perbarui Ringkasan' : 'Buat Ringkasan AI' }}
                            </span>
                        </button>

                    </div>

                </div>


                {{-- ================= AI CONTENT ================= --}}
                <div class="p-6">

                    {{-- LOADING --}}
                    <div
                        id="ai-loading"
                        class="hidden text-center py-12"
                    >

                        <div class="inline-flex items-center gap-3 text-purple-600">

                            <div
                                class="w-5 h-5 border-2 border-purple-600 border-t-transparent rounded-full animate-spin"
                            ></div>

                            <span class="text-sm font-medium">
                                AI sedang menganalisis seluruh logbook...
                            </span>

                        </div>

                        <p class="text-xs text-gray-400 mt-2">
                            Mohon tunggu beberapa saat.
                        </p>

                    </div>


                    {{-- ERROR --}}
                    <div
                        id="ai-error"
                        class="hidden bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700"
                    ></div>


                    {{-- EMPTY --}}
                    <div
                        id="ai-empty"
                        class="{{ $summary ? 'hidden' : '' }} text-center py-12"
                    >

                        <div class="text-4xl mb-4">
                            ✦
                        </div>

                        <h3 class="font-semibold text-gray-900">
                            Belum ada ringkasan AI
                        </h3>

                        <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">
                            Klik tombol
                            <strong>"Buat Ringkasan AI"</strong>
                            untuk menganalisis seluruh logbook
                            {{ $user->name }}
                            yang telah direview.
                        </p>

                    </div>


                    {{-- SUMMARY --}}
                    <div
                        id="ai-content"
                        class="{{ $summary ? '' : 'hidden' }} text-sm text-gray-700 leading-7"
                    ></div>

                </div>

            </div>


            {{-- ================= SUMBER DATA ================= --}}
            <div class="mt-6 bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">

                <div class="px-6 py-4 border-b border-gray-200">

                    <h2 class="font-semibold text-gray-900">
                        Logbook yang Digunakan
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Seluruh logbook yang telah direview digunakan sebagai sumber analisis AI.
                    </p>

                </div>


                <div class="divide-y divide-gray-100">

                    @forelse($logbooks as $logbook)

                        <div class="px-6 py-4">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $logbook->date?->format('d M Y') ?? '-' }}
                                    </p>

                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ Str::limit($logbook->activity ?? '-', 180) }}
                                    </p>

                                </div>


                                <div class="shrink-0">

                                    @if($logbook->status === 'approved')

                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700"
                                        >
                                            Approved
                                        </span>

                                    @else

                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700"
                                        >
                                            Rejected
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="px-6 py-10 text-center text-sm text-gray-500">
                            Belum ada logbook yang telah direview.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- ================= JAVASCRIPT ================= --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const button = document.getElementById('generate-ai-btn');
                const buttonText = document.getElementById('generate-ai-text');

                const loading = document.getElementById('ai-loading');
                const content = document.getElementById('ai-content');
                const empty = document.getElementById('ai-empty');
                const error = document.getElementById('ai-error');

                const existingSummary = @json($summary);

                const generateUrl = @json(
                    route('leader.employees.ai-summary', [
                        'user' => $user->id
                    ])
                );


                /*
                * Escape HTML
                */
                function escapeHtml(text) {

                    const div = document.createElement('div');

                    div.textContent = text ?? '';

                    return div.innerHTML;
                }


                /*
                * Format hasil AI
                */
                function formatSummary(text) {

                    let html = escapeHtml(text);


                    // Heading ###
                    html = html.replace(
                        /^###\s+(.*?)$/gm,
                        '<h3 class="text-lg font-bold text-gray-900 mt-6 mb-2">$1</h3>'
                    );


                    // Heading ##
                    html = html.replace(
                        /^##\s+(.*?)$/gm,
                        '<h2 class="text-xl font-bold text-gray-900 mt-6 mb-3">$1</h2>'
                    );


                    // Heading angka
                    html = html.replace(
                        /^\d+\.\s+(.*?)$/gm,
                        '<h3 class="text-lg font-bold text-gray-900 mt-6 mb-2">$1</h3>'
                    );


                    // Bold
                    html = html.replace(
                        /\*\*(.*?)\*\*/g,
                        '<strong>$1</strong>'
                    );


                    // Bullet
                    html = html.replace(
                        /^\-\s+(.*?)$/gm,
                        '<div class="flex gap-2 ml-2 mb-2">' +
                            '<span>•</span>' +
                            '<span>$1</span>' +
                        '</div>'
                    );


                    // Paragraf
                    html = html.replace(
                        /\n\n/g,
                        '<div class="h-3"></div>'
                    );


                    // Line break
                    html = html.replace(
                        /\n/g,
                        '<br>'
                    );


                    return html;
                }


                /*
                * Tampilkan hasil AI
                */
                function showSummary(summary) {

                    content.innerHTML = formatSummary(summary);

                    content.classList.remove('hidden');

                    empty.classList.add('hidden');

                    error.classList.add('hidden');
                }


                /*
                * Generate AI Summary
                */
                async function generateSummary(refresh = false) {

                    button.disabled = true;

                    buttonText.textContent = 'Sedang Menganalisis...';

                    loading.classList.remove('hidden');

                    content.classList.add('hidden');

                    empty.classList.add('hidden');

                    error.classList.add('hidden');


                    try {

                        const csrfToken = document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute('content');


                        if (!csrfToken) {

                            throw new Error(
                                'CSRF token tidak ditemukan.'
                            );

                        }


                        const response = await fetch(
                            generateUrl,
                            {
                                method: 'POST',

                                headers: {
                                    'Content-Type': 'application/json',

                                    'Accept': 'application/json',

                                    'X-CSRF-TOKEN': csrfToken,

                                    'X-Requested-With': 'XMLHttpRequest'
                                },

                                body: JSON.stringify({
                                    refresh: refresh
                                })
                            }
                        );


                        const contentType =
                            response.headers.get('content-type') || '';


                        let data = null;


                        if (contentType.includes('application/json')) {

                            data = await response.json();

                        } else {

                            const text = await response.text();

                            throw new Error(
                                text ||
                                'Server tidak mengembalikan response JSON.'
                            );

                        }


        if (!response.ok || !data.success) {

            console.error('Gemini Backend Error:', data);

            throw new Error(
                data.error ||
                data.message ||
                'Gagal membuat ringkasan AI.'
            );

        }


                        /*
                        * Jika controller mengembalikan URL redirect,
                        * pindah ke halaman Ringkasan AI.
                        */
                        if (data.redirect) {

                            window.location.href = data.redirect;

                            return;
                        }


                        /*
                        * Fallback jika controller masih
                        * mengembalikan summary secara langsung.
                        */
                        if (data.summary) {

                            showSummary(data.summary);

                            buttonText.textContent =
                                'Perbarui Ringkasan';

                            return;
                        }


                        throw new Error(
                            'Ringkasan berhasil dibuat, tetapi halaman tujuan tidak ditemukan.'
                        );


        } catch (err) {

            console.error(
                'AI Summary Error:',
                err
            );

            error.textContent =
                err.message ||
                'Terjadi kesalahan saat membuat ringkasan AI.';

            error.classList.remove('hidden');

            buttonText.textContent =
                existingSummary
                    ? 'Perbarui Ringkasan'
                    : 'Buat Ringkasan AI';

        }
         finally {

                        loading.classList.add('hidden');

                        button.disabled = false;

                    }

                }


                /*
                * Klik tombol AI
                */
                if (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const refresh =
                                Boolean(existingSummary);

                            generateSummary(refresh);

                        }
                    );

                }


                /*
                * Jika summary sudah tersedia dari cache,
                * tampilkan ketika halaman dibuka.
                */
                if (existingSummary) {

                    showSummary(existingSummary);

                }

            });
        </script>

    </x-app-layout>