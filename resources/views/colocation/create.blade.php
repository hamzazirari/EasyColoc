<x-app-layout>
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Créer une nouvelle colocation</h2>
        
        <form action="{{ route('colocation.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">Nom de la colocation</label>
                <input type="text" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ex: Les Amis de Safi" required>
            </div>
            
            <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">
                Créer et devenir Owner
            </button>
        </form>
    </div>
</x-app-layout>