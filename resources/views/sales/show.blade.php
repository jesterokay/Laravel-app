@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Sale Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Customer:</strong> {{ $sale->customer ? $sale->customer->first_name . ' ' . $sale->customer->last_name : 'N/A' }}</p>
            <p><strong>Employee:</strong> {{ $sale->user->first_name }} {{ $sale->user->last_name }}</p>
            <p><strong>Total Amount:</strong> {{ $sale->total_amount }}</p>
            <p><strong>Tax Amount:</strong> {{ $sale->tax_amount }}</p>
            <p><strong>Status:</strong> {{ $sale->status }}</p>
            <h2 class="text-lg font-semibold mt-4">Sale Items</h2>
            <table class="min-w-full bg-white border mt-2">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Product</th>
                        <th class="py-2 px-4 border-b">Quantity</th>
                        <th class="py-2 px-4 border-b">Unit Price</th>
                        <th class="py-2 px-4 border-b">Subtotal</th>
                        <th class="py-2 px-4 border-b">Tax Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->saleItems as $item)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $item->product->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $item->quantity }}</td>
                            <td class="py-2 px-4 border-b">{{ $item->unit_price }}</td>
                            <td class="py-2 px-4 border-b">{{ $item->subtotal }}</td>
                            <td class="py-2 px-4 border-b">{{ $item->tax_amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                <a href="{{ route('sales.edit', $sale) }}" class="bg-green-500 text-white px-4 py-2 rounded">Edit</a>
                <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection