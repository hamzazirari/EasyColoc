<x-app-layout>
    <div class="p-6">
        @if($colocation)
            @if($colocation->pivot->role === 'owner')
                <div class="mb-6 flex justify-end">
                    <a href="{{ route('expenses.create') }}" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5">
                        + Ajouter une dépense
                    </a>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
                    <h5 class="mb-2 text-sm font-bold tracking-tight text-gray-500 uppercase">Mon Solde actuel</h5>
                    <p class="text-2xl font-extrabold text-green-600">+150.00 DH</p>
                </div>

                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
                    <h5 class="mb-2 text-sm font-bold tracking-tight text-gray-500 uppercase">Réputation</h5>
                    <p class="text-2xl font-extrabold 
                        {{ auth()->user()->reputation > 0 ? 'text-green-600' : (auth()->user()->reputation < 0 ? 'text-red-600' : 'text-gray-500') }}">
                        {{ auth()->user()->reputation }} pts
                    </p>
                </div>

                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
                    <h5 class="mb-2 text-sm font-bold tracking-tight text-gray-500 uppercase">Ma Coloc</h5>
                    <p class="text-2xl font-extrabold text-gray-900 capitalize">{{ $colocation->name }}</p>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <p class="text-yellow-700">Vous ne faites partie d'aucune colocation. 
                    <a href="{{ route('colocation.create') }}" class="font-bold underline">Créez-en une ici</a>.
                </p>
            </div>
        @endif
    </div>
</x-app-layout>