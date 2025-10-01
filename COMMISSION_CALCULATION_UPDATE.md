# Commission Calculation System - Updated

## Overview
Updated the commission system to calculate based on the **total monthly fees of all assigned members** rather than individual commission records per member assignment.

## New Commission Logic

### How It Works

**Formula:**
```
Commission = (Total Member Fees × Commission Rate) / 100
```

**Example:**
```
Coach: John Smith
Basic Salary: 40,000
Commission Rate: 10%

Assigned Members:
- Member A: Monthly Fee = 3,000
- Member B: Monthly Fee = 3,000

Total Member Fees: 6,000
Commission: 6,000 × 10% = 600

Total Payable: 40,000 + 600 = 40,600
```

### Calculation Details

1. **System counts all active members** assigned to the coach
2. **For each member**, gets their active membership plan
3. **Sums up all monthly fees** from active plans
4. **Applies commission rate** to the total
5. **Adds to basic salary** for total payable amount

## What Changed

### 1. CoachController@show
**Added:**
- `$totalMemberFees` - Sum of all assigned members' monthly fees
- `$calculatedCommission` - Commission based on total member fees
- `$memberFeesBreakdown` - Array with each member's fee details

**Logic:**
```php
foreach ($coach->members as $member) {
    $activePlan = $member->memberMembershipPlans()
        ->where('status', 'active')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->first();
    
    if ($activePlan && $activePlan->membershipPlan) {
        $totalMemberFees += $activePlan->membershipPlan->fee;
    }
}

$calculatedCommission = ($totalMemberFees * $coach->commission_rate) / 100;
```

### 2. Coach Show View
**Added Sections:**

#### A. Financial Summary (4 Cards)
1. **Assigned Members** - Count of members
2. **Total Member Fees** - Sum of all monthly fees
3. **Commission** - Calculated commission amount
4. **Total Salary Paid** - Historical payments

#### B. Commission Calculation Breakdown Table
Shows:
- Each member's name
- Their membership plan
- Monthly fee
- Total calculation
- Final commission amount

#### C. Updated Salary Payment Form
- Shows 4-column breakdown: Basic Salary, Member Fees, Commission, Total
- Checkbox to include/exclude commission
- Commission breakdown box with calculation details
- Pre-filled amount with basic + commission

## UI Display

### Financial Summary Cards
```
┌──────────────┬──────────────┬──────────────┬──────────────┐
│   Assigned   │ Total Member │  Commission  │ Total Salary │
│   Members    │     Fees     │    (10%)     │     Paid     │
│      2       │   6,000.00   │    600.00    │  120,000.00  │
└──────────────┴──────────────┴──────────────┴──────────────┘
```

### Commission Breakdown Table
```
┌────────────────────────────────────────────────────────┐
│ Commission Calculation Breakdown                       │
├─────────────┬──────────────────┬─────────────────────┤
│ Member      │ Membership Plan  │ Monthly Fee         │
├─────────────┼──────────────────┼─────────────────────┤
│ Member A    │ Premium Plan     │          3,000.00   │
│ Member B    │ Basic Plan       │          3,000.00   │
├─────────────┴──────────────────┼─────────────────────┤
│ Total Member Fees:              │          6,000.00   │
├─────────────────────────────────┼─────────────────────┤
│ Commission (10% of 6,000.00):   │            600.00   │
└─────────────────────────────────┴─────────────────────┘
```

### Salary Payment Summary
```
┌──────────────┬──────────────┬──────────────┬──────────────┐
│ Basic Salary │ Member Fees  │ Commission   │    Total     │
│              │    Total     │    (10%)     │   Payable    │
│  40,000.00   │   6,000.00   │    600.00    │  40,600.00   │
└──────────────┴──────────────┴──────────────┴──────────────┘

☑ Include commission (600.00)          [Basic Salary Only]

┌─────────────────────────────────────────────────────────┐
│ Commission Calculation:                                 │
│ Total Member Fees: 6,000.00                             │
│ Commission Rate: 10%                                    │
│ Commission Amount: 600.00                               │
└─────────────────────────────────────────────────────────┘

Amount to Pay *: [40,600.00]  (Adjust if needed)
```

## Benefits

### 1. **Automatic Calculation**
- No manual commission entry needed
- Updates in real-time when members are assigned/unassigned
- Always reflects current member roster

### 2. **Transparent Breakdown**
- Shows exactly which members contribute to commission
- Displays each member's fee
- Clear calculation formula visible

### 3. **Flexible Payment**
- Can pay with or without commission
- Amount pre-filled but adjustable
- Clear indication of what's included

### 4. **Accurate Tracking**
- Commission based on active members only
- Only counts members with active plans
- Reflects current month's earnings

## Example Scenarios

### Scenario 1: Coach with Multiple Members
```
Coach: Sarah Johnson
Basic Salary: 50,000
Commission Rate: 15%

Members:
- Ali (Gold Plan): 5,000/month
- Ahmed (Premium): 4,000/month
- Fatima (Basic): 2,500/month
- Hassan (Silver): 3,500/month

Total Member Fees: 15,000
Commission: 15,000 × 15% = 2,250
Total Payable: 50,000 + 2,250 = 52,250
```

### Scenario 2: Coach with No Commission Rate
```
Coach: Mike Davis
Basic Salary: 45,000
Commission Rate: 0% (or not set)

Members: 5 members with various plans

Total Member Fees: 12,000
Commission: 12,000 × 0% = 0
Total Payable: 45,000 + 0 = 45,000
```

### Scenario 3: Coach with No Assigned Members
```
Coach: Lisa Brown
Basic Salary: 40,000
Commission Rate: 10%

Members: 0 assigned

Total Member Fees: 0
Commission: 0 × 10% = 0
Total Payable: 40,000 + 0 = 40,000
```

### Scenario 4: Member Added Mid-Month
```
Initial:
- 2 members, Total Fees: 6,000
- Commission: 600

After adding 3rd member (Fee: 4,000):
- 3 members, Total Fees: 10,000
- Commission: 1,000 (automatically updated)
```

## Active Plan Detection

The system only counts members with **active** membership plans:

**Criteria for Active Plan:**
- Status = 'active'
- Start Date ≤ Today
- End Date ≥ Today

**Not Counted:**
- Expired plans
- Cancelled plans
- Future plans (not started yet)
- Members without any plan

## Payment Process

### Step 1: View Coach Details
Navigate to `/coaches/{id}` to see:
- Financial summary cards
- Commission breakdown table
- Salary payment form

### Step 2: Review Calculation
Check:
- Number of assigned members
- Total member fees
- Calculated commission
- Total payable amount

### Step 3: Process Payment
Options:
- **Include Commission** (default): Pay basic + commission
- **Basic Salary Only**: Uncheck box or click button
- **Custom Amount**: Adjust the amount field

### Step 4: Submit
- Payment recorded as expense
- Category: 'salaries'
- Expense Type: 'Coach Salary'
- Appears in expense reports

## Files Modified

1. **app/Http/Controllers/CoachController.php**
   - Added member fee calculation logic
   - Added commission calculation
   - Added member fees breakdown

2. **resources/views/coaches/show.blade.php**
   - Updated financial summary (3 → 4 cards)
   - Added commission breakdown table
   - Updated salary payment form
   - Updated JavaScript for new calculation

## Database Queries

### Get Active Members with Fees
```sql
SELECT 
    m.id,
    m.name,
    mp.name as plan_name,
    mp.fee
FROM members m
INNER JOIN member_membership_plan mmp ON m.id = mmp.member_id
INNER JOIN membership_plans mp ON mmp.membership_plan_id = mp.id
WHERE m.coach_id = ?
AND mmp.status = 'active'
AND mmp.start_date <= NOW()
AND mmp.end_date >= NOW()
```

### Calculate Total Commission
```sql
SELECT 
    SUM(mp.fee) as total_fees,
    (SUM(mp.fee) * c.commission_rate / 100) as commission
FROM coaches c
LEFT JOIN members m ON c.id = m.coach_id
LEFT JOIN member_membership_plan mmp ON m.id = mmp.member_id
LEFT JOIN membership_plans mp ON mmp.membership_plan_id = mp.id
WHERE c.id = ?
AND mmp.status = 'active'
AND mmp.start_date <= NOW()
AND mmp.end_date >= NOW()
GROUP BY c.id
```

## Testing

### Test Case 1: Basic Calculation
1. Create coach with 10% commission rate
2. Assign 2 members with 3,000/month each
3. View coach details
4. Verify: Commission = 600, Total = Basic + 600

### Test Case 2: No Commission Rate
1. Create coach without commission rate
2. Assign members
3. Verify: Commission = 0

### Test Case 3: No Members
1. Create coach with commission rate
2. Don't assign any members
3. Verify: Commission = 0, Total = Basic only

### Test Case 4: Mixed Active/Inactive Plans
1. Assign 3 members to coach
2. Set 1 member's plan to expired
3. Verify: Only 2 active members counted

### Test Case 5: Payment with Commission
1. View coach with commission
2. Keep "Include commission" checked
3. Submit payment
4. Verify: Expense = Basic + Commission

### Test Case 6: Payment without Commission
1. View coach with commission
2. Uncheck "Include commission"
3. Submit payment
4. Verify: Expense = Basic only

## Troubleshooting

### Commission showing as 0?
**Check:**
- Coach has commission_rate set
- Members are assigned to coach
- Members have active plans
- Plan start_date ≤ today
- Plan end_date ≥ today

### Member not counted in total?
**Check:**
- Member's coach_id matches this coach
- Member has active membership plan
- Plan status = 'active'
- Plan dates include today

### Total seems incorrect?
**Check:**
- Review commission breakdown table
- Verify each member's fee
- Check commission rate percentage
- Ensure calculation: (Total Fees × Rate) / 100

## Future Enhancements (Not Implemented)

- Historical commission tracking
- Commission payment separately from salary
- Tiered commission rates
- Bonus commission for member retention
- Commission caps or limits
- Pro-rated commission for partial months
- Commission reports and analytics

## Summary

The updated commission system:
- ✅ Calculates based on total member fees
- ✅ Shows clear breakdown of calculation
- ✅ Updates automatically when members change
- ✅ Displays in 4-card financial summary
- ✅ Includes detailed breakdown table
- ✅ Flexible payment options
- ✅ Recorded as expense (not revenue)

**Example:**
- Basic Salary: 40,000
- Commission Rate: 10%
- 2 Members @ 3,000 each = 6,000 total
- Commission: 600
- **Total Payable: 40,600**

---

**Updated**: 2025-10-01  
**Version**: 2.0  
**Compatibility**: Laravel 11.x, PHP 8.1+
