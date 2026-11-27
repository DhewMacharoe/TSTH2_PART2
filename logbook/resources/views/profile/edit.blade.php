<x-app-layout>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-6">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                Buat Profil
            </h1>
            <p class="text-gray-500 mt-1">
                Lengkapi informasi akun, asal daerah, dan foto profil Anda.
            </p>
        </div>

        @if (in_array(session('status'), ['profile-created', 'profile-updated']))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-5 py-4">
                <p class="text-sm font-medium text-emerald-700">
                    Profil berhasil disimpan.
                </p>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('profile.store') }}"
            enctype="multipart/form-data"
            class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden"
        >
            @csrf

            {{-- Foto Profil --}}
            <div class="px-6 py-6 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Foto Profil</h2>
                <div class="flex items-center gap-5">

                    {{-- Preview --}}
                    <div
                        id="photo-preview-wrapper"
                        class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200 shrink-0 flex items-center justify-center"
                    >
                        @if ($user->profile_photo)
                            <img
                                id="photo-preview"
                                src="{{ Storage::url($user->profile_photo) }}"
                                alt="Foto Profil"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <img
                                id="photo-preview"
                                src=""
                                alt="Foto Profil"
                                class="w-full h-full object-cover hidden"
                            >
                            <svg id="photo-placeholder" class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                            </svg>
                        @endif
                    </div>

                    <div>
                        <label
                            for="profile_photo"
                            class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-sm font-medium text-gray-700 rounded-lg transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Pilih Foto
                        </label>
                        <input
                            type="file"
                            id="profile_photo"
                            name="profile_photo"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="hidden"
                            onchange="previewPhoto(this)"
                        >
                        <p class="text-xs text-gray-400 mt-2">JPG, PNG, WEBP. Maks. 5MB.</p>
                        @error('profile_photo')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Data Pribadi --}}
            <div class="px-6 py-6 space-y-5">
                <h2 class="text-sm font-semibold text-gray-700">Data Pribadi</h2>

                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Lengkap 
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('name') border-red-400 @enderror"
                        required
                    >
                    @error('name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No Telepon --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                        No. Telepon
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        placeholder="Contoh: 08123456789"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('phone') border-red-400 @enderror"
                    >
                    @error('phone')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tanggal Lahir
                    </label>
                    <input
                        type="date"
                        id="birth_date"
                        name="birth_date"
                        value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('birth_date') border-red-400 @enderror"
                    >
                    @error('birth_date')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            {{-- Asal --}}
            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Alamat
                </label>

                <input
                    type="text"
                    id="alamat"
                    name="alamat"
                    value="{{ old('alamat', $user->alamat) }}"
                    placeholder="Contoh: JL. Merpati No. 01"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('alamat') border-red-400 @enderror"
                >

                @error('alamat')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>


            </div>

            {{-- Footer Actions --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <a href="{{ route('profile.show') }}"
            class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">                     
             Kembali
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Profil
                </button>
            </div>

        </form>

         {{-- Pengaturan Email --}}
                <div class="mt-6 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-6">
                        <h2 class="text-sm font-semibold text-gray-700 mb-1">
                            Pengaturan Email
                        </h2>

                        <p class="text-sm text-gray-500 mb-5">
                            Ubah alamat email akun melalui verifikasi kode OTP.
                        </p>

                        {{-- Email Saat Ini --}}
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email Saat Ini
                            </label>

                            <input
                                type="email"
                                value="{{ $user->email }}"
                                readonly
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-gray-100 text-gray-600 cursor-not-allowed"
                            >
                        </div>

                        {{-- Form Email Baru --}}
                        <form method="POST" action="{{ route('profile.email-otp.send') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="new_email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Email Baru
                                </label>

                                <input
                                    type="email"
                                    id="new_email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Masukkan alamat email baru"
                                    maxlength="255"
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('email') border-red-400 @enderror"
                                >

                                @error('email')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror

                                @error('otp')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition"
                            >
                                Kirim Kode OTP
                            </button>
                        </form>

                {{-- Pengaturan Password --}}
                <div class="mt-6 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-6">
                        <h2 class="text-sm font-semibold text-gray-700 mb-1">
                            Pengaturan Password
                        </h2>

                        <p class="text-sm text-gray-500 mb-5">
                            Ubah password akun melalui verifikasi kode OTP yang dikirim ke email kamu.
                        </p>

                        {{-- Email Akun --}}
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email Akun
                            </label>

                            <input
                                type="email"
                                value="{{ $user->email }}"
                                readonly
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-gray-100 text-gray-600 cursor-not-allowed"
                            >
                        </div>

                        {{-- Form Kirim OTP Password --}}
                        <form method="POST" action="{{ route('profile.password-otp.send') }}">
                            @csrf

                            @error('otp')
                                <p class="text-xs text-red-600 mb-3">
                                    {{ $message }}
                                </p>
                            @enderror

                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition"
                            >
                                Kirim OTP untuk Ubah Password
                            </button>
                        </form>
                    </div>
                </div>

                    </div>
                </div>

            </div>
        </div>

<script>
function previewPhoto(input) {
    const preview  = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</x-app-layout>
