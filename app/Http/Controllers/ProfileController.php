<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function uploadPhoto(Request $request): RedirectResponse
{
    $request->validate([
        'photo' => ['required', 'image', 'max:2048'],
    ]);

    $path = $request->file('photo')->store('photos', 'public');

    $request->user()->update([
        'photo' => $path,
    ]);

    return Redirect::route('profile.edit')->with('status', 'photo-updated');
}

public function leaveColocation(Request $request): RedirectResponse
{
    $user = $request->user();

    $user->colocations()->updateExistingPivot(
        $user->colocations()->first()->id,
        ['left_at' => now()]
    );

    return Redirect::route('profile.edit')->with('status', 'colocation-left');
}

public function cancelColocation(Request $request): RedirectResponse
{
    $user = $request->user();

    $colocation = $user->ownedColocations()
                       ->where('status', 'active')
                       ->first();

    $colocation->update([
        'status' => 'cancelled'
    ]);

    return Redirect::route('profile.edit')->with('status', 'colocation-cancelled');
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
