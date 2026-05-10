<?php

namespace App\Http\Controllers;

use App\Models\SystemLookup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- ADD THIS IMPORT

class SystemLookupController extends Controller
{
    public function index()
    {
        $lookups = SystemLookup::with('parent')
            ->orderBy('category')
            ->orderBy('value')
            ->get()
            ->groupBy('category');

        $brands = SystemLookup::query()->where('category', 'Brand')->orderBy('value','asc')->get();
        $categories = ['Brand', 'Model', 'Processor', 'RamSize', 'StorageSize', 'ScreenSize'];

        return view('lookups.index', compact('lookups', 'brands', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'parent_id' => 'nullable|exists:system_lookups,id',
            'value' => [
                'required',
                'string',
                'max:255',
                // PREVENT DUPLICATES: Must be unique for this specific category and parent
                Rule::unique('system_lookups')->where(function ($query) use ($request) {
                    return $query->where('category', $request->category)
                                 ->where('parent_id', $request->category === 'Model' ? $request->parent_id : null);
                })
            ],
        ]);

        if ($request->category === 'Model' && empty($request->parent_id)) {
            return back()->with('error', 'You must select a parent Brand when adding a Model.')->withInput();
        }

        SystemLookup::create([
            'category' => $request->category,
            'value' => $request->value,
            'parent_id' => $request->category === 'Model' ? $request->parent_id : null,
            'is_active' => true,
        ]);

        return back()->with('success', "{$request->value} added successfully.");
    }

    public function update(Request $request, SystemLookup $lookup)
    {
        $request->validate([
            'category' => 'required|string',
            'parent_id' => 'nullable|exists:system_lookups,id',
            'value' => [
                'required',
                'string',
                'max:255',
                // PREVENT DUPLICATES: Ignore the current item being edited so it can be saved
                Rule::unique('system_lookups')->where(function ($query) use ($request) {
                    return $query->where('category', $request->category)
                                 ->where('parent_id', $request->category === 'Model' ? $request->parent_id : null);
                })->ignore($lookup->id)
            ],
        ]);

        if ($request->category === 'Model' && empty($request->parent_id)) {
            return back()->with('error', 'You must select a parent Brand when editing a Model.')->withInput();
        }

        $lookup->update([
            'category' => $request->category,
            'value' => $request->value,
            'parent_id' => $request->category === 'Model' ? $request->parent_id : null,
        ]);

        return back()->with('success', "Entry updated successfully.");
    }

    public function destroy(SystemLookup $lookup)
    {
        $lookup->delete($lookup->id);
        return back()->with('success', 'Item removed successfully.');
    }
}