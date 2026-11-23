<x-guest-layout>

    {{-- SESSION STATUS --}}
    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    @if(session('info'))
        <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg text-sm flex items-start gap-2">
            <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>{{ session('info') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm flex items-start gap-2">
            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif


    {{-- JUDUL --}}
    <div class="mb-6 text-center">

        <h2 class="text-xl font-bold text-gray-800">
            Masuk ke Akun
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Silakan masuk untuk melanjutkan
        </p>

    </div>


    <form method="POST" action="{{ route('login') }}">

        @csrf


        {{-- EMAIL --}}
        <div>

            <x-input-label
                for="email"
                value="Email"
                class="text-gray-700"
            />

            <x-text-input
                id="email"
                class="block mt-2 w-full"
                type="email"
                name="email"
                :value="old('email')"
                placeholder="Masukkan email"
                required
                autofocus
                autocomplete="username"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>


        {{-- PASSWORD --}}
        <div class="mt-5">

            <x-input-label
                for="password"
                value="Password"
                class="text-gray-700"
            />

            <x-text-input
                id="password"
                class="block mt-2 w-full"
                type="password"
                name="password"
                placeholder="Masukkan password"
                required
                autocomplete="current-password"
            />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />

        </div>


        {{-- REMEMBER ME --}}
        <div class="mt-5">

            <label
                for="remember_me"
                class="inline-flex items-center cursor-pointer"
            >

                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="w-4 h-4 bg-white border border-gray-300 rounded text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-0"
                >

                <span class="ms-2 text-sm text-gray-600">
                    Remember me
                </span>

            </label>

        </div>


        {{-- LUPA PASSWORD + LOGIN --}}
        <div class="flex items-center justify-between mt-6">

            @if (Route::has('password.request'))

                <a
                    href="{{ route('password.request') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 hover:underline transition"
                >
                    Lupa password?
                </a>

            @endif


            <button
                type="submit"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition"
            >
                Masuk
            </button>

        </div>

        {{-- DAFTAR AKUN BARU --}}
        @if (Route::has('register'))
            <div class="mt-6 pt-5 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-600">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-800 hover:underline transition">
                        Daftar Akun Baru
                    </a>
                </p>
            </div>
        @endif

    </form>

</x-guest-layout>