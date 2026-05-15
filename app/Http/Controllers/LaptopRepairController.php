<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\SystemLookup; // <-- Added import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaptopRepairController extends Controller
{
    public function createSend(Laptop $laptop)
    {
        if ($laptop->is_disposed || $laptop->status === 'In repair') {
            return redirect()->route('laptops.index')->with('error', 'Laptop cannot be sent to repair from its current status.');
        }

        // 1. Calculate Minimum Valid Date (Purchase Date vs Last Return Date)
        $minDate = $laptop->purchase_date;

        // Check last assignment return
        $lastAssignment = $laptop->assignments()->whereNotNull('returned_date')->latest('returned_date')->first();
        if ($lastAssignment && $lastAssignment->returned_date > $minDate) {
            $minDate = $lastAssignment->returned_date;
        }

        // Check last repair return
        $lastRepair = $laptop->repairs()->whereNotNull('returned_date')->latest('returned_date')->first();
        if ($lastRepair && $lastRepair->returned_date > $minDate) {
            $minDate = $lastRepair->returned_date;
        }

        // 2. Fetch vendors from SystemLookup instead of grouping strings
        $vendors = SystemLookup::query()
            ->where('category', 'Vendor')
            ->orderBy('value', 'asc')
            ->get();

        return view('laptops.repair_send', compact('laptop', 'minDate', 'vendors'));
    }

    public function storeSend(Request $request, Laptop $laptop)
    {
        $request->validate([
            'vendor_id' => 'required|exists:system_lookups,id', // <-- Changed validation
            'issue_description' => 'required|string',
            // DATE VALIDATION: Cannot be in the future, cannot be before previous events
            'sent_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
        ]);

        // 1. Log the Repair Event
        $laptop->repairs()->create([
            'vendor_id' => $request->vendor_id, // <-- Save the ID
            'issue_description' => $request->issue_description,
            'sent_date' => $request->sent_date,
            'sent_by_id' => Auth::id(),
        ]);

        // 2. Update the main hardware status
        $laptop->update(['status' => 'In repair']);

        return redirect()->route('laptops.index')->with('success', 'Laptop logged as sent to repair.');
    }

    public function createReturn(Laptop $laptop)
    {
        if ($laptop->status !== 'In repair') {
            return redirect()->route('laptops.index')->with('error', 'Laptop is not currently in repair.');
        }

        // Find the open repair ticket
        $activeRepair = $laptop->repairs()->whereNull('returned_date')->latest('sent_date')->first();
        if (! $activeRepair) {
            return redirect()->route('laptops.index')->with('error', 'No active repair log found.');
        }

        // Check if an employee still legally "holds" this laptop
        $activeAssignment = $laptop->assignments()->whereNull('returned_date')->latest('assigned_date')->first();

        return view('laptops.repair_return', compact('laptop', 'activeRepair', 'activeAssignment'));
    }

    public function storeReturn(Request $request, Laptop $laptop)
    {
        $activeRepair = $laptop->repairs()->whereNull('returned_date')->latest('sent_date')->first();

        $request->validate([
            'returned_date' => 'required|date|before_or_equal:today|after_or_equal:'.$activeRepair->sent_date->format('Y-m-d'),
            'repair_cost' => 'nullable|numeric|min:0',
            'repair_notes' => 'required|string',
            'return_destination' => 'required|in:Office,Employee',
        ]);

        $activeAssignment = $laptop->assignments()->whereNull('returned_date')->latest('assigned_date')->first();

        // Security check: Don't allow returning to an employee if no one was assigned to it
        if ($request->return_destination === 'Employee' && ! $activeAssignment) {
            return back()->with('error', 'Cannot return to employee: This laptop was unassigned before repair.')->withInput();
        }

        // 1. Close the Repair Ticket
        $activeRepair->update([
            'returned_date' => $request->returned_date,
            'repair_cost' => $request->repair_cost,
            'repair_notes' => $request->repair_notes,
            'returned_by_id' => Auth::id(),
        ]);

        // 2. Handle the Destination Logic
        if ($request->return_destination === 'Office') {
            $laptop->update(['status' => 'Available']);

            // BUSINESS LOGIC: Retroactively close the employee's assignment on the date it was sent for repair
            if ($activeAssignment) {
                $activeAssignment->update([
                    'returned_date' => $activeRepair->sent_date,
                    'return_condition' => 'Sent to Repair',
                    'return_reason' => 'Automatically returned to office after repair. Original issue: '.$activeRepair->issue_description,
                    'returned_by_id' => Auth::id(),
                ]);
            }
        } else {
            // Returned directly to the Employee. Status goes back to Assigned, assignment stays open.
            $laptop->update(['status' => 'Assigned']);
        }

        return redirect()->route('laptops.index')->with('success', 'Laptop successfully received from repair vendor.');
    }
}
