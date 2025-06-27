@extends('layouts.app')
@section('title', 'Promotions')
@section('content')
    <div class="container">
        <h1>Promotions</h1>
        @can('create-promotions')
            <a href="{{ route('promotions.create') }}" class="btn btn-primary mb-3">Add Promotion</a>
        @endcan
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Applies To</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($promotions as $promotion)
                    <tr>
                        <td>{{ $promotion->name }}</td>
                        <td>{{ $promotion->type }}</td>
                        <td>{{ $promotion->value }}</td>
                        <td>{{ $promotion->applies_to }}</td>
                        <td>{{ $promotion->start_date->format('Y-m-d') }}</td>
                        <td>{{ $promotion->end_date->format('Y-m-d') }}</td>
                        <td>
                            @can('view-promotions')
                                <a href="{{ route('promotions.show', $promotion) }}" class="btn btn-info btn-sm">View</a>
                            @endcan
                            @can('edit-promotions')
                                <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-warning btn-sm">Edit</a>
                            @endcan
                            @can('delete-promotions')
                                <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" style="display:inline;">
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