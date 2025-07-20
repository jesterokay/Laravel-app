@extends('layouts.app')
@section('title', 'Products')
@section('content')
    @can('view-products')
        <div class="container mx-auto p-6">
            <h1 class="text-2xl font-bold mb-4">Products</h1>
            @can('create-products')
                <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Product</a>
            @endcan
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Name</th>
                        <th class="py-2 px-4 border-b">Category</th>
                        <th class="py-2 px-4 border-b">Price</th>
                        <th class="py-2 px-4 border-b">Stock</th>
                        <th class="py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $product->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $product->category->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $product->price }}</td>
                            <td class="py-2 px-4 border-b">{{ $product->stock_quantity }}</td>
                            <td class="py-2 px-4 border-b">
                                <a href="{{ route('products.show', $product) }}" class="text-blue-500">View</a>
                                @can('edit-products')
                                    <a href="{{ route('products.edit', $product) }}" class="text-green-500 ml-2">Edit</a>
                                @endcan
                                @can('delete-products')
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    @else
        <div class="container mx-auto p-6">
            <p class="text-red-500">You do not have permission to view products.</p>
        </div>
    @endcan
@endsection