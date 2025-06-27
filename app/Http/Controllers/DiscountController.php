<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view-discounts'])->only(['index', 'show']);
        $this->middleware(['permission:create-discounts'])->only(['create', 'store']);
        $this->middleware(['permission:edit-discounts'])->only(['edit', 'update']);
        $this->middleware(['permission:delete-discounts'])->only(['destroy']);
    }

    public function index()
    {
        $discounts = Discount::all();
        return view('discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('discounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,flat',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'applies_to' => 'required|in:product,sale',
        ]);

        Discount::create($request->all());
        return redirect()->route('discounts.index')->with('success', 'Discount created successfully.');
    }

    public function show(Discount $discount)
    {
        return view('discounts.show', compact('discount'));
    }

    public function edit(Discount $discount)
    {
        return view('discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,flat',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'applies_to' => 'required|in:product,sale',
        ]);

        $discount->update($request->all());
        return redirect()->route('discounts.index')->with('success', 'Discount updated successfully.');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Discount deleted successfully.');
    }
}