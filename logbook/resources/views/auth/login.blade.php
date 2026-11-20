<x-guest-layout>

    {{-- SESSION STATUS --}}
    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />


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

    </form>

</x-guest-layout>