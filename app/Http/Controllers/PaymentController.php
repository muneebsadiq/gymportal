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

        if ($request->member_id) {
            $selectedMember = Member::findOrFail($request->member_id);
            // Pick active assignment whose window includes today; fallback to most recent
            $currentAssignment = $selectedMember->memberMembershipPlans()
                ->with('membershipPlan')
                ->orderByDesc('end_date')
                ->get()
                ->first(function ($a) {
                    $today = Carbon::today();
                    return $today->betweenIncluded(Carbon::parse($a->start_date), Carbon::parse($a->end_date));
                }) ?? $selectedMember->memberMembershipPlans()->with('membershipPlan')->orderByDesc('end_date')->first();
        }
        
        return view('payments.create', compact('members', 'selectedMember', 'currentAssignment'));
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
            $payment = Payment::create($validated);

            return redirect()->route('payments.index')->with('success', 'Payment recorded successfully!');
        }
        
        DB::beginTransaction();
        try {
        // Always resolve the current assignment server-side to avoid tampering
        $assignment = $member->memberMembershipPlans()
            ->with('membershipPlan')
            ->orderByDesc('end_date')
            ->get()
            ->first(function ($a) {
                $today = Carbon::today();
                return $today->betweenIncluded(Carbon::parse($a->start_date), Carbon::parse($a->end_date));
            }) ?? $member->memberMembershipPlans()->with('membershipPlan')->orderByDesc('end_date')->first();
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
        $planFee = (float) ($plan->fee ?? 0);
        $amount = (float) $validated['amount'];

        // Fixed window: [current_period_start .. current_end]
        $durationType = strtolower($plan->duration_type);
        $durationValue = (int) $plan->duration_value;
        $currentEnd = Carbon::parse($assignment->end_date);
        $currentStart = Carbon::parse($assignment->start_date);

        // Sum payments in current window BEFORE this payment
        $sumBefore = Payment::where('member_membership_plan_id', $assignment->id)
            ->where('payment_type', 'membership_fee')
            ->whereBetween('payment_date', [$currentStart->toDateString(), $currentEnd->toDateString()])
            ->sum('amount');

        // Prepare payment: due_date is the current period end (pre-renewal)
        if (empty($validated['due_date'])) {
            $validated['due_date'] = $currentEnd->toDateString();
        }

        $sumAfter = $sumBefore + $amount;
        $paymentDate = Carbon::parse($validated['payment_date']);

        // Block new payments inside active period if already fully paid
        if ($paymentDate->betweenIncluded($currentStart, $currentEnd)) {
            if ($planFee > 0 && $sumBefore >= $planFee) {
                return back()->withErrors(['amount' => 'This membership period is already fully paid. No additional payment is allowed in the active period.'])->withInput();
            }
            // Allow only remaining amount (partial) within the same period
            $remaining = max(0, $planFee - $sumBefore);
            if ($planFee > 0 && $amount > $remaining) {
                return back()->withErrors(['amount' => 'Amount exceeds remaining balance for this period. Remaining: ' . number_format($remaining, 2)])->withInput();
            }
        }

        // Default: do NOT renew, even if >= fee, when within current period
        $shouldRenewNow = false;
        if ($paymentDate->greaterThan($currentEnd)) {
            // Membership expired; accumulate payments since expiry and renew only when threshold reached
            $sinceExpiryBefore = Payment::where('member_membership_plan_id', $assignment->id)
                ->where('payment_type', 'membership_fee')
                ->whereDate('payment_date', '>', $currentEnd->toDateString())
                ->whereDate('payment_date', '<=', $paymentDate->toDateString())
                ->sum('amount');
            $sinceExpiryAfter = $sinceExpiryBefore + $amount;
            if ($planFee > 0 && $sinceExpiryAfter >= $planFee) {
                $shouldRenewNow = true;
            }
        }

        if ($planFee > 0 && $shouldRenewNow) {
            // Mark payment and renew exactly one period from previous end
            $validated['status'] = 'paid';
            $payment = Payment::create($validated);

            $newEnd = match ($durationType) {
                'day', 'days' => $currentEnd->copy()->addDays($durationValue),
                'week', 'weeks' => $currentEnd->copy()->addWeeks($durationValue),
                'month', 'months' => $currentEnd->copy()->addMonths($durationValue),
                'year', 'years' => $currentEnd->copy()->addYears($durationValue),
                default => $currentEnd->copy()->addMonths($durationValue),
            };

            $assignment->end_date = $newEnd->toDateString();
            $assignment->status = 'active';
            $assignment->save();
        } else {
            // No renewal; set status based on in-window sum for display (partial/paid/excess) but do not advance end_date
            if ($planFee > 0 && $sumAfter < $planFee) {
                $validated['status'] = 'partial';
            } else if ($planFee > 0 && $sumAfter >= $planFee) {
                // Mark fully paid within the period when cumulative reaches fee
                $validated['status'] = 'paid';
            } else {
                $validated['status'] = 'paid';
            }
            $payment = Payment::create($validated);
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
        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully!');
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
