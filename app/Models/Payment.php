<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'expense_id',
        'payer_user_id',
        'receiver_user_id',
        'amount',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Relation avec Expense
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    // Celui qui rembourse
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_user_id');
    }

    // Celui qui reçoit
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }
}