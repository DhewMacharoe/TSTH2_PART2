<x-guest-layout>

    {{-- JUDUL --}}
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-gray-800">
            Daftar Akun Karyawan
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Silakan lengkapi data untuk mendaftar akun baru
        </p>
    </div>

    {{-- NOTICE INFO --}}
    <div class="mb-5 p-3.5 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-800 flex items-start gap-2.5 leading-relaxed">
        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <span class="font-semibold">Informasi Pendaftaran:</span>
            Akun baru akan otomatis terdaftar sebagai <strong>Karyawan (Pending)</strong>. Administrator akan menentukan role spesifik (PIC Gudang/Lab, Kepala Departemen, Kepala Divisi, atau Karyawan) dan divisi penempatan Anda sebelum dapat mengakses menu kerja.
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" autocomplete="off">
        @csrf

        {{-- NAMA --}}
        <div>
            <x-input-label for="name" value="Nama Lengkap" class="text-gray-700" />
            <x-text-input
                id="name"
                class="block mt-1.5 w-full"
                type="text"
                name="name"
                :value="old('name')"
                placeholder="Masukkan nama lengkap"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- EMAIL --}}
        <div class="mt-4">
            <x-input-label for="email" value="Email" class="text-gray-700" />
            <x-text-input
                id="email"
                class="block mt-1.5 w-full"
                type="email"
                name="email"
                :value="old('email')"
                placeholder="contoh: nama@tsth2.id / nama@gmail.com"
                required
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- PASSWORD --}}
        <div class="mt-4">
            <x-input-label for="password" value="Password" class="text-gray-700" />
            <x-text-input
                id="password"
                class="block mt-1.5 w-full"
                type="password"
                name="password"
                placeholder="Minimal 8 karakter"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- KONFIRMASI PASSWORD --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" class="text-gray-700" />
            <x-text-input
                id="password_confirmation"
                class="block mt-1.5 w-full"
                type="password"
                name="password_confirmation"
                placeholder="Ulangi password"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- TOMBOL & LINK LOGIN --}}
        <div class="mt-6 flex flex-col gap-3">
            <button
                type="submit"
                class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition text-center text-sm"
            >
                Daftar Sekarang
            </button>

            <p class="text-center text-sm text-gray-600">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>

