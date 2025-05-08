<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\ClockController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeServiceController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/thank-you', function () {
    return view('thank-you');
})->name('thank-you');

// Dashboard route - will redirect based on user role
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Breeze default routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Employee routes
Route::middleware(['auth', 'role:employee,manager'])->prefix('employee')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'employeeDashboard'])->name('employee.dashboard');
    
    // Clock in/out
    Route::post('/clock-in', [ClockController::class, 'clockIn'])->name('employee.clock-in');
    Route::post('/clock-out', [ClockController::class, 'clockOut'])->name('employee.clock-out');
    
    // Customer search and service
    Route::get('/search-customer', [CustomerServiceController::class, 'searchCustomerForm'])->name('employee.search-customer');
    Route::post('/search-customer', [CustomerServiceController::class, 'searchCustomer'])->name('employee.search-customer.post');
    Route::get('/customer-service', [CustomerServiceController::class, 'customerServiceForm'])->name('employee.customer-service');
    Route::post('/customer-service', [CustomerServiceController::class, 'storeService'])->name('employee.customer-service.post');
    
    // Billing and payment
    Route::get('/billing/{serviceRecord}', [PaymentController::class, 'billing'])->name('employee.billing');
    Route::post('/payment/{serviceRecord}', [PaymentController::class, 'processPayment'])->name('employee.payment.process');
    Route::get('/receipt/{payment}', [PaymentController::class, 'generateReceipt'])->name('employee.receipt');
    Route::get('/receipt/{payment}/pdf', [PaymentController::class, 'downloadReceiptPdf'])->name('employee.receipt.pdf');
    
    // New service selection and payment routes
    Route::get('/services', [EmployeeServiceController::class, 'selectService'])->name('employee.services');
    Route::post('/services/process', [EmployeeServiceController::class, 'processSelectedServices'])->name('employee.services.process');
    Route::get('/payment', [EmployeeServiceController::class, 'showPaymentPage'])->name('employee.payment');
    Route::post('/payment/process', [EmployeeServiceController::class, 'processPayment'])->name('employee.payment.process');
});

// Manager routes
Route::middleware(['auth', 'role:manager'])->prefix('manager')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
    
    // Attendance management
    Route::get('/attendance', [ClockController::class, 'attendanceReport'])->name('manager.attendance');
    
    // Member management
    Route::get('/members', [MemberController::class, 'index'])->name('manager.members');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('manager.members.show');
    Route::post('/members/{member}/approve', [MemberController::class, 'approve'])->name('manager.members.approve');
    Route::post('/members/{member}/reject', [MemberController::class, 'reject'])->name('manager.members.reject');
    
    // Sales reports
    Route::get('/sales-daily', [ReportController::class, 'dailySales'])->name('manager.sales-daily');
    Route::get('/sales-monthly', [ReportController::class, 'monthlySales'])->name('manager.sales-monthly');
    
    // Benefits management
    Route::get('/benefits', [BenefitController::class, 'index'])->name('manager.benefits');
    Route::get('/benefits/create', [BenefitController::class, 'create'])->name('manager.benefits.create');
    Route::post('/benefits', [BenefitController::class, 'store'])->name('manager.benefits.store');
    Route::get('/benefits/{benefit}/edit', [BenefitController::class, 'edit'])->name('manager.benefits.edit');
    Route::put('/benefits/{benefit}', [BenefitController::class, 'update'])->name('manager.benefits.update');
    Route::delete('/benefits/{benefit}', [BenefitController::class, 'destroy'])->name('manager.benefits.destroy');
    Route::get('/benefits/{benefit}/assign', [BenefitController::class, 'assignForm'])->name('manager.benefits.assign-form');
    Route::post('/benefits/{benefit}/assign', [BenefitController::class, 'assign'])->name('manager.benefits.assign');
    
    // Employee QR code management
    Route::get('/employees', [UserController::class, 'employees'])->name('manager.employees');
    Route::get('/employees/{user}/qr', [UserController::class, 'qrForm'])->name('manager.employees.qr-form');
    Route::post('/employees/{user}/qr', [UserController::class, 'uploadQr'])->name('manager.employees.upload-qr');
    
    // Service management
    Route::get('/services', [ServiceController::class, 'index'])->name('manager.services');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('manager.services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('manager.services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('manager.services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('manager.services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('manager.services.destroy');
});

require __DIR__.'/auth.php';