@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Purchases</h1>
        <a href="{{ route('purchases.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Purchase</a>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Supplier</th>
                    <th class="py-2 px-4 border-b">Total Amount</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchases as $purchase)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $purchase->supplier->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $purchase->total_amount }}</td>
                        <td class="py-2 px-4 border-b">{{ $purchase->status }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('purchases.show', $purchase) }}" class="text-blue-500">View</a>
                            <a href="{{ route('purchases.edit', $purchase) }}" class="text-green-500 ml-2">Edit</a>
                            <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $purchases->links() }}
    </div>
@endsection