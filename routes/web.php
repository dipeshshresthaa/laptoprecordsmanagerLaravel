<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return redirect()->route('employees.index');
});

Route::get('employees/{employee}/deed', [\App\Http\Controllers\EmployeeController::class, 'viewDeed'])
    ->name('employees.deed');

Route::resource('employees', \App\Http\Controllers\EmployeeController::class);