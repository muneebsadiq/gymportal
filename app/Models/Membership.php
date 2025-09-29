<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

// LEGACY: This model is retained for backward compatibility. Use MembershipPlan and MemberMembershipPlan for new features.
class Membership extends Model
{
    protected $fillable = [
        'member_id', 'plan_name', 'plan_description', 'monthly_fee',
        'start_date', 'end_date', 'duration_months', 'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_fee' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($membership) {
            if ($membership->start_date && $membership->duration_months) {
                $membership->end_date = Carbon::parse($membership->start_date)
                    ->addMonths($membership->duration_months)
                    ->format('Y-m-d');
            }
        });

        static::updating(function ($membership) {
            if ($membership->isDirty(['start_date', 'duration_months'])) {
                $membership->end_date = Carbon::parse($membership->start_date)
                    ->addMonths($membership->duration_months)
                    ->format('Y-m-d');
            }
        });
    }

    // Relationships
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Helper methods
    public function isExpired()
    {
        return Carbon::parse($this->end_date)->isPast();
    }

    public function daysRemaining()
    {
        return Carbon::now()->diffInDays(Carbon::parse($this->end_date), false);
    }
}
