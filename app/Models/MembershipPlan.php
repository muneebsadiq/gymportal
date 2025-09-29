<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'fee',
        'duration_type',
        'duration_value',
        'status',
    ];

    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_membership_plan')
            ->withPivot(['start_date', 'end_date', 'status'])
            ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
