<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index(Request $request)
    {
        $query = Coach::with(['expenses' => function($q) {
            $q->where('expense_type', 'Coach Salary')
              ->whereYear('expense_date', now()->year)
              ->whereMonth('expense_date', now()->month);
        }]);
        
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('phone', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('email', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('coach_id', 'LIKE', '%'.$request->search.'%');
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $coaches = $query->latest()->paginate(20);
        return view('coaches.index', compact('coaches'));
    }

    public function create()
    {
        return view('coaches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|unique:coaches,email',
            'specialization' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'salary' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        Coach::create($validated);
        return redirect()->route('coaches.index')->with('success', 'Coach created successfully!');
    }

    public function show(Coach $coach)
    {
        $coach->load(['members.memberMembershipPlans.membershipPlan', 'members.payments', 'commissions', 'expenses' => function($q) {
            $q->where('expense_type', 'Coach Salary')->latest('expense_date');
        }]);
        
        // Check if salary already paid for current month
        $currentMonth = now()->format('Y-m');
        $salaryPaidThisMonth = $coach->expenses()
            ->where('expense_type', 'Coach Salary')
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->exists();
        
        // Calculate total monthly fees of all assigned members (only from PAID fees)
        $totalMemberFees = 0;
        $memberFeesBreakdown = [];
        
        foreach ($coach->members as $member) {
            // Get active membership plan
            $activePlan = $member->memberMembershipPlans()
                ->with('membershipPlan')
                ->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();
            
            if ($activePlan && $activePlan->membershipPlan) {
                // Only count if member has paid fees for current month
                $hasPaidThisMonth = $member->payments()
                    ->whereIn('status', ['paid', 'partial'])
                    ->whereYear('payment_date', now()->year)
                    ->whereMonth('payment_date', now()->month)
                    ->exists();
                
                if ($hasPaidThisMonth) {
                    $fee = $activePlan->membershipPlan->fee;
                    $totalMemberFees += $fee;
                    $memberFeesBreakdown[] = [
                        'member' => $member,
                        'plan' => $activePlan->membershipPlan,
                        'fee' => $fee,
                        'paid' => true
                    ];
                } else {
                    // Include in breakdown but mark as unpaid
                    $memberFeesBreakdown[] = [
                        'member' => $member,
                        'plan' => $activePlan->membershipPlan,
                        'fee' => $activePlan->membershipPlan->fee,
                        'paid' => false
                    ];
                }
            }
        }
        
        // Calculate commission based on total PAID member fees
        $calculatedCommission = 0;
        if ($coach->commission_rate && $totalMemberFees > 0) {
            $calculatedCommission = ($totalMemberFees * $coach->commission_rate) / 100;
        }
        
        // Keep track of old commission system for reference
        $totalCommissions = $coach->commissions()->sum('amount');
        $unpaidCommissions = $coach->commissions()->where('status', 'unpaid')->sum('amount');
        $totalSalaryPaid = $coach->expenses()->where('expense_type', 'Coach Salary')->sum('amount');
        
        return view('coaches.show', compact(
            'coach', 
            'totalCommissions', 
            'unpaidCommissions', 
            'totalSalaryPaid',
            'totalMemberFees',
            'calculatedCommission',
            'memberFeesBreakdown',
            'salaryPaidThisMonth'
        ));
    }

    public function edit(Coach $coach)
    {
        return view('coaches.edit', compact('coach'));
    }

    public function update(Request $request, Coach $coach)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|unique:coaches,email,'.$coach->id,
            'specialization' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'salary' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $coach->update($validated);
        return redirect()->route('coaches.index')->with('success', 'Coach updated successfully!');
    }

    public function destroy(Coach $coach)
    {
        $coach->delete();
        return redirect()->route('coaches.index')->with('success', 'Coach deleted successfully!');
    }
}


