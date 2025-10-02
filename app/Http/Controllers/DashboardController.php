<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Setting;
use App\Models\Coach;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Key metrics
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $monthlyRevenue = Payment::whereMonth('payment_date', Carbon::now()->month)
            ->whereYear('payment_date', Carbon::now()->year)
            ->whereIn('status', ['paid', 'partial'])
            ->sum('amount');
        $totalExpenses = Expense::whereMonth('expense_date', Carbon::now()->month)
            ->whereYear('expense_date', Carbon::now()->year)
            ->sum('amount');
        $netProfit = $monthlyRevenue - $totalExpenses;

        // Members with due fees
        $membersWithDueFees = Member::where('status', 'active')
            ->with('memberMembershipPlans.membershipPlan')
            ->get()
            ->filter(function ($member) {
                return $member->hasDueFees();
            });
        $dueFeesCount = $membersWithDueFees->count();

        // Recent payments
        $recentPayments = Payment::with('member')
            ->whereIn('status', ['paid', 'partial'])
            ->latest('payment_date')
            ->take(5)
            ->get();

        // Monthly revenue chart data (last 6 months)
        $revenueChartData = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Payment::query()
                ->whereYear('payment_date', $date->year)
                ->whereMonth('payment_date', $date->month)
                ->whereIn('status', ['paid', 'partial'])
                ->sum('amount');
            $revenueChartData['labels'][] = $date->format('M Y');
            $revenueChartData['data'][] = floatval($revenue);
        }

        // Member growth chart data (last 6 months)
        $memberChartData = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $newMembers = Member::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $memberChartData['labels'][] = $date->format('M Y');
            $memberChartData['data'][] = $newMembers;
        }

        // Expense categories for current month
        $expensesByCategory = Expense::whereMonth('expense_date', Carbon::now()->month)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        // Get settings
        $settings = Setting::get();

        return view('dashboard', compact(
            'totalMembers', 'activeMembers', 'monthlyRevenue', 'totalExpenses', 'netProfit',
            'membersWithDueFees', 'dueFeesCount', 'recentPayments', 
            'revenueChartData', 'memberChartData', 'expensesByCategory', 'settings'
        ));
    }

    public function getDueFeesModal()
    {
        $membersWithDueFees = Member::where('status', 'active')
            ->with('memberMembershipPlans.membershipPlan')
            ->get()
            ->filter(function ($member) {
                return $member->hasDueFees();
            });

        return response()->json([
            'html' => view('partials.due-fees-modal', compact('membersWithDueFees'))->render()
        ]);
    }

    public function searchMember(Request $request)
    {
        $memberId = $request->input('member_id');
        
        if (!$memberId) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a member ID'
            ], 400);
        }

        $member = Member::where('member_id', $memberId)
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
        $paidInWindow = Payment::where('member_membership_plan_id', $activeAssignment->id)
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

    public function searchCoach(Request $request)
    {
        $coachId = $request->input('coach_id');
        
        if (!$coachId) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a coach ID'
            ], 400);
        }

        $coach = Coach::where('coach_id', $coachId)
            ->with([
                'members' => function($query) {
                    $query->where('status', 'active')->take(10);
                },
                'commissions' => function($query) {
                    $query->latest()->take(5);
                }
            ])
            ->first();

        if (!$coach) {
            return response()->json([
                'success' => false,
                'message' => 'Coach not found'
            ], 404);
        }

        // Calculate total commission
        $totalCommission = $coach->commissions()->sum('amount');
        
        // Get this month's commission
        $monthlyCommission = $coach->commissions()
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        return response()->json([
            'success' => true,
            'coach' => [
                'id' => $coach->id,
                'coach_id' => $coach->coach_id,
                'name' => $coach->name,
                'email' => $coach->email,
                'phone' => $coach->phone,
                'specialization' => $coach->specialization,
                'join_date' => $coach->join_date->format('M d, Y'),
                'status' => $coach->status,
                'salary' => number_format($coach->salary, 2),
                'commission_rate' => $coach->commission_rate,
                'total_members' => $coach->members()->count(),
                'active_members' => $coach->members()->where('status', 'active')->count(),
                'total_commission' => number_format($totalCommission, 2),
                'monthly_commission' => number_format($monthlyCommission, 2),
                'members' => $coach->members->map(function($member) {
                    return [
                        'id' => $member->id,
                        'member_id' => $member->member_id,
                        'name' => $member->name,
                        'status' => $member->status,
                    ];
                }),
                'view_url' => route('coaches.show', $coach->id),
                'edit_url' => route('coaches.edit', $coach->id),
                'salary_url' => route('coaches.salary-history', $coach->id),
            ]
        ]);
    }
}
