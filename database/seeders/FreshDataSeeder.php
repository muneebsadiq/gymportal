<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\Coach;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\MembershipPlan;
use App\Models\MemberMembershipPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FreshDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Payment::truncate();
        MemberMembershipPlan::truncate();
        Member::truncate();
        Expense::truncate();
        Coach::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        echo "✓ Cleared all existing data\n";
        
        // Create 4 Coaches
        $coaches = [
            [
                'name' => 'Ahmed Khan',
                'phone' => '0300-1234567',
                'email' => 'ahmed.khan@gym.com',
                'specialization' => 'Strength Training',
                'join_date' => Carbon::now()->subMonths(6),
                'status' => 'active',
                'salary' => 50000,
                'commission_rate' => 10,
            ],
            [
                'name' => 'Sara Ali',
                'phone' => '0301-2345678',
                'email' => 'sara.ali@gym.com',
                'specialization' => 'Cardio & Weight Loss',
                'join_date' => Carbon::now()->subMonths(4),
                'status' => 'active',
                'salary' => 45000,
                'commission_rate' => 8,
            ],
            [
                'name' => 'Hassan Raza',
                'phone' => '0302-3456789',
                'email' => 'hassan.raza@gym.com',
                'specialization' => 'CrossFit',
                'join_date' => Carbon::now()->subMonths(8),
                'status' => 'active',
                'salary' => 55000,
                'commission_rate' => 12,
            ],
            [
                'name' => 'Fatima Malik',
                'phone' => '0303-4567890',
                'email' => 'fatima.malik@gym.com',
                'specialization' => 'Yoga & Flexibility',
                'join_date' => Carbon::now()->subMonths(3),
                'status' => 'active',
                'salary' => 40000,
                'commission_rate' => 10,
            ],
        ];

        foreach ($coaches as $coachData) {
            Coach::create($coachData);
        }
        
        echo "✓ Created 4 coaches\n";
        
        // Get all coaches
        $allCoaches = Coach::all();
        
        // Create 4 Members
        $members = [
            [
                'name' => 'Ali Haider',
                'phone' => '0321-1111111',
                'email' => 'ali.haider@email.com',
                'date_of_birth' => Carbon::now()->subYears(25),
                'gender' => 'male',
                'address' => 'House 123, Block A, DHA, Lahore',
                'emergency_contact' => '0322-1111111',
                'joined_date' => Carbon::now()->subMonths(2),
                'status' => 'active',
                'coach_id' => $allCoaches[0]->id,
            ],
            [
                'name' => 'Ayesha Siddiqui',
                'phone' => '0321-2222222',
                'email' => 'ayesha.siddiqui@email.com',
                'date_of_birth' => Carbon::now()->subYears(28),
                'gender' => 'female',
                'address' => 'Flat 45, Gulberg, Lahore',
                'emergency_contact' => '0322-2222222',
                'joined_date' => Carbon::now()->subMonths(1),
                'status' => 'active',
                'coach_id' => $allCoaches[1]->id,
            ],
            [
                'name' => 'Usman Ahmed',
                'phone' => '0321-3333333',
                'email' => 'usman.ahmed@email.com',
                'date_of_birth' => Carbon::now()->subYears(30),
                'gender' => 'male',
                'address' => 'House 789, Johar Town, Lahore',
                'emergency_contact' => '0322-3333333',
                'joined_date' => Carbon::now()->subMonths(3),
                'status' => 'active',
                'coach_id' => $allCoaches[2]->id,
            ],
            [
                'name' => 'Zainab Hassan',
                'phone' => '0321-4444444',
                'email' => 'zainab.hassan@email.com',
                'date_of_birth' => Carbon::now()->subYears(22),
                'gender' => 'female',
                'address' => 'House 456, Model Town, Lahore',
                'emergency_contact' => '0322-4444444',
                'joined_date' => Carbon::now()->subWeeks(2),
                'status' => 'active',
                'coach_id' => $allCoaches[3]->id,
            ],
        ];

        $createdMembers = [];
        foreach ($members as $memberData) {
            $createdMembers[] = Member::create($memberData);
        }
        
        echo "✓ Created 4 members\n";
        
        // Create or get membership plans
        $monthlyPlan = MembershipPlan::firstOrCreate(
            ['name' => 'Monthly'],
            [
                'duration_type' => 'monthly',
                'duration_value' => 1,
                'fee' => 5000,
                'description' => 'Monthly membership plan',
                'status' => 'active',
            ]
        );
        
        $quarterlyPlan = MembershipPlan::firstOrCreate(
            ['name' => 'Quarterly'],
            [
                'duration_type' => 'monthly',
                'duration_value' => 3,
                'fee' => 13500,
                'description' => 'Quarterly membership plan (3 months)',
                'status' => 'active',
            ]
        );
        
        echo "✓ Ensured membership plans exist\n";
        
        // Assign membership plans to members
        $membershipAssignments = [
            [
                'member' => $createdMembers[0],
                'plan' => $monthlyPlan,
                'start_date' => Carbon::now()->subMonths(1),
            ],
            [
                'member' => $createdMembers[1],
                'plan' => $quarterlyPlan,
                'start_date' => Carbon::now()->subWeeks(3),
            ],
            [
                'member' => $createdMembers[2],
                'plan' => $monthlyPlan,
                'start_date' => Carbon::now()->subMonths(2),
            ],
            [
                'member' => $createdMembers[3],
                'plan' => $monthlyPlan,
                'start_date' => Carbon::now()->subWeeks(1),
            ],
        ];

        foreach ($membershipAssignments as $assignment) {
            $startDate = $assignment['start_date'];
            $plan = $assignment['plan'];
            
            // Calculate end date based on plan duration
            if ($plan->duration_type === 'monthly') {
                $endDate = $startDate->copy()->addMonths($plan->duration_value);
            } else {
                $endDate = $startDate->copy()->addYears($plan->duration_value);
            }
            
            MemberMembershipPlan::create([
                'member_id' => $assignment['member']->id,
                'membership_plan_id' => $plan->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
            ]);
        }
        
        echo "✓ Assigned membership plans\n";
        
        // Create payments for members
        $payments = [
            [
                'member_id' => $createdMembers[0]->id,
                'amount' => $monthlyPlan->fee,
                'payment_date' => Carbon::now()->subMonths(1),
                'due_date' => Carbon::now()->subMonths(1),
                'payment_method' => 'cash',
                'payment_type' => 'membership_fee',
                'receipt_number' => 'RCP-' . str_pad(1, 6, '0', STR_PAD_LEFT),
            ],
            [
                'member_id' => $createdMembers[0]->id,
                'amount' => $monthlyPlan->fee,
                'payment_date' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->subDays(5),
                'payment_method' => 'bank_transfer',
                'payment_type' => 'membership_fee',
                'receipt_number' => 'RCP-' . str_pad(2, 6, '0', STR_PAD_LEFT),
            ],
            [
                'member_id' => $createdMembers[1]->id,
                'amount' => $quarterlyPlan->fee,
                'payment_date' => Carbon::now()->subWeeks(3),
                'due_date' => Carbon::now()->subWeeks(3),
                'payment_method' => 'card',
                'payment_type' => 'membership_fee',
                'receipt_number' => 'RCP-' . str_pad(3, 6, '0', STR_PAD_LEFT),
            ],
            [
                'member_id' => $createdMembers[2]->id,
                'amount' => $monthlyPlan->fee,
                'payment_date' => Carbon::now()->subMonths(2),
                'due_date' => Carbon::now()->subMonths(2),
                'payment_method' => 'cash',
                'payment_type' => 'membership_fee',
                'receipt_number' => 'RCP-' . str_pad(4, 6, '0', STR_PAD_LEFT),
            ],
            [
                'member_id' => $createdMembers[2]->id,
                'amount' => $monthlyPlan->fee,
                'payment_date' => Carbon::now()->subMonths(1),
                'due_date' => Carbon::now()->subMonths(1),
                'payment_method' => 'cash',
                'payment_type' => 'membership_fee',
                'receipt_number' => 'RCP-' . str_pad(5, 6, '0', STR_PAD_LEFT),
            ],
            [
                'member_id' => $createdMembers[3]->id,
                'amount' => 2000,
                'payment_date' => Carbon::now()->subWeeks(1),
                'due_date' => Carbon::now()->subWeeks(1),
                'payment_method' => 'cash',
                'payment_type' => 'other',
                'receipt_number' => 'RCP-' . str_pad(6, 6, '0', STR_PAD_LEFT),
            ],
        ];

        foreach ($payments as $paymentData) {
            Payment::create($paymentData);
        }
        
        echo "✓ Created 6 payments\n";
        
        // Create expenses
        $expenses = [
            [
                'title' => 'Monthly Gym Rent',
                'category' => 'rent',
                'amount' => 80000,
                'expense_date' => Carbon::now()->subDays(1),
                'payment_method' => 'bank_transfer',
                'description' => 'Monthly gym rent for ' . Carbon::now()->format('F Y'),
                'coach_id' => null,
            ],
            [
                'title' => 'Utilities Payment',
                'category' => 'utilities',
                'amount' => 15000,
                'expense_date' => Carbon::now()->subDays(3),
                'payment_method' => 'cash',
                'description' => 'Electricity and water bills',
                'coach_id' => null,
            ],
            [
                'title' => 'Gym Equipment Purchase',
                'category' => 'equipment',
                'amount' => 35000,
                'expense_date' => Carbon::now()->subWeeks(1),
                'payment_method' => 'card',
                'description' => 'New dumbbells and resistance bands',
                'coach_id' => null,
            ],
            [
                'title' => 'Coach Salary - ' . $allCoaches[0]->name,
                'category' => 'other',
                'amount' => $allCoaches[0]->salary,
                'expense_date' => Carbon::now()->subDays(2),
                'payment_method' => 'bank_transfer',
                'description' => 'Monthly salary payment for ' . Carbon::now()->format('F Y'),
                'coach_id' => $allCoaches[0]->id,
            ],
        ];

        foreach ($expenses as $expenseData) {
            Expense::create($expenseData);
        }
        
        echo "✓ Created 4 expenses\n";
        
        echo "\n";
        echo "========================================\n";
        echo "✅ DATABASE RESET COMPLETE!\n";
        echo "========================================\n";
        echo "Summary:\n";
        echo "- 4 Coaches created (TRN0001 - TRN0004)\n";
        echo "- 4 Members created (MEM0001 - MEM0004)\n";
        echo "- 6 Payments recorded\n";
        echo "- 4 Expenses recorded\n";
        echo "========================================\n";
    }
}
