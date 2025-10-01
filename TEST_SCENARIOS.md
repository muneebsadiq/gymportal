# Test Scenarios for Commission and Salary Payment System

## Scenario 1: Commission Creation on Payment (Not Assignment)

### Test Steps:
1. **Create/Select a Coach**
   - Navigate to Coaches
   - Ensure coach has a commission rate set (e.g., 10%)
   - Note the coach ID

2. **Create/Select a Member**
   - Navigate to Members
   - Assign the member to the coach created above
   - Note the member ID

3. **Assign Membership Plan**
   - Go to member details
   - Assign a membership plan (e.g., Monthly - 5000)
   - **Expected**: No commission should be created yet
   - **Verify**: Check `commissions` table - should have no new entry

4. **Record Payment**
   - Navigate to Payments → Create Payment
   - Select the member
   - Payment type: Membership Fee
   - Amount: 5000
   - Payment date: Today
   - Payment method: Cash
   - Submit payment
   - **Expected**: Commission should now be created
   - **Verify**: Check `commissions` table - should have new entry with:
     - `coach_id`: The coach's ID
     - `member_id`: The member's ID
     - `payment_id`: The payment's ID
     - `amount`: 500 (10% of 5000)
     - `status`: 'unpaid'

### SQL Verification:
```sql
-- Check if commission was created
SELECT * FROM commissions WHERE payment_id = [PAYMENT_ID];

-- Verify commission amount
SELECT c.*, p.amount as payment_amount, co.commission_rate 
FROM commissions c
JOIN payments p ON c.payment_id = p.id
JOIN coaches co ON c.coach_id = co.id
WHERE p.id = [PAYMENT_ID];
```

---

## Scenario 2: Commission Only on Paid Fees

### Test Steps:
1. **Partial Payment**
   - Create a payment with amount less than plan fee
   - Status should be 'partial'
   - **Expected**: Commission created based on partial amount
   - **Example**: Plan fee = 5000, Payment = 2000
   - **Commission**: 10% of 2000 = 200

2. **Second Payment to Complete**
   - Create another payment for remaining amount (3000)
   - **Expected**: Another commission created for this payment
   - **Commission**: 10% of 3000 = 300
   - **Total commissions**: 200 + 300 = 500 (10% of total 5000)

### SQL Verification:
```sql
-- Check all commissions for a member
SELECT c.*, p.amount as payment_amount 
FROM commissions c
JOIN payments p ON c.payment_id = p.id
WHERE c.member_id = [MEMBER_ID]
ORDER BY c.created_at;
```

---

## Scenario 3: Coach Salary Payment - Already Paid Check

### Test Steps:
1. **First Salary Payment**
   - Navigate to Coaches → Select a coach → View details
   - Scroll to "Pay Salary" section
   - Note the total payable amount (salary + commission)
   - Fill in payment details:
     - Amount: [calculated amount]
     - Payment date: Today
     - Payment method: Bank Transfer
   - Click "Record Payment as Expense"
   - **Expected**: Success message, payment recorded

2. **Verify "Already Paid" Status**
   - Refresh the coach details page
   - **Expected**: 
     - Green "Already Paid" badge visible at top of Pay Salary section
     - Payment form should be HIDDEN
     - "Already Paid" message displayed with checkmark icon
     - Message: "Salary Already Paid for [Current Month Year]"
     - "View Salary History" button visible

3. **Verify No Duplicate Payment Option**
   - Try to access the page again
   - **Expected**: Same "Already Paid" view, no way to pay again for current month

4. **Next Month Test**
   - Change system date to next month (or wait for next month)
   - Visit coach details page
   - **Expected**: Payment form should be visible again for new month

### SQL Verification:
```sql
-- Check salary payments for current month
SELECT * FROM expenses 
WHERE coach_id = [COACH_ID] 
AND expense_type = 'Coach Salary'
AND YEAR(expense_date) = YEAR(CURDATE())
AND MONTH(expense_date) = MONTH(CURDATE());
```

---

## Scenario 4: Commission Calculation Display

### Test Steps:
1. **Navigate to Coach Details**
   - Go to coach with assigned members
   - Check "Commission Calculation Breakdown" section

2. **Verify Breakdown Table**
   - **Expected columns**:
     - Member name
     - Membership plan
     - Payment status (Paid/Not Paid)
     - Monthly fee
   
3. **Verify Calculation Logic**
   - Members who PAID this month: Fee included, shown with green "Paid" badge
   - Members who NOT paid: Fee shown with strikethrough, red "Not Paid" badge
   - Total only includes PAID member fees
   - Commission = Total Paid Fees × Commission Rate

4. **Test with Mixed Payments**
   - Have 3 members assigned to coach:
     - Member A: Paid this month (5000)
     - Member B: Not paid this month (5000)
     - Member C: Paid this month (3000)
   - **Expected calculation**:
     - Total Paid Fees: 8000 (only A + C)
     - Commission (10%): 800
     - Member B's 5000 should be excluded

---

## Scenario 5: Edge Cases

### Test Case 5.1: Member Without Coach
- Create payment for member with no coach assigned
- **Expected**: No commission created
- **Verify**: No error, payment processed normally

### Test Case 5.2: Coach Without Commission Rate
- Assign member to coach with commission_rate = 0 or NULL
- Record payment
- **Expected**: No commission created
- **Verify**: Payment processed, no commission entry

### Test Case 5.3: Non-Membership Payment
- Record payment with type "Admission Fee" or "Other"
- **Expected**: No commission created
- **Verify**: Payment recorded, no commission

### Test Case 5.4: Payment Update/Delete
- Create payment (commission created)
- Delete the payment
- **Expected**: Commission should be deleted (cascade) or nullified
- **Verify**: Check `payment_id` in commissions table

---

## Automated Test Commands

```bash
# Clear cache before testing
php artisan config:clear
php artisan cache:clear

# Check routes are working
php artisan route:list --path=coaches
php artisan route:list --path=payments

# Run migrations (if needed)
php artisan migrate:fresh --seed

# Check for syntax errors
php -l app/Http/Controllers/PaymentController.php
php -l app/Http/Controllers/CoachController.php
php -l app/Models/Commission.php
php -l app/Models/Payment.php
```

---

## Expected Database State After Tests

### Commissions Table
```
| id | coach_id | member_id | payment_id | amount | status  | commission_date |
|----|----------|-----------|------------|--------|---------|-----------------|
| 1  | 1        | 1         | 1          | 500.00 | unpaid  | 2025-10-01      |
| 2  | 1        | 1         | 2          | 200.00 | unpaid  | 2025-10-01      |
| 3  | 1        | 2         | 3          | 300.00 | unpaid  | 2025-10-01      |
```

### Expenses Table (Salary Payments)
```
| id | coach_id | expense_type  | amount   | expense_date |
|----|----------|---------------|----------|--------------|
| 1  | 1        | Coach Salary  | 25500.00 | 2025-10-01   |
```

Note: Only ONE salary payment per coach per month should exist.
