@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Create Tax Rate</h1>
        <form action="{{ route('tax_rates.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name" class="mt-1 block w-full border rounded p-2" value="{{ old('name') }}">
                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Rate (%)</label>
                <input type="number" name="rate" step="0.01" class="mt-1 block w-full border rounded p-2" value="{{ old('rate') }}">
                @error('rate') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        </form>
    </div>
@endsection