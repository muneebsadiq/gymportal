# Quick Start Guide - New Features

## 🚀 Getting Started

### 1. Apply Database Changes
```bash
cd /var/www/html/gymportal
php artisan migrate
php artisan cache:clear
```

### 2. Verify Installation
Check that migrations ran successfully:
```bash
php artisan migrate:status
```

You should see these three migrations as "Ran":
- `2025_10_01_000000_add_salary_and_commission_rate_to_coaches`
- `2025_10_01_000100_create_commissions_table`
- `2025_10_01_000200_add_expense_type_to_expenses_table`

## 📋 Feature Overview

### ✅ Coach Salary Management
**What it does:** Track and record monthly salary payments for coaches

**How to use:**
1. Go to **Coaches** → **Create/Edit Coach**
2. Set **Monthly Salary** (e.g., 50000)
3. Set **Commission Rate** (e.g., 5 for 5%)
4. Save the coach
5. On coach detail page, use **Pay Salary** form to record payments
6. Payments automatically appear in **Expenses** with type "Coach Salary"

### ✅ Commission Tracking
**What it does:** Automatically calculate and track commissions when members are assigned to coaches

**How it works:**
1. Assign a member to a coach (in member edit page)
2. When you assign a membership plan to that member, commission is auto-calculated
3. Formula: `Commission = (Plan Fee × Coach Commission Rate) / 100`
4. View commissions on coach detail page

**Example:**
- Member assigned to Coach A (commission rate: 5%)
- Member gets "Premium Plan" (fee: 10,000)
- Commission created: 10,000 × 5% = 500

### ✅ Due Fees Filter
**What it does:** Quickly find members with overdue membership fees

**How to use:**
1. Go to **Members** page
2. In filters section, select **Fee Status** → **Due Fees Only**
3. Click **Filter**
4. See only members with expired membership plans
5. "Due Fees" badge appears on member cards

### ✅ Active Plan Display
**What it does:** Show member's current active membership plan

**Where to see:**
- Members list page (next to member info)
- Dashboard (in member details)
- Due fees modal

## 🎯 Common Workflows

### Workflow 1: Onboard New Coach
```
1. Navigate to /coaches/create
2. Fill in basic info (name, phone, email)
3. Set Monthly Salary: 50000
4. Set Commission Rate: 5
5. Set Status: Active
6. Click Save
```

### Workflow 2: Pay Coach Salary
```
1. Navigate to /coaches
2. Click "View" on coach
3. Scroll to "Pay Salary" section
4. Verify amount (pre-filled with salary)
5. Select payment date and method
6. Add description (optional)
7. Click "Record Salary Payment"
8. ✓ Expense automatically created
```

### Workflow 3: Assign Member to Coach
```
1. Navigate to /members/{member}/edit
2. Select coach from "Coach" dropdown
3. Click Save
4. Now when you assign membership plan:
   → Commission automatically calculated
   → Commission record created
   → Visible on coach detail page
```

### Workflow 4: Find Members with Due Fees
```
1. Navigate to /members
2. Select "Due Fees Only" from Fee Status filter
3. Click Filter
4. See all members with overdue fees
5. Click "Collect Fee" to record payment
```

### Workflow 5: View Financial Summary
```
1. Navigate to /coaches/{coach}
2. See three cards:
   - Total Commissions (all time)
   - Unpaid Commissions (pending)
   - Total Salary Paid (all time)
3. Scroll down to see assigned members
```

## 📊 Reports & Analytics

### Expense Reports
- Navigate to **Reports** → **Expenses**
- Filter by category: "salaries"
- See all coach salary payments
- Export to PDF/Excel (if implemented)

### Coach Performance
- View coach detail page
- Check "Assigned Members" count
- Review commission earnings
- Compare salary vs commission ratio

### Due Fees Dashboard
- Dashboard shows "Due Fees" count
- Click on count to see modal with list
- Quick access to collect fees

## 🔍 Where to Find Things

| Feature | Location | URL |
|---------|----------|-----|
| Coach List | Coaches menu | `/coaches` |
| Add Coach | Coaches → Add | `/coaches/create` |
| Coach Details | Coaches → View | `/coaches/{id}` |
| Pay Salary | Coach Details page | `/coaches/{id}` (scroll down) |
| Members with Due Fees | Members → Filter | `/members?due_fees=1` |
| Expense Reports | Reports → Expenses | `/reports/expenses` |
| Dashboard | Home | `/` |

## 💡 Tips & Best Practices

### For Salary Management
- ✅ Record salary payments on the same date each month
- ✅ Use consistent payment methods for tracking
- ✅ Add descriptions for audit trail
- ✅ Review expense reports monthly

### For Commissions
- ✅ Set commission rates when creating coaches
- ✅ Review unpaid commissions regularly
- ✅ Include commission in total compensation calculations
- ✅ Assign members to coaches before creating membership plans

### For Due Fees
- ✅ Check due fees filter daily/weekly
- ✅ Contact members before fees become overdue
- ✅ Use "Collect Fee" button for quick payment recording
- ✅ Update membership plans after payment

## 🐛 Troubleshooting

### Commission not created?
**Check:**
- ✓ Member has coach assigned (coach_id field)
- ✓ Coach has commission_rate > 0
- ✓ Membership plan has fee > 0

### Due fees filter shows no results?
**Check:**
- ✓ Members have membership assignments
- ✓ Assignment end_date is in the past
- ✓ Assignment status is not 'cancelled'

### Salary payment not in expenses?
**Check:**
- ✓ Form submitted successfully (check for validation errors)
- ✓ Navigate to /expenses and search for coach name
- ✓ Filter by category "salaries"

### Active plan not showing?
**Check:**
- ✓ Member has active membership assignment
- ✓ Today's date is between start_date and end_date
- ✓ Assignment status is 'active'

## 📞 Need Help?

1. **Check Logs**: `storage/logs/laravel.log`
2. **Review Documentation**: 
   - `FEATURE_UPDATE_SUMMARY.md` - Detailed technical info
   - `DEPLOYMENT_GUIDE.md` - Deployment and testing
3. **Database Check**: Use phpMyAdmin or MySQL CLI to verify data
4. **Clear Cache**: Run `php artisan cache:clear` if changes not appearing

## 🎓 Training Checklist

### For Administrators
- [ ] Create test coach with salary and commission
- [ ] Record test salary payment
- [ ] Verify expense appears in reports
- [ ] Assign test member to coach
- [ ] Create membership plan assignment
- [ ] Verify commission created
- [ ] Test due fees filter
- [ ] Review financial summaries

### For Staff
- [ ] Learn to assign members to coaches
- [ ] Understand commission calculation
- [ ] Use due fees filter daily
- [ ] Know how to collect fees
- [ ] View member's active plan

## 📈 Success Metrics

After 1 week, you should see:
- ✅ All coaches have salary and commission rate set
- ✅ Salary payments recorded monthly
- ✅ Commissions tracking for new member assignments
- ✅ Due fees filter used regularly
- ✅ Reduced overdue fees

## 🔄 Regular Tasks

### Daily
- Check due fees filter
- Follow up with members having due fees

### Weekly
- Review unpaid commissions
- Verify salary payment schedule

### Monthly
- Record all coach salary payments
- Generate expense reports
- Review commission totals
- Analyze member retention

## 🚨 Important Notes

1. **Backup First**: Always backup database before migrations
2. **Test Environment**: Test features in staging before production
3. **User Training**: Train staff before going live
4. **Monitor Logs**: Check logs for first few days
5. **Gather Feedback**: Ask users for improvement suggestions

## ✨ What's Next?

Future enhancements could include:
- Commission payment tracking (mark as paid)
- Bulk salary payments
- Automated payment reminders
- Commission rate history
- Advanced financial reports
- Mobile app integration

---

**Version**: 1.0  
**Last Updated**: 2025-10-01  
**Laravel Version**: 11.x  
**PHP Version**: 8.1+
