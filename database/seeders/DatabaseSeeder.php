<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gym.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create sample members
        $member1 = Member::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+91 9876543210',
            'address' => '123 Main Street, Mumbai',
            'date_of_birth' => '1990-05-15',
            'gender' => 'male',
            'emergency_contact' => 'Jane Doe',
            'emergency_phone' => '+91 9876543211',
            'status' => 'active',
            'joined_date' => Carbon::now()->subMonths(3),
        ]);

        $member2 = Member::create([
            'name' => 'Sarah Smith',
            'email' => 'sarah@example.com',
            'phone' => '+91 9876543212',
            'address' => '456 Park Avenue, Delhi',
            'date_of_birth' => '1985-08-22',
            'gender' => 'female',
            'status' => 'active',
            'joined_date' => Carbon::now()->subMonths(2),
        ]);

        $member3 = Member::create([
            'name' => 'Mike Johnson',
            'phone' => '+91 9876543213',
            'address' => '789 Oak Road, Bangalore',
            'status' => 'active',
            'joined_date' => Carbon::now()->subMonth(),
        ]);

        // Create memberships
        $membership1 = Membership::create([
            'member_id' => $member1->id,
            'plan_name' => 'Premium Annual',
            'plan_description' => 'Full gym access with personal trainer',
            'monthly_fee' => 2500.00,
            'start_date' => $member1->joined_date,
            'duration_months' => 12,
        ]);

        $membership2 = Membership::create([
            'member_id' => $member2->id,
            'plan_name' => 'Basic Monthly',
            'plan_description' => 'Basic gym access',
            'monthly_fee' => 1500.00,
            'start_date' => $member2->joined_date,
            'duration_months' => 6,
        ]);

        $membership3 = Membership::create([
            'member_id' => $member3->id,
            'plan_name' => 'Student Plan',
            'plan_description' => 'Discounted rate for students',
            'monthly_fee' => 1000.00,
            'start_date' => $member3->joined_date,
            'duration_months' => 3,
        ]);

        // Create payments
        Payment::create([
            'member_id' => $member1->id,
            'membership_id' => $membership1->id,
            'amount' => 2500.00,
            'payment_date' => $member1->joined_date,
            'due_date' => $member1->joined_date->copy()->addMonth(),
            'payment_method' => 'card',
            'payment_type' => 'membership_fee',
            'status' => 'paid',
        ]);

        Payment::create([
            'member_id' => $member2->id,
            'membership_id' => $membership2->id,
            'amount' => 1500.00,
            'payment_date' => $member2->joined_date,
            'due_date' => $member2->joined_date->copy()->addMonth(),
            'payment_method' => 'upi',
            'payment_type' => 'membership_fee',
            'status' => 'paid',
        ]);

        // Create some expenses
        Expense::create([
            'title' => 'New Treadmill Purchase',
            'description' => 'Commercial grade treadmill for cardio section',
            'amount' => 85000.00,
            'expense_date' => Carbon::now()->subWeeks(2),
            'category' => 'equipment',
            'payment_method' => 'bank_transfer',
            'vendor_name' => 'Fitness Equipment Co.',
        ]);

        Expense::create([
            'title' => 'Monthly Electricity Bill',
            'description' => 'Electricity charges for the gym',
            'amount' => 12000.00,
            'expense_date' => Carbon::now()->subWeek(),
            'category' => 'utilities',
            'payment_method' => 'bank_transfer',
            'vendor_name' => 'State Electricity Board',
        ]);
    }
}
