<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Soldes de la colocation</h1>

        {{-- Total et part individuelle --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white p-6 rounded-xl shadow text-center">
                <p class="text-gray-500 text-sm mb-2">Total des dépenses</p>
                <p class="text-3xl font-bold text-indigo-600">{{ number_format($totalExpenses, 2) }} DH</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow text-center">
                <p class="text-gray-500 text-sm mb-2">Part individuelle</p>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($individualShare, 2) }} DH</p>
            </div>
        </div>

        {{-- Soldes de chaque membre --}}
        <div class="bg-white rounded-xl shadow mb-6">
            <h2 class="text-lg font-bold text-gray-800 p-6 border-b">Soldes individuels</h2>
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-gray-400 uppercase border-b">
                        <th class="px-6 py-4">Membre</th>
                        <th class="px-6 py-4">A payé</th>
                        <th class="px-6 py-4">Sa part</th>
                        <th class="px-6 py-4">Solde</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($balances as $balance)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $balance['member']->name }}</td>
                            <td class="px-6 py-4">{{ number_format($balance['paid'], 2) }} DH</td>
                            <td class="px-6 py-4">{{ number_format($balance['share'], 2) }} DH</td>
                            <td class="px-6 py-4 font-bold">
                                @if($balance['balance'] > 0)
                                    <span class="text-green-600">+{{ number_format($balance['balance'], 2) }} DH</span>
                                @elseif($balance['balance'] < 0)
                                    <span class="text-red-600">{{ number_format($balance['balance'], 2) }} DH</span>
                                @else
                                    <span class="text-gray-500">0.00 DH</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Qui doit à qui --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Qui doit à qui ?</h2>
            @php
                $debtors = array_filter($balances, fn($b) => $b['balance'] < 0);
                $creditors = array_filter($balances, fn($b) => $b['balance'] > 0);
            @endphp

            @forelse($debtors as $debtor)
                @foreach($creditors as $creditor)
                    <div class="flex items-center justify-between py-3 border-b">
                        <p class="text-gray-800">
                            <span class="font-bold text-red-600">{{ $debtor['member']->name }}</span>
                            doit
                            <span class="font-bold text-green-600">{{ number_format(abs($debtor['balance']), 2) }} DH</span>
                            à
                            <span class="font-bold text-indigo-600">{{ $creditor['member']->name }}</span>
                        </p>

                        @if(auth()->user()->id === $debtor['member']->id)
                            <form action="{{ route('payments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="expense_id" value="{{ $debtor['expense_id'] ?? 0 }}">
                                <input type="hidden" name="amount" value="{{ abs($debtor['balance']) }}">
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                                    Marquer payé ✅
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            @empty
                <p class="text-gray-400 text-center py-4">Aucun remboursement en attente ! 🎉</p>
            @endforelse
        </div>

        {{-- Bouton retour --}}
        <div class="mt-6">
            <a href="{{ route('expenses.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">
                Retour aux dépenses
            </a>
        </div>

    </div>
</x-app-layout>