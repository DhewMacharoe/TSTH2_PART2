<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Kami telah mengirimkan kode OTP ke email yang terdaftar.
        Silakan periksa Inbox atau folder Spam.
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    @if ($email)
        <div class="mb-4 text-sm font-semibold text-gray-800">
            {{ $email }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.otp.verify') }}">
        @csrf

        <div>
            <x-input-label for="otp" value="Kode OTP" />

            <x-text-input
                id="otp"
                class="block mt-1 w-full"
                type="text"
                name="otp"
                maxlength="6"
                inputmode="numeric"
                pattern="[0-9]{6}"
                placeholder="Masukkan 6 digit OTP"
                required
                autofocus
            />

            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Verifikasi OTP
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('password.request') }}"
            class="text-sm text-gray-600 hover:text-gray-900 underline">
            Kembali ke Lupa Password
        </a>
    </div>
</x-guest-layout>