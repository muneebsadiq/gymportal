<?php

namespace App\Http\Controllers;

use App\Models\MemberMembershipPlan;
use App\Models\MembershipPlan;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberMembershipPlanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'membership_plan_id' => 'required|exists:membership_plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,expired,cancelled',
        ]);

        MemberMembershipPlan::create($validated);

        return redirect()->route('members.show', $validated['member_id'])
            ->with('success', 'Membership plan assigned successfully!');
    }

    public function edit(MemberMembershipPlan $memberMembershipPlan)
    {
        $memberMembershipPlan->load(['membershipPlan', 'member']);
        return view('member_membership_plans.edit', compact('memberMembershipPlan'));
    }

    public function update(Request $request, MemberMembershipPlan $memberMembershipPlan)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,expired,cancelled',
        ]);

        $memberMembershipPlan->update($validated);

        return redirect()->route('members.show', $memberMembershipPlan->member_id)
            ->with('success', 'Membership assignment updated successfully!');
    }

    public function destroy(MemberMembershipPlan $memberMembershipPlan)
    {
        $memberId = $memberMembershipPlan->member_id;
        $memberMembershipPlan->delete();
        return redirect()->route('members.show', $memberId)
            ->with('success', 'Membership plan assignment removed!');
    }
}
