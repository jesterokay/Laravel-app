@extends('layouts.app')
@section('title', 'Customer Details')
@section('content')
<style>
    .container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        padding: 40px;
        width: 100%;
        max-width: 800px;
        margin: 20px auto;
    }

    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
        font-size: 2.5em;
    }

    .profile-image {
        max-width: 200px;
        height: auto;
        border-radius: 10px;
        margin-bottom: 20px;
        display: block;
        margin-left: auto;
        margin-right: auto;
        object-fit: cover;
    }

    .no-image {
        text-align: center;
        color: #666;
        margin-bottom: 20px;
    }

    .alert {
        margin-bottom: 20px;
    }

    p, ul {
        margin-bottom: 15px;
        color: #333;
    }

    h3 {
        color: #333;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    .btn {
        margin-right: 10px;
        margin-top: 10px;
    }
</style>

<div class="container">
    <h1>Customer: {{ $customer->first_name }} {{ $customer->last_name }}</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $customer->first_name }} {{ $customer->last_name }}" class="profile-image">
            @else
                <p class="no-image">No profile image available</p>
            @endif
            <p><strong>Email:</strong> {{ $customer->email ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($customer->status) }}</p>
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
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('customers.index') }}" class="btn btn-primary">Back to Customers</a>
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
</div>
@endsection