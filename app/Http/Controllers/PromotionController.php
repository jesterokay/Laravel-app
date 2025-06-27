<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();
        return view('promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,buy_x_get_y',
            'value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'applies_to' => 'required|in:product,sale',
            'conditions' => 'nullable|json',
        ]);

        Promotion::create($request->all());
        return redirect()->route('promotions.index')->with('success', 'Promotion created successfully.');
    }

    public function show(Promotion $promotion)
    {
        return view('promotions.show', compact('promotion'));
    }

    public function edit(Promotion $promotion)
    {
        return view('promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,buy_x_get_y',
            'value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'applies_to' => 'required|in:product,sale',
            'conditions' => 'nullable|json',
        ]);

        $promotion->update($request->all());
        return redirect()->route('promotions.index')->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('promotions.index')->with('success', 'Promotion deleted successfully.');
    }
}