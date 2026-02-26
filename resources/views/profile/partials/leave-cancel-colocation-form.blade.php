<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Gestion de la colocation') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Quitter ou annuler votre colocation.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6">

        {{-- Quitter une colocation --}}
        <form method="post" action="{{ route('profile.leave') }}">
            @csrf
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">
                {{ __('Quitter la colocation') }}
            </button>
        </form>

       {{-- Annuler une colocation - seulement pour l'owner --}}
@if(auth()->user()->ownedColocations()->where('status', 'active')->exists())
    <form method="post" action="{{ route('profile.cancel') }}">
        @csrf
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
            {{ __('Annuler la colocation') }}
        </button>
    </form>
@endif

    </div>
</section>