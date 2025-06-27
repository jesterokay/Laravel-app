@extends('layouts.app')
@section('title', 'Manage Permissions for ' . $role->name)
@section('content')
    <h1>Manage Permissions for {{ $role->name }}</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('permissions.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Module</th>
                    <th>View</th>
                    <th>Create</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modules as $module)
                    <tr>
                        <td>{{ ucfirst($module) }}</td>
                        @foreach ($actions as $action)
                            <td>
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $action . '-' . $module }}"
                                       @if ($role->hasPermissionTo($action . '-' . $module)) checked @endif>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Save Permissions</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection