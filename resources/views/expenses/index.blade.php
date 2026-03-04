<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dépenses</h1>
            <a href="{{ route('expenses.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                + Ajouter une dépense
            </a>
        </div>

        {{-- Message de succès --}}
        @if(session('status'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-6">
                Dépense ajoutée avec succès !
            </div>
        @endif

        {{-- Liste des dépenses --}}
        <div class="bg-white rounded-xl shadow">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-gray-400 uppercase border-b">
                        <th class="px-6 py-4">Titre</th>
                        <th class="px-6 py-4">Catégorie</th>
                        <th class="px-6 py-4">Payeur</th>
                        <th class="px-6 py-4">Montant</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $expense->title }}</td>
                            <td class="px-6 py-4">{{ ucfirst($expense->category) }}</td>
                            <td class="px-6 py-4">{{ $expense->paidBy->name }}</td>
                            <td class="px-6 py-4 font-bold text-indigo-600">{{ $expense->amount }} DH</td>
                            <td class="px-6 py-4">{{ $expense->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 flex space-x-2">
                                {{-- Modifier --}}
                                <a href="{{ route('expenses.edit', $expense) }}" 
                                   class="bg-yellow-400 text-white px-3 py-1 rounded-lg text-sm">
                                    Modifier
                                </a>
                                {{-- Supprimer --}}
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 text-white px-3 py-1 rounded-lg text-sm"