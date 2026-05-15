<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Laptop;
use App\Models\LaptopAssignment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch lists for the modals (Eager loading relationships for performance)
        $availableLaptopsList = Laptop::with(['brand', 'model'])
            ->where('status', 'Available')->get();

        $assignedLaptopsList = Laptop::with(['brand', 'model', 'currentAssignment.employee'])
            ->where('status', 'Assigned')->get();

        $maintenanceLaptopsList = Laptop::with(['brand', 'model', 'repairs' => function ($query) {
            // Get only the currently active repair ticket
            $query->whereNull('returned_date')->latest('sent_date');
        }, 'repairs.vendor'])
            ->where('status', 'In repair')->get();

        // Calculate counts
        $availableLaptops = $availableLaptopsList->count();
        $assignedLaptops = $assignedLaptopsList->count();
        $maintenanceLaptops = $maintenanceLaptopsList->count();

        // Employee Metric (Exclude ONLY 'ADMIN001')
        $totalEmployees = Employee::where('is_active', true)
            ->where('emp_code', '!=', 'ADMIN001')
            ->whereDoesntHave('userAccount', function ($query) {
                $query->where('username', 'ADMIN001');
            })->count();

        // User Metric
        $totalUsers = User::count();

        // Recent Activity
        $recentAssignments = LaptopAssignment::with(['laptop', 'employee'])
            ->latest('assigned_date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'availableLaptops', 'availableLaptopsList',
            'assignedLaptops', 'assignedLaptopsList',
            'maintenanceLaptops', 'maintenanceLaptopsList',
            'totalEmployees',
            'totalUsers',
            'recentAssignments'
        ));
    }
}
