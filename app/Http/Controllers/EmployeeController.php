<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Equivalent to EmployeeListViewModel.LoadData()
        $showLeftEmployees = $request->has('show_left_employees');
        
        $employees = Employee::query()->where('is_active', !$showLeftEmployees)->get();

        return view('employees.index', compact('employees', 'showLeftEmployees'));
    }

    public function create()
    {
        $potentialPrincipals = Employee::query()->where('role', 'Partner')->get();
        return view('employees.create', compact('potentialPrincipals'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateEmployee($request);

        // Check for unique emp_code manually to match your C# specific error handling
        if (Employee::query()->where('emp_code', $validated['emp_code'])->exists()) {
            return back()->withInput()->withErrors(['emp_code' => 'Employee code already exists.']);
        }

        $employee = new Employee($validated);
        $employee->created_by_id = Auth::id();

        $this->handleTraineeData($request, $employee);

        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Employee saved successfully.');
    }

    public function edit(Employee $employee)
    {
        // Equivalent to EmployeeListViewModel Edit Check
        if (!$employee->is_active) {
            return redirect()->route('employees.index')->with('error', 'This record is locked because the employee has left the company. To edit details, you must first reactivate their employment.');
        }

        $potentialPrincipals = Employee::query()->where('role', 'Partner')->where('id', '!=', $employee->id)->get();
        return view('employees.edit', compact('employee', 'potentialPrincipals'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validateEmployee($request, $employee->id);

        $employee->fill($validated);
        $employee->modified_by_id = Auth::id();

        $this->handleTraineeData($request, $employee);

        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        Employee::destroy($employee->id);
        return redirect()->route('employees.index')->with('success', "{$employee->first_name} deleted successfully.");
    }

    // --- Helper Methods ---

    private function validateEmployee(Request $request, $ignoreId = null)
    {
        $uniqueRule = $ignoreId ? "unique:employees,emp_code,{$ignoreId},id" : '';

        return $request->validate([
            'emp_code' => ['required', 'string', $uniqueRule],
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'phone_number' => 'nullable|string',
            'designation' => 'nullable|string',
            'role' => 'required|string',
            'principal_id' => 'required_if:role,ArticleTrainee',
            'articleship_deed_pdf' => 'nullable|file|mimes:pdf|max:5120',
        ], [
            'principal_id.required_if' => 'An article trainee must have a principal assigned.'
        ]);
    }

    private function handleTraineeData(Request $request, Employee $employee)
    {
        if ($employee->role === 'ArticleTrainee') {
            $employee->principal_id = $request->input('principal_id');

            if ($request->hasFile('articleship_deed_pdf')) {
                // Delete old file if it exists
                if ($employee->articleship_deed_path) {
                    Storage::disk('local')->delete($employee->articleship_deed_path);
                }
                $path = $request->file('articleship_deed_pdf')->store('articleship_deeds', 'local');
                $employee->articleship_deed_path = $path;
            }
        } else {
            $employee->principal_id = null;
            // Optionally delete file if role changes from Trainee to something else
            if ($employee->articleship_deed_path) {
                Storage::disk('local')->delete($employee->articleship_deed_path);
                $employee->articleship_deed_path = null;
            }
        }
    }
}