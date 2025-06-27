@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Sale Item</h1>
        <form action="{{ route('sale_items.update', $saleItem) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium">Sale</label>
                <select name="sale_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($sales as $sale)
                        <option value="{{ $sale->id }}" {{ $saleItem->sale_id == $sale->id ? 'selected' : '' }}>Sale #{{ $sale->id }}</option>
                    @endforeach
                </select>
                @error('sale_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Product</label>
                <select name="product_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $saleItem->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Quantity</label>
                <input type="number" name="quantity" class="mt-1 block w-full border rounded p-2" value="{{ $saleItem->quantity }}">
                @error('quantity') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Unit Price</label>
                <input type="number" name="unit_price" step="0.01" class="mt-1 block w-full border rounded p-2" value="{{ $saleItem->unit_price }}">
                @error('unit_price') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Subtotal</label>
                <input type="number" name="subtotal" step="0.01" class="mt-1 block w-full border rounded p-2" value="{{ $saleItem->subtotal }}">
                @error('subtotal') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Tax Amount</label>
                <input type="number" name="tax_amount" step="0.01" class="mt-1 block w-full border rounded p-2" value="{{ $saleItem->tax_amount }}">
                @error('tax_amount') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
@endsection