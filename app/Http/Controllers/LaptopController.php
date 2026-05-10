<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\SystemLookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaptopController extends Controller
{
    public function index(Request $request)
    {
        $showDisposed = $request->boolean('show_disposed');
        $search = $request->input('search');

        // Start query and eager load relationships to prevent N+1 performance issues
        $query = Laptop::with(['brand', 'model']);

        // Handle Active/Disposed Toggle
        if (!$showDisposed) {
            $query->where('status', '!=', 'Disposed');
        }

        // Handle Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('laptop_fa_code', 'like', "%{$search}%");
            });
        }

        // Get results sorted by newest first
        $laptops = $query->latest()->get();

        return view('laptops.index', compact('laptops', 'showDisposed'));
    }

    // Fetches dropdown options (Used by Create and Edit)
    private function getFormOptions()
    {
        return [
            'brands' => SystemLookup::query()->where('category', 'Brand')->get(),
            'processors' => SystemLookup::query()->where('category', 'Processor')->get(),
            'ramSizes' => SystemLookup::query()->where('category', 'RamSize')->get(),
            'storageSizes' => SystemLookup::query()->where('category', 'StorageSize')->get(),
            'screenSizes' => SystemLookup::query()->where('category', 'ScreenSize')->get(),
            'ramTypes' => ['DDR3', 'DDR4', 'DDR5', 'LPDDR4x'],
            'storageTypes' => ['SSD (NVMe)', 'SSD (SATA)', 'HDD'],
            'statuses' => ['Available', 'Assigned', 'In Repair', 'Disposed']
        ];
    }

    public function create()
    {
        $options = $this->getFormOptions();
        $laptop = new Laptop(); // Empty instance for the view
        $models = collect(); // Models load dynamically based on brand
        
        return view('laptops.form', array_merge($options, compact('laptop', 'models')));
    }

    public function edit(Laptop $laptop)
    {
        $options = $this->getFormOptions();
        
        // Pre-load models for the currently selected brand
        $models = SystemLookup::query()
                              ->where('category', 'Model')
                              ->where('parent_id', $laptop->brand_id)
                              ->get();
                              
        return view('laptops.form', array_merge($options, compact('laptop', 'models')));
    }

    public function store(Request $request)
    {
        return $this->saveLaptop($request, new Laptop());
    }

    public function update(Request $request, Laptop $laptop)
    {
        if ($laptop->is_disposed) {
            return back()->with('error', 'Cannot edit a disposed laptop.');
        }
        return $this->saveLaptop($request, $laptop);
    }

    private function saveLaptop(Request $request, Laptop $laptop)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:laptops,serial_number,' . $laptop->id,
            'brand_id' => 'required|exists:system_lookups,id',
            'model_id' => 'required|exists:system_lookups,id',
            'purchase_date' => 'required|date',
            'photo' => 'nullable|image|max:2048', // 2MB restriction from your C# code
        ]);

        $laptop->fill($request->except(['photo', '_token', '_method']));

        // Handle Photo Upload (Replaces byte[] array logic)
        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($laptop->laptop_photo_path) {
                Storage::disk('public')->delete($laptop->laptop_photo_path);
            }
            $laptop->laptop_photo_path = $request->file('photo')->store('laptops', 'public');
        }

        // Audit Trail
        if (!$laptop->exists) {
            $laptop->created_by_id = Auth::id();
        } else {
            $laptop->modified_by_id = Auth::id();
        }

        $laptop->save();

        return redirect()->route('laptops.index')->with('success', 'Laptop saved successfully.');
    }

    // API Endpoint for Cascading Dropdowns
    public function getModelsByBrand($brandId)
    {
        $models = SystemLookup::query()->where('category', 'Model')->where('parent_id', $brandId)->get();
        return response()->json($models);
    }
}