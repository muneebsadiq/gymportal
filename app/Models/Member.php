<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Member extends Model
{
    protected $fillable = [
        'member_id', 'name', 'email', 'phone', 'address', 
        'date_of_birth', 'gender', 'emergency_contact', 'emergency_phone',
        'medical_conditions', 'profile_photo', 'status', 'joined_date'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joined_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($member) {
            if (!$member->member_id) {
                $member->member_id = 'GYM' . str_pad(Member::max('id') + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function membershipPlans()
    {
        return $this->belongsToMany(MembershipPlan::class, 'member_membership_plan')
            ->withPivot(['start_date', 'end_date', 'status'])
            ->withTimestamps();
    }

    public function memberMembershipPlans()
    {
        return $this->hasMany(MemberMembershipPlan::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function activeMembershipPlans()
    {
        return $this->membershipPlans()->wherePivot('status', 'active');
    }

    public function activeMembership()
    {
        return $this->memberships()->where('status', 'active')->latest()->first();
    }

    // Helper methods
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    public function hasDueFees()
    {
        // Any non-cancelled assignment where sum(payments within window) < fee and end_date is past
        $assignments = $this->memberMembershipPlans()
            ->with('membershipPlan')
            ->where('status', '!=', 'cancelled')
            ->get();
        foreach ($assignments as $a) {
            $planFee = (float) ($a->membershipPlan->fee ?? 0);
            if ($planFee <= 0) continue;
            $end = Carbon::parse($a->end_date)->endOfDay();
            $dtype = strtolower($a->membershipPlan?->duration_type ?? 'month');
            $dval = (int) ($a->membershipPlan?->duration_value ?? 1);
            $start = match ($dtype) {
                'day','days' => $end->copy()->subDays($dval)->startOfDay(),
                'week','weeks' => $end->copy()->subWeeks($dval)->startOfDay(),
                'month','months' => $end->copy()->subMonths($dval)->startOfDay(),
                'year','years' => $end->copy()->subYears($dval)->startOfDay(),
                default => $end->copy()->subMonths($dval)->startOfDay(),
            };
            $sum = Payment::where('member_membership_plan_id', $a->id)
                ->where('payment_type', 'membership_fee')
                ->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])
                ->sum('amount');
            if ($sum < $planFee && Carbon::parse($a->end_date)->isPast()) {
                return true;
            }
        }
        return false;
    }

    public function getNextDueDateAttribute()
    {
        // Return the soonest end_date among active assignments (acts as next due date)
        $assignments = $this->memberMembershipPlans()->where('status', 'active')->get();
        if ($assignments->isEmpty()) return null;
        $min = $assignments->min('end_date');
        return $min ? Carbon::parse($min)->format('Y-m-d') : null;
    }
}
