@extends('layouts.app')
@section('title', 'Roles')
@section('content')
    <h1>Roles</h1>
    @can('create-roles')
        <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Create Role</a>
    @endcan
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->description ?? '-' }}</td>
                    <td>
                        @can('view-roles')
                            <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        @endcan
                        @can('edit-roles')
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('edit-permissions')
                            @if (auth()->user()->hasRole('superadmin') || $role->name !== 'superadmin')
                                <a href="{{ route('permissions.edit', $role->id) }}" class="btn btn-sm btn-primary">Update Permission</a>
                            @endif
                        @endcan
                        @can('delete-roles')
                            @if ($role->name !== 'superadmin' && !auth()->user()->hasRole($role->name))
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection