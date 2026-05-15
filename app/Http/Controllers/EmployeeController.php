<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveEmployeeRequest;
use App\Models\Employee;
use App\Models\LaptopAssignment;
use App\Models\SystemLookup;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $showLeftEmployees = $request->boolean('show_left_employees');
        $search = $request->input('search');

        $query = Employee::query();

        // Handle Active/Inactive Toggle
        if (! $showLeftEmployees) {
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
        $employees = $query->where('emp_code', '!=', 'ADMIN001')->orderBy('is_active', 'desc')->orderBy('role', 'desc')->orderBy('first_name', 'asc')->orderBy('middle_name', 'asc')->orderBy('last_name', 'asc')->get();

        return view('employees.index', compact('employees', 'showLeftEmployees'));
    }

    public function show(Employee $employee)
    {
        // Eager load relationships to prevent N+1 query issues
        $employee->load(['principal', 'trainees']);

        // Fetch their laptop assignments (both active and returned)
        $assignments = LaptopAssignment::with(['laptop.brand', 'laptop.model'])
            ->where('employee_id', $employee->id)
            ->latest('assigned_date')
            ->get();

        return view('employees.show', compact('employee', 'assignments'));
    }

    // The Upload Method (Handles individual PDF uploads from profile)
    public function uploadDocuments(Request $request, Employee $employee)
    {
        $request->validate([
            'articleship_deed' => 'nullable|mimes:pdf|max:10240',
            'articleship_completion' => 'nullable|mimes:pdf|max:10240',
        ]);

        $docsDir = 'employee_documents';
        $uploadedCount = 0;

        if ($request->hasFile('articleship_deed')) {
            if ($employee->articleship_deed_path) {
                Storage::disk('public')->delete($employee->articleship_deed_path);
            }
            $fileName = $employee->emp_code.'_Deed_'.time().'.pdf';
            $employee->articleship_deed_path = $request->file('articleship_deed')->storeAs($docsDir, $fileName, 'public');
            $uploadedCount++;
        }

        if ($request->hasFile('articleship_completion')) {
            if ($employee->articleship_completion_path) {
                Storage::disk('public')->delete($employee->articleship_completion_path);
            }
            $fileName = $employee->emp_code.'_Completion_'.time().'.pdf';
            $employee->articleship_completion_path = $request->file('articleship_completion')->storeAs($docsDir, $fileName, 'public');
            $uploadedCount++;
        }

        if ($uploadedCount > 0) {
            $employee->save();

            return back()->with('success', "Successfully uploaded {$uploadedCount} document(s).");
        }

        return back()->with('error', 'No valid PDF documents were selected.');
    }

    public function create()
    {
        $potentialPrincipals = Employee::query()->where('role', 'Partner')->where('emp_code', '!=', 'ADMIN001')->get();

        $bankNames = SystemLookup::query()->where('category', 'BankName')->get();
        $bankBranches = SystemLookup::query()->where('category', 'BankBranch')->get();

        $employee = new Employee;

        return view('employees.form', compact('potentialPrincipals', 'bankNames', 'bankBranches', 'employee'));
    }

    public function store(SaveEmployeeRequest $request)
    {
        $validated = $request->validated();

        $employee = new Employee($validated);
        $employee->created_by_id = Auth::id();

        $this->handleTraineeData($request, $employee);

        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Employee saved successfully.');
    }

    public function edit(Employee $employee)
    {
        if (! $employee->is_active) {
            return redirect()->route('employees.index')->with('error', 'This record is locked because the employee has left the company. To edit details, you must first reactivate their employment.');
        }

        $potentialPrincipals = Employee::query()->where('role', 'Partner')->where('id', '!=', $employee->id)->get();

        $bankNames = SystemLookup::query()->where('category', 'BankName')->get();
        $bankBranches = SystemLookup::query()->where('category', 'BankBranch')->get();

        return view('employees.form', compact('employee', 'potentialPrincipals', 'bankNames', 'bankBranches'));
    }

    public function update(SaveEmployeeRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        $employee->fill($validated);
        $employee->modified_by_id = Auth::id();

        $this->handleTraineeData($request, $employee);

        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        // Clean up files before deleting the user!
        if ($employee->articleship_deed_path) {
            Storage::disk('public')->delete($employee->articleship_deed_path);
        }
        if ($employee->articleship_completion_path) {
            Storage::disk('public')->delete($employee->articleship_completion_path);
        }

        Employee::destroy($employee->id);

        return redirect()->route('employees.index')->with('success', "{$employee->first_name} deleted successfully.");
    }

    // --- Helper Methods ---
    private function handleTraineeData(Request $request, Employee $employee)
    {
        if ($employee->role === 'ArticleTrainee') {
            $employee->principal_id = $request->input('principal_id');

            // Check if a file was uploaded through the main form
            if ($request->hasFile('articleship_deed')) {
                // 1. Delete old file from storage if it exists (Cleanup)
                if ($employee->articleship_deed_path) {
                    Storage::disk('public')->delete($employee->articleship_deed_path);
                }

                // 2. Generate clean filename: EMP-001_Deed_1715525413.pdf
                $fileName = $employee->emp_code.'_Deed_'.time().'.pdf';

                // 3. Store in storage/app/public/employee_documents
                $path = $request->file('articleship_deed')->storeAs('employee_documents', $fileName, 'public');

                // 4. Save the PATH (string) to the database
                $employee->articleship_deed_path = $path;
            }
        } else {
            // If they are no longer a Trainee, clear their principal
            $employee->principal_id = null;
        }
    }

    public function viewDeed(Employee $employee)
    {
        // Check if the file actually exists on the disk
        if (empty($employee->articleship_deed_path) || ! Storage::disk('public')->exists($employee->articleship_deed_path)) {
            abort(404, 'Document not found.');
        }

        // Get the absolute physical path to make Intelephense happy
        $fullPath = Storage::disk('public')->path($employee->articleship_deed_path);

        // Return the file safely using the global response helper
        return response()->file($fullPath);
    }

    public function viewCompletion(Employee $employee)
    {
        // Check if the completion certificate file exists
        if (empty($employee->completion_certificate_path) || ! Storage::disk('public')->exists($employee->completion_certificate_path)) {
            abort(404, 'Completion certificate not found.');
        }

        $fullPath = Storage::disk('public')->path($employee->completion_certificate_path);

        return response()->file($fullPath);
    }

    public function showMarkLeftForm(Employee $employee)
    {
        if (Auth::user()->employee_id === $employee->id) {
            return redirect()->route('employees.index')->with('error', 'Action denied: You cannot offboard yourself.');
        }

        if (! $employee->is_active) {
            return redirect()->route('employees.index')->with('error', 'This employee has already been marked as left.');
        }

        return view('employees.mark-left', compact('employee'));
    }

    public function upgradeTrainee(Request $request, Employee $employee)
    {
        $request->validate([
            'new_designation' => 'required|string|max:255',
            'completion_date' => 'required|date|before_or_equal:today',
            'certificate' => 'nullable|mimes:pdf|max:10240',
        ]);

        $employee->role = 'Other'; // Upgrading out of trainee status
        $employee->designation = $request->new_designation;
        $employee->articleship_completion_date = $request->completion_date;
        $employee->principal_id = null; // No longer needs a principal

        if ($request->hasFile('certificate')) {
            // Cleanup old certificate if exists
            if ($employee->completion_certificate_path) {
                Storage::disk('public')->delete($employee->completion_certificate_path);
            }

            $fileName = $employee->emp_code.'_Completion_'.time().'.pdf';
            $employee->completion_certificate_path = $request->file('certificate')->storeAs('employee_documents', $fileName, 'public');
        }

        $employee->save();

        return redirect()->route('employees.index')->with('success', "{$employee->first_name} has been upgraded to {$employee->designation}!");
    }

    public function processMarkLeft(Request $request, Employee $employee)
    {
        if (Auth::user()->employee_id === $employee->id) {
            return redirect()->route('employees.index')->with('error', 'Action denied: You cannot offboard yourself.');
        }

        $request->validate([
            'exit_date' => 'required|date|before_or_equal:9999-12-31',
            'reason' => 'required|string|max:255',
        ], [
            'reason.required' => 'Please provide a reason (e.g., Resigned, Terminated).',
        ]);

        $employee->is_active = false;
        $employee->exit_date = $request->exit_date;
        $employee->exit_reason = $request->reason;
        $employee->modified_by_id = Auth::id();
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

        if (! $showLeftEmployees) {
            $query->where('is_active', true);
        }

        $employees = $query->where('emp_code', '!=', 'ADMIN001')->orderBy('is_active', 'desc')->orderBy('role', 'desc')->orderBy('first_name', 'asc')->orderBy('middle_name', 'asc')->orderBy('last_name', 'asc')->get();

        $pdf = Pdf::loadView('employees.pdf', compact('employees', 'showLeftEmployees'));

        $fileName = 'Employee_Report_'.date('Y-m-d').'.pdf';

        return $pdf->download($fileName);
    }
}
