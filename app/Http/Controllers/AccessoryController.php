<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    /**
     * Display a listing of accessories.
     */
    public function index()
    {
        $accessories = Accessory::orderBy('name')->get();
        return view('accessories.index', compact('accessories'));
    }

    /**
     * Show the form for creating a new accessory.
     */
    public function create()
    {
        return view('accessories.create');
    }

    /**
     * Store a newly created accessory in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:accessories',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Accessory::create($validated);

        return redirect()->route('accessories.index')
            ->with('success', 'Accessory created successfully!');
    }

    /**
     * Show the form for editing the specified accessory.
     */
    public function edit(Accessory $accessory)
    {
        return view('accessories.edit', compact('accessory'));
    }

    /**
     * Update the specified accessory in database.
     */
    public function update(Request $request, Accessory $accessory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:accessories,name,' . $accessory->id,
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $accessory->update($validated);

        return redirect()->route('accessories.index')
            ->with('success', 'Accessory updated successfully!');
    }

    /**
     * Remove the specified accessory from database.
     */
    public function destroy(Accessory $accessory)
    {
        $accessory->delete();

        return redirect()->route('accessories.index')
            ->with('success', 'Accessory deleted successfully!');
    }
}
