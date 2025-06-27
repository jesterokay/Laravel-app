@extends('layouts.app')
@section('title', 'Customers')
@section('content')
    <div class="container">
        <h1>Customers</h1>
        @can('create-customers')
            <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">Add Customer</a>
        @endcan
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                        <td>{{ $customer->email ?? 'N/A' }}</td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td>{{ $customer->status }}</td>
                        <td>
                            @can('view-customers')
                                <a href="{{ route('customers.show', $customer) }}" class="btn btn-info btn-sm">View</a>
                            @endcan
                            @can('edit-customers')
                                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm">Edit</a>
                            @endcan
                            @can('delete-customers')
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $customers->links() }}
    </div>
@endsection