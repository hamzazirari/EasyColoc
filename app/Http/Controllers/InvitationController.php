<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Colocation;
use App\Mail\InvitationMail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = $request->user();

        // Recuperer la colocation active de owner
        $colocation = $user->colocations()
                           ->wherePivot('role', 'owner')
                           ->where('status', 'active')
                           ->first();

        if (!$colocation) {
            return back()->withErrors(['email' => 'Vous devez être owner pour inviter !']);
        }

        // Creer linvitation avec token unique
        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $request->email,
            'token' => Str::uuid(),
            'status' => 'pending',
            'expires_at' => Carbon::now()->addHours(48),
        ]);

        // Envoyer l email
        Mail::to($request->email)->send(new InvitationMail($invitation));

        return redirect()->route('colocation.index')->with('status', 'invitation-sent');
    }

    public function accept(string $token): RedirectResponse
{
    // Trouver invitation avec token
    $invitation = Invitation::where('token', $token)
                            ->where('status', 'pending')
                            ->first();

    // Vérifier si invitation existe
    if (!$invitation) {
        return redirect()->route('dashboard')->withErrors(['error' => 'Invitation invalide !']);
    }

    // Verifier si linvitation est expire
    if ($invitation->expires_at < now()) {
        return redirect()->route('dashboard')->withErrors(['error' => 'Invitation expirée !']);
    }

    $user = auth()->user();

    // Vérifier si l'user a déjà une colocation active
    $isOwnerActive = $user->colocations()
                          ->wherePivot('role', 'owner')
                          ->where('status', 'active')
                          ->exists();

    $isMemberActive = $user->colocations()
                           ->wherePivot('role', 'member')
                           ->wherePivot('left_at', null)
                           ->exists();

    if ($isOwnerActive || $isMemberActive) {
        return redirect()->route('dashboard')->withErrors(['error' => 'Vous avez déjà une colocation active !']);
    }

    // Ajouter user comme membre
    $invitation->colocation->members()->attach($user->id, [
        'role' => 'member',
        'joined_at' => now(),
    ]);

    // Mise a jour dial status -> accepted
    $invitation->update(['status' => 'accepted']);

    return redirect()->route('colocation.index')->with('status', 'invitation-accepted');
}


public function refuse(string $token): RedirectResponse
{
    // Trouver invitation avec le token
    $invitation = Invitation::where('token', $token)
                            ->where('status', 'pending')
                            ->first();

    // Verifier si invitation existe
    if (!$invitation) {
        return redirect()->route('dashboard')->withErrors(['error' => 'Invitation invalide !']);
    }

    // mise a jour dial  le status 
    $invitation->update(['status' => 'refused']);

    return redirect()->route('dashboard')->with('status', 'invitation-refused');
}
}