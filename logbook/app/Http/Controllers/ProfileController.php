<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
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
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users')->ignore($user->id),
            ],
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
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'asal' => $validated['asal'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

    if ($request->hasFile('profile_photo')) {
        $oldPhoto = $user->profile_photo;

        // Simpan foto baru terlebih dahulu
        $path = $request->file('profile_photo')
            ->store('profile-photos', 'public');

        $user->profile_photo = $path;

        // Hapus foto lama setelah foto baru tersimpan
        if (
            $oldPhoto &&
            Storage::disk('public')->exists($oldPhoto)
        ) {
            Storage::disk('public')->delete($oldPhoto);
        }
    }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-created');
    }




    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['department', 'divisionRelation']);
        $stats = [
            'total_logbooks' => $user->logbooks()->count(),
            'total_tasks'    => $user->assignedTasks()->count(),
        ];

        return view('profile.edit', [
            'user'  => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'phone'         => ['nullable', 'string', 'max:20'],
            'asal' => ['nullable', 'string', 'max:255'],
            'birth_date'    => ['nullable', 'date'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $user->fill([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'asal'       => $validated['asal'] ?? null,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

        // TAMBAHKAN METHOD SHOW DI SINI
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
     * Delete the user's account.
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
