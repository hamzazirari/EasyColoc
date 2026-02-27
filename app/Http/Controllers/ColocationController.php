<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ColocationController extends Controller
{
    public function create(): View
    {
        return view('colocation.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();

        // Vérifier si owner avec colocation active
        $isOwnerActive = $user->colocations()
                              ->wherePivot('role', 'owner')
                              ->where('status', 'active')
                              ->exists();

        // Vérifier si member qui n'a pas quitté
        $isMemberActive = $user->colocations()
                               ->wherePivot('role', 'member')
                               ->wherePivot('left_at', null)
                               ->exists();

        if ($isOwnerActive || $isMemberActive) {
            return back()->withErrors(['name' => 'Vous avez déjà une colocation active !']);
        }

        $colocation = Colocation::create([
            'name' => $request->name,
            'owner_id' => $user->id,
        ]);

        $colocation->members()->attach($user->id, [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        return redirect()->route('colocation.index')->with('status', 'colocation-created');
    }

    public function index(): View
    {
        $user = auth()->user();
        $colocation = $user->colocations()
                           ->wherePivot('left_at', null)
                           ->where('status', 'active')
                           ->first();

        return view('colocation.index', compact('colocation'));
    }

    public function destroy(Colocation $colocation): RedirectResponse
    {
        $colocation->update([
            'status' => 'cancelled'
        ]);

        return redirect()->route('colocation.index')->with('status', 'colocation-cancelled');
    }
}