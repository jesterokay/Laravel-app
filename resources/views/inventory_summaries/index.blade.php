@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Inventory Summaries</h1>
        <a href="{{ route('inventory_summaries.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create New Inventory Summary</a>
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
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Product</th>
                    <th class="py-2 px-4 border-b">Stock Quantity</th>
                    <th class="py-2 px-4 border-b">Last Updated</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventorySummaries as $inventorySummary)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->product ? $inventorySummary->product->name : 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->stock_quantity }}</td>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->last_updated->format('Y-m-d H:i:s') }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('inventory_summaries.show', $inventorySummary) }}" class="text-blue-500 mr-2">View</a>
                            <a href="{{ route('inventory_summaries.edit', $inventorySummary) }}" class="text-green-500 mr-2">Edit</a>
                            <form action="{{ route('inventory_summaries.destroy', $inventorySummary) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this inventory summary? This will adjust the product stock.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $inventorySummaries->links() }}
    </div>
@endsection