<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Profil Saya
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Informasi akun dan data pribadi Anda.
                    </p>
                </div>

                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                    Edit Profil
                </a>
            </div>

            {{-- Pesan sukses --}}
            @if (in_array(session('status'), ['profile-created', 'profile-updated']))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-5 py-4">
                    <p class="text-sm font-medium text-green-700">
                        Profil berhasil disimpan.
                    </p>
                </div>
            @endif

            {{-- Kartu profil --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                {{-- Foto dan identitas --}}
                <div class="px-6 py-8 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row items-center gap-5">

                        <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200 flex items-center justify-center shrink-0">
                            @if ($user->profile_photo)
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::url($user->profile_photo) }}"
                                    alt="Foto Profil"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <svg class="w-12 h-12 text-gray-400"
                                     fill="currentColor"
                                     viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                </svg>
                            @endif
                        </div>

                        <div class="text-center sm:text-left">
                            <h2 class="text-xl font-bold text-gray-800">
                                {{ $user->name }}
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $user->email }}
                            </p>

                            <span class="inline-flex mt-3 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                {{ ucfirst(str_replace('_', ' ', $user->role ?? 'Pengguna')) }}
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Informasi pribadi --}}
                <div class="p-6 sm:p-8">
                    <h3 class="text-base font-semibold text-gray-800 mb-6">
                        Informasi Pribadi
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">

                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">
                                Nama Lengkap
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $user->name ?: '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">
                                Alamat Email
                            </p>
                            <p class="text-sm font-medium text-gray-800 break-all">
                                {{ $user->email ?: '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">
                                Nomor Telepon
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $user->phone ?: '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">
                                Tanggal Lahir
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $user->birth_date ? \Illuminate\Support\Carbon::parse($user->birth_date)->format('d-m-Y') : '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">
                                Asal Daerah
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $user->asal ?: '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">
                                Departemen
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $user->department?->name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">
                                Divisi
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $user->divisionRelation?->name ?? '-' }}
                            </p>
                        </div>

                    </div>
                </div>

                {{-- Tombol navigasi --}}
                <div class="px-6 py-5 sm:px-8 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                <a href="{{ match (Auth::user()->role) {
                        'admin' => route('dashboard'),
                        'employee' => route('dashboard'),
                        'kepala_divisi' => route('dashboard'),
                        'kepala_departemen' => route('dashboard'),
                        'pimpinan' => route('pimpinan.dashboard'),
                        default => route('dashboard'),
                    } }}"
                class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                    Kembali ke Dashboard
                </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>