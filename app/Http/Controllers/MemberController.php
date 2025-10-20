<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\MemberMembershipPlan;
use App\Models\Coach;
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
        $query = Member::with('memberships', 'memberMembershipPlans.membershipPlan', 'payments');

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

        // Filter for members with payment due
        if ($request->has('payment_due') && $request->payment_due == '1') {
            $query->whereHas('payments', function ($q) {
                $q->whereIn('status', ['pending', 'partial'])
                  ->where(function ($subQ) {
                      $subQ->where('due_date', '<', Carbon::now())
                           ->orWhereNull('due_date');
                  });
            });
        }

        // Filter for members with due fees
        if ($request->has('due_fees') && $request->due_fees == '1') {
            $query->withDueFees();
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
            'coach_id' => 'nullable|exists:coaches,id',
            
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
        $coaches = Coach::orderBy('name')->get();
        // Determine currently assigned membership plan (active window includes today, fallback to most recent)
        $currentAssignment = $member->memberMembershipPlans()
            ->with('membershipPlan')
            ->orderByDesc('end_date')
            ->get()
            ->first(function ($a) {
                $today = \Carbon\Carbon::today();
                return $today->betweenIncluded(\Carbon\Carbon::parse($a->start_date), \Carbon\Carbon::parse($a->end_date));
            }) ?? $member->memberMembershipPlans()->with('membershipPlan')->orderByDesc('end_date')->first();

        return view('members.edit', compact('member','plans','coaches','currentAssignment'));
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
            'coach_id' => 'nullable|exists:coaches,id',
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

    /**
     * Get member data via API
     */
    public function showApi($id)
    {
        $member = Member::where('id', $id)
            ->with([
                'memberMembershipPlans.membershipPlan',
                'payments' => function($query) {
                    $query->latest('payment_date')->take(10);
                },
                'coach'
            ])
            ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'member_id' => $member->member_id,
                'name' => $member->name,
                'email' => $member->email,
                'phone' => $member->phone,
                'address' => $member->address,
                'date_of_birth' => $member->date_of_birth ? $member->date_of_birth->format('M d, Y') : null,
                'age' => $member->age,
                'gender' => $member->gender ? ucfirst($member->gender) : null,
                'emergency_contact' => $member->emergency_contact,
                'emergency_phone' => $member->emergency_phone,
                'medical_conditions' => $member->medical_conditions,
                'profile_photo' => $member->profile_photo ? asset('storage/' . $member->profile_photo) : null,
                'status' => $member->status,
                'joined_date' => $member->joined_date->format('M d, Y'),
                'coach' => $member->coach ? $member->coach->name : null,
                'has_due_fees' => $member->hasDueFees(),
                'next_due_date' => $member->next_due_date ? Carbon::parse($member->next_due_date)->format('M d, Y') : null,
                'active_plan' => $this->getActivePlanData($member),
                'payments' => $member->payments->map(function($payment) {
                    return [
                        'id' => $payment->id,
                        'receipt_number' => $payment->receipt_number,
                        'amount' => number_format($payment->amount, 2),
                        'payment_date' => $payment->payment_date->format('M d, Y'),
                        'payment_type' => ucfirst(str_replace('_', ' ', $payment->payment_type)),
                        'payment_method' => ucfirst($payment->payment_method),
                        'status' => $payment->status,
                    ];
                }),
                'view_url' => route('members.show', $member->id),
                'edit_url' => route('members.edit', $member->id),
                'payment_url' => route('payments.create', ['member_id' => $member->id]),
            ]
        ]);
    }

    /**
     * Display member data from API in UI format
     */
    public function showApiView($id)
    {
        $member = Member::where('id', $id)
            ->with([
                'memberMembershipPlans.membershipPlan',
                'payments' => function($query) {
                    $query->latest('payment_date')->take(10);
                },
                'coach'
            ])
            ->first();

        if (!$member) {
            abort(404, 'Member not found');
        }

        // Format member data similar to API response
        $memberData = [
            'id' => $member->id,
            'member_id' => $member->member_id,
            'name' => $member->name,
            'email' => $member->email,
            'phone' => $member->phone,
            'address' => $member->address,
            'date_of_birth' => $member->date_of_birth ? $member->date_of_birth->format('M d, Y') : null,
            'age' => $member->age,
            'gender' => $member->gender ? ucfirst($member->gender) : null,
            'emergency_contact' => $member->emergency_contact,
            'emergency_phone' => $member->emergency_phone,
            'medical_conditions' => $member->medical_conditions,
            'profile_photo' => $member->profile_photo ? asset('storage/' . $member->profile_photo) : null,
            'status' => $member->status,
            'joined_date' => $member->joined_date->format('M d, Y'),
            'coach' => $member->coach ? $member->coach->name : null,
            'has_due_fees' => $member->hasDueFees(),
            'next_due_date' => $member->next_due_date ? Carbon::parse($member->next_due_date)->format('M d, Y') : null,
            'active_plan' => $this->getActivePlanData($member),
            'payments' => $member->payments->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'receipt_number' => $payment->receipt_number,
                    'amount' => number_format($payment->amount, 2),
                    'payment_date' => $payment->payment_date->format('M d, Y'),
                    'payment_type' => ucfirst(str_replace('_', ' ', $payment->payment_type)),
                    'payment_method' => ucfirst($payment->payment_method),
                    'status' => $payment->status,
                ];
            })->toArray(),
            'view_url' => route('members.show', $member->id),
            'edit_url' => route('members.edit', $member->id),
            'payment_url' => route('payments.create', ['member_id' => $member->id]),
        ];

        return view('members.api-show', compact('memberData'));
    }

    private function getActivePlanData($member)
    {
        $activeAssignment = $member->memberMembershipPlans
            ->where('status', 'active')
            ->sortByDesc('start_date')
            ->first();

        if (!$activeAssignment || !$activeAssignment->membershipPlan) {
            return null;
        }

        $plan = $activeAssignment->membershipPlan;
        $windowEnd = Carbon::parse($activeAssignment->end_date);
        $dtype = strtolower($plan->duration_type ?? 'month');
        $dval = (int) ($plan->duration_value ?? 1);
        
        $windowStart = match ($dtype) {
            'day','days' => $windowEnd->copy()->subDays($dval),
            'week','weeks' => $windowEnd->copy()->subWeeks($dval),
            'month','months' => $windowEnd->copy()->subMonths($dval),
            'year','years' => $windowEnd->copy()->subYears($dval),
            default => $windowEnd->copy()->subMonths($dval),
        };

        $planFee = (float) ($plan->fee ?? 0);
        $paidInWindow = \App\Models\Payment::where('member_membership_plan_id', $activeAssignment->id)
            ->where('payment_type', 'membership_fee')
            ->whereBetween('payment_date', [$windowStart->toDateString(), $windowEnd->toDateString()])
            ->sum('amount');
        
        $diff = $paidInWindow - $planFee;
        $isOverdue = ($diff < 0) && $windowEnd->isPast();
        $daysRemaining = Carbon::now()->diffInDays($windowEnd, false);

        return [
            'name' => $plan->name,
            'description' => $plan->description,
            'fee' => number_format($planFee, 2),
            'duration' => $plan->duration_value . ' ' . ucfirst($plan->duration_type),
            'start_date' => Carbon::parse($activeAssignment->start_date)->format('M d, Y'),
            'end_date' => $windowEnd->format('M d, Y'),
            'period_start' => $windowStart->format('M d, Y'),
            'period_end' => $windowEnd->format('M d, Y'),
            'days_remaining' => max($daysRemaining, 0),
            'fee_status' => $diff === 0 ? 'paid' : ($diff < 0 ? ($isOverdue ? 'overdue' : 'partial') : 'excess'),
            'fee_amount_due' => $diff < 0 ? number_format(abs($diff), 2) : null,
            'fee_amount_excess' => $diff > 0 ? number_format($diff, 2) : null,
        ];
    }
}
