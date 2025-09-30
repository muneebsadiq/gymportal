<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coach extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'specialization', 'join_date', 'status'
    ];

    protected $casts = [
        'join_date' => 'date',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}


