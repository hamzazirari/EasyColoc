<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier une dépense</h1>

        <div class="bg-white p-6 rounded-xl shadow">
            <form action="{{ route('expenses.update', $expense) }}" method="POST">
                @csrf
                @method('PATCH')

                {{-- Titre --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                    <input type="text" name="title" value="{{ old('title', $expense->title) }}"
                        class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Montant --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Montant (DH)</label>
                    <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" step="0.01"
                        class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                    @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}"
                        class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catégorie --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select name="category" class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                        <option value="loyer" {{ old('category', $expense->category) == 'loyer' ? 'selected' : '' }}>Loyer</option>
                        <option value="courses" {{ old('category', $expense->category) == 'courses' ? 'selected' : '' }}>Courses</option>
                        <option value="electricite" {{ old('category', $expense->category) == 'electricite' ? 'selected' : '' }}>Electricité</option>
                        <option value="internet" {{ old('category', $expense->category) == 'internet' ? 'selected' : '' }}>Internet</option>
                        <option value="autre" {{ old('category', $expense->category) == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Boutons --}}
                <div class="flex space-x-4">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg">
                        Sauvegarder
                    </button>
                    <a href="{{ route('expenses.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg">
                        Annuler
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>