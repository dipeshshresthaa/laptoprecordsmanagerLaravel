<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\SystemLookup;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LaptopController extends Controller
{
    public function index(Request $request)
    {
        $showDisposed = $request->boolean('show_disposed');
        $search = $request->input('search');

        // Start query and eager load relationships to prevent N+1 performance issues
        $query = Laptop::with(['brand', 'model']);

        // Handle Active/Disposed Toggle
        if (! $showDisposed) {
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
            'statuses' => ['Available', 'Assigned', 'In repair', 'Disposed'],
        ];
    }

    public function create()
    {
        $options = $this->getFormOptions();
        $laptop = new Laptop; // Empty instance for the view
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
        return $this->saveLaptop($request, new Laptop);
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
            'serial_number' => [
                'required',
                'string',
                Rule::unique('laptops', 'serial_number')->ignore($laptop->id),
            ],
            'brand_id' => 'required|exists:system_lookups,id',
            'model_id' => 'required|exists:system_lookups,id',
            'purchase_date' => 'required|date|before_or_equal:today',
            'photo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Exclude 'photo' from mass assignment so we can handle the binary conversion manually
        $laptop->fill($request->except(['photo', '_token', '_method', 'status']));

        // NEW: Convert uploaded image to raw binary and store in DB
        if ($request->hasFile('photo')) {
            $laptop->laptop_photo = base64_encode(file_get_contents($request->file('photo')->getRealPath()));
        }
        if (! $laptop->exists) {
            $laptop->created_by_id = Auth::id();
            $laptop->status = 'Available';
        } else {
            $laptop->modified_by_id = Auth::id();
            // Status changes for existing laptops will be handled by the new event system below
        }

        if (! $laptop->exists) {
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

    public function show(Laptop $laptop)
    {
        $timeline = $this->getTimelineData($laptop);

        return view('laptops.history', compact('laptop', 'timeline'));
    }

    public function downloadHistoryPdf(Laptop $laptop)
    {
        $timeline = $this->getTimelineData($laptop);

        // Generates the PDF using a dedicated clean HTML view
        $pdf = Pdf::loadView('pdfs.laptop_history', compact('laptop', 'timeline'));

        $filename = "Laptop_History_Report_{$laptop->serial_number}.pdf";

        return $pdf->stream($filename);
    }

    private function getTimelineData(Laptop $laptop)
    {
        $laptop->load(['brand', 'model', 'processor', 'ramSize', 'storageSize', 'screenSize', 'assignments.employee', 'repairs', 'upgrades']);
        $timeline = collect();

        // Priority logic for same-day events (Lower number = happens earlier in the day)
        // 10: Purchase, 20: Upgrade, 30: Assignment,40: Employee Return,50: Repair Dispatch,    60: Repair Return, 70: Disposal

        $timeline->push((object) [
            'date' => $laptop->purchase_date,
            'priority' => 10,
            'type' => 'Purchase',
            'icon' => '📦',
            'color' => 'bg-emerald-100 text-emerald-600',
            'title' => 'Hardware purchased',
            'details' => 'Added to inventory. Status set to Available.',
        ]);

        foreach ($laptop->assignments as $assignment) {
            $timeline->push((object) [
                'date' => $assignment->assigned_date,
                'priority' => 30,
                'type' => 'Assignment',
                'icon' => '👨‍💻',
                'color' => 'bg-blue-100 text-blue-600',
                'title' => "Assigned to {$assignment->employee->full_name}",
                'details' => "Department: {$assignment->employee->department}",
            ]);

            if ($assignment->returned_date) {
                $timeline->push((object) [
                    'date' => $assignment->returned_date,
                    'priority' => 40,
                    'type' => 'Return',
                    'icon' => '↩️',
                    'color' => 'bg-amber-100 text-amber-600',
                    'title' => "Returned by {$assignment->employee->full_name}",
                    'details' => "Condition: {$assignment->return_condition}. Notes: {$assignment->return_reason}",
                ]);
            }
        }

        foreach ($laptop->repairs as $repair) {
            $timeline->push((object) [
                'date' => $repair->sent_date,
                'priority' => 50,
                'type' => 'Repair dispatch',
                'icon' => '🛠️',
                'color' => 'bg-rose-100 text-rose-600',
                'title' => "Sent to {$repair->vendor_name} for repair",
                'details' => "Issue: {$repair->issue_description}",
            ]);

            if ($repair->returned_date) {
                $timeline->push((object) [
                    'date' => $repair->returned_date,
                    'priority' => 60,
                    'type' => 'Repair return',
                    'icon' => '📥',
                    'color' => 'bg-emerald-100 text-emerald-600',
                    'title' => "Received back from {$repair->vendor_name}",
                    'details' => "Resolution: {$repair->repair_notes} | Cost: Rs. ".number_format($repair->repair_cost, 2),
                ]);
            }
        }

        foreach ($laptop->upgrades as $upgrade) {
            $timeline->push((object) [
                'date' => $upgrade->upgrade_date,
                'priority' => 20,
                'type' => 'Hardware upgrade',
                'icon' => '⚙️',
                'color' => 'bg-purple-100 text-purple-600',
                'title' => "{$upgrade->upgrade_type} upgraded",
                'details' => "Changed from {$upgrade->previous_spec} to {$upgrade->new_spec}. Cost: Rs. ".number_format($upgrade->cost, 2),
            ]);
        }

        if ($laptop->is_disposed && $laptop->disposal_date) {
            $timeline->push((object) [
                'date' => $laptop->disposal_date,
                'priority' => 70,
                'type' => 'Disposal',
                'icon' => '🗑️',
                'color' => 'bg-slate-200 text-slate-600',
                'title' => 'Laptop disposed as '.strtolower($laptop->disposal_method),
                'details' => "Reason: {$laptop->disposal_reason}",
            ]);
        }

        // CUSTOM SORT: Sort by Date Descending, then by Priority Descending
        return $timeline->sort(function ($a, $b) {
            if ($a->date->eq($b->date)) {
                return $b->priority <=> $a->priority; // Same day: Highest priority number goes to top
            }

            return $b->date <=> $a->date; // Different days: Newest date goes to top
        })->values();
    }

    public function getNextFaSuggestions()
    {
        $laptops = Laptop::whereNotNull('laptop_fa_code')->pluck('laptop_fa_code');
        $suggestions = [];

        foreach ($laptops as $code) {
            // Regex to split prefix from number (e.g., NAC-LAPT-001 -> [NAC-LAPT-, 001])
            if (preg_match('/^(.*?)(\d+)$/', $code, $matches)) {
                $prefix = $matches[1];
                $number = intval($matches[2]);

                if (! isset($suggestions[$prefix]) || $number > $suggestions[$prefix]) {
                    $suggestions[$prefix] = $number;
                }
            }
        }

        $finalSuggestions = [];
        foreach ($suggestions as $prefix => $lastNum) {
            $nextNum = str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
            $finalSuggestions[] = $prefix.$nextNum;
        }

        return response()->json($finalSuggestions);
    }
}
