<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfilController extends Controller
{
    /**
     * Display the user's profil form.
     */
    public function edit(Request $permintaan): View
    {
        return view('profil.edit', [
            'user' => $permintaan->user(),
        ]);
    }

    /**
     * Update the user's profil information.
     */
    public function update(ProfileUpdateRequest $permintaan): RedirectResponse
    {
        $permintaan->user()->fill($permintaan->validated());

        if ($permintaan->user()->isDirty('email')) {
            $permintaan->user()->email_verified_at = null;
        }

        $permintaan->user()->save();

        return Redirect::route('profil.edit')->with('status', 'profil-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $permintaan): RedirectResponse
    {
        $permintaan->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $permintaan->user();

        Auth::logout();

        $user->delete();

        $permintaan->session()->invalidate();
        $permintaan->session()->regenerateToken();

        return Redirect::to('/');
    }
}
