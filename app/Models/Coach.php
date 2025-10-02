<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coach extends Model
{
    protected $fillable = [
        'coach_id', 'name', 'phone', 'email', 'specialization', 'join_date', 'status', 'salary', 'commission_rate'
    ];

    protected $casts = [
        'join_date' => 'date',
        'salary' => 'decimal:2',
        'commission_rate' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($coach) {
            if (!$coach->coach_id) {
                $coach->coach_id = 'TRN' . str_pad(Coach::max('id') + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}


