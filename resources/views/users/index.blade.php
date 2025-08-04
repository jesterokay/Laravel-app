@extends('layouts.app')
@section('title', 'Users')
@section('content')
    <h1>Users</h1>
    @can('create-users')
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Create User</a>
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
                <th>Email</th>
                <th>Department</th>
                <th>Position</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->email ?? '-' }}</td>
                    <td>{{ $user->department ? $user->department->name : '-' }}</td>
                    <td>{{ $user->position ? $user->position->name : '-' }}</td>
                    <td>{{ ucfirst($user->status) }}</td>
                    <td>
                        @can('view-users')
                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info"><i
                                    class="fas fa-eye"></i></a>
                        @endcan
                        @can('edit-users')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning"><i
                                    class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete-users')
                            @if (auth()->user()->hasRole('superadmin') && $user->id !== auth()->user()->id || 
                                (!auth()->user()->hasRole('superadmin') && $user->id !== auth()->user()->id && 
                                 !$user->hasRole('admin') && !$user->hasRole('superadmin')))
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        @endcan
                        @hasrole('superadmin')
                            <a href="{{ route('impersonate', $user->id) }}" class="btn btn-sm btn-primary"><i
                                    class="fas fa-sign-in-alt"></i> Login</a>
                        @endhasrole
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
@endsection