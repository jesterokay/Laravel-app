@extends('layouts.app')
@section('title', 'Customer Details')
@section('content')
    <div class="container">
        <h1>{{ $customer->first_name }} {{ $customer->last_name }}</h1>
        <p><strong>Email:</strong> {{ $customer->email ?? 'N/A' }}</p>
        <p><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ $customer->status }}</p>
        <p><strong>Image:</strong></p>
        <img src="{{ $customer->image }}" alt="{{ $customer->first_name }} {{ $customer->last_name }}" class="w-32 h-32 object-cover">
        <h3>Contacts</h3>
        @if ($customer->contacts->isEmpty())
            <p>No contacts available.</p>
        @else
            <ul>
                @foreach ($customer->contacts as $contact)
                    <li>{{ $contact->name }} ({{ $contact->email ?? 'N/A' }}, {{ $contact->phone ?? 'N/A' }})</li>
                @endforeach
            </ul>
        @endif

        <h3>Leads</h3>
        @if ($customer->leads->isEmpty())
            <p>No leads available.</p>
        @else
            <ul>
                @foreach ($customer->leads as $lead)
                    <li>{{ $lead->title }} - {{ $lead->status }}</li>
                @endforeach
            </ul>
        @endif

        <h3>Tasks</h3>
        @if ($customer->tasks->isEmpty())
            <p>No tasks available.</p>
        @else
            <ul>
                @foreach ($customer->tasks as $task)
                    <li>{{ $task->title }} - {{ $task->status }}</li>
                @endforeach
            </ul>
        @endif

        <h3>Follow-Ups</h3>
        @if ($customer->followUps->isEmpty())
            <p>No follow-ups available.</p>
        @else
            <ul>
                @foreach ($customer->followUps as $followUp)
                    <li>{{ $followUp->notes ?? 'N/A' }} - {{ $followUp->follow_up_date }} ({{ $followUp->status }})</li>
                @endforeach
            </ul>
        @endif

        <h3>Sales</h3>
        @if ($customer->sales->isEmpty())
            <p>No sales available.</p>
        @else
            <ul>
                @foreach ($customer->sales as $sale)
                    <li>{{ $sale->amount }} - {{ $sale->sale_date }} ({{ $sale->status }})</li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
        @can('edit-customers')
            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">Edit</a>
        @endcan
        @can('delete-customers')
            <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        @endcan
    </div>
@endsection