<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $date = Carbon::parse($month . '-01');
        
        // Revenue data (sum all payments recorded this month)
        $revenue = Payment::whereYear('payment_date', $date->year)
            ->whereMonth('payment_date', $date->month)
            ->sum('amount');
            
        $revenueByType = Payment::whereYear('payment_date', $date->year)
            ->whereMonth('payment_date', $date->month)
            ->select('payment_type', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_type')
            ->get();

        // Expense data
        $expenses = Expense::whereYear('expense_date', $date->year)
            ->whereMonth('expense_date', $date->month)
            ->sum('amount');
            
        $expensesByCategory = Expense::whereYear('expense_date', $date->year)
            ->whereMonth('expense_date', $date->month)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        // Member statistics
        $newMembers = Member::whereYear('joined_date', $date->year)
            ->whereMonth('joined_date', $date->month)
            ->count();
            
        $totalActiveMembers = Member::where('status', 'active')->count();
        
        // Payment method breakdown
        $paymentMethods = Payment::whereYear('payment_date', $date->year)
            ->whereMonth('payment_date', $date->month)
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Net profit
        $netProfit = $revenue - $expenses;
        
        // Daily revenue for the month
        $dailyRevenue = Payment::whereYear('payment_date', $date->year)
            ->whereMonth('payment_date', $date->month)
            ->select(DB::raw('DAY(payment_date) as day'), DB::raw('SUM(amount) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('reports.index', compact(
            'month', 'date', 'revenue', 'expenses', 'netProfit', 'newMembers',
            'totalActiveMembers', 'revenueByType', 'expensesByCategory', 
            'paymentMethods', 'dailyRevenue'
        ));
    }

    public function memberReport(Request $request)
    {
        $query = Member::with(['memberships', 'payments']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->joined_from) {
            $query->where('joined_date', '>=', $request->joined_from);
        }
        
        if ($request->joined_to) {
            $query->where('joined_date', '<=', $request->joined_to);
        }
        
        $members = $query->paginate(50);
        
        return view('reports.members', compact('members'));
    }

    public function paymentReport(Request $request)
    {
        $query = Payment::with('member');
        
        if ($request->date_from) {
            $query->where('payment_date', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->where('payment_date', '<=', $request->date_to);
        }
        
        if ($request->payment_type) {
            $query->where('payment_type', $request->payment_type);
        }
        
        $payments = $query->latest('payment_date')->paginate(50);
        $totalAmount = $query->sum('amount');
        
        return view('reports.payments', compact('payments', 'totalAmount'));
    }

    public function expenseReport(Request $request)
    {
        $query = Expense::query();
        
        if ($request->date_from) {
            $query->where('expense_date', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->where('expense_date', '<=', $request->date_to);
        }
        
        if ($request->category) {
            $query->where('category', $request->category);
        }
        
        $expenses = $query->latest('expense_date')->paginate(50);
        $totalAmount = $query->sum('amount');
        
        return view('reports.expenses', compact('expenses', 'totalAmount'));
    }
}
