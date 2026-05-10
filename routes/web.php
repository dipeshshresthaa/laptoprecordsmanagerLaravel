<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// Protected Application Routes (Requires Login)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Password Change Routes (Must be accessible even if requires_password_change is true)
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');

    // Standard Routes (Protected by the force.password middleware)
    Route::middleware('force.password')->group(function () {

        // Main Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('employees/export/pdf', [EmployeeController::class, 'exportPdf'])->name('employees.export.pdf');

        Route::get('employees/{employee}/mark-left', [EmployeeController::class, 'showMarkLeftForm'])->name('employees.mark-left');
        Route::post('employees/{employee}/mark-left', [EmployeeController::class, 'processMarkLeft'])->name('employees.process-left');

        // Employees
        Route::get('employees/{employee}/deed', [EmployeeController::class, 'viewDeed'])->name('employees.deed');
        Route::resource('employees', EmployeeController::class);

        // Users (Admin Only)
        Route::middleware('admin')->group(function () {
            Route::resource('users', UserController::class);
        });
    });
});
