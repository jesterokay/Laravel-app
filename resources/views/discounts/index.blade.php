@extends('layouts.app')
@section('title', 'Discounts')
@section('content')
    <div class="container">
        <h1>Discounts</h1>
        @can('create-discounts')
            <a href="{{ route('discounts.create') }}" class="btn btn-primary mb-3">Add Discount</a>
        @endcan
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Applies To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($discounts as $discount)
                    <tr>
                        <td>{{ $discount->name }}</td>
                        <td>{{ $discount->type }}</td>
                        <td>{{ $discount->value }}</td>
                        <td>{{ $discount->applies_to }}</td>
                        <td>
                            @can('view-discounts')
                                <a href="{{ route('discounts.show', $discount) }}" class="btn btn-info btn-sm">View</a>
                            @endcan
                            @can('edit-discounts')
                                <a href="{{ route('discounts.edit', $discount) }}" class="btn btn-warning btn-sm">Edit</a>
                            @endcan
                            @can('delete-discounts')
                                <form action="{{ route('discounts.destroy', $discount) }}" method="POST" style="display:inline;">
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
    </div>
@endsection