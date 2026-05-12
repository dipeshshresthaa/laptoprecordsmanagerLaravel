<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Laptop;
use App\Models\LaptopAssignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaptopAssignmentController extends Controller
{
    // --- ASSIGN MODE ---
    public function createAssign(Laptop $laptop)
    {
        if ($laptop->status !== 'Available') {
            return redirect()->route('laptops.index')->with('error', "Laptop is {$laptop->status} and cannot be assigned.");
        }

        // Calculate Minimum Date (Purchase date OR last returned date)
        $minDate = $laptop->purchase_date;
        $lastReturn = $laptop->assignments()->whereNotNull('returned_date')->latest('returned_date')->first();
        if ($lastReturn && $lastReturn->returned_date > $minDate) {
            $minDate = $lastReturn->returned_date;
        }

        $employees = Employee::query()->where('is_active', true)->orderBy('first_name', 'asc')->get();

        return view('laptops.assign', compact('laptop', 'employees', 'minDate'));
    }

    public function storeAssign(Request $request, Laptop $laptop)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'assigned_date' => 'required|date|before_or_equal:today|after_or_equal:' . $laptop->purchase_date->format('Y-m-d'),
        ]);

        // 1. Create the Assignment Record
        $assignment = $laptop->assignments()->create([
            'employee_id' => $request->employee_id,
            'assigned_date' => $request->assigned_date,
            'assigned_by_id' => Auth::id(),
        ]);

        // 2. Update Laptop Status
        $laptop->update(['status' => 'Assigned']);

        // 3. Redirect back with a command to auto-download the PDF
        return redirect()->route('laptops.index')
            ->with('success', 'Laptop assigned successfully.')
            ->with('receipt_url', route('assignments.assign_pdf', $assignment->id));
    }

    // --- RETURN MODE ---
    public function createReturn(Laptop $laptop)
    {
        $activeAssignment = $laptop->currentAssignment;

        if (! $activeAssignment) {
            return redirect()->route('laptops.index')->with('error', 'This laptop is not currently assigned.');
        }

        return view('laptops.return', compact('laptop', 'activeAssignment'));
    }

    public function storeReturn(Request $request, Laptop $laptop)
    {
        $activeAssignment = $laptop->currentAssignment;

        $request->validate([
            // ADDED: before_or_equal:today
            'returned_date' => 'required|date|before_or_equal:today|after_or_equal:' . $activeAssignment->assigned_date->format('Y-m-d'),
            'return_condition' => 'required|string',
            'return_reason' => 'nullable|string',
            'next_status' => 'required|in:Available,In repair,Disposed',
        ]);

        // 1. Update Assignment
        $activeAssignment->update([
            'returned_date' => $request->returned_date,
            'return_condition' => $request->return_condition,
            'return_reason' => $request->return_reason,
            'returned_by_id' => Auth::id(),
        ]);

        // 2. Update Laptop Status
        $laptop->update(['status' => $request->next_status]);

        return redirect()->route('laptops.index')
            ->with('success', 'Laptop returned successfully.')
            ->with('receipt_url', route('assignments.return_pdf', $activeAssignment->id));
    }

    public function downloadAssignPdf(LaptopAssignment $assignment)
    {
        $assignment->load(['laptop.brand', 'laptop.model', 'laptop.processor', 'laptop.ramSize', 'laptop.storageSize', 'laptop.screenSize', 'employee', 'assignedBy']);
        $pdf = Pdf::loadView('pdfs.assignment_form', compact('assignment'));
        $filename = "Assignment-{$assignment->employee->first_name}-{$assignment->employee->last_name}-" . now()->format('YmdHis') . '.pdf';

        // CHANGED from stream() to download()
        return $pdf->stream($filename);
    }

    public function downloadReturnPdf(LaptopAssignment $assignment)
    {
        $assignment->load(['laptop.brand', 'laptop.model', 'laptop.processor', 'laptop.ramSize', 'laptop.storageSize', 'laptop.screenSize', 'employee', 'returnedBy']);
        $pdf = Pdf::loadView('pdfs.return_form', compact('assignment'));
        $filename = "Return_{$assignment->employee->first_name}_{$assignment->employee->last_name}_" . now()->format('YmdHis') . '.pdf';

        // CHANGED from stream() to download()
        return $pdf->stream($filename);
    }
}
