<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'colocation_id',
        'user_id',
        'title',
        'amount',
        'date',
        'category',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relation avec Colocation
    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    // Relation avec User (le payeur)
    public function paidBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payments()
{
    return $this->hasMany(Payment::class);
}
}