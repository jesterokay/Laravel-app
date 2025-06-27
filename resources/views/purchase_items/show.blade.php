@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Purchase Item Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Purchase ID:</strong> {{ $purchaseItem->purchase_id }}</p>
            <p><strong>Product:</strong> {{ $purchaseItem->product->name }}</p>
            <p><strong>Quantity:</strong> {{ $purchaseItem->quantity }}</p>
            <p><strong>Unit Price:</strong> {{ $purchaseItem->unit_price }}</p>
            <p><strong>Subtotal:</strong> {{ $purchaseItem->subtotal }}</p>
            <p><strong>Tax Amount:</strong> {{ $purchaseItem->tax_amount }}</p>
            <p><strong>Tax Rate:</strong> {{ $purchaseItem->taxRate ? $purchaseItem->taxRate->rate . '%' : 'None' }}</p>
            <div class="mt-4">
                <a href="{{ route('purchase_items.edit', $purchaseItem) }}" class="bg-green-500 text-white px-4 py-2 rounded">Edit</a>
                <form action="{{ route('purchase_items.destroy', $purchaseItem) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection