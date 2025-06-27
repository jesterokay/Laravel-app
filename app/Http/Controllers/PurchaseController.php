<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Employee;
use App\Models\Product;
use App\Models\TaxRate;
use App\Models\InventorySummary;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-purchases')) {
            throw UnauthorizedException::forPermissions(['view-purchases']);
        }

        $purchases = Purchase::with(['supplier', 'user'])->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-purchases')) {
            throw UnauthorizedException::forPermissions(['create-purchases']);
        }

        $suppliers = Supplier::all();
        $employees = Employee::all();
        $products = Product::all();
        $taxRates = TaxRate::whereNull('end_date')->orWhere('end_date', '>=', now())->get();
        return view('purchases.create', compact('suppliers', 'employees', 'products', 'taxRates'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('create-purchases')) {
            throw UnauthorizedException::forPermissions(['create-purchases']);
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id' => 'required|exists:employees,id',
            'status' => 'required|in:completed,pending,cancelled',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $taxAmount = 0;

            // Generate invoice number
            $latestPurchase = Purchase::latest()->first();
            $invoiceNumber = 'PUR-' . str_pad(($latestPurchase ? $latestPurchase->id + 1 : 1), 6, '0', STR_PAD_LEFT);

            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => $validated['user_id'],
                'invoice_number' => $invoiceNumber,
                'status' => $validated['status'],
                'total_amount' => 0,
                'tax_amount' => 0,
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $quantity = $item['quantity'];
                $unitPrice = $item['unit_price'];
                $subtotal = $unitPrice * $quantity;
                $itemTaxAmount = 0;

                if (isset($item['tax_rate_id'])) {
                    $taxRate = TaxRate::find($item['tax_rate_id']);
                    $itemTaxAmount = $subtotal * ($taxRate->rate / 100);
                }

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'tax_amount' => $itemTaxAmount,
                    'tax_rate_id' => $item['tax_rate_id'] ?? null,
                ]);

                $totalAmount += $subtotal;
                $taxAmount += $itemTaxAmount;

                // Increase stock quantity
                $product->increment('stock_quantity', $quantity);

                // Update or create InventorySummary
                $inventorySummary = InventorySummary::updateOrCreate(
                    [
                        'product_id' => $item['product_id'],
                    ],
                    [
                        'stock_quantity' => $product->stock_quantity,
                        'last_updated' => now(),
                    ]
                );
            }

            $purchase->update([
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
            ]);

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create purchase: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        if (!auth()->user()->hasPermissionTo('view-purchases')) {
            throw UnauthorizedException::forPermissions(['view-purchases']);
        }

        $purchase->load(['supplier', 'user', 'purchaseItems.product', 'purchaseItems.taxRate']);
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        if (!auth()->user()->hasPermissionTo('edit-purchases')) {
            throw UnauthorizedException::forPermissions(['edit-purchases']);
        }

        $suppliers = Supplier::all();
        $employees = Employee::all();
        $products = Product::all();
        $taxRates = TaxRate::whereNull('end_date')->orWhere('end_date', '>=', now())->get();
        $purchase->load('purchaseItems');
        return view('purchases.edit', compact('purchase', 'suppliers', 'employees', 'products', 'taxRates'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        if (!auth()->user()->hasPermissionTo('edit-purchases')) {
            throw UnauthorizedException::forPermissions(['edit-purchases']);
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id' => 'required|exists:employees,id',
            'status' => 'required|in:completed,pending,cancelled',
            'items' => 'required|array',
            'items.*.id' => 'nullable|exists:purchase_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        DB::beginTransaction();
        try {
            // Restore stock for existing items
            foreach ($purchase->purchaseItems as $item) {
                $product = Product::find($item->product_id);
                $product->decrement('stock_quantity', $item->quantity);

                // Update InventorySummary
                $inventorySummary = InventorySummary::where([
                    'product_id' => $item->product_id,
                ])->first();
                if ($inventorySummary) {
                    $inventorySummary->update([
                        'stock_quantity' => $product->stock_quantity,
                        'last_updated' => now(),
                    ]);
                }
            }

            $totalAmount = 0;
            $taxAmount = 0;

            // Delete existing items
            $existingItemIds = $purchase->purchaseItems->pluck('id')->toArray();
            $submittedItemIds = array_filter(array_column($validated['items'], 'id'));
            $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
            if (!empty($itemsToDelete)) {
                PurchaseItem::whereIn('id', $itemsToDelete)->delete();
            }

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $quantity = $item['quantity'];
                $unitPrice = $item['unit_price'];
                $subtotal = $unitPrice * $quantity;
                $itemTaxAmount = 0;

                if (isset($item['tax_rate_id'])) {
                    $taxRate = TaxRate::find($item['tax_rate_id']);
                    $itemTaxAmount = $subtotal * ($taxRate->rate / 100);
                }

                $purchaseItemData = [
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'tax_amount' => $itemTaxAmount,
                    'tax_rate_id' => $item['tax_rate_id'] ?? null,
                ];

                if (isset($item['id']) && $item['id']) {
                    PurchaseItem::where('id', $item['id'])->update($purchaseItemData);
                } else {
                    PurchaseItem::create($purchaseItemData);
                }

                $totalAmount += $subtotal;
                $taxAmount += $itemTaxAmount;

                // Increase stock quantity
                $product->increment('stock_quantity', $quantity);

                // Update or create InventorySummary
                $inventorySummary = InventorySummary::updateOrCreate(
                    [
                        'product_id' => $item['product_id'],
                    ],
                    [
                        'stock_quantity' => $product->stock_quantity,
                        'last_updated' => now(),
                    ]
                );
            }

            $purchase->update([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => $validated['user_id'],
                'status' => $validated['status'],
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
            ]);

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update purchase: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Purchase $purchase)
    {
        if (!auth()->user()->hasPermissionTo('delete-purchases')) {
            throw UnauthorizedException::forPermissions(['delete-purchases']);
        }

        DB::beginTransaction();
        try {
            // Restore stock for items
            foreach ($purchase->purchaseItems as $item) {
                $product = Product::find($item->product_id);
                $product->decrement('stock_quantity', $item->quantity);

                // Update InventorySummary
                $inventorySummary = InventorySummary::where([
                    'product_id' => $item->product_id,
                ])->first();
                if ($inventorySummary) {
                    $inventorySummary->update([
                        'stock_quantity' => $product->stock_quantity,
                        'last_updated' => now(),
                    ]);
                }
            }

            $purchase->purchaseItems()->delete();
            $purchase->delete();

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete purchase: ' . $e->getMessage());
        }
    }
}