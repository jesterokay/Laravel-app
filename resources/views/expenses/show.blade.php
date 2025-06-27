@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Expense Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Employee:</strong> {{ $expense->user->first_name }} {{ $expense->user->last_name }}</p>
            <p><strong>Category:</strong> {{ $expense->category->name }}</p>
            <p><strong>Amount:</strong> {{ $expense->amount }}</p>
            <p><strong>Description:</strong> {{ $expense->description ?? 'N/A' }}</p>
            <p><strong>Expense Date:</strong> {{ $expense->expense_date->format('Y-m-d') }}</p>
            <div class="mt-4">
                <a href="{{ route('expenses.edit', $expense) }}" class="bg-green-500 text-white px-4 py-2 rounded">Edit</a>
                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection