# Deployment Guide - Coach Salary & Commission Features

## Prerequisites
- PHP 8.1+
- MySQL 5.7+
- Composer installed
- Laravel 11.x
- Existing gym portal database

## Step 1: Backup Database
```bash
# Create a backup before running migrations
mysqldump -u your_user -p your_database > backup_$(date +%Y%m%d_%H%M%S).sql
```

## Step 2: Run Migrations
```bash
cd /var/www/html/gymportal
php artisan migrate
```

Expected output:
```
Migrating: 2025_10_01_000000_add_salary_and_commission_rate_to_coaches
Migrated:  2025_10_01_000000_add_salary_and_commission_rate_to_coaches (XX.XXms)
Migrating: 2025_10_01_000100_create_commissions_table
Migrated:  2025_10_01_000100_create_commissions_table (XX.XXms)
Migrating: 2025_10_01_000200_add_expense_type_to_expenses_table
Migrated:  2025_10_01_000200_add_expense_type_to_expenses_table (XX.XXms)
```

## Step 3: Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## Step 4: Verify Database Schema

### Check coaches table
```sql
DESCRIBE coaches;
-- Should show: salary (decimal), commission_rate (decimal)
```

### Check commissions table
```sql
DESCRIBE commissions;
-- Should show: id, coach_id, member_id, member_membership_plan_id, amount, commission_date, status, description, timestamps
```

### Check expenses table
```sql
DESCRIBE expenses;
-- Should show: expense_type (varchar), coach_id (bigint unsigned)
```

## Step 5: Test Features

### Test 1: Create Coach with Salary
1. Navigate to `/coaches/create`
2. Fill in:
   - Name: "Test Coach"
   - Phone: "1234567890"
   - Email: "test@coach.com"
   - Specialization: "Fitness"
   - Join Date: Today
   - Status: Active
   - Monthly Salary: 50000
   - Commission Rate: 5
3. Click Save
4. Verify coach appears in list with salary and commission displayed

### Test 2: Pay Coach Salary
1. Navigate to coach detail page
2. Scroll to "Pay Salary" section
3. Fill in:
   - Amount: 50000 (pre-filled)
   - Payment Date: Today
   - Payment Method: Bank Transfer
   - Description: "Monthly salary for October"
4. Click "Record Salary Payment"
5. Verify success message
6. Navigate to `/expenses`
7. Verify expense record exists with:
   - Title: "Coach Salary - Test Coach"
   - Category: salaries
   - Expense Type: Coach Salary
   - Amount: 50000

### Test 3: Commission Creation
1. Create/Edit a member and assign them to a coach
2. Navigate to member detail page
3. Assign a membership plan (e.g., Monthly Plan - 5000)
4. Fill in start date and end date
5. Submit the form
6. Check database:
   ```sql
   SELECT * FROM commissions WHERE member_id = [member_id];
   ```
7. Verify commission record created with:
   - amount = (5000 * 5) / 100 = 250
   - status = 'unpaid'
8. Navigate to coach detail page
9. Verify "Total Commissions" and "Unpaid Commissions" show correct values

### Test 4: Due Fees Filter
1. Create a member with membership plan that has end_date in the past
2. Navigate to `/members`
3. Select "Due Fees Only" from Fee Status dropdown
4. Click Filter
5. Verify only members with overdue fees are displayed
6. Verify "Due Fees" badge appears on member card

### Test 5: Active Plan Display
1. Create a member with active membership plan (start_date <= today, end_date >= today)
2. Navigate to `/members`
3. Verify plan name appears next to member info
4. Navigate to dashboard
5. Verify member's active plan is displayed in due fees modal (if applicable)

### Test 6: Expense Reports
1. Navigate to `/reports/expenses`
2. Filter by category: "salaries"
3. Verify coach salary payments appear
4. Check that expense_type shows "Coach Salary"
5. Verify coach name appears in vendor_name

## Step 6: Verify Relationships

### Test Foreign Keys
```sql
-- Test coach deletion (should cascade to commissions)
DELETE FROM coaches WHERE id = [test_coach_id];
SELECT * FROM commissions WHERE coach_id = [test_coach_id];
-- Should return 0 rows

-- Test member deletion (should cascade to commissions)
DELETE FROM members WHERE id = [test_member_id];
SELECT * FROM commissions WHERE member_id = [test_member_id];
-- Should return 0 rows
```

## Step 7: Performance Check

### Check Query Performance
```sql
-- Check members with due fees query
EXPLAIN SELECT * FROM members 
WHERE id IN (
    SELECT member_id FROM member_membership_plan 
    WHERE status != 'cancelled' 
    AND end_date < NOW()
);

-- Add index if needed
CREATE INDEX idx_mmp_due_fees ON member_membership_plan(status, end_date, member_id);
```

## Rollback Instructions (If Needed)

If you need to rollback the migrations:

```bash
php artisan migrate:rollback --step=3
```

This will rollback the last 3 migrations:
1. add_expense_type_to_expenses_table
2. create_commissions_table
3. add_salary_and_commission_rate_to_coaches

## Troubleshooting

### Issue: Migration fails with foreign key constraint error
**Solution**: Ensure all referenced tables exist and have proper primary keys
```bash
php artisan migrate:status
# Check if all previous migrations are applied
```

### Issue: Commission not created when assigning member
**Solution**: 
1. Verify member has coach_id set
2. Verify coach has commission_rate > 0
3. Check logs: `tail -f storage/logs/laravel.log`

### Issue: Salary payment not appearing in expenses
**Solution**:
1. Check if expense was created: `SELECT * FROM expenses WHERE expense_type = 'Coach Salary'`
2. Verify coach_id is set in expense record
3. Check validation errors in session

### Issue: Due fees filter not working
**Solution**:
1. Verify member has membership assignments with end_date < today
2. Check status is not 'cancelled'
3. Test query directly:
```sql
SELECT m.* FROM members m
INNER JOIN member_membership_plan mmp ON m.id = mmp.member_id
WHERE mmp.status != 'cancelled' AND mmp.end_date < NOW();
```

### Issue: Active plan not showing
**Solution**:
1. Verify membership assignment dates:
   - start_date <= today
   - end_date >= today
   - status = 'active'
2. Check relationship is loaded: `$member->load('memberMembershipPlans.membershipPlan')`

## Security Checklist

- [ ] All routes protected by auth middleware
- [ ] CSRF tokens present in all forms
- [ ] Input validation implemented
- [ ] SQL injection prevented (using Eloquent)
- [ ] XSS prevention (Blade escaping)
- [ ] Foreign key constraints in place
- [ ] Soft deletes considered for audit trail (optional)

## Performance Optimization

### Add Indexes (Optional)
```sql
-- Index for due fees queries
CREATE INDEX idx_mmp_status_dates ON member_membership_plan(status, end_date, start_date);

-- Index for commission queries
CREATE INDEX idx_commissions_coach_status ON commissions(coach_id, status);

-- Index for expense queries
CREATE INDEX idx_expenses_type_coach ON expenses(expense_type, coach_id);
```

### Enable Query Caching (Optional)
```php
// In config/database.php
'options' => [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET SESSION sql_mode="STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION"',
],
```

## Monitoring

### Key Metrics to Monitor
1. Number of salary payments per month
2. Total commissions generated
3. Unpaid commissions amount
4. Members with due fees count
5. Expense report accuracy

### Logging
All salary payments and commission creations are logged in Laravel logs:
```bash
tail -f storage/logs/laravel.log | grep -i "salary\|commission"
```

## User Training

### For Administrators
1. How to add/edit coach salary and commission rate
2. How to record salary payments
3. How to view commission reports
4. How to filter members with due fees

### For Staff
1. How to assign members to coaches
2. How membership assignments trigger commissions
3. How to view member's active plan
4. How to collect due fees

## Support

For technical issues:
- Check Laravel logs: `storage/logs/laravel.log`
- Check web server logs: `/var/log/nginx/error.log` or `/var/log/apache2/error.log`
- Enable debug mode (only in development): Set `APP_DEBUG=true` in `.env`

For feature requests or bugs:
- Document the issue with screenshots
- Include steps to reproduce
- Check database state before and after

## Next Steps

After successful deployment:
1. Train staff on new features
2. Update user documentation
3. Monitor system for 1-2 weeks
4. Gather user feedback
5. Plan next iteration improvements

## Maintenance

### Monthly Tasks
- Review unpaid commissions
- Verify salary payment records
- Check for members with long-overdue fees
- Generate financial reports

### Quarterly Tasks
- Audit commission calculations
- Review expense categorization
- Optimize database queries if needed
- Update documentation based on user feedback
