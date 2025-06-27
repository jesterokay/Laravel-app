@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Purchase Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Supplier:</strong> {{ $purchase->supplier->name }}</p>
            <p><strong>Employee:</strong> {{ $purchase->user->first_name }} {{ $purchase->user->last_name }}</p>
            <p><strong>Total Amount:</strong> {{ $purchase->total_amount }}</p>
            <p><strong>Tax Amount:</strong> {{ $purchase->tax_amount }}</p>
            <p><strong>Status:</strong> {{ $purchase->status }}</p>
            <h2 class="text-lg font-semibold mt-4">Purchase Items</h2>
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
                    @foreach ($purchase->purchaseItems as $item)
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
                <a href="{{ route('purchases.edit', $purchase) }}" class="bg-green-500 text-white px-4 py-2 rounded">Edit</a>
                <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection