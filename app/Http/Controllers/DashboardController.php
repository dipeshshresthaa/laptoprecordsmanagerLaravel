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

        // --- NEW: Calculate Real Hardware Allocations ---
        $allLaptops = Laptop::with('brand')->get();
        $totalLaptopsCount = $allLaptops->count();
        
        $brandCounts = $allLaptops->groupBy(function ($laptop) {
            return $laptop->brand ? $laptop->brand->value : 'Unknown/Unbranded';
        })->map->count();

        $allocations = [];
        $colors = ['bg-indigo-500', 'bg-purple-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500', 'bg-sky-500', 'bg-slate-500'];
        $colorIndex = 0;

        // Sort brands by most popular first
        foreach ($brandCounts->sortByDesc(fn($count) => $count) as $brandName => $count) {
            $allocations[] = [
                'name' => $brandName,
                'val' => $totalLaptopsCount > 0 ? round(($count / $totalLaptopsCount) * 100) : 0,
                'color' => $colors[$colorIndex % count($colors)],
            ];
            $colorIndex++;
        }

        return view('dashboard', compact(
            'availableLaptops', 'availableLaptopsList',
            'assignedLaptops', 'assignedLaptopsList',
            'maintenanceLaptops', 'maintenanceLaptopsList',
            'totalEmployees',
            'totalUsers',
            'recentAssignments',
            'allocations' // Pass the real dynamic data to the view
        ));
    }
}