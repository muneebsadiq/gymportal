# Commission and Salary Payment System Changes

## Summary
Updated the gym portal system to ensure that:
1. Coach commissions are only calculated and assigned when members actually pay their fees
2. Coaches cannot be paid salary twice for the same month (UI shows "Already Paid" instead of payment form)

## Changes Made

### 1. Database Migration
**File**: `database/migrations/2025_10_01_162008_add_payment_id_to_commissions_table.php`
- Added `payment_id` foreign key to `commissions` table
- Links each commission directly to the payment that generated it
- Ensures commissions are tied to actual payments, not just membership assignments

### 2. Commission Model Update
**File**: `app/Models/Commission.php`
- Added `payment_id` to fillable fields
- Added `payment()` relationship method to link commission to payment

### 3. Payment Model Update
**File**: `app/Models/Payment.php`
- Added `commissions()` relationship method
- Allows tracking all commissions generated from a payment

### 4. Membership Assignment Controller
**File**: `app/Http/Controllers/MemberMembershipPlanController.php`
- **REMOVED** commission creation from `store()` method
- Previously created commissions when membership was assigned (incorrect)
- Now commissions are only created when payment is received (correct)

### 5. Payment Controller
**File**: `app/Http/Controllers/PaymentController.php`
- Added commission creation logic in `store()` method
- Commissions are now created ONLY when:
  - Payment type is 'membership_fee'
  - Payment status is 'paid' or 'partial'
  - Member has an assigned coach
  - Coach has a commission rate set
- Commission amount is calculated based on actual payment amount, not plan fee
- Wrapped in database transaction for data integrity
- Commission is linked to the payment via `payment_id`

## How It Works Now

### Commission Flow
1. Member is assigned a membership plan → **NO commission created**
2. Member makes a payment → **Commission created based on payment amount**
3. Commission is linked to both the coach and the specific payment
4. Coach's commission calculation only includes PAID member fees

### Salary Payment Flow
1. Coach details page shows current month salary calculation
2. Calculation includes:
   - Basic salary
   - Commission from PAID member fees only (already implemented)
3. If salary already paid for current month:
   - "Already Paid" badge displayed
   - Payment form hidden
   - "Already Paid" message shown with link to salary history
4. If salary NOT paid:
   - Payment form displayed
   - "Record Payment" button available

## Benefits
- ✅ Coaches only earn commission on actual revenue (paid fees)
- ✅ No duplicate salary payments for the same month
- ✅ Better financial tracking and accuracy
- ✅ Commission tied to specific payments for audit trail
- ✅ Clear UI indication of payment status

## Testing Recommendations
1. Assign a membership plan to a member with a coach → Verify no commission created
2. Record a payment for that member → Verify commission is created
3. Check coach details page → Verify commission calculation only includes paid fees
4. Pay coach salary for current month → Verify "Already Paid" appears
5. Try to pay again → Verify form is hidden and "Already Paid" message shows
