<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    // Afficher toutes les dépenses
    public function index(): View
    {
        $user = auth()->user();
        $colocation = $user->colocations()
                           ->wherePivot('left_at', null)
                           ->where('status', 'active')
                           ->first();

        if (!$colocation) {
            return redirect()->route('colocation.index');
        }

        $expenses = $colocation->expenses()->with('paidBy')->latest()->get();

        return view('expenses.index', compact('colocation', 'expenses'));
    }


    // Afficher le formulaire de creation
    public function create(): View
    {
        $user = auth()->user();
        $colocation = $user->colocations()
                           ->wherePivot('left_at', null)
                           ->where('status', 'active')
                           ->first();

        return view('expenses.create', compact('colocation'));
    }


    public function store(Request $request): RedirectResponse
{
   
    $request->validate([
        'title'    => ['required', 'string', 'max:255'],
        'amount'   => ['required', 'numeric', 'min:0'],
        'date'     => ['required', 'date'],
        'category' => ['required', 'in:loyer,courses,electricite,internet,autre'],
    ]);

    $user = auth()->user();

    
    $colocation = $user->colocations()
                       ->wherePivot('left_at', null)
                       ->where('status', 'active')
                       ->first();

    
    Expense::create([
        'colocation_id' => $colocation->id,
        'user_id'       => $user->id,
        'title'         => $request->title,
        'amount'        => $request->amount,
        'date'          => $request->date,
        'category'      => $request->category,
    ]);

 
    return redirect()->route('expenses.index')->with('status', 'expense-created');
}


// Afficher le formulaire de modification
public function edit(Expense $expense): View
{
    return view('expenses.edit', compact('expense'));
}



// Sauvegard  modif
public function update(Request $request, Expense $expense): RedirectResponse
{
   
    $request->validate([
        'title'    => ['required', 'string', 'max:255'],
        'amount'   => ['required', 'numeric', 'min:0'],
        'date'     => ['required', 'date'],
        'category' => ['required', 'in:loyer,courses,electricite,internet,autre'],
    ]);

    
    $expense->update([
        'title'    => $request->title,
        'amount'   => $request->amount,
        'date'     => $request->date,
        'category' => $request->category,
    ]);

    return redirect()->route('expenses.index')->with('status', 'expense-updated');
}

// Supprimer une depense
public function destroy(Expense $expense): RedirectResponse
{
    $expense->payments()->delete();

    $expense->delete();

    return redirect()->route('expenses.index')->with('status', 'expense-deleted');
}

/////////////////////////////////////////////////////////////////////////////////////////
                   // logic

public function balances(): View
{
    $user = auth()->user();

    $colocation = $user->colocations()
                       ->wherePivot('left_at', null)
                       ->where('status', 'active')
                       ->first();

    $members = $colocation->members;
    $expenses = $colocation->expenses;
    $totalExpenses = $expenses->sum('amount');
    $numberOfMembers = $members->count();
    $individualShare = $numberOfMembers > 0 ? $totalExpenses / $numberOfMembers : 0;

    $balances = [];
   foreach ($members as $member) {

$paid = $expenses->where('user_id', $member->id)->sum('amount');

$alreadyPaid = $member->payments()
                      ->whereIn('expense_id', $expenses->pluck('id'))
                      ->sum('amount');

$paid = $paid + $alreadyPaid;    $balance = $paid - $individualShare;
    
    $creditorExpense = $expenses->where('user_id', '!=', $member->id)->first();
    
    $balances[] = [
        'member' => $member,
        'paid' => $paid,
        'share' => $individualShare,
        'balance' => $balance,
        'expense_id' => $creditorExpense ? $creditorExpense->id : null,
    ];
}

    return view('expenses.balances', compact('colocation', 'balances', 'totalExpenses', 'individualShare'));
}

}