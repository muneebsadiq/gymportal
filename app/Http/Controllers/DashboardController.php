<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Setting;
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
}
