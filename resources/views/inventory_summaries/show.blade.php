@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Inventory Summary Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Product:</strong> {{ $inventorySummary->product->name }}</p>
            <p><strong>Stock Quantity:</strong> {{ $inventorySummary->stock_quantity }}</p>
            <p><strong>Last Updated:</strong> {{ $inventorySummary->last_updated->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
@endsection