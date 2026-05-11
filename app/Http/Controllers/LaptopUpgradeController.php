<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\SystemLookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaptopUpgradeController extends Controller
{
    public function create(Laptop $laptop)
    {
        if ($laptop->is_disposed) {
            return redirect()->route('laptops.index')->with('error', 'Cannot upgrade a disposed laptop.');
        }

        // Fetch lookups for the upgrade dropdowns
        $ramSizes = SystemLookup::query()->where('category', 'RamSize')->orderBy('value', 'asc')->get();
        $storageSizes = SystemLookup::query()->where('category', 'StorageSize')->orderBy('value', 'asc')->get();
        $processors = SystemLookup::query()->where('category', 'Processor')->orderBy('value', 'asc')->get();

        return view('laptops.upgrade', compact('laptop', 'ramSizes', 'storageSizes', 'processors'));
    }

    public function store(Request $request, Laptop $laptop)
    {
        $request->validate([
            'upgrade_type' => 'required|in:RAM,Storage,Processor',
            'upgrade_date' => 'required|date|before_or_equal:today|after_or_equal:'.$laptop->purchase_date->format('Y-m-d'),
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $oldSpecName = 'Unknown';
        $newSpecName = 'Unknown';
        $updateData = [];

        // Determine what is being upgraded and validate the new component
        switch ($request->upgrade_type) {
            case 'RAM':
                $request->validate(['new_ram_id' => 'required|exists:system_lookups,id']);
                if ($laptop->ram_size_id == $request->new_ram_id) {
                    return back()->with('error', 'New RAM size must be different from current RAM size.');
                }
                $oldSpecName = $laptop->ramSize->value ?? 'None';
                $newSpecModel = SystemLookup::query()->find($request->new_ram_id);
                $newSpecName = $newSpecModel->value;
                $updateData['ram_size_id'] = $newSpecModel->id;
                break;

            case 'Storage':
                $request->validate(['new_storage_id' => 'required|exists:system_lookups,id']);
                if ($laptop->storage_size_id == $request->new_storage_id) {
                    return back()->with('error', 'New Storage size must be different from current Storage size.');
                }
                $oldSpecName = $laptop->storageSize->value ?? 'None';
                $newSpecModel = SystemLookup::query()->find($request->new_storage_id);
                $newSpecName = $newSpecModel->value;
                $updateData['storage_size_id'] = $newSpecModel->id;
                break;

            case 'Processor':
                $request->validate(['new_processor_id' => 'required|exists:system_lookups,id']);
                if ($laptop->processor_id == $request->new_processor_id) {
                    return back()->with('error', 'New Processor must be different from current Processor.');
                }
                $oldSpecName = $laptop->processor->value ?? 'None';
                $newSpecModel = SystemLookup::query()->find($request->new_processor_id);
                $newSpecName = $newSpecModel->value;
                $updateData['processor_id'] = $newSpecModel->id;
                break;
        }

        // 1. Create the History Log
        $laptop->upgrades()->create([
            'upgrade_type' => $request->upgrade_type,
            'previous_spec' => $oldSpecName,
            'new_spec' => $newSpecName,
            'upgrade_date' => $request->upgrade_date,
            'cost' => $request->cost,
            'notes' => $request->notes,
            'performed_by_id' => Auth::id(),
        ]);

        // 2. Actually update the Laptop's current hardware configuration
        $laptop->update($updateData);

        return redirect()->route('laptops.index')->with('success', "Laptop {$request->upgrade_type} upgraded successfully.");
    }
}
