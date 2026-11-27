<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-md mx-auto px-4">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-800">
                    Verifikasi OTP
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Masukkan kode OTP 6 digit yang telah dikirim ke email akun kamu.
                </p>

                @if (session('status'))
                    <p class="text-sm text-green-600 mt-3">
                        {{ session('status') }}
                    </p>
                @endif

                <form method="POST" action="{{ route('profile.password-otp.verify') }}" class="mt-6">
                    @csrf

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kode OTP
                    </label>

                    <input
                        type="text"
                        name="otp"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        required
                        autofocus
                        placeholder="Masukkan 6 digit OTP"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    @error('otp')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror

                    <button type="submit"
                        class="w-full mt-5 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-lg font-medium">
                        Verifikasi OTP
                    </button>
                </form>

                <a href="{{ route('profile.edit') }}"
                    class="block text-center text-sm text-gray-500 mt-4 hover:text-gray-700">
                    Kembali ke Profil
                </a>
            </div>
        </div>
    </div>
</x-app-layout>