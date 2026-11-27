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

            {{-- Pesan sukses --}}
            @if (session('status') === 'profile-created')
                <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 px-5 py-4">
                    <p class="text-sm font-medium text-emerald-700">
                        Profil berhasil dibuat.
                    </p>
                </div>
            @endif

            {{-- Pesan validasi --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-5 py-4">
                    <p class="text-sm font-semibold text-red-700 mb-2">
                        Periksa kembali data yang kamu masukkan.
                    </p>
                    <ul class="list-disc pl-5 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Create Profil --}}
            <form
                method="POST"
                action="{{ route('profile.store') }}"
                enctype="multipart/form-data"
                class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden"
            >
                @csrf

                {{-- Foto Profil --}}
                <div class="px-6 py-6 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-700 mb-4">
                        Foto Profil
                    </h2>

                    <div class="flex items-center gap-5">
                        <div
                            class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200 shrink-0 flex items-center justify-center"
                        >
                            <img
                                id="photo-preview"
                                src="{{ $user->profile_photo ? \Illuminate\Support\Facades\Storage::url($user->profile_photo) : '' }}"
                                alt="Preview Foto Profil"
                                class="w-full h-full object-cover {{ $user->profile_photo ? '' : 'hidden' }}"
                            >

                            <svg
                                id="photo-placeholder"
                                class="w-10 h-10 text-gray-400 {{ $user->profile_photo ? 'hidden' : '' }}"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                            </svg>
                        </div>

                        <div>
                            <label
                                for="profile_photo"
                                class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-sm font-medium text-gray-700 rounded-lg transition"
                            >
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

                            <p class="text-xs text-gray-400 mt-2">
                                JPG, PNG, WEBP. Maksimal 5 MB.
                            </p>

                            @error('profile_photo')
                                <p class="text-xs text-red-600 mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Data Pribadi --}}
                <div class="px-6 py-6 space-y-5">
                    <h2 class="text-sm font-semibold text-gray-700">
                        Data Pribadi
                    </h2>

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
                            maxlength="255"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('name') border-red-400 @enderror"
                            placeholder="Masukkan nama lengkap"
                        >

                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Nomor Telepon --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                            No. Telepon
                        </label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            maxlength="20"
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
                            value="{{ old('birth_date', $user->birth_date ? \Illuminate\Support\Carbon::parse($user->birth_date)->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('birth_date') border-red-400 @enderror"
                        >

                        @error('birth_date')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="asal" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Alamat
                        </label>

                        <input
                            type="text"
                            id="asal"
                            name="asal"
                            value="{{ old('asal', $user->asal) }}"
                            maxlength="255"
                            placeholder="Contoh: Balige"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('asal') border-red-400 @enderror"
                        >

                        @error('asal')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3">
                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition"
                    >
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

            {{-- Form Ganti Email --}}
            <div class="mt-6 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5">
                    <h2 class="text-sm font-semibold text-gray-700 mb-1">
                        Pengaturan Email
                    </h2>

                    <p class="text-sm text-gray-500 mb-4">
                        Email digunakan untuk menerima kode OTP dan notifikasi akun.
                    </p>

                    {{-- Email Saat Ini --}}
                    <div class="mb-4">
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

                    {{-- Form Permintaan OTP --}}
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
                                required
                                maxlength="255"
                                placeholder="Masukkan email baru"
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
                </div>
            </div>
        </div>
    </div>

    {{-- Preview Foto --}}
    <script>
        function previewPhoto(input) {
            const preview = document.getElementById('photo-preview');
            const placeholder = document.getElementById('photo-placeholder');

            if (!input.files || !input.files[0]) {
                return;
            }

            const file = input.files[0];
            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                alert('Format foto harus JPG, JPEG, PNG, atau WEBP.');
                input.value = '';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran foto maksimal 5 MB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');

                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            };

            reader.readAsDataURL(file);
        }
    </script>
</x-app-layout>
