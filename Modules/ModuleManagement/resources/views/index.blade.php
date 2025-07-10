@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modules</h1>
    <a href="{{ route('modulemanagement.create') }}" class="btn btn-primary mb-3">Create New Module</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Enabled</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($modules as $module)
            <tr>
                <td>{{ $module->id }}</td>
                <td>{{ $module->name }}</td>
                <td>{{ $module->description }}</td>
                <td>{{ $module->enabled ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('modulemanagement.edit', $module->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('modulemanagement.destroy', $module->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection