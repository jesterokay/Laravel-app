@extends('layouts.app')
@section('title', 'Permissions')
@section('content')
    <h1>Permissions</h1>
    @can('create-permissions')
        <a href="{{ route('permissions.assign') }}" class="btn btn-primary mb-3">Assign Permission</a>
    @endcan
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>
                        @can('edit-permissions')
                            <a href="{{ route('permissions.edit', $role->id) }}" class="btn btn-sm btn-primary">Update Permission</a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection