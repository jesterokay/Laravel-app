@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Payment Methods</h1>
        <a href="{{ route('payment_methods.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Payment Method</a>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Active</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paymentMethods as $paymentMethod)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $paymentMethod->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $paymentMethod->is_active ? 'Yes' : 'No' }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('payment_methods.show', $paymentMethod) }}" class="text-blue-500">View</a>
                            <a href="{{ route('payment_methods.edit', $paymentMethod) }}" class="text-green-500 ml-2">Edit</a>
                            <form action="{{ route('payment_methods.destroy', $paymentMethod) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $paymentMethods->links() }}
    </div>
@endsection