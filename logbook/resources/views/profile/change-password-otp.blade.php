<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-md mx-auto px-4">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-800">
                    Ubah Password
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Verifikasi OTP berhasil. Silakan masukkan password baru.
                </p>

                <form method="POST" action="{{ route('profile.password.change') }}" class="mt-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                        </label>

                        <input
                            type="password"
                            name="password"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('password')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            placeholder="Ulangi password baru"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <button type="submit"
                        class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-lg font-medium">
                        Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>