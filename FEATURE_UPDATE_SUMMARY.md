# Gym Management System - Feature Update Summary

## Overview
This document summarizes the coach salary management, commission tracking, due fees filtering, and expense integration features added to the Laravel Gym Management System.

## Database Changes

### New Migrations Created
1. **2025_10_01_000000_add_salary_and_commission_rate_to_coaches.php**
   - Adds `salary` (decimal) and `commission_rate` (decimal) columns to `coaches` table

2. **2025_10_01_000100_create_commissions_table.php**
   - Creates `commissions` table with:
     - `coach_id` (foreign key to coaches)
     - `member_id` (foreign key to members)
     - `member_membership_plan_id` (foreign key to member_membership_plan)
     - `amount` (decimal)
     - `commission_date` (date)
     - `status` (enum: unpaid, paid)
     - `description` (string)

3. **2025_10_01_000200_add_expense_type_to_expenses_table.php**
   - Adds `expense_type` (string) column to `expenses` table
   - Adds `coach_id` (foreign key to coaches) to `expenses` table

### To Apply Migrations
```bash
cd /var/www/html/gymportal
php artisan migrate
```

## New Models

### Commission Model
- **Location**: `app/Models/Commission.php`
- **Relationships**:
  - `belongsTo(Coach::class)`
  - `belongsTo(Member::class)`
  - `belongsTo(MemberMembershipPlan::class)`
- **Fillable**: coach_id, member_id, member_membership_plan_id, amount, commission_date, status, description

## Updated Models

### Coach Model
- **Added Fillable**: salary, commission_rate
- **Added Casts**: salary (decimal:2), commission_rate (decimal:2)
- **New Relationships**:
  - `commissions()` - hasMany(Commission::class)
  - `expenses()` - hasMany(Expense::class)

### Member Model
- **New Relationship**: `commissions()` - hasMany(Commission::class)
- **New Scope**: `scopeWithDueFees($query)` - filters members with overdue fees
- **New Accessor**: `getActivePlanAttribute()` - returns currently active membership plan

### Expense Model
- **Added Fillable**: expense_type, coach_id
- **New Relationship**: `coach()` - belongsTo(Coach::class)

## New Controllers

### CoachSalaryController
- **Location**: `app/Http/Controllers/CoachSalaryController.php`
- **Methods**:
  - `paySalary(Request $request, Coach $coach)` - Records salary payment as expense
  - `salaryHistory(Coach $coach)` - Shows salary payment history

## Updated Controllers

### CoachController
- **Updated Methods**:
  - `store()` - Added validation for salary and commission_rate
  - `update()` - Added validation for salary and commission_rate
  - `show()` - Loads commissions, expenses, and calculates financial summaries

### MemberController
- **Updated Methods**:
  - `index()` - Added due_fees filter support, loads memberMembershipPlans relationship

### MemberMembershipPlanController
- **Updated Methods**:
  - `store()` - Automatically creates commission record when member is assigned to coach

### DashboardController
- **Updated Methods**:
  - `index()` - Loads memberMembershipPlans for due fees calculation
  - `getDueFeesModal()` - Loads memberMembershipPlans for modal display

## Routes Added

```php
// Coach Salary Management
Route::post('coaches/{coach}/pay-salary', [CoachSalaryController::class, 'paySalary'])->name('coaches.pay-salary');
Route::get('coaches/{coach}/salary-history', [CoachSalaryController::class, 'salaryHistory'])->name('coaches.salary-history');
```

## View Updates

### Members Index (`resources/views/members/index.blade.php`)
- Added "Fee Status" filter dropdown with "Due Fees Only" option
- Updated grid layout from 4 to 5 columns to accommodate new filter
- Added display of active plan name from `$member->active_plan`

### Coach Views

#### Create (`resources/views/coaches/create.blade.php`)
- Added "Monthly Salary" input field
- Added "Commission Rate (%)" input field with helper text

#### Edit (`resources/views/coaches/edit.blade.php`)
- Added "Monthly Salary" input field
- Added "Commission Rate (%)" input field with helper text

#### Show (`resources/views/coaches/show.blade.php`)
- Added salary and commission_rate display in details section
- Added financial summary cards showing:
  - Total Commissions
  - Unpaid Commissions
  - Total Salary Paid
- Added "Pay Salary" form with fields:
  - Amount (pre-filled with coach salary)
  - Payment Date
  - Payment Method
  - Description
- Added "Assigned Members" list section

## Business Logic

### Commission Creation
- When a member is assigned to a coach (via `MemberMembershipPlanController@store`):
  1. System checks if member has a coach assigned
  2. If coach has a commission_rate set
  3. Calculates commission: `(plan_fee * commission_rate) / 100`
  4. Creates commission record with status 'unpaid'

### Salary Payment
- When salary is paid via coach show page:
  1. Creates expense record with:
     - `category` = 'salaries'
     - `expense_type` = 'Coach Salary'
     - `coach_id` = linked to coach
     - `title` = 'Coach Salary - {coach_name}'
     - `vendor_name` = coach name
  2. Expense automatically appears in expense reports
  3. Contributes to monthly expense calculations

### Due Fees Logic
- Member has due fees if:
  - Any non-cancelled membership assignment has `end_date` in the past
- Due fees filter uses `Member::scopeWithDueFees()` scope
- Dashboard shows count of members with due fees
- Members list shows "Due Fees" badge for affected members

### Active Plan Display
- `Member::getActivePlanAttribute()` returns:
  - MembershipPlan where assignment is active
  - start_date <= today
  - end_date >= today
- Displayed on dashboard and members list

## Validation Rules

### Coach Salary/Commission
```php
'salary' => 'nullable|numeric|min:0'
'commission_rate' => 'nullable|numeric|min:0|max:100'
```

### Salary Payment
```php
'amount' => 'required|numeric|min:0'
'payment_date' => 'required|date'
'payment_method' => 'required|in:cash,card,bank_transfer,cheque,other'
'description' => 'nullable|string|max:500'
```

## Testing the Features

### 1. Test Coach Salary Management
```bash
# Create/Edit a coach with salary and commission rate
# Visit coach show page
# Fill salary payment form and submit
# Verify expense record created in expenses list
# Check expense has expense_type = 'Coach Salary'
```

### 2. Test Commission Creation
```bash
# Assign a member to a coach (member must have coach_id set)
# Create a membership plan assignment for that member
# Check commissions table for new record
# Visit coach show page to see commission totals
```

### 3. Test Due Fees Filter
```bash
# Create member with membership assignment that has end_date in past
# Visit members list
# Select "Due Fees Only" from Fee Status filter
# Click Filter button
# Verify only members with overdue fees are shown
```

### 4. Test Active Plan Display
```bash
# Create member with active membership plan assignment
# Visit members list
# Verify plan name appears next to member info
# Visit dashboard
# Check member's active plan is displayed
```

## Reports Integration

Coach salary expenses automatically appear in:
- Expense reports (filtered by category='salaries' or expense_type='Coach Salary')
- Monthly expense calculations on dashboard
- Expense by category breakdowns

## Security Considerations

- All routes are protected by `auth` middleware
- Foreign key constraints ensure data integrity
- Cascade deletes configured for commissions when coach/member deleted
- Validation prevents negative amounts and invalid percentages

## Future Enhancements (Not Implemented)

- Commission payment tracking (mark commissions as paid)
- Bulk salary payment for multiple coaches
- Automated salary payment reminders
- Commission rate history tracking
- Salary payment approval workflow
- Export salary/commission reports to PDF/Excel

## Files Modified

### Models
- `app/Models/Coach.php`
- `app/Models/Member.php`
- `app/Models/Expense.php`

### Controllers
- `app/Http/Controllers/CoachController.php`
- `app/Http/Controllers/MemberController.php`
- `app/Http/Controllers/MemberMembershipPlanController.php`
- `app/Http/Controllers/DashboardController.php`

### Views
- `resources/views/coaches/create.blade.php`
- `resources/views/coaches/edit.blade.php`
- `resources/views/coaches/show.blade.php`
- `resources/views/members/index.blade.php`

### Routes
- `routes/web.php`

### Migrations (New)
- `database/migrations/2025_10_01_000000_add_salary_and_commission_rate_to_coaches.php`
- `database/migrations/2025_10_01_000100_create_commissions_table.php`
- `database/migrations/2025_10_01_000200_add_expense_type_to_expenses_table.php`

### Models (New)
- `app/Models/Commission.php`

### Controllers (New)
- `app/Http/Controllers/CoachSalaryController.php`

## Deployment Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Clear application cache: `php artisan cache:clear`
- [ ] Clear config cache: `php artisan config:clear`
- [ ] Clear view cache: `php artisan view:clear`
- [ ] Test all new features in staging environment
- [ ] Verify existing functionality still works
- [ ] Update user documentation
- [ ] Train staff on new features

## Support

For issues or questions, refer to the Laravel documentation or contact the development team.
