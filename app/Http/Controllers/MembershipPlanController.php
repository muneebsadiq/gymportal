<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::all();
        return view('membership_plans.index', compact('plans'));
    }

    public function create()
    {
        return view('membership_plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fee' => 'required|numeric|min:0',
            'duration_type' => 'required|in:monthly,yearly',
            'duration_value' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);
        MembershipPlan::create($validated);
        return redirect()->route('membership_plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(MembershipPlan $membershipPlan)
    {
        return view('membership_plans.edit', compact('membershipPlan'));
    }

    public function update(Request $request, MembershipPlan $membershipPlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fee' => 'required|numeric|min:0',
            'duration_type' => 'required|in:monthly,yearly',
            'duration_value' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);
        $membershipPlan->update($validated);
        return redirect()->route('membership_plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(MembershipPlan $membershipPlan)
    {
        $membershipPlan->delete();
        return redirect()->route('membership_plans.index')->with('success', 'Plan deleted successfully.');
    }
}
