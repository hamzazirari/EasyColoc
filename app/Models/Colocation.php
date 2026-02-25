<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = [
        'name',
        'status',
        'owner_id',
    ];

    // L'owner de la colocation
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Les membres de la colocation
    public function members()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role', 'joined_at', 'left_at');
    }
}