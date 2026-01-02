<?php

namespace App\Http\Controllers;

use App\Models\TermsCondition;
use Illuminate\Http\Request;

class TermsConditionController extends Controller
{
    /**
     * Display a listing of the terms and conditions.
     */
    public function index()
    {
        $termsConditions = TermsCondition::orderBy('display_order')->get();
        return view('terms_conditions.index', compact('termsConditions'));
    }

    /**
     * Show the form for creating a new terms and condition.
     */
    public function create()
    {
        return view('terms_conditions.create');
    }

    /**
     * Store a newly created terms and condition in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
            'display_order' => 'required|integer|min:0'
        ]);

        TermsCondition::create($validated);

        return redirect()->route('terms-conditions.index')
            ->with('success', 'Terms & Condition created successfully!');
    }

    /**
     * Show the form for editing the specified terms and condition.
     */
    public function edit(TermsCondition $termsCondition)
    {
        return view('terms_conditions.edit', compact('termsCondition'));
    }

    /**
     * Update the specified terms and condition in database.
     */
    public function update(Request $request, TermsCondition $termsCondition)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
            'display_order' => 'required|integer|min:0'
        ]);

        $termsCondition->update($validated);

        return redirect()->route('terms-conditions.index')
            ->with('success', 'Terms & Condition updated successfully!');
    }

    /**
     * Remove the specified terms and condition from database.
     */
    public function destroy(TermsCondition $termsCondition)
    {
        $termsCondition->delete();

        return redirect()->route('terms-conditions.index')
            ->with('success', 'Terms & Condition deleted successfully!');
    }
}
