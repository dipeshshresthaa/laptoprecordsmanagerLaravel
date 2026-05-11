<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaptopDisposalController extends Controller
{
    public function create(Laptop $laptop)
    {
        if ($laptop->is_disposed) {
            return redirect()->route('laptops.index')->with('error', 'This laptop is already marked as disposed.');
        }

        $activeAssignment = $laptop->assignments()->whereNull('returned_date')->latest('assigned_date')->first();
        $activeRepair = $laptop->repairs()->whereNull('returned_date')->latest('sent_date')->first();

        // Calculate the absolute minimum disposal date to prevent timeline corruption
        $minDate = $laptop->purchase_date;
        if ($activeAssignment) {
            $minDate = $activeAssignment->assigned_date;
        }
        if ($activeRepair) {
            $minDate = $activeRepair->sent_date;
        }

        return view('laptops.dispose', compact('laptop', 'activeAssignment', 'activeRepair', 'minDate'));
    }

    public function store(Request $request, Laptop $laptop)
    {
        $request->validate([
            'disposal_date' => 'required|date|before_or_equal:today|after_or_equal:'.$request->min_date,
            'disposal_method' => 'required|string',
            'disposal_reason' => 'required|string',
        ]);

        $disposalNote = "Disposed ({$request->disposal_method}): {$request->disposal_reason}";

        // 1. Auto-close any active assignment
        $activeAssignment = $laptop->assignments()->whereNull('returned_date')->latest('assigned_date')->first();
        if ($activeAssignment) {
            $activeAssignment->update([
                'returned_date' => $request->disposal_date,
                'return_condition' => $request->disposal_method,
                'return_reason' => 'Auto-closed due to disposal. '.$disposalNote,
                'returned_by_id' => Auth::id(),
            ]);
        }

        // 2. Auto-close any active repair ticket
        $activeRepair = $laptop->repairs()->whereNull('returned_date')->latest('sent_date')->first();
        if ($activeRepair) {
            $activeRepair->update([
                'returned_date' => $request->disposal_date,
                'repair_notes' => 'Auto-closed due to disposal. '.$disposalNote,
                'returned_by_id' => Auth::id(),
            ]);
        }

        // 3. Permanently mark the laptop as Disposed
        $laptop->update([
            'status' => 'Disposed',
            'disposal_date' => $request->disposal_date,
            'disposal_method' => $request->disposal_method,
            'disposal_reason' => $request->disposal_reason,
            'disposed_by_id' => Auth::id(),
        ]);

        return redirect()->route('laptops.index')->with('success', 'Laptop permanently marked as disposed.');
    }
}
