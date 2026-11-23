<x-guest-layout>
    <div class="text-center py-4">
        {{-- ICON MENUNGGU --}}
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-amber-100 mb-5 shadow-inner">
            <svg class="h-10 w-10 text-amber-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-800">
            Menunggu Persetujuan Admin
        </h2>

        <p class="mt-2 text-sm text-gray-600 leading-relaxed max-w-sm mx-auto">
            Halo <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>, akun Anda telah berhasil didaftarkan sebagai <strong>Karyawan</strong>.
        </p>

        <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl text-left text-xs text-amber-900 leading-relaxed">
            <div class="flex items-center gap-2 font-bold mb-1">
                <svg class="w-4 h-4 text-amber-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <span>Tahap Persetujuan Akun</span>
            </div>
            Administrator sedang memverifikasi akun Anda untuk menetapkan:
            <ul class="list-disc list-inside mt-1.5 space-y-0.5 text-amber-800 font-medium">
                <li>Role spesifik (PIC Gudang/Lab, Kepala Departemen, Kepala Divisi, atau Karyawan)</li>
                <li>Penempatan Departemen & Divisi kerja</li>
                <li>Kode unik karyawan otomatis</li>
            </ul>
            <p class="mt-2 text-amber-700 font-semibold">
                Setelah Admin menyetujui akun, Anda akan otomatis dialihkan ke menu dan task kerja Anda masing-masing.
            </p>
        </div>

        <div class="mt-6 flex flex-col gap-2.5">
            <button
                type="button"
                onclick="window.location.reload();"
                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm text-sm transition flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Periksa Status Approval Sekarang
            </button>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button
                    type="submit"
                    class="w-full py-2 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm transition"
                >
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>

