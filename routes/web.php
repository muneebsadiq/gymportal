<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LandingController;

// Public Routes
Route::get('/', [LandingController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/due-fees-modal', [DashboardController::class, 'getDueFeesModal'])->name('due-fees-modal');
    Route::get('/search-member', [DashboardController::class, 'searchMember'])->name('search-member');
    Route::get('/search-coach', [DashboardController::class, 'searchCoach'])->name('search-coach');

    // Members
    Route::resource('members', MemberController::class);
    Route::get('members/api-view/{id}', [MemberController::class, 'showApiView'])->name('members.api-view');

    // Payments
    Route::resource('payments', PaymentController::class);
    Route::get('payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
    
    // Coaches
    Route::resource('coaches', \App\Http\Controllers\CoachController::class);
    Route::post('coaches/{coach}/pay-salary', [\App\Http\Controllers\CoachSalaryController::class, 'paySalary'])->name('coaches.pay-salary');
    Route::get('coaches/{coach}/salary-history', [\App\Http\Controllers\CoachSalaryController::class, 'salaryHistory'])->name('coaches.salary-history');

    // Expenses
    Route::resource('expenses', ExpenseController::class);

    // Settings
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::get('settings/edit', [\App\Http\Controllers\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/members', [ReportController::class, 'memberReport'])->name('reports.members');
    Route::get('reports/payments', [ReportController::class, 'paymentReport'])->name('reports.payments');
    Route::get('reports/expenses', [ReportController::class, 'expenseReport'])->name('reports.expenses');
    
    // Admin-only routes (check done in controller)
    Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);

    // Membership Plan Assignment
    Route::post('member_membership_plans', [\App\Http\Controllers\MemberMembershipPlanController::class, 'store'])->name('member_membership_plans.store');
    Route::get('member_membership_plans/{memberMembershipPlan}/edit', [\App\Http\Controllers\MemberMembershipPlanController::class, 'edit'])->name('member_membership_plans.edit');
    Route::put('member_membership_plans/{memberMembershipPlan}', [\App\Http\Controllers\MemberMembershipPlanController::class, 'update'])->name('member_membership_plans.update');
    Route::delete('member_membership_plans/{memberMembershipPlan}', [\App\Http\Controllers\MemberMembershipPlanController::class, 'destroy'])->name('member_membership_plans.destroy');
    // Membership Plans CRUD (admin only)
    Route::get('membership_plans', [\App\Http\Controllers\MembershipPlanController::class, 'index'])->name('membership_plans.index');
    Route::get('membership_plans/create', [\App\Http\Controllers\MembershipPlanController::class, 'create'])->name('membership_plans.create');
    Route::post('membership_plans', [\App\Http\Controllers\MembershipPlanController::class, 'store'])->name('membership_plans.store');
    Route::get('membership_plans/{plan}/edit', [\App\Http\Controllers\MembershipPlanController::class, 'edit'])->name('membership_plans.edit');
    Route::put('membership_plans/{plan}', [\App\Http\Controllers\MembershipPlanController::class, 'update'])->name('membership_plans.update');
    Route::delete('membership_plans/{plan}', [\App\Http\Controllers\MembershipPlanController::class, 'destroy'])->name('membership_plans.destroy');
});

// Test route
Route::get('/test', function() {
    return 'Application is working! Auth: ' . (auth()->check() ? 'Yes (' . auth()->user()->name . ')' : 'No');
});
