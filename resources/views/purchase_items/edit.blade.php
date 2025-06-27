@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Purchase Item</h1>
        <form action="{{ route('purchase_items.update', $purchaseItem) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium">Purchase</label>
                <select name="purchase_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($purchases as $purchase)
                        <option value="{{ $purchase->id }}" {{ $purchaseItem->purchase_id == $purchase->id ? 'selected' : '' }}>Purchase #{{ $purchase->id }}</option>
                    @endforeach
                </select>
                @error('purchase_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Product</label>
                <select name="product_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $purchaseItem->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Quantity</label>
                <input type="number" name="quantity" class="mt-1 block w-full border rounded p-2" value="{{ $purchaseItem->quantity }}">
                @error('quantity') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Unit Price</label>
                <input type="number" name="unit_price" step="0.01" class="mt-1 block w-full border rounded p-2" value="{{ $purchaseItem->unit_price }}">
                @error('unit_price') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Subtotal</label>
                <input type="number" name="subtotal" step="0.01" class="mt-1 block w-full border rounded p-2" value="{{ $purchaseItem->subtotal }}">
                @error('subtotal') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Tax Amount</label>
                <input type="number" name="tax_amount" step="0.01" class="mt-1 block w-full border rounded p-2" value="{{ $purchaseItem->tax_amount }}">
                @error('tax_amount') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Tax Rate</label>
                <select name="tax_rate_id" class="mt-1 block w-full border rounded p-2">
                    <option value="">None</option>
                    @foreach ($taxRates as $taxRate)
                        <option value="{{ $taxRate->id }}" {{ $purchaseItem->tax_rate_id == $taxRate->id ? 'selected' : '' }}>{{ $taxRate->rate }}%</option>
                    @endforeach
                </select>
                @error('tax_rate_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
@endsection