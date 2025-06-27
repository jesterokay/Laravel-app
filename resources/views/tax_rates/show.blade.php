@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Tax Rate Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Name:</strong> {{ $taxRate->name }}</p>
            <p><strong>Rate (%):</strong> {{ $taxRate->rate }}</p>
            <div class="mt-4">
                <a href="{{ route('tax_rates.edit', $taxRate) }}" class="bg-green-500 text-white px-4 py-2 rounded">Edit</a>
                <form action="{{ route('tax_rates.destroy', $taxRate) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection