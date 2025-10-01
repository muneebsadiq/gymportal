<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    protected $fillable = [
        'coach_id', 'member_id', 'member_membership_plan_id', 'payment_id', 'amount', 'commission_date', 'status', 'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_date' => 'date',
    ];

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function memberMembershipPlan(): BelongsTo
    {
        return $this->belongsTo(MemberMembershipPlan::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
