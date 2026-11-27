<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman Buat Profil.
     */
    public function create(Request $request): View
    {
        $user = $request->user()->load([
            'department',
            'divisionRelation',
        ]);

        $stats = [
            'total_logbooks' => $user->logbooks()->count(),
            'total_tasks' => $user->assignedTasks()->count(),
        ];

        return view('profile.create', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Menyimpan data profil.
     * Email tidak boleh diubah melalui fungsi ini.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'asal' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'asal' => $validated['asal'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
        ]);

        $oldPhoto = $user->profile_photo;
        $newPhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $newPhotoPath = $request->file('profile_photo')
                ->store('profile-photos', 'public');

            $user->profile_photo = $newPhotoPath;
        }

        try {
            $user->save();

            if (
                $newPhotoPath &&
                $oldPhoto &&
                Storage::disk('public')->exists($oldPhoto)
            ) {
                Storage::disk('public')->delete($oldPhoto);
            }
        } catch (\Throwable $e) {
            if ($newPhotoPath) {
                Storage::disk('public')->delete($newPhotoPath);
            }

            throw $e;
        }

        return Redirect::route('profile.edit')
            ->with('status', 'profile-created');
    }

    /**
     * Menampilkan halaman Edit Profil.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load([
            'department',
            'divisionRelation',
        ]);

        $stats = [
            'total_logbooks' => $user->logbooks()->count(),
            'total_tasks' => $user->assignedTasks()->count(),
        ];

        return view('profile.edit', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Memperbarui data profil.
     * Perubahan email hanya boleh melalui verifikasi OTP.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'asal' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'asal' => $validated['asal'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
        ]);

        $oldPhoto = $user->profile_photo;
        $newPhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $newPhotoPath = $request->file('profile_photo')
                ->store('profile-photos', 'public');

            $user->profile_photo = $newPhotoPath;
        }

        try {
            $user->save();

            if (
                $newPhotoPath &&
                $oldPhoto &&
                Storage::disk('public')->exists($oldPhoto)
            ) {
                Storage::disk('public')->delete($oldPhoto);
            }
        } catch (\Throwable $e) {
            if ($newPhotoPath) {
                Storage::disk('public')->delete($newPhotoPath);
            }

            throw $e;
        }

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Menampilkan detail profil pengguna.
     */
    public function show(Request $request): View
    {
        $user = $request->user()->load([
            'department',
            'divisionRelation',
        ]);

        $stats = [
            'total_logbooks' => $user->logbooks()->count(),
            'total_tasks' => $user->assignedTasks()->count(),
        ];

        return view('profile.show', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
