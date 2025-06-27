<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\TaxRate;
use App\Models\InventorySummary;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-sales')) {
            throw UnauthorizedException::forPermissions(['view-sales']);
        }

        $sales = Sale::with(['customer'])->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-sales')) {
            throw UnauthorizedException::forPermissions(['create-sales']);
        }

        $customers = Customer::all();
        $employees = Employee::all();
        $products = Product::all();
        $taxRates = TaxRate::all();

        return view('sales.create', compact('customers', 'employees', 'products', 'taxRates'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('create-sales')) {
            throw UnauthorizedException::forPermissions(['create-sales']);
        }

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'user_id' => 'required|exists:employees,id',
            'status' => 'required|in:completed,pending,cancelled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        // Validate stock availability
        foreach ($validated['items'] as $index => $item) {
            $product = Product::findOrFail($item['product_id']);
            if ($product->stock_quantity < $item['quantity']) {
                return redirect()->back()->withErrors([
                    "items.$index.quantity" => "Insufficient stock for product {$product->name}. Available: {$product->stock_quantity}, Requested: {$item['quantity']}",
                ])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $taxAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $subtotal = $product->price * $quantity;
                $taxRate = isset($item['tax_rate_id']) ? TaxRate::find($item['tax_rate_id']) : null;
                $itemTaxAmount = $taxRate ? ($subtotal * $taxRate->rate / 100) : 0;
                $totalAmount += $subtotal + $itemTaxAmount;
                $taxAmount += $itemTaxAmount;
            }

            // Generate unique invoice number
            $latestSale = Sale::latest()->first();
            $invoiceNumber = 'INV-' . str_pad(($latestSale ? $latestSale->id + 1 : 1), 8, '0', STR_PAD_LEFT);

            $sale = Sale::create([
                'customer_id' => $validated['customer_id'] ?: null,
                'user_id' => $validated['user_id'],
                'invoice_number' => $invoiceNumber,
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'status' => $validated['status'],
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $unit_price = $product->price;
                $subtotal = $unit_price * $quantity;
                $taxRate = isset($item['tax_rate_id']) ? TaxRate::find($item['tax_rate_id']) : null;
                $itemTaxAmount = $taxRate ? ($subtotal * $taxRate->rate / 100) : 0;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $quantity,
                    'unit_price' => $unit_price,
                    'subtotal' => $subtotal,
                    'tax_amount' => $itemTaxAmount,
                    'tax_rate_id' => $item['tax_rate_id'] ?? null,
                ]);

                // Deduct stock quantity
                $product->decrement('stock_quantity', $quantity);

                // Update or create InventorySummary
                InventorySummary::updateOrCreate(
                    ['product_id' => $item['product_id']],
                    ['stock_quantity' => $product->stock_quantity, 'last_updated' => now()]
                );
            }

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create sale: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Sale $sale)
    {
        if (!auth()->user()->hasPermissionTo('view-sales')) {
            throw UnauthorizedException::forPermissions(['view-sales']);
        }

        $sale->load('saleItems.product', 'saleItems.taxRate', 'customer', 'user');
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        if (!auth()->user()->hasPermissionTo('edit-sales')) {
            throw UnauthorizedException::forPermissions(['edit-sales']);
        }

        $sale->load('saleItems.product', 'saleItems.taxRate');
        $customers = Customer::all();
        $employees = Employee::all();
        $products = Product::all();
        $taxRates = TaxRate::all();

        return view('sales.edit', compact('sale', 'customers', 'employees', 'products', 'taxRates'));
    }

    public function update(Request $request, Sale $sale)
    {
        if (!auth()->user()->hasPermissionTo('edit-sales')) {
            throw UnauthorizedException::forPermissions(['edit-sales']);
        }

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'user_id' => 'required|exists:employees,id',
            'status' => 'required|in:completed,pending,cancelled',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:sale_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        DB::beginTransaction();
        try {
            // Restore original quantities
            foreach ($sale->saleItems as $saleItem) {
                $product = Product::findOrFail($saleItem->product_id);
                $product->increment('stock_quantity', $saleItem->quantity);

                InventorySummary::updateOrCreate(
                    ['product_id' => $saleItem->product_id],
                    ['stock_quantity' => $product->stock_quantity, 'last_updated' => now()]
                );
            }

            // Validate new stock availability
            foreach ($validated['items'] as $index => $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock_quantity < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->back()->withErrors([
                        "items.$index.quantity" => "Insufficient stock for product {$product->name}. Available: {$product->stock_quantity}, Requested: {$item['quantity']}",
                    ])->withInput();
                }
            }

            $totalAmount = 0;
            $taxAmount = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $subtotal = $product->price * $quantity;
                $taxRate = isset($item['tax_rate_id']) ? TaxRate::find($item['tax_rate_id']) : null;
                $itemTaxAmount = $taxRate ? ($subtotal * $taxRate->rate / 100) : 0;
                $totalAmount += $subtotal + $itemTaxAmount;
                $taxAmount += $itemTaxAmount;
            }

            $sale->update([
                'customer_id' => $validated['customer_id'] ?: null,
                'user_id' => $validated['user_id'],
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'status' => $validated['status'],
            ]);

            $existingItemIds = $sale->saleItems->pluck('id')->toArray();
            $submittedItemIds = array_filter(array_column($validated['items'], 'id'));

            $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
            if (!empty($itemsToDelete)) {
                SaleItem::whereIn('id', $itemsToDelete)->delete();
            }

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $unit_price = $product->price;
                $subtotal = $unit_price * $quantity;
                $taxRate = isset($item['tax_rate_id']) ? TaxRate::find($item['tax_rate_id']) : null;
                $itemTaxAmount = $taxRate ? ($subtotal * $taxRate->rate / 100) : 0;

                $saleItemData = [
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $quantity,
                    'unit_price' => $unit_price,
                    'subtotal' => $subtotal,
                    'tax_amount' => $itemTaxAmount,
                    'tax_rate_id' => $item['tax_rate_id'] ?? null,
                ];

                if (isset($item['id']) && $item['id']) {
                    SaleItem::where('id', $item['id'])->update($saleItemData);
                } else {
                    SaleItem::create($saleItemData);
                }

                $product->decrement('stock_quantity', $quantity);

                InventorySummary::updateOrCreate(
                    ['product_id' => $item['product_id']],
                    ['stock_quantity' => $product->stock_quantity, 'last_updated' => now()]
                );
            }

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update sale: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Sale $sale)
    {
        if (!auth()->user()->hasPermissionTo('delete-sales')) {
            throw UnauthorizedException::forPermissions(['delete-sales']);
        }

        DB::beginTransaction();
        try {
            // Restore stock quantities
            foreach ($sale->saleItems as $saleItem) {
                $product = Product::findOrFail($saleItem->product_id);
                $product->increment('stock_quantity', $saleItem->quantity);

                InventorySummary::updateOrCreate(
                    ['product_id' => $saleItem->product_id],
                    ['stock_quantity' => $product->stock_quantity, 'last_updated' => now()]
                );
            }

            $sale->saleItems()->delete();
            $sale->delete();

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete sale: ' . $e->getMessage());
        }
    }
}