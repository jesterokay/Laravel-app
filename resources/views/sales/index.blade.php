@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Sales</h1>
        <a href="{{ route('sales.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Sale</a>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Customer</th>
                    <th class="py-2 px-4 border-b">Total Amount</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $sale)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $sale->customer ? $sale->customer->first_name . ' ' . $sale->customer->last_name : 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $sale->total_amount }}</td>
                        <td class="py-2 px-4 border-b">{{ $sale->status }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('sales.show', $sale) }}" class="text-blue-500">View</a>
                            <a href="{{ route('sales.edit', $sale) }}" class="text-green-500 ml-2">Edit</a>
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $sales->links() }}
    </div>
@endsection