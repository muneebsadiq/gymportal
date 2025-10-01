# Testing Checklist - Coach Salary & Commission Features

## Pre-Testing Setup

- [ ] Database backup created
- [ ] Migrations applied successfully
- [ ] Cache cleared (`php artisan cache:clear`)
- [ ] Test environment ready
- [ ] Test user accounts created

## 1. Database Schema Tests

### Coaches Table
- [ ] `salary` column exists (decimal, nullable)
- [ ] `commission_rate` column exists (decimal, nullable)
- [ ] Can insert coach with salary and commission_rate
- [ ] Can insert coach without salary (nullable)
- [ ] Can update existing coach with salary fields

### Commissions Table
- [ ] Table exists with all required columns
- [ ] Foreign key to `coaches` table works
- [ ] Foreign key to `members` table works
- [ ] Foreign key to `member_membership_plan` table works
- [ ] Cascade delete works (delete coach → commissions deleted)
- [ ] Status enum accepts 'unpaid' and 'paid'

### Expenses Table
- [ ] `expense_type` column exists (varchar, nullable)
- [ ] `coach_id` column exists (bigint unsigned, nullable)
- [ ] Foreign key to `coaches` table works
- [ ] Can create expense with expense_type
- [ ] Can create expense linked to coach

## 2. Model Tests

### Coach Model
- [ ] `salary` is fillable
- [ ] `commission_rate` is fillable
- [ ] `salary` casts to decimal
- [ ] `commission_rate` casts to decimal
- [ ] `commissions()` relationship works
- [ ] `expenses()` relationship works
- [ ] `members()` relationship works

### Member Model
- [ ] `commissions()` relationship works
- [ ] `scopeWithDueFees()` filters correctly
- [ ] `getActivePlanAttribute()` returns correct plan
- [ ] `hasDueFees()` method works correctly

### Commission Model
- [ ] `coach()` relationship works
- [ ] `member()` relationship works
- [ ] `memberMembershipPlan()` relationship works
- [ ] Amount casts to decimal
- [ ] Commission_date casts to date

### Expense Model
- [ ] `coach()` relationship works
- [ ] `expense_type` is fillable
- [ ] `coach_id` is fillable

## 3. Controller Tests

### CoachController
- [ ] Create coach with salary and commission_rate
- [ ] Update coach salary and commission_rate
- [ ] Show page loads with financial data
- [ ] Validation works for salary (numeric, min:0)
- [ ] Validation works for commission_rate (numeric, min:0, max:100)

### CoachSalaryController
- [ ] Pay salary creates expense record
- [ ] Expense has correct expense_type ('Coach Salary')
- [ ] Expense has correct category ('salaries')
- [ ] Expense linked to coach (coach_id set)
- [ ] Validation works for payment form
- [ ] Success message displayed after payment
- [ ] Redirects to coach show page

### MemberController
- [ ] Index loads with due_fees filter
- [ ] Filter by due_fees=1 works correctly
- [ ] Active plan displays in list
- [ ] Pagination works with filters
- [ ] Search works with filters

### MemberMembershipPlanController
- [ ] Creating assignment triggers commission
- [ ] Commission only created if member has coach
- [ ] Commission only created if coach has commission_rate
- [ ] Commission amount calculated correctly
- [ ] Commission status set to 'unpaid'
- [ ] Commission linked to correct records

## 4. Route Tests

- [ ] `GET /coaches` - accessible
- [ ] `GET /coaches/create` - accessible
- [ ] `POST /coaches` - creates coach with salary
- [ ] `GET /coaches/{id}` - shows financial summary
- [ ] `GET /coaches/{id}/edit` - shows salary fields
- [ ] `PUT /coaches/{id}` - updates salary
- [ ] `POST /coaches/{id}/pay-salary` - records payment
- [ ] `GET /members?due_fees=1` - filters correctly

## 5. View Tests

### Coach Create View
- [ ] Salary input field present
- [ ] Commission rate input field present
- [ ] Fields accept decimal values
- [ ] Validation errors display correctly
- [ ] Form submits successfully

### Coach Edit View
- [ ] Salary field pre-filled with current value
- [ ] Commission rate field pre-filled
- [ ] Can update values
- [ ] Validation errors display

### Coach Show View
- [ ] Salary displays correctly
- [ ] Commission rate displays with % symbol
- [ ] Financial summary cards show:
  - [ ] Total Commissions
  - [ ] Unpaid Commissions
  - [ ] Total Salary Paid
- [ ] Pay Salary form present (if salary set)
- [ ] Amount pre-filled with salary
- [ ] Payment method dropdown works
- [ ] Form submits successfully
- [ ] Assigned members list displays

### Coach Index View
- [ ] Salary column displays
- [ ] Commission column displays
- [ ] Values formatted correctly (2 decimals)
- [ ] Null values show as "—"

### Members Index View
- [ ] Fee Status filter dropdown present
- [ ] "Due Fees Only" option available
- [ ] Filter works correctly
- [ ] Due Fees badge shows for overdue members
- [ ] Active plan name displays
- [ ] Pagination preserves filter

## 6. Business Logic Tests

### Commission Creation
- [ ] Commission created when member assigned to coach
- [ ] Commission amount = (plan_fee × commission_rate) / 100
- [ ] Commission not created if no coach assigned
- [ ] Commission not created if commission_rate is 0 or null
- [ ] Multiple commissions can exist for same coach
- [ ] Commission date set to current date

### Salary Payment
- [ ] Expense created with correct data
- [ ] Expense number auto-generated
- [ ] Title includes coach name
- [ ] Vendor name set to coach name
- [ ] Amount matches payment amount
- [ ] Payment date matches input
- [ ] Payment method matches selection
- [ ] Description saved correctly

### Due Fees Logic
- [ ] Member with end_date < today flagged
- [ ] Cancelled assignments ignored
- [ ] Active assignments within dates not flagged
- [ ] Multiple overdue assignments handled
- [ ] Filter query performs efficiently

### Active Plan Logic
- [ ] Returns plan where start_date <= today
- [ ] Returns plan where end_date >= today
- [ ] Returns plan where status = 'active'
- [ ] Returns null if no active plan
- [ ] Returns most recent if multiple active

## 7. Integration Tests

### End-to-End: Coach Salary Payment
1. [ ] Create coach with salary 50000
2. [ ] Navigate to coach detail page
3. [ ] Fill pay salary form
4. [ ] Submit form
5. [ ] Verify success message
6. [ ] Navigate to expenses
7. [ ] Find expense with coach name
8. [ ] Verify amount, date, type correct
9. [ ] Navigate back to coach page
10. [ ] Verify "Total Salary Paid" updated

### End-to-End: Commission Creation
1. [ ] Create coach with commission_rate 5
2. [ ] Create member
3. [ ] Assign member to coach
4. [ ] Create membership plan (fee 10000)
5. [ ] Assign plan to member
6. [ ] Check commissions table
7. [ ] Verify commission = 500
8. [ ] Navigate to coach detail
9. [ ] Verify commission totals updated

### End-to-End: Due Fees Filter
1. [ ] Create member with plan ending yesterday
2. [ ] Navigate to members page
3. [ ] Select "Due Fees Only"
4. [ ] Click Filter
5. [ ] Verify member appears
6. [ ] Verify "Due Fees" badge visible
7. [ ] Clear filter
8. [ ] Verify all members shown

## 8. UI/UX Tests

### Layout & Styling
- [ ] Forms use Bootstrap styling
- [ ] Buttons styled consistently
- [ ] Cards display correctly
- [ ] Tables responsive on mobile
- [ ] Icons display properly
- [ ] Colors match theme

### User Experience
- [ ] Form fields have clear labels
- [ ] Validation messages helpful
- [ ] Success messages clear
- [ ] Error messages actionable
- [ ] Navigation intuitive
- [ ] Loading states handled

### Accessibility
- [ ] Form labels associated with inputs
- [ ] Required fields marked
- [ ] Error messages readable
- [ ] Keyboard navigation works
- [ ] Color contrast sufficient

## 9. Performance Tests

### Query Performance
- [ ] Members with due fees query < 100ms
- [ ] Coach detail page loads < 500ms
- [ ] Expense list with filters < 200ms
- [ ] Commission calculations instant

### Database Indexes
- [ ] Consider index on `member_membership_plan(status, end_date)`
- [ ] Consider index on `commissions(coach_id, status)`
- [ ] Consider index on `expenses(expense_type, coach_id)`

## 10. Security Tests

### Authentication
- [ ] All routes require authentication
- [ ] Unauthenticated users redirected to login
- [ ] Session timeout works

### Authorization
- [ ] Only authorized users can pay salary
- [ ] Only authorized users can create coaches
- [ ] Only authorized users can view financial data

### Input Validation
- [ ] Salary accepts only positive numbers
- [ ] Commission rate limited to 0-100
- [ ] SQL injection prevented
- [ ] XSS attacks prevented
- [ ] CSRF tokens present and valid

### Data Integrity
- [ ] Foreign keys prevent orphaned records
- [ ] Cascade deletes work correctly
- [ ] Transactions used where needed
- [ ] Decimal precision maintained

## 11. Edge Cases

### Null Values
- [ ] Coach without salary displays correctly
- [ ] Coach without commission_rate displays correctly
- [ ] Member without coach doesn't break
- [ ] Member without plan doesn't break

### Boundary Values
- [ ] Salary = 0 accepted
- [ ] Commission rate = 0 accepted
- [ ] Commission rate = 100 accepted
- [ ] Commission rate > 100 rejected
- [ ] Negative values rejected

### Data Consistency
- [ ] Deleting coach removes commissions
- [ ] Deleting member removes commissions
- [ ] Updating coach salary doesn't affect past payments
- [ ] Updating commission rate doesn't affect past commissions

## 12. Regression Tests

### Existing Features
- [ ] Member CRUD still works
- [ ] Payment recording still works
- [ ] Expense management still works
- [ ] Reports still generate
- [ ] Dashboard still loads
- [ ] User authentication still works

### Data Migration
- [ ] Existing coaches not affected
- [ ] Existing members not affected
- [ ] Existing expenses not affected
- [ ] Existing payments not affected

## 13. Browser Compatibility

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Safari (iOS)
- [ ] Mobile Chrome (Android)

## 14. Error Handling

### User Errors
- [ ] Invalid salary shows validation error
- [ ] Invalid commission rate shows error
- [ ] Missing required fields show error
- [ ] Duplicate entries handled gracefully

### System Errors
- [ ] Database connection failure handled
- [ ] Foreign key violations caught
- [ ] Unexpected errors logged
- [ ] User sees friendly error message

## 15. Documentation Tests

- [ ] FEATURE_UPDATE_SUMMARY.md accurate
- [ ] DEPLOYMENT_GUIDE.md complete
- [ ] QUICK_START.md helpful
- [ ] Code comments present
- [ ] README updated

## Test Results Summary

**Date Tested**: _______________  
**Tested By**: _______________  
**Environment**: _______________

**Total Tests**: _____  
**Passed**: _____  
**Failed**: _____  
**Skipped**: _____

### Critical Issues Found
1. 
2. 
3. 

### Minor Issues Found
1. 
2. 
3. 

### Recommendations
1. 
2. 
3. 

### Sign-off

**QA Lead**: _______________ Date: _______________  
**Developer**: _______________ Date: _______________  
**Product Owner**: _______________ Date: _______________

---

## Quick Test Commands

```bash
# Run migrations
php artisan migrate

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Check migration status
php artisan migrate:status

# Run tests (if PHPUnit tests exist)
php artisan test

# Check routes
php artisan route:list | grep coach

# Database check
mysql -u user -p database -e "DESCRIBE coaches;"
mysql -u user -p database -e "DESCRIBE commissions;"
mysql -u user -p database -e "SELECT * FROM commissions LIMIT 5;"
```

## Automated Testing (Optional)

If you want to create automated tests, add to `tests/Feature/`:

```php
// tests/Feature/CoachSalaryTest.php
public function test_coach_salary_payment_creates_expense()
{
    $coach = Coach::factory()->create(['salary' => 50000]);
    
    $response = $this->post(route('coaches.pay-salary', $coach), [
        'amount' => 50000,
        'payment_date' => now(),
        'payment_method' => 'bank_transfer',
    ]);
    
    $this->assertDatabaseHas('expenses', [
        'coach_id' => $coach->id,
        'expense_type' => 'Coach Salary',
        'amount' => 50000,
    ]);
}
```
