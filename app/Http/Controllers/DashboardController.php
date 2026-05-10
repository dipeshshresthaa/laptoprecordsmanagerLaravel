<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $employeeCount = Employee::all()->count();
        $userCount = User::all()->count();
        // Add laptop counts here later when the module is ready

        return view('dashboard', compact('employeeCount', 'userCount'));
    }
}