<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Member;
use App\Models\MemberMembershipPlan;
use App\Models\MembershipPlan;
use App\Models\Commission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['member', 'membershipPlan']);

        if ($request->search) {
            $query->whereHas('member', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('member_id', 'LIKE', '%' . $request->search . '%');
            })->orWhere('receipt_number', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_type) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->date_from) {
            $query->where('payment_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->where('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest('payment_date')->paginate(20);
        $totalAmount = $query->sum('amount');

        return view('payments.index', compact('payments', 'totalAmount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $members = Member::where('status', 'active')->get();
        $selectedMember = null;
        $currentAssignment = null;
        $currentDues = 0;
        $currentFee = 0;
        $totalPayable = 0;
        $partialMessage = null;
        $fullMessage = null;
        $allowPayment = true;

        if ($request->member_id) {
            $selectedMember = Member::findOrFail($request->member_id);
            
            // If member_membership_plan_id is provided, use that specific assignment
            if ($request->member_membership_plan_id) {
                $currentAssignment = MemberMembershipPlan::where('id', $request->member_membership_plan_id)
                    ->where('member_id', $selectedMember->id)
                    ->with('membershipPlan')
                    ->first();
            }
            
            // Otherwise, pick active assignment whose window includes today; fallback to most recent
            if (!$currentAssignment) {
                $currentAssignment = $selectedMember->memberMembershipPlans()
                    ->with('membershipPlan')
                    ->orderByDesc('end_date')
                    ->get()
                    ->first(function ($a) {
                        $today = Carbon::today();
                        return $today->betweenIncluded(Carbon::parse($a->start_date), Carbon::parse($a->end_date));
                    }) ?? $selectedMember->memberMembershipPlans()->with('membershipPlan')->orderByDesc('end_date')->first();
            }

            if ($currentAssignment && $currentAssignment->membershipPlan) {
                $plan = $currentAssignment->membershipPlan;
                $baseFee = (float) $plan->fee;
                $durationType = strtolower($plan->duration_type ?? 'month');
                $durationValue = (int) ($plan->duration_value ?? 1);

                // Calculate the fee per payment period
                if (($durationType === 'year' || $durationType === 'years') && $durationValue === 1) {
                    // For yearly plans, assume fee is yearly total, so monthly fee = total / 12
                    $currentFee = $baseFee / 12;
                } else {
                    // For monthly plans or other durations, fee is as stored
                    $currentFee = $baseFee;
                }

                // Calculate current dues from previous months
                $currentDues = Payment::where('member_membership_plan_id', $currentAssignment->id)
                    ->where('payment_type', 'membership_fee')
                    ->where('due_amount', '>', 0)
                    ->sum('due_amount');

                // Calculate if there are partial payments for the current month for this assignment
                $currentMonth = Carbon::now()->format('Y-m');
                $totalPaidThisMonth = Payment::where('member_membership_plan_id', $currentAssignment->id)
                    ->where('payment_type', 'membership_fee')
                    ->whereRaw("DATE_FORMAT(payment_date, '%Y-%m') = ?", [$currentMonth])
                    ->sum('amount');

                if ($totalPaidThisMonth >= $currentFee) {
                    $fullMessage = "The full fee for this month has already been paid. Please wait for the next month to record a new payment.";
                    $allowPayment = false;
                } elseif ($totalPaidThisMonth > 0) {
                    $remainingDueThisMonth = $currentFee - $totalPaidThisMonth;
                    $partialMessage = "This member has already paid a partial amount of " . number_format($totalPaidThisMonth, 2) . " PKR. The remaining amount for this month's fee is " . number_format($remainingDueThisMonth, 2) . " PKR.";
                }

                $remainingDueThisMonth = $currentFee - $totalPaidThisMonth;
                if ($remainingDueThisMonth < 0) {
                    $remainingDueThisMonth = 0;
                }

                $totalPayable = $currentDues + $remainingDueThisMonth;
            }
        }
        
        return view('payments.create', compact('members', 'selectedMember', 'currentAssignment', 'currentDues', 'currentFee', 'totalPayable', 'partialMessage', 'fullMessage', 'allowPayment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'member_membership_plan_id' => 'required_if:payment_type,membership_fee|nullable|exists:member_membership_plan,id',
            'amount' => 'required|numeric|min:0',
            'due_amount' => 'numeric|min:0',
            'payment_date' => 'required|date',
            'due_date' => 'nullable|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,upi,other',
            'payment_type' => 'required|in:membership_fee,admission_fee,personal_training,other',
            'notes' => 'nullable|string',
        ]);

        $member = Member::findOrFail($validated['member_id']);

        // If not membership fee, record a simple payment without membership assignment logic
        if (($validated['payment_type'] ?? null) !== 'membership_fee') {
            $validated['status'] = 'paid';
            $validated['member_membership_plan_id'] = null;
            $validated['membership_plan_id'] = null;
            $validated['due_amount'] = 0;
            $payment = Payment::create($validated);

            return redirect()->route('payments.index')->with('success', 'Payment recorded successfully!');
        }
        
        DB::beginTransaction();
        try {
        // Resolve the assignment: use provided id if valid, otherwise find current
        $assignment = null;
        if (!empty($validated['member_membership_plan_id'])) {
            $assignment = MemberMembershipPlan::where('id', $validated['member_membership_plan_id'])
                ->where('member_id', $member->id)
                ->with('membershipPlan')
                ->first();
        }
        
        if (!$assignment) {
            // Pick active assignment whose window includes today; fallback to most recent
            $assignment = $member->memberMembershipPlans()
                ->with('membershipPlan')
                ->orderByDesc('end_date')
                ->get()
                ->first(function ($a) {
                    $today = Carbon::today();
                    return $today->betweenIncluded(Carbon::parse($a->start_date), Carbon::parse($a->end_date));
                }) ?? $member->memberMembershipPlans()->with('membershipPlan')->orderByDesc('end_date')->first();
        }
        
        if (!$assignment) {
            return back()->withErrors(['member_id' => 'Selected member has no membership assignment.'])->withInput();
        }
        // Override incoming id
        $validated['member_membership_plan_id'] = $assignment->id;

        // Ensure assignment belongs to member
        if ($assignment->member_id !== $member->id) {
            return back()->withErrors(['member_membership_plan_id' => 'Selected membership does not belong to the chosen member.'])->withInput();
        }

        // Set membership_plan_id from assignment for quick reference
        $validated['membership_plan_id'] = $assignment->membership_plan_id;

        $plan = $assignment->membershipPlan;
        $baseFee = (float) ($plan->fee ?? 0);
        $durationType = strtolower($plan->duration_type ?? 'month');
        $durationValue = (int) ($plan->duration_value ?? 1);

        // Calculate the fee per payment period
        if (($durationType === 'year' || $durationType === 'years') && $durationValue === 1) {
            // For yearly plans, assume fee is yearly total, so monthly fee = total / 12
            $planFee = $baseFee / 12;
        } else {
            // For monthly plans or other durations, fee is as stored
            $planFee = $baseFee;
        }
        
        $amount = (float) $validated['amount'];
        $paymentDate = Carbon::parse($validated['payment_date']);
        $paymentMonth = $paymentDate->format('Y-m');

        // Check if fees for this month have already been paid in full
        $existingFullPayment = Payment::where('member_id', $member->id)
            ->where('payment_type', 'membership_fee')
            ->whereRaw("DATE_FORMAT(payment_date, '%Y-%m') = ?", [$paymentMonth])
            ->where('status', 'paid')
            ->first();
        
        if ($existingFullPayment) {
            return back()->withErrors(['duplicate' => 'Fees for this month have already been paid in full.'])->withInput();
        }

        // Calculate total paid this month for this assignment
        $totalPaidThisMonth = Payment::where('member_membership_plan_id', $assignment->id)
            ->where('payment_type', 'membership_fee')
            ->whereRaw("DATE_FORMAT(payment_date, '%Y-%m') = ?", [$paymentMonth])
            ->sum('amount');

        $remainingDue = $planFee - $totalPaidThisMonth;

        if ($amount > $remainingDue) {
            return back()->withErrors(['amount' => 'You cannot record a payment greater than the remaining amount or total due.'])->withInput();
        }

        // Additional check for yearly plans: ensure total payments don't exceed yearly fee
        $planDurationType = strtolower($plan->duration_type ?? 'month');
        if ($planDurationType === 'year' || $planDurationType === 'years') {
            $totalPaidForYear = Payment::where('member_membership_plan_id', $assignment->id)
                ->where('payment_type', 'membership_fee')
                ->whereBetween('payment_date', [$assignment->start_date, $assignment->end_date])
                ->sum('amount');
            
            $yearlyFee = $baseFee; // Use the stored fee as yearly total
            if ($totalPaidForYear + $amount > $yearlyFee) {
                $remaining = $yearlyFee - $totalPaidForYear;
                return back()->withErrors(['amount' => 'Payment would exceed the yearly fee. Remaining amount for this year: ' . number_format($remaining, 2)])->withInput();
            }
        }

        // Calculate previous dues: sum of due_amount from payments in previous months for this assignment
        $previousDues = Payment::where('member_membership_plan_id', $assignment->id)
            ->where('payment_type', 'membership_fee')
            ->where('due_amount', '>', 0)
            ->where('payment_date', '<', $paymentDate->toDateString())
            ->whereRaw("DATE_FORMAT(payment_date, '%Y-%m') != ?", [$paymentMonth])
            ->sum('due_amount');

        $totalDue = $previousDues + $planFee;
        $remaining = $amount;

        $clearedDues = min($remaining, $previousDues);
        $remaining -= $clearedDues;

        $paidCurrent = min($remaining, $planFee);
        $remaining -= $paidCurrent;

        // Calculate the new due amount for the current month
        $newDueAmount = $planFee - ($totalPaidThisMonth + $amount);

        if ($newDueAmount <= 0) {
            $validated['status'] = 'paid';
            $validated['due_amount'] = 0;
        } else {
            $validated['status'] = 'partial';
            $validated['due_amount'] = $newDueAmount;
        }

        // Set due_date to end of current month if not set
        if (empty($validated['due_date'])) {
            $validated['due_date'] = $paymentDate->copy()->endOfMonth()->toDateString();
        }

        $payment = Payment::create($validated);

        // If the payment completed the current month's fee for this assignment, update all partial payments in the month for the member to paid
        if ($newDueAmount <= 0) {
            Payment::where('member_id', $member->id)
                ->where('payment_type', 'membership_fee')
                ->whereRaw("DATE_FORMAT(payment_date, '%Y-%m') = ?", [$paymentMonth])
                ->where('status', 'partial')
                ->update(['status' => 'paid', 'due_amount' => 0]);
        }

        // Clear previous dues if any were cleared
        if ($clearedDues > 0) {
            $previousPayments = Payment::where('member_membership_plan_id', $assignment->id)
                ->where('payment_type', 'membership_fee')
                ->where('due_amount', '>', 0)
                ->where('payment_date', '<', $paymentDate->toDateString())
                ->orderBy('payment_date')
                ->get();

            $remainingToClear = $clearedDues;
            foreach ($previousPayments as $prevPayment) {
                if ($remainingToClear <= 0) break;
                $clearAmount = min($remainingToClear, (float) $prevPayment->due_amount);
                $prevPayment->due_amount = $prevPayment->due_amount - $clearAmount;
                if ($prevPayment->due_amount <= 0) {
                    $prevPayment->status = 'paid';
                }
                $prevPayment->save();
                $remainingToClear -= $clearAmount;
            }
        }

        // Create commission for coach if member has a coach and payment is paid/partial
        if ($member->coach_id && in_array($payment->status, ['paid', 'partial'])) {
            $coach = $member->coach;
            
            if ($coach && $coach->commission_rate && $plan) {
                $commissionAmount = ($amount * $coach->commission_rate) / 100;
                
                Commission::create([
                    'coach_id' => $coach->id,
                    'member_id' => $member->id,
                    'member_membership_plan_id' => $assignment->id,
                    'payment_id' => $payment->id,
                    'amount' => $commissionAmount,
                    'commission_date' => $payment->payment_date,
                    'status' => 'unpaid',
                    'description' => 'Commission for ' . $member->name . ' payment of ' . number_format($amount, 2),
                ]);
            }
        }

        DB::commit();
        
        // Update membership assignment dates if this is a membership fee payment
        if ($validated['payment_type'] === 'membership_fee' && $assignment) {
            // Extend the assignment end date based on the plan duration
            $plan = $assignment->membershipPlan;
            if ($plan) {
                $currentEndDate = Carbon::parse($assignment->end_date);
                $paymentDate = Carbon::parse($validated['payment_date']);
                
                // If payment is made after the current end date, extend the assignment
                if ($paymentDate->greaterThanOrEqualTo($currentEndDate)) {
                    $durationType = strtolower($plan->duration_type ?? 'month');
                    $durationValue = (int) ($plan->duration_value ?? 1);
                    
                    // Calculate new end date
                    $newEndDate = $currentEndDate->copy();
                    switch ($durationType) {
                        case 'day':
                        case 'days':
                            $newEndDate->addDays($durationValue);
                            break;
                        case 'week':
                        case 'weeks':
                            $newEndDate->addWeeks($durationValue);
                            break;
                        case 'year':
                        case 'years':
                            $newEndDate->addYears($durationValue);
                            break;
                        case 'month':
                        case 'months':
                        default:
                            $newEndDate->addMonths($durationValue);
                            break;
                    }
                    
                    // Update the assignment
                    $assignment->end_date = $newEndDate->toDateString();
                    $assignment->status = 'active'; // Ensure it's active
                    $assignment->save();
                    
                    \Log::info("Extended membership assignment {$assignment->id} to {$newEndDate->toDateString()} due to payment {$payment->id}");
                }
            }
        }
        
        return redirect()->route('members.index')->with('success', 'Payment recorded successfully! Member status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to record payment: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['member', 'membershipPlan', 'memberMembershipPlan']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $members = Member::where('status', 'active')->get();
        return view('payments.edit', compact('payment', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'due_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,upi,other',
            'payment_type' => 'required|in:membership_fee,admission_fee,personal_training,other',
            'status' => 'required|in:paid,pending,partial,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.show', $payment)->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully!');
    }

    public function receipt(Payment $payment)
    {
        $payment->load(['member', 'membershipPlan', 'memberMembershipPlan']);
        return view('payments.receipt', compact('payment'));
    }
}
