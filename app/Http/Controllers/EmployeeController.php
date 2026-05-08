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

        // Explicitly call get() to convert the query to a Collection for Intelephense
        $bankNames = Employee::query()
            ->whereNotNull('bank_name', 'and')
            ->where('bank_name', '!=', '')
            ->distinct()
            ->get(['bank_name'])
            ->pluck('bank_name');

        $bankBranches = Employee::query()
            ->whereNotNull('bank_branch', 'and')
            ->where('bank_branch', '!=', '')
            ->distinct()
            ->get(['bank_branch'])
            ->pluck('bank_branch');

        $employee = new Employee();

        return view('employees.form', compact('potentialPrincipals', 'bankNames', 'bankBranches', 'employee'));
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
        if (!$employee->is_active) {
            return redirect()->route('employees.index')->with('error', 'This record is locked because the employee has left the company. To edit details, you must first reactivate their employment.');
        }

        $potentialPrincipals = Employee::query()->where('role', 'Partner')->where('id', '!=', $employee->id)->get();

        // Explicitly call get() to convert the query to a Collection for Intelephense
        $bankNames = Employee::query()
            ->whereNotNull('bank_name', 'and')
            ->where('bank_name', '!=', '')
            ->distinct()
            ->get(['bank_name'])
            ->pluck('bank_name');

        $bankBranches = Employee::query()
            ->whereNotNull('bank_branch', 'and')
            ->where('bank_branch', '!=', '')
            ->distinct()
            ->get(['bank_branch'])
            ->pluck('bank_branch');

        return view('employees.form', compact('employee', 'potentialPrincipals', 'bankNames', 'bankBranches'));
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
        $uniqueEmpRule = $ignoreId ? "unique:employees,emp_code,{$ignoreId},id" : '';
        $uniqueAccountRule = $ignoreId ? "unique:employees,bank_account_number,{$ignoreId},id" : 'unique:employees,bank_account_number';

        return $request->validate([
            'emp_code' => ['required', 'string', $uniqueEmpRule],
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'phone_number' => 'nullable|numeric',
            'address_state' => 'nullable|string',
            'address_district' => 'nullable|string',
            'address_municipality' => 'nullable|string',
            'pan_number' => 'nullable|digits:9',
            'designation' => 'nullable|string',
            'joining_date' => 'nullable|date|before_or_equal:9999-12-31',
            'bank_name' => 'nullable|string',
            'bank_branch' => 'nullable|string',
            'bank_account_number' => ['nullable', 'string', $uniqueAccountRule],
            'cit_number' => 'nullable|string',
            'role' => 'required|string',
            'principal_id' => 'required_if:role,ArticleTrainee',
            'articleship_deed_pdf' => 'nullable|file|mimes:pdf|max:5120',
        ], [
            'principal_id.required_if' => 'An article trainee must have a principal assigned.',
            // Add the custom message here:
            'bank_account_number.unique' => 'The bank account number is already in use by some employee. Confirm the bank account number again.'
        ]);
    }

    private function handleTraineeData(Request $request, Employee $employee)
    {
        if ($employee->role === 'ArticleTrainee') {
            $employee->principal_id = $request->input('principal_id');

            // If a new file is uploaded, convert it to a byte array and store it
            if ($request->hasFile('articleship_deed_pdf')) {
                $file = $request->file('articleship_deed_pdf');

                // file_get_contents reads the raw binary data (equivalent to byte[])
                $employee->articleship_deed_pdf = file_get_contents($file->getRealPath());
            }
        } else {
            $employee->principal_id = null;
            $employee->articleship_deed_pdf = null;
        }
    }

    public function viewDeed(Employee $employee)
    {
        // Check if the binary column has data
        if (empty($employee->articleship_deed_pdf)) {
            abort(404, 'Document not found.');
        }

        // Stream the binary data back to the browser as a PDF
        return response($employee->articleship_deed_pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="ArticleshipDeed_' . $employee->emp_code . '.pdf"'
        ]);
    }
}
