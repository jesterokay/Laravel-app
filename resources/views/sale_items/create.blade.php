@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Create Sale Item</h1>
        <form action="{{ route('sale_items.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium">Sale</label>
                <select name="sale_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($sales as $sale)
                        <option value="{{ $sale->id }}">Sale #{{ $sale->id }}</option>
                    @endforeach
                </select>
                @error('sale_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Product</label>
                <select name="product_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Quantity</label>
                <input type="number" name="quantity" class="mt-1 block w-full border rounded p-2" value="{{ old('quantity') }}">
                @error('quantity') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Unit Price</label>
                <input type="number" name="unit_price" step="0.01" class="mt-1 block w-full border rounded p-2" value="{{ old('unit_price') }}">
                @error('unit_price') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Subtotal</label>
                <input type="number" name="subtotal" step="0.01" class="mt-1 block w-full border rounded p-2" value="{{ old('subtotal') }}">
                @error('subtotal') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Tax Amount</label>
                <input type="number" name="tax_amount" step="0.01" class="mt-1 block w-full border rounded p-2" value="{{ old('tax_amount', 0) }}">
                @error('tax_amount') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        </form>
    </div>
@endsection