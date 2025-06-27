@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Supplier Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Name:</strong> {{ $supplier->name }}</p>
            <p><strong>Email:</strong> {{ $supplier->email ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $supplier->phone ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $supplier->address ?? 'N/A' }}</p>
            <div class="mt-4">
                <a href="{{ route('suppliers.edit', $supplier) }}" class="bg-green-500 text-white px-4 py-2 rounded">Edit</a>
                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection