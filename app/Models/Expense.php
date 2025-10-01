<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_number', 'title', 'description', 'amount',
        'expense_date', 'category', 'expense_type', 'payment_method', 
        'vendor_name', 'receipt_file', 'coach_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($expense) {
            if (!$expense->expense_number) {
                $expense->expense_number = 'EXP' . str_pad(Expense::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    // Helper methods
    public function getCategoryDisplayAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->category));
    }

    public function getPaymentMethodDisplayAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->payment_method));
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
}
