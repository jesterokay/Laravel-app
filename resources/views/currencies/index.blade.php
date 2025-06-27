@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Currencies</h1>
        <a href="{{ route('currencies.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Currency</a>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Code</th>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Symbol</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($currencies as $currency)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $currency->code }}</td>
                        <td class="py-2 px-4 border-b">{{ $currency->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $currency->symbol }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('currencies.show', $currency) }}" class="text-blue-500">View</a>
                            <a href="{{ route('currencies.edit', $currency) }}" class="text-green-500 ml-2">Edit</a>
                            <form action="{{ route('currencies.destroy', $currency) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $currencies->links() }}
    </div>
@endsection