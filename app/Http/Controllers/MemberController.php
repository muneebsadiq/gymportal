<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\MemberMembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Member::with('memberships');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('member_id', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $members = $query->latest()->paginate(20);
        
        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = \App\Models\MembershipPlan::all();
        return view('members.create', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'joined_date' => 'required|date',
            
            // Membership details
            'plan_name' => 'required|string|max:255',
            'plan_description' => 'nullable|string',
            'monthly_fee' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
        ]);

        $memberData = $validated;
        unset($memberData['plan_name'], $memberData['plan_description'], $memberData['monthly_fee'], $memberData['duration_months']);

        if ($request->hasFile('profile_photo')) {
            $memberData['profile_photo'] = $request->file('profile_photo')->store('members', 'public');
        }

        $member = Member::create($memberData);

        // Create membership
        Membership::create([
            'member_id' => $member->id,
            'plan_name' => $validated['plan_name'],
            'plan_description' => $validated['plan_description'],
            'monthly_fee' => $validated['monthly_fee'],
            'start_date' => $validated['joined_date'],
            'duration_months' => $validated['duration_months'],
        ]);

        return redirect()->route('members.index')->with('success', 'Member created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        $member->load(['memberships', 'payments', 'memberMembershipPlans.membershipPlan']);
        $plans = \App\Models\MembershipPlan::all();
        return view('members.show', compact('member', 'plans'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member ,MembershipPlan $plans)
    {
        $plans = \App\Models\MembershipPlan::all();
        return view('members.edit', compact('member','plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive,suspended',
            // Membership plan selection from edit form
            'membership_plan_id' => 'nullable|exists:membership_plans,id',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($member->profile_photo) {
                Storage::disk('public')->delete($member->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('members', 'public');
        }

        $member->update(collect($validated)->except('membership_plan_id')->toArray());

        // Update member's active membership plan if provided and changed
        if ($request->filled('membership_plan_id')) {
            $newPlanId = (int) $request->input('membership_plan_id');

            // Find current active mapping, if any
            $currentPivot = $member->memberMembershipPlans()
                ->where('status', 'active')
                ->latest('start_date')
                ->first();

            $isDifferent = !$currentPivot || (int) $currentPivot->membership_plan_id !== $newPlanId;

            if ($isDifferent) {
                // End current active plan
                if ($currentPivot) {
                    $currentPivot->update([
                        'status' => 'cancelled',
                        'end_date' => Carbon::now()->toDateString(),
                    ]);
                }

                $plan = MembershipPlan::find($newPlanId);
                if ($plan) {
                    $startDate = Carbon::now();
                    // Compute end date based on plan duration
                    $endDate = match (strtolower($plan->duration_type)) {
                        'day', 'days' => (clone $startDate)->copy()->addDays((int) $plan->duration_value),
                        'week', 'weeks' => (clone $startDate)->copy()->addWeeks((int) $plan->duration_value),
                        'month', 'months' => (clone $startDate)->copy()->addMonths((int) $plan->duration_value),
                        'year', 'years' => (clone $startDate)->copy()->addYears((int) $plan->duration_value),
                        default => (clone $startDate)->copy()->addMonths((int) $plan->duration_value), // fallback
                    };

                    MemberMembershipPlan::create([
                        'member_id' => $member->id,
                        'membership_plan_id' => $plan->id,
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $endDate->toDateString(),
                        'status' => 'active',
                    ]);
                }
            }
        }

        return redirect()->route('members.show', $member)->with('success', 'Member updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        if ($member->profile_photo) {
            Storage::disk('public')->delete($member->profile_photo);
        }

        $member->delete();

        return redirect()->route('members.index')->with('success', 'Member deleted successfully!');
    }
}
