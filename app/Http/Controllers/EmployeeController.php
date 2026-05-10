<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $showLeftEmployees = $request->boolean('show_left_employees');
        $search = $request->input('search');

        $query = Employee::query();

        // Handle Active/Inactive Toggle
        if (!$showLeftEmployees) {
            $query->where('is_active', true);
        }

        // Handle the Search Box
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('emp_code', 'like', "%{$search}%");
            });
        }

        // Sort alphabetically by first name
        $employees = $query->orderBy('first_name', 'asc')->get();

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

    public function showMarkLeftForm(Employee $employee)
    {
        // 1. Check if they are trying to offboard themselves
        if (\Illuminate\Support\Facades\Auth::user()->employee_id === $employee->id) {
            return redirect()->route('employees.index')->with('error', 'Action denied: You cannot offboard yourself.');
        }

        if (!$employee->is_active) {
            return redirect()->route('employees.index')->with('error', 'This employee has already been marked as left.');
        }

        return view('employees.mark-left', compact('employee'));
    }

    public function processMarkLeft(Request $request, Employee $employee)
    {
        // 1. Check if they are trying to offboard themselves (prevents bypassing UI)
        if (\Illuminate\Support\Facades\Auth::user()->employee_id === $employee->id) {
            return redirect()->route('employees.index')->with('error', 'Action denied: You cannot offboard yourself.');
        }

        $request->validate([
            'exit_date' => 'required|date|before_or_equal:9999-12-31',
            'reason' => 'required|string|max:255',
        ], [
            'reason.required' => 'Please provide a reason (e.g., Resigned, Terminated).'
        ]);

        $employee->is_active = false;
        $employee->exit_date = $request->exit_date;
        $employee->exit_reason = $request->reason;
        $employee->modified_by_id = \Illuminate\Support\Facades\Auth::id();
        $employee->save();

        if ($request->boolean('deactivate_user') && $employee->userAccount) {
            $employee->userAccount->is_active = false;
            $employee->userAccount->save();
        }

        return redirect()->route('employees.index')->with('success', "{$employee->first_name} {$employee->last_name} has been successfully offboarded.");
    }

    public function exportPdf(Request $request)
    {
        $showLeftEmployees = $request->boolean('show_left_employees');

        $query = Employee::query();

        // Match the UI filtering
        if (!$showLeftEmployees) {
            $query->where('is_active', true);
        }

        // Sort alphabetically
        $employees = $query->orderBy('first_name', 'asc')->get();

        // Load the specialized PDF Blade view
        $pdf = Pdf::loadView('employees.pdf', compact('employees', 'showLeftEmployees'));

        // Download the file
        $fileName = 'Employee_Report_' . date('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }
}
