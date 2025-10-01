# Payment Due Filter - Feature Update

## Overview
Added a new "Payment Due" filter to the members list page, positioned after the Status filter and before the Fee Status filter.

## What Was Added

### 1. New Filter in Members Index View ✅
- **Location**: Between "Status" and "Fee Status" filters
- **Label**: "Payment Due"
- **Options**: 
  - "All Members" (default)
  - "Payment Due Only"

### 2. Controller Logic ✅
- **File**: `app/Http/Controllers/MemberController.php`
- **Filter Logic**: Shows members with pending or partial payments that are overdue or have no due date
- **Query**: 
  ```php
  whereHas('payments', function ($q) {
      $q->whereIn('status', ['pending', 'partial'])
        ->where(function ($subQ) {
            $subQ->where('due_date', '<', Carbon::now())
                 ->orWhereNull('due_date');
        });
  });
  ```

### 3. Member Model Helper Method ✅
- **File**: `app/Models/Member.php`
- **Method**: `hasPaymentDue()`
- **Purpose**: Check if member has overdue payments
- **Returns**: Boolean

### 4. Visual Badge ✅
- **Badge Color**: Orange (bg-orange-100 text-orange-800)
- **Text**: "Payment Due"
- **Location**: Appears next to member name in list
- **Distinction**: Different from "Due Fees" badge (red)

## How It Works

### Filter Behavior
When "Payment Due Only" is selected:
1. Queries members with payments in 'pending' or 'partial' status
2. Checks if payment due_date is in the past OR due_date is null
3. Returns only members matching these criteria
4. Works alongside other filters (search, status, due fees)

### Badge Display
- **Payment Due Badge** (Orange): Shows when member has overdue payments
- **Due Fees Badge** (Red): Shows when membership plan has expired
- Both badges can appear simultaneously if applicable

## Differences: Payment Due vs Due Fees

| Feature | Payment Due | Due Fees |
|---------|-------------|----------|
| **What it checks** | Pending/partial payments with past due dates | Membership plans with past end dates |
| **Badge Color** | Orange | Red |
| **Data Source** | `payments` table | `member_membership_plan` table |
| **Status Checked** | pending, partial | active (not cancelled) |
| **Date Field** | `due_date` | `end_date` |

## Files Modified

1. **resources/views/members/index.blade.php**
   - Changed grid from 5 columns to 6 columns
   - Added "Payment Due" filter dropdown after "Status"
   - Added orange "Payment Due" badge in member list

2. **app/Http/Controllers/MemberController.php**
   - Added `payment_due` filter logic in `index()` method
   - Eager loads `payments` relationship

3. **app/Models/Member.php**
   - Added `hasPaymentDue()` helper method

## Usage Examples

### Example 1: Find Members with Overdue Payments
```
1. Navigate to /members
2. Select "Payment Due Only" from Payment Due dropdown
3. Click Filter
4. See all members with pending/partial payments that are overdue
```

### Example 2: Combine Filters
```
1. Navigate to /members
2. Select "Active" from Status
3. Select "Payment Due Only" from Payment Due
4. Click Filter
5. See only active members with overdue payments
```

### Example 3: Visual Indicators
```
Member Card Display:
- Green badge: Active status
- Orange badge: Payment Due (has overdue payment)
- Red badge: Due Fees (membership expired)
```

## Testing

### Test Case 1: Payment Due Filter
1. Create member with pending payment (due_date in past)
2. Navigate to members page
3. Select "Payment Due Only"
4. Verify member appears in filtered list
5. Verify orange "Payment Due" badge visible

### Test Case 2: Badge Display
1. Create member with:
   - Pending payment (due_date yesterday)
   - Expired membership plan (end_date yesterday)
2. Navigate to members page
3. Verify both badges appear:
   - Orange "Payment Due" badge
   - Red "Due Fees" badge

### Test Case 3: Filter Combination
1. Set Status filter to "Active"
2. Set Payment Due filter to "Payment Due Only"
3. Click Filter
4. Verify only active members with payment due appear

### Test Case 4: No Results
1. Select "Payment Due Only" when no members have overdue payments
2. Verify "No members found" message displays

## Database Query

The filter uses this query structure:
```sql
SELECT * FROM members
WHERE EXISTS (
    SELECT 1 FROM payments
    WHERE payments.member_id = members.id
    AND payments.status IN ('pending', 'partial')
    AND (
        payments.due_date < NOW()
        OR payments.due_date IS NULL
    )
)
```

## Performance Considerations

- Uses `whereHas()` for efficient subquery
- Eager loads `payments` relationship to avoid N+1 queries
- Indexed foreign keys ensure fast lookups
- Pagination limits results to 20 per page

## UI Layout

```
[Search] [Status] [Payment Due] [Fee Status] [Filter] [Clear]
   ^         ^          ^             ^
   1         2          3             4

1. Search by name, ID, or phone
2. Filter by member status (active/inactive/suspended)
3. Filter by payment due status (NEW)
4. Filter by fee status (membership expiry)
```

## Future Enhancements (Not Implemented)

- Show payment amount due in badge
- Click badge to go directly to payment page
- Sort by payment due date
- Email reminders for overdue payments
- Bulk payment collection
- Payment due dashboard widget

## Troubleshooting

### Badge not showing?
**Check:**
- Member has payments in database
- Payment status is 'pending' or 'partial'
- Payment due_date is in the past or null

### Filter returns no results?
**Check:**
- At least one member has overdue payments
- Payments table has records with status pending/partial
- due_date field is populated and in past

### Both badges showing?
**This is correct if:**
- Member has overdue payment (Payment Due badge)
- Member has expired membership (Due Fees badge)
- Both conditions can exist simultaneously

## Summary

The Payment Due filter provides a quick way to identify members with overdue payments, complementing the existing Due Fees filter which tracks membership expiry. The orange badge provides clear visual distinction from the red Due Fees badge, allowing staff to quickly identify different types of outstanding obligations.

---

**Added**: 2025-10-01  
**Version**: 1.1  
**Compatibility**: Laravel 11.x, PHP 8.1+
