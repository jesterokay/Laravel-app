@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Inventory Adjustment</h1>
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('inventory_summaries.update', $inventorySummary) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium">Product</label>
                <select name="product_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $inventorySummary->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }} (Current Stock: {{ $product->stock_quantity }})</option>
                    @endforeach
                </select>
                @error('product_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Adjustment Type</label>
                <select name="adjustment_type" class="mt-1 block w-full border rounded p-2">
                    <option value="increase">Increase</option>
                    <option value="decrease">Decrease</option>
                </select>
                @error('adjustment_type') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Adjustment Quantity</label>
                <input type="number" name="quantity" min="1" class="mt-1 block w-full border rounded p-2" value="1">
                @error('quantity') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Reason (Optional)</label>
                <input type="text" name="reason" class="mt-1 block w-full border rounded p-2" placeholder="e.g., Stock take, Damage" value="{{ old('reason') }}">
                @error('reason') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Apply Adjustment</button>
        </form>
    </div>
@endsection