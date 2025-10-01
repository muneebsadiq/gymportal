# System Flow Diagrams

## Commission Creation Flow

### BEFORE (Incorrect) ❌
```
┌─────────────────────────────────────────────────────────────┐
│                    Membership Assignment                     │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ Create Assignment│
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ Create Commission│ ◄── WRONG! Created before payment
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ Member May/May   │
                    │ Not Pay Later    │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ Coach Gets       │
                    │ Commission Even  │
                    │ If Not Paid ❌   │
                    └──────────────────┘
```

### AFTER (Correct) ✅
```
┌─────────────────────────────────────────────────────────────┐
│                    Membership Assignment                     │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ Create Assignment│
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ NO Commission    │ ◄── CORRECT! Wait for payment
                    │ Created Yet      │
                    └──────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      Member Makes Payment                    │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ Create Payment   │
                    │ Record           │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ Check: Member    │
                    │ Has Coach?       │
                    └──────────────────┘
                              │
                    ┌─────────┴─────────┐
                    │                   │
                   YES                 NO
                    │                   │
                    ▼                   ▼
          ┌──────────────────┐  ┌──────────────┐
          │ Create Commission│  │ No Commission│
          │ Based on Payment │  │ Created      │
          │ Amount ✅        │  └──────────────┘
          └──────────────────┘
                    │
                    ▼
          ┌──────────────────┐
          │ Commission Linked│
          │ to Payment ID    │
          └──────────────────┘
```

---

## Salary Payment Flow

### Monthly Salary Payment Process
```
┌─────────────────────────────────────────────────────────────┐
│              Admin Opens Coach Details Page                  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ System Checks:   │
                    │ Salary Paid This │
                    │ Month?           │
                    └──────────────────┘
                              │
                ┌─────────────┴─────────────┐
                │                           │
               YES                         NO
                │                           │
                ▼                           ▼
    ┌────────────────────┐      ┌────────────────────┐
    │ Show "Already Paid"│      │ Show Payment Form  │
    │ Badge & Message ✅ │      │ with "Record       │
    │                    │      │ Payment" Button    │
    │ Hide Payment Form  │      └────────────────────┘
    │                    │                  │
    │ Show "View Salary  │                  │
    │ History" Link      │                  ▼
    └────────────────────┘      ┌────────────────────┐
                                │ Admin Fills Form:  │
                                │ - Amount           │
                                │ - Payment Date     │
                                │ - Payment Method   │
                                └────────────────────┘
                                            │
                                            ▼
                                ┌────────────────────┐
                                │ Submit Payment     │
                                └────────────────────┘
                                            │
                                            ▼
                                ┌────────────────────┐
                                │ Create Expense     │
                                │ Record with Type:  │
                                │ "Coach Salary"     │
                                └────────────────────┘
                                            │
                                            ▼
                                ┌────────────────────┐
                                │ Success! Redirect  │
                                │ to Coach Details   │
                                └────────────────────┘
                                            │
                                            ▼
                                ┌────────────────────┐
                                │ Now Shows "Already │
                                │ Paid" for Current  │
                                │ Month ✅           │
                                └────────────────────┘
```

---

## Commission Calculation Display

### Coach Details Page - Commission Breakdown
```
┌───────────────────────────────────────────────────────────────┐
│                    COACH DETAILS PAGE                         │
├───────────────────────────────────────────────────────────────┤
│                                                               │
│  Financial Summary                                            │
│  ┌─────────────┬─────────────┬─────────────┬─────────────┐  │
│  │ Assigned    │ Total Member│ Commission  │ Total Salary│  │
│  │ Members     │ Fees        │ (10%)       │ Paid        │  │
│  │     5       │   15,000    │   1,500     │   50,000    │  │
│  └─────────────┴─────────────┴─────────────┴─────────────┘  │
│                                                               │
│  Commission Calculation Breakdown                             │
│  ┌───────────────────────────────────────────────────────┐   │
│  │ Member         │ Plan    │ Status    │ Monthly Fee   │   │
│  ├───────────────────────────────────────────────────────┤   │
│  │ John Doe       │ Monthly │ ✅ Paid   │ 5,000         │   │
│  │ Jane Smith     │ Monthly │ ❌ Not Paid│ 5,000        │   │
│  │ Bob Wilson     │ Monthly │ ✅ Paid   │ 5,000         │   │
│  │ Alice Brown    │ Quarterly│ ✅ Paid   │ 5,000         │   │
│  │ Charlie Davis  │ Monthly │ ❌ Not Paid│ 3,000        │   │
│  ├───────────────────────────────────────────────────────┤   │
│  │ Total Paid Member Fees:              │ 15,000        │   │
│  │ Commission (10% of 15,000):          │  1,500        │   │
│  └───────────────────────────────────────────────────────┘   │
│                                                               │
│  ⚠️ Note: Commission only calculated for members who have    │
│     paid their fees this month. Unpaid members excluded.     │
│                                                               │
│  Pay Salary                                                   │
│  ┌───────────────────────────────────────────────────────┐   │
│  │ October 2025 - Record salary payment                  │   │
│  │                                                        │   │
│  │ IF ALREADY PAID:                                       │   │
│  │ ┌────────────────────────────────────────────────┐    │   │
│  │ │  ✅ Already Paid                               │    │   │
│  │ │                                                 │    │   │
│  │ │  Salary Already Paid for October 2025          │    │   │
│  │ │  This coach's salary has already been paid.    │    │   │
│  │ │                                                 │    │   │
│  │ │  [View Salary History]                         │    │   │
│  │ └────────────────────────────────────────────────┘    │   │
│  │                                                        │   │
│  │ IF NOT PAID:                                           │   │
│  │ ┌────────────────────────────────────────────────┐    │   │
│  │ │ Basic Salary:     25,000                       │    │   │
│  │ │ Commission:        1,500                       │    │   │
│  │ │ Total Payable:    26,500                       │    │   │
│  │ │                                                 │    │   │
│  │ │ Amount: [26500]                                │    │   │
│  │ │ Payment Date: [2025-10-01]                     │    │   │
│  │ │ Payment Method: [Bank Transfer ▼]             │    │   │
│  │ │ Description: [Salary for October 2025]        │    │   │
│  │ │                                                 │    │   │
│  │ │ [Record Payment as Expense]                    │    │   │
│  │ └────────────────────────────────────────────────┘    │   │
│  └───────────────────────────────────────────────────────┘   │
└───────────────────────────────────────────────────────────────┘
```

---

## Database Relationships

### Entity Relationship Diagram
```
┌──────────────┐
│   COACHES    │
│──────────────│
│ id           │◄─────────┐
│ name         │          │
│ salary       │          │
│ commission_  │          │
│   rate       │          │
└──────────────┘          │
       │                  │
       │ 1:N              │ N:1
       │                  │
       ▼                  │
┌──────────────┐          │
│   MEMBERS    │          │
│──────────────│          │
│ id           │◄─────┐   │
│ name         │      │   │
│ coach_id     │──────┘   │
└──────────────┘          │
       │                  │
       │ 1:N              │
       │                  │
       ▼                  │
┌──────────────┐          │
│  PAYMENTS    │          │
│──────────────│          │
│ id           │◄─────┐   │
│ member_id    │      │   │
│ amount       │      │   │
│ status       │      │   │
│ payment_date │      │   │
└──────────────┘      │   │
       │              │   │
       │ 1:N          │   │
       │              │ N:1
       ▼              │   │
┌──────────────┐      │   │
│ COMMISSIONS  │      │   │
│──────────────│      │   │
│ id           │      │   │
│ coach_id     │──────┼───┘
│ member_id    │      │
│ payment_id   │──────┘ ◄── NEW! Links commission to payment
│ amount       │
│ status       │
└──────────────┘

┌──────────────┐
│   EXPENSES   │
│──────────────│
│ id           │
│ coach_id     │──────┐
│ expense_type │      │ N:1
│ amount       │      │
│ expense_date │      │
└──────────────┘      │
                      │
                      ▼
              ┌──────────────┐
              │   COACHES    │
              └──────────────┘
```

---

## Key Points Summary

### ✅ What's Working Now
1. **Commission on Payment**: Commissions created only when member pays
2. **Payment Tracking**: Each commission linked to specific payment
3. **Duplicate Prevention**: Can't pay coach salary twice in same month
4. **Clear UI**: "Already Paid" message when salary paid
5. **Accurate Calculation**: Only paid member fees count toward commission

### 🔄 Process Changes
- **Before**: Assign plan → Create commission → Maybe get paid
- **After**: Assign plan → Get paid → Create commission

### 📊 Data Integrity
- Transactions ensure atomicity
- Foreign keys maintain referential integrity
- Null values handled gracefully for old data

### 🎯 Business Logic
- Commission = Payment Amount × Commission Rate
- Only 'paid' and 'partial' payments generate commissions
- One salary payment per coach per month maximum
