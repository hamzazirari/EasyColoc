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

    $colocation = $user->colocations()
                       ->wherePivot('left_at', null)
                       ->where('status', 'active')
                       ->first();

    if (!$colocation) {
        return back()->withErrors(['error' => "Vous n'avez pas de colocation active !"]);
    }

    $expenses = $colocation->expenses;
    $totalExpenses = $expenses->sum('amount');
    $numberOfMembers = $colocation->members->count();
    $individualShare = $numberOfMembers > 0 ? $totalExpenses / $numberOfMembers : 0;

    $paid = $expenses->where('user_id', $user->id)->sum('amount');

    $balance = $paid - $individualShare;

    if ($balance >= 0) {
        $user->increment('reputation');
    } else {
        $user->decrement('reputation');
    }

    $user->colocations()->updateExistingPivot(
        $colocation->id,
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

    if (!$colocation) {
        return back()->withErrors(['error' => "Vous n'avez pas de colocation active !"]);
    }

    $expenses = $colocation->expenses;
    $totalExpenses = $expenses->sum('amount');
    $numberOfMembers = $colocation->members->count();
    $individualShare = $numberOfMembers > 0 ? $totalExpenses / $numberOfMembers : 0;

    foreach ($colocation->members as $member) {

        $paid = $expenses->where('user_id', $member->id)->sum('amount');

        $balance = $paid - $individualShare;

        if ($balance < 0) {
            $member->decrement('reputation');
        }
    }

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
