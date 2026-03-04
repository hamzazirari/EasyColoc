<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $payer = auth()->user();

        $expense = Expense::find($request->expense_id);

        $receiver = $expense->paidBy;

        Payment::create([
            'expense_id'      => $expense->id,
            'payer_user_id'   => $payer->id,
            'receiver_user_id'=> $receiver->id,
            'amount'          => $request->amount,
            'paid_at'         => now(),
        ]);

        return redirect()->route('expenses.balances')->with('status', 'payment-done');
    }
}