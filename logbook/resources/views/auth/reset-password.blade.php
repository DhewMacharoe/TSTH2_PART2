<x-guest-layout>

    {{-- JUDUL --}}
    <div class="mb-6 text-center">

        <div class="flex items-center justify-center mb-3">
            <div class="p-3 bg-blue-50 rounded-full">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-800">
            Reset Password
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Buat password baru untuk akun Anda.
        </p>

    </div>


    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        {{-- Token --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- EMAIL --}}
        <div>

            <x-input-label
                for="email"
                value="Alamat Email"
                class="text-gray-700"
            />

            <x-text-input
                id="email"
                class="block mt-2 w-full"
                type="email"
                name="email"
                :value="old('email', $request->email)"
                placeholder="Masukkan email Anda"
                required
                autofocus
                autocomplete="username"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>


        {{-- PASSWORD BARU --}}
        <div class="mt-5">

            <x-input-label
                for="password"
                value="Password Baru"
                class="text-gray-700"
            />

            <x-text-input
                id="password"
                class="block mt-2 w-full"
                type="password"
                name="password"
                placeholder="Minimal 8 karakter"
                required
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />

        </div>


        {{-- KONFIRMASI PASSWORD --}}
        <div class="mt-5">

            <x-input-label
                for="password_confirmation"
                value="Konfirmasi Password Baru"
                class="text-gray-700"
            />

            <x-text-input
                id="password_confirmation"
                class="block mt-2 w-full"
                type="password"
                name="password_confirmation"
                placeholder="Ulangi password baru"
                required
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2"
            />

        </div>


        {{-- TOMBOL RESET --}}
        <div class="mt-6">
            <button
                type="submit"
                class="w-full px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors duration-200"
            >
                Reset Password
            </button>
        </div>


        {{-- KEMBALI KE LOGIN --}}
        <div class="mt-5 text-center">
            <a
                href="{{ route('login') }}"
                class="text-sm text-blue-600 hover:text-blue-800 hover:underline transition"
            >
                &larr; Kembali ke halaman login
            </a>
        </div>

    </form>

</x-guest-layout>
