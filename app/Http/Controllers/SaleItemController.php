<?php

namespace App\Http\Controllers;

use App\Models\SaleItem;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleItemController extends Controller
{
    public function index()
    {
        $saleItems = SaleItem::with(['sale', 'product'])->paginate(10);
        return view('sale_items.index', compact('saleItems'));
    }

    public function create()
    {
        $sales = Sale::all();
        $products = Product::all();
        return view('sale_items.create', compact('sales', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
        ]);

        SaleItem::create($validated);

        return redirect()->route('sale_items.index')->with('success', 'Sale Item created successfully.');
    }

    public function show(SaleItem $saleItem)
    {
        $saleItem->load(['sale', 'product']);
        return view('sale_items.show', compact('saleItem'));
    }

    public function edit(SaleItem $saleItem)
    {
        $sales = Sale::all();
        $products = Product::all();
        return view('sale_items.edit', compact('saleItem', 'sales', 'products'));
    }

    public function update(Request $request, SaleItem $saleItem)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
        ]);

        $saleItem->update($validated);

        return redirect()->route('sale_items.index')->with('success', 'Sale Item updated successfully.');
    }

    public function destroy(SaleItem $saleItem)
    {
        $saleItem->delete();

        return redirect()->route('sale_items.index')->with('success', 'Sale Item deleted successfully.');
    }
}