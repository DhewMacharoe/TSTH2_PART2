<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Verifikasi OTP berhasil. Silakan buat password baru untuk akun kamu.
    </div>

    <form method="POST" action="{{ route('password.otp.reset.submit') }}">
        @csrf

        <div>
            <x-input-label for="password" value="Password Baru" />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" />

            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Ulangi password baru"
            />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Simpan Password Baru
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>