<?php

namespace App\Http\Controllers;

use App\Models\InventorySummary;
use App\Models\InventoryAdjustment;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\DB;

class InventorySummaryController extends Controller
{
    public function index()
    {
        $inventorySummaries = InventorySummary::with(['product'])->paginate(10);
        return view('inventory_summaries.index', compact('inventorySummaries'));
    }

    public function create()
    {
        $products = Product::all();
        return view('inventory_summaries.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::find($validated['product_id']);
            $inventorySummary = InventorySummary::firstOrCreate(
                [
                    'product_id' => $validated['product_id'],
                ],
                [
                    'stock_quantity' => $product->stock_quantity,
                    'last_updated' => now(),
                ]
            );

            $newStockQuantity = $inventorySummary->stock_quantity;
            if ($validated['adjustment_type'] === 'increase') {
                $newStockQuantity += $validated['quantity'];
            } else {
                $newStockQuantity -= $validated['quantity'];
                if ($newStockQuantity < 0) {
                    throw new \Exception("Cannot decrease stock below 0 for product {$product->name}. Current stock: {$inventorySummary->stock_quantity}.");
                }
            }
            $inventorySummary->update([
                'stock_quantity' => $newStockQuantity,
                'last_updated' => now(),
            ]);
            $product->update(['stock_quantity' => $newStockQuantity]);
            InventoryAdjustment::create([
                'inventory_summary_id' => $inventorySummary->id,
                'product_id' => $validated['product_id'],
                'adjustment_type' => $validated['adjustment_type'],
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'],
                'adjusted_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('inventory_summaries.index')->with('success', 'Inventory adjustment completed, and product stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(InventorySummary $inventorySummary)
    {
        $inventorySummary->load(['product']);
        return view('inventory_summaries.show', compact('inventorySummary'));
    }

    public function edit(InventorySummary $inventorySummary)
    {
        $products = Product::all();
        return view('inventory_summaries.edit', compact('inventorySummary', 'products'));
    }

    public function update(Request $request, InventorySummary $inventorySummary)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::find($validated['product_id']);
            $newStockQuantity = $inventorySummary->stock_quantity;

            if ($validated['adjustment_type'] === 'increase') {
                $newStockQuantity += $validated['quantity'];
            } else {
                $newStockQuantity -= $validated['quantity'];
                if ($newStockQuantity < 0) {
                    throw new \Exception("Cannot decrease stock below 0 for product {$product->name}. Current stock: {$inventorySummary->stock_quantity}.");
                }
            }
            $inventorySummary->update([
                'product_id' => $validated['product_id'],
                'stock_quantity' => $newStockQuantity,
                'last_updated' => now(),
            ]);
            $product->update(['stock_quantity' => $newStockQuantity]);
            InventoryAdjustment::create([
                'inventory_summary_id' => $inventorySummary->id,
                'product_id' => $validated['product_id'],
                'adjustment_type' => $validated['adjustment_type'],
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'],
                'adjusted_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('inventory_summaries.index')->with('success', 'Inventory adjustment updated, and product stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(InventorySummary $inventorySummary)
    {
        DB::beginTransaction();
        try {
            $product = Product::find($inventorySummary->product_id);
            if ($product) {                $newStockQuantity = $product->stock_quantity - $inventorySummary->stock_quantity;
                if ($newStockQuantity < 0) {
                    throw new \Exception("Cannot delete inventory summary as it would result in negative stock for product {$product->name}.");
                }
                $product->update(['stock_quantity' => $newStockQuantity]);
            }
            $inventorySummary->delete();

            DB::commit();
            return redirect()->route('inventory_summaries.index')->with('success', 'Inventory summary deleted successfully, and product stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete inventory summary: ' . $e->getMessage());
        }
    }
}