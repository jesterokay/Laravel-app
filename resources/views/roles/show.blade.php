@extends('layouts.app')
@section('title', 'Role Details')
@section('content')
    <h1>Role: {{ $role->name }}</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $role->name }}</p>
            <p><strong>Description:</strong> {{ $role->description ?? '-' }}</p>
            <p><strong>Permissions:</strong></p>
            <ul>
                @if ($role->permissions->isNotEmpty())
                    @foreach ($role->permissions as $permission)
                        <li>{{ $permission->name }}</li>
                    @endforeach
                @else
                    <li>No permissions assigned</li>
                @endif
            </ul>
        </div>
    </div>
    <a href="{{ route('roles.index') }}" class="btn btn-secondary mt-3">Back to Roles</a>
    @can('edit-permissions')
        <a href="{{ route('permissions.edit', $role->id) }}" class="btn btn-primary mt-3">Update Permissions</a>
    @endcan
@endsection