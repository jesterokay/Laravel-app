@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Expense</h1>
        <form action="{{ route('expenses.update', $expense) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium">Employee</label>
                <select name="user_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $expense->user_id == $employee->id ? 'selected' : '' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Category</label>
                <select name="category_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $expense->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Amount</label>
                <input type="number" name="amount" step="0.01" class="mt-1 block w-full border rounded p-2" value="{{ $expense->amount }}">
                @error('amount') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="mt-1 block w-full border rounded p-2">{{ $expense->description }}</textarea>
                @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Expense Date</label>
                <input type="date" name="expense_date" class="mt-1 block w-full border rounded p-2" value="{{ $expense->expense_date->format('Y-m-d') }}">
                @error('expense_date') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
@endsection