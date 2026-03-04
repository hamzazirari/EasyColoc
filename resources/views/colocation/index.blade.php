@use('Illuminate\Support\Facades\Storage')
<x-app-layout>
    @if(!$colocation)
        <div class="p-6 text-center">
            <p class="text-gray-600 mb-4">Vous n'avez pas de colocation active.</p>
            <a href="{{ route('colocation.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                Créer une colocation
            </a>
        </div>
    @else
        <div class="p-6">

            {{-- Infos colocation --}}
            <div class="bg-white p-6 rounded-xl shadow mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $colocation->name }}</h1>
                <p>Statut : 
                    @if($colocation->status === 'active')
                        <span class="text-green-600 font-bold">Active</span>
                    @else
                        <span class="text-red-600 font-bold">Annulée</span>
                    @endif
                </p>
            </div>

            {{-- Membres --}}
            <div class="bg-white p-6 rounded-xl shadow mb-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Membres</h2>
                @foreach($colocation->members as $member)
                    <div class="flex items-center justify-between py-2 border-b">
    <div class="flex items-center space-x-3">
        @if($member->photo)
            <img src="{{ Storage::url($member->photo) }}" class="w-10 h-10 rounded-full object-cover">
        @else
            <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>
        @endif
        <div>
            <p class="font-medium">{{ $member->name }}</p>
            <p class="text-sm text-gray-500">{{ $member->email }}</p>
        </div>
    </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full 
                            {{ $member->pivot->role === 'owner' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ strtoupper($member->pivot->role) }}
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- Invitation - seulement owner --}}
@if(auth()->user()->colocations()->first()->pivot->role === 'owner')
    <div class="bg-white p-6 rounded-xl shadow mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Inviter un membre</h2>
        <form action="{{ route('colocation.invite') }}" method="POST" class="flex space-x-4">
            @csrf
            <input type="email" name="email" required placeholder="Email de l'invité"
                class="border border-gray-300 rounded-lg px-4 py-2 w-full">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg whitespace-nowrap">
                Envoyer l'invitation
            </button>
        </form>
        @error('email')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>
@endif

            {{-- Boutons --}}
<div class="flex space-x-4">

    {{-- Ajouter une dépense --}}
    <a href="{{ route('expenses.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
        + Ajouter une dépense
    </a>

    {{-- Annuler la colocation - seulement owner --}}
    @if(auth()->user()->colocations()->first()->pivot->role === 'owner')
        <form action="{{ route('colocations.destroy', $colocation) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                Annuler la colocation
            </button>
        </form>

    {{-- Quitter la colocation - seulement member --}}
    @else
        <form action="{{ route('profile.leave') }}" method="POST">
            @csrf
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg">
                Quitter la colocation
            </button>
        </form>
    @endif

</div>

        </div>
    @endif
</x-app-layout>