@extends('layouts.dasbor')

@section('title', 'Pengaturan Profil - TSTH2 Hub')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan Profil</h1>
        <p class="text-sm sm:text-base text-gray-500 font-medium mt-1">Perbarui informasi akun dan kredensial keamanan Anda.</p>
    </div>

    @if(session('status') === 'profil-updated' || session('status') === 'password-updated')
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-semibold shadow-sm animate-fade-in">
            <span class="text-lg">✅</span>
            <span>Profil berhasil diperbarui.</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Update Profile Info Card -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center text-xl font-bold">
                        👤
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 leading-tight">Informasi Akun</h2>
                        <p class="text-xs text-gray-400">Data identitas pengguna sistem</p>
                    </div>
                </div>

                <form method="post" action="{{ route('profil.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent text-sm font-medium transition" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Alamat Email</label>
                        <input type="email" name="email" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent text-sm font-medium transition" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Peran / Role</label>
                        <input type="text" disabled class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 text-sm font-semibold capitalize cursor-not-allowed" value="{{ str_replace('_', ' ', $user->role) }}">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-primary hover:bg-primary-light text-white rounded-xl text-sm font-bold shadow-sm transition-all">
                            Simpan Informasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-accent/15 text-accent-dark flex items-center justify-center text-xl font-bold">
                        🔒
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 leading-tight">Ubah Kata Sandi</h2>
                        <p class="text-xs text-gray-400">Pastikan akun menggunakan sandi yang kuat</p>
                    </div>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Sandi Saat Ini</label>
                        <input type="password" name="current_password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-accent focus:border-transparent text-sm transition" required>
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Sandi Baru</label>
                        <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-accent focus:border-transparent text-sm transition" required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Konfirmasi Sandi Baru</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-accent focus:border-transparent text-sm transition" required>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-accent hover:bg-accent-dark text-white rounded-xl text-sm font-bold shadow-sm transition-all">
                            Perbarui Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
