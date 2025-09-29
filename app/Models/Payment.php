<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'receipt_number', 'member_id', 'membership_plan_id', 'member_membership_plan_id', 'amount',
        'payment_date', 'due_date', 'payment_method', 'payment_type',
        'status', 'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'due_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payment) {
            if (!$payment->receipt_number) {
                $payment->receipt_number = 'RCP' . str_pad(Payment::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function membershipPlan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class);
    }

    public function memberMembershipPlan(): BelongsTo
    {
        return $this->belongsTo(MemberMembershipPlan::class);
    }

    // Helper methods
    public function isOverdue()
    {
        return in_array($this->status, ['pending', 'partial'], true) && $this->due_date && $this->due_date->isPast();
    }

    public function remainingDue(float $planFee): float
    {
        if ($this->status !== 'partial') return 0.0;
        $remaining = max(0, $planFee - (float) $this->amount);
        // normalize to 2 decimals
        return round($remaining, 2);
    }
}
