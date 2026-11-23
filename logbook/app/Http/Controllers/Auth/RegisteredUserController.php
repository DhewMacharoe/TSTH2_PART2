<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan form registrasi akun baru.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Simpan pendaftaran akun baru.
     * Pengguna baru awalnya didaftarkan dengan role 'employee' (karyawan)
     * dan berstatus 'pending' menunggu persetujuan (approval) dari Administrator.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email ini sudah terdaftar pada sistem.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal harus 8 karakter.',
        ]);

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => 'employee',
            'status'       => 'pending',
            'force_logout' => false,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('approval.pending');
    }

    /**
     * Tampilkan halaman pemberitahuan akun sedang menunggu approval Admin.
     */
    public function pendingNotice(): View|RedirectResponse
    {
        $user = Auth::user();

        // Jika user sudah disetujui, arahkan ke dashboard
        if ($user && $user->status === 'approved') {
            return redirect()->route('dashboard');
        }

        return view('auth.pending', compact('user'));
    }
}

