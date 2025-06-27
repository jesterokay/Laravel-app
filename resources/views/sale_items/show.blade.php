@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Sale Item Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Sale ID:</strong> {{ $saleItem->sale_id }}</p>
            <p><strong>Product:</strong> {{ $saleItem->product->name }}</p>
            <p><strong>Quantity:</strong> {{ $saleItem->quantity }}</p>
            <p><strong>Unit Price:</strong> {{ $saleItem->unit_price }}</p>
            <p><strong>Subtotal:</strong> {{ $saleItem->subtotal }}</p>
            <p><strong>Tax Amount:</strong> {{ $saleItem->tax_amount }}</p>
            <div class="mt-4">
                <a href="{{ route('sale_items.edit', $saleItem) }}" class="bg-green-500 text-white px-4 py-2 rounded">Edit</a>
                <form action="{{ route('sale_items.destroy', $saleItem) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection