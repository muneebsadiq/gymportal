<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Expense;
use App\Models\Commission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CoachSalaryController extends Controller
{
    public function paySalary(Request $request, Coach $coach)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,cheque,other',
            'description' => 'nullable|string|max:500',
            'include_commissions' => 'nullable|boolean',
            'commission_ids' => 'nullable|array',
            'commission_ids.*' => 'exists:commissions,id',
        ]);

        DB::beginTransaction();
        try {
            // Create expense record for coach salary
            $expense = Expense::create([
                'title' => 'Coach Salary - ' . $coach->name,
                'description' => $validated['description'] ?? 'Salary payment for ' . $coach->name,
                'amount' => $validated['amount'],
                'expense_date' => $validated['payment_date'],
                'category' => 'salaries',
                'expense_type' => 'Coach Salary',
                'payment_method' => $validated['payment_method'],
                'vendor_name' => $coach->name,
                'coach_id' => $coach->id,
            ]);

            // Mark commissions as paid if included
            if ($request->has('include_commissions') && $request->include_commissions) {
                if (!empty($validated['commission_ids'])) {
                    Commission::whereIn('id', $validated['commission_ids'])
                        ->where('coach_id', $coach->id)
                        ->update([
                            'status' => 'paid',
                            'updated_at' => now(),
                        ]);
                }
            }

            DB::commit();

            return redirect()->route('coaches.show', $coach)
                ->with('success', 'Salary payment of ' . number_format($validated['amount'], 2) . ' recorded successfully as expense!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to record salary payment: ' . $e->getMessage());
        }
    }

    public function salaryHistory(Coach $coach)
    {
        $salaryPayments = Expense::where('coach_id', $coach->id)
            ->where('expense_type', 'Coach Salary')
            ->latest('expense_date')
            ->paginate(20);

        return view('coaches.salary-history', compact('coach', 'salaryPayments'));
    }
}
