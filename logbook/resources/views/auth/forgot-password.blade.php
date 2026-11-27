<x-guest-layout>

    {{-- SESSION STATUS --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />


    {{-- JUDUL --}}
    <div class="mb-6 text-center">

        <div class="flex items-center justify-center mb-3">
            <div class="p-3 bg-blue-50 rounded-full">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-800">
            Lupa Password?
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Masukkan email Anda dan kami akan mengirimkan kode OTP untuk reset password.
        </p>

    </div>


    <form method="POST" action="{{ route('password.email') }}">
        @csrf

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
                :value="old('email')"
                placeholder="Masukkan email terdaftar"
                required
                autofocus
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>


        {{-- TOMBOL KIRIM --}}
        <div class="mt-6">
            <button
                type="submit"
                class="w-full px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors duration-200"
            >
                Kirim Kode OTP
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
