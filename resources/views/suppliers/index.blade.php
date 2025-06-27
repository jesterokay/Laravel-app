@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Suppliers</h1>
        <a href="{{ route('suppliers.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Supplier</a>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Email</th>
                    <th class="py-2 px-4 border-b">Phone</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $supplier->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $supplier->email ?? 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $supplier->phone ?? 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="text-blue-500">View</a>
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="text-green-500 ml-2">Edit</a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $suppliers->links() }}
    </div>
@endsection