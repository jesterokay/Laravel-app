<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use Illuminate\Http\Request;

class TaxRateController extends Controller
{
    public function index()
    {
        $taxRates = TaxRate::paginate(10);
        return view('tax_rates.index', compact('taxRates'));
    }

    public function create()
    {
        return view('tax_rates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        TaxRate::create($validated);

        return redirect()->route('tax_rates.index')->with('success', 'Tax Rate created successfully.');
    }

    public function show(TaxRate $taxRate)
    {
        return view('tax_rates.show', compact('taxRate'));
    }

    public function edit(TaxRate $taxRate)
    {
        return view('tax_rates.edit', compact('taxRate'));
    }

    public function update(Request $request, TaxRate $taxRate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        $taxRate->update($validated);

        return redirect()->route('tax_rates.index')->with('success', 'Tax Rate updated successfully.');
    }

    public function destroy(TaxRate $taxRate)
    {
        $taxRate->delete();

        return redirect()->route('tax_rates.index')->with('success', 'Tax Rate deleted successfully.');
    }
}