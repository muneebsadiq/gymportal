<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Member;
use App\Models\MemberMembershipPlan;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $assignments = collect();

        if ($request->member_id) {
            $selectedMember = Member::findOrFail($request->member_id);
            $assignments = $selectedMember->memberMembershipPlans()
                ->with('membershipPlan')
                ->orderByDesc('start_date')
                ->get();
        }
        
        return view('payments.create', compact('members', 'selectedMember', 'assignments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'member_membership_plan_id' => 'required|exists:member_membership_plan,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'due_date' => 'nullable|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,upi,other',
            'payment_type' => 'required|in:membership_fee,admission_fee,personal_training,other',
            'notes' => 'nullable|string',
        ]);

        $member = Member::findOrFail($validated['member_id']);
        $assignment = MemberMembershipPlan::with('membershipPlan')
            ->findOrFail($validated['member_membership_plan_id']);

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
            } else {
                $validated['status'] = 'paid'; // equal or excess, but no renewal as per rule
            }
            $payment = Payment::create($validated);
        }

        return redirect()->route('payments.index')->with('success', 'Payment recorded and membership renewed successfully!');
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
