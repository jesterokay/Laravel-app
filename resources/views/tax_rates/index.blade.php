@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Tax Rates</h1>
        <a href="{{ route('tax_rates.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Tax Rate</a>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Rate (%)</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($taxRates as $taxRate)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $taxRate->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $taxRate->rate }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('tax_rates.show', $taxRate) }}" class="text-blue-500">View</a>
                            <a href="{{ route('tax_rates.edit', $taxRate) }}" class="text-green-500 ml-2">Edit</a>
                            <form action="{{ route('tax_rates.destroy', $taxRate) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $taxRates->links() }}
    </div>
@endsection