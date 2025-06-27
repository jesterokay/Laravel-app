@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Purchase Items</h1>
        <a href="{{ route('purchase_items.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Purchase Item</a>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Purchase ID</th>
                    <th class="py-2 px-4 border-b">Product</th>
                    <th class="py-2 px-4 border-b">Quantity</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseItems as $purchaseItem)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $purchaseItem->purchase_id }}</td>
                        <td class="py-2 px-4 border-b">{{ $purchaseItem->product->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $purchaseItem->quantity }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('purchase_items.show', $purchaseItem) }}" class="text-blue-500">View</a>
                            <a href="{{ route('purchase_items.edit', $purchaseItem) }}" class="text-green-500 ml-2">Edit</a>
                            <form action="{{ route('purchase_items.destroy', $purchaseItem) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $purchaseItems->links() }}
    </div>
@endsection