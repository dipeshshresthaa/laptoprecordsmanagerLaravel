<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LaptopAssignmentController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\LaptopDisposalController;
use App\Http\Controllers\LaptopRepairController;
use App\Http\Controllers\LaptopUpgradeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SystemLookupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
        Route::get('settings/lookups', [SystemLookupController::class, 'index'])->name('lookups.index');
        Route::post('settings/lookups', [SystemLookupController::class, 'store'])->name('lookups.store');
        Route::put('settings/lookups/{lookup}', [SystemLookupController::class, 'update'])->name('lookups.update');
        Route::delete('settings/lookups/{lookup}', [SystemLookupController::class, 'destroy'])->name('lookups.destroy');

        Route::get('employees/{employee}/mark-left', [EmployeeController::class, 'showMarkLeftForm'])->name('employees.mark-left');
        Route::post('employees/{employee}/mark-left', [EmployeeController::class, 'processMarkLeft'])->name('employees.process-left');
        Route::get('/api/lookups/models/{brandId}', [LaptopController::class, 'getModelsByBrand']);

        // Laptop Lifecycle (Assignment & Return)
        Route::get('laptops/{laptop}/assign', [LaptopAssignmentController::class, 'createAssign'])->name('laptops.assign');
        Route::post('laptops/{laptop}/assign', [LaptopAssignmentController::class, 'storeAssign'])->name('laptops.store_assign');

        Route::get('laptops/{laptop}/return', [LaptopAssignmentController::class, 'createReturn'])->name('laptops.return');
        Route::post('laptops/{laptop}/return', [LaptopAssignmentController::class, 'storeReturn'])->name('laptops.store_return');

        // PDF Download
        Route::get('assignments/{assignment}/assign-pdf', [LaptopAssignmentController::class, 'downloadAssignPdf'])->name('assignments.assign_pdf');
        Route::get('assignments/{assignment}/return-pdf', [LaptopAssignmentController::class, 'downloadReturnPdf'])->name('assignments.return_pdf');

        // Repairs
        Route::get('laptops/{laptop}/repair', [LaptopRepairController::class, 'createSend'])->name('laptops.repair');
        Route::post('laptops/{laptop}/repair', [LaptopRepairController::class, 'storeSend'])->name('laptops.store_repair');

        // NEW: Return from Repair
        Route::get('laptops/{laptop}/repair-return', [LaptopRepairController::class, 'createReturn'])->name('laptops.repair_return');
        Route::post('laptops/{laptop}/repair-return', [LaptopRepairController::class, 'storeReturn'])->name('laptops.store_repair_return');

        // Upgrades
        Route::get('laptops/{laptop}/upgrade', [LaptopUpgradeController::class, 'create'])->name('laptops.upgrade');
        Route::post('laptops/{laptop}/upgrade', [LaptopUpgradeController::class, 'store'])->name('laptops.store_upgrade');

        // Disposal
        Route::get('laptops/{laptop}/dispose', [LaptopDisposalController::class, 'create'])->name('laptops.dispose');
        Route::post('laptops/{laptop}/dispose', [LaptopDisposalController::class, 'store'])->name('laptops.store_dispose');

        Route::get('laptops/{laptop}/history', [LaptopController::class, 'show'])->name('laptops.history');
        Route::get('laptops/{laptop}/history/pdf', [LaptopController::class, 'downloadHistoryPdf'])->name('laptops.history.pdf');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/api/principal/{id}/trainees', [ReportController::class, 'getTraineesByPrincipal'])
            ->name('reports.api.trainees'); // <-- Add this name

        Route::get('/reports/export-comprehensive', [ReportController::class, 'downloadComprehensivePdf'])->name('reports.export_comprehensive');

        // Employees
        Route::get('employees/{employee}/deed', [EmployeeController::class, 'viewDeed'])->name('employees.deed');
        Route::resource('employees', EmployeeController::class);

        // Laptop Routes
        Route::resource('laptops', LaptopController::class);

        // Users (Admin Only)
        Route::middleware('admin')->group(function () {
            Route::resource('users', UserController::class);
        });
    });
});
