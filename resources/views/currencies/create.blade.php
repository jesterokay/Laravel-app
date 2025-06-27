@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Create Currency</h1>

    <form action="{{ route('currencies.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium">Code</label>
            <input type="text" name="code" class="mt-1 block w-full border rounded p-2" value="{{ old('code') }}">
            @error('code')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" class="mt-1 block w-full border rounded p-2" value="{{ old('name') }}">
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Symbol</label>
            <input type="text" name="symbol" class="mt-1 block w-full border rounded p-2" value="{{ old('symbol') }}">
            @error('symbol')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
    </form>
</div>
@endsection
