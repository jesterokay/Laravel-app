<?php

namespace App\Http\Controllers;

use App\Models\PurchaseItem;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\TaxRate;
use Illuminate\Http\Request;

class PurchaseItemController extends Controller
{
    public function index()
    {
        $purchaseItems = PurchaseItem::with(['purchase', 'product'])->paginate(10);
        return view('purchase_items.index', compact('purchaseItems'));
    }

    public function create()
    {
        $purchases = Purchase::all();
        $products = Product::all();
        $taxRates = TaxRate::whereNull('end_date')->orWhere('end_date', '>=', now())->get();
        return view('purchase_items.create', compact('purchases', 'products', 'taxRates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        PurchaseItem::create($validated);

        return redirect()->route('purchase_items.index')->with('success', 'Purchase Item created successfully.');
    }

    public function show(PurchaseItem $purchaseItem)
    {
        $purchaseItem->load(['purchase', 'product', 'taxRate']);
        return view('purchase_items.show', compact('purchaseItem'));
    }

    public function edit(PurchaseItem $purchaseItem)
    {
        $purchases = Purchase::all();
        $products = Product::all();
        $taxRates = TaxRate::whereNull('end_date')->orWhere('end_date', '>=', now())->get();
        return view('purchase_items.edit', compact('purchaseItem', 'purchases', 'products', 'taxRates'));
    }

    public function update(Request $request, PurchaseItem $purchaseItem)
    {
        $validated = $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        $purchaseItem->update($validated);

        return redirect()->route('purchase_items.index')->with('success', 'Purchase Item updated successfully.');
    }

    public function destroy(PurchaseItem $purchaseItem)
    {
        $purchaseItem->delete();

        return redirect()->route('purchase_items.index')->with('success', 'Purchase Item deleted successfully.');
    }
}