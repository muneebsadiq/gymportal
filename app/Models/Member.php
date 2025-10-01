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
        'medical_conditions', 'profile_photo', 'status', 'joined_date', 'coach_id'
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
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
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

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
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
        // Due if any non-cancelled assignment period has ended (next cycle payment needed)
        $assignments = $this->memberMembershipPlans()
            ->with('membershipPlan')
            ->where('status', '!=', 'cancelled')
            ->get();
        foreach ($assignments as $a) {
            if (Carbon::parse($a->end_date)->isPast()) {
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

    public function scopeWithDueFees($query)
    {
        return $query->whereHas('memberMembershipPlans', function ($q) {
            $q->where('status', '!=', 'cancelled')
              ->where('end_date', '<', Carbon::now());
        });
    }

    public function getActivePlanAttribute()
    {
        $assignment = $this->memberMembershipPlans()
            ->with('membershipPlan')
            ->where('status', 'active')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->orderByDesc('start_date')
            ->first();
        
        return $assignment ? $assignment->membershipPlan : null;
    }

    public function hasPaymentDue()
    {
        // Check if member has any pending or partial payments that are overdue
        return $this->payments()
            ->whereIn('status', ['pending', 'partial'])
            ->where(function ($q) {
                $q->where('due_date', '<', Carbon::now())
                  ->orWhereNull('due_date');
            })
            ->exists();
    }
}
