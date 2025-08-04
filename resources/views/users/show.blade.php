@extends('layouts.app')
@section('title', 'User Details')
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
    }

    .no-image {
        text-align: center;
        color: #666;
        margin-bottom: 20px;
    }
</style>

<div class="container">
    <h1>User: {{ $user->first_name }} {{ $user->last_name }}</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="Profile Image" class="profile-image">
            @else
                <p class="no-image">No profile image available</p>
            @endif
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
            <p><strong>Phone:</strong> {{ $user->phone ?? '-' }}</p>
            <p><strong>Department:</strong> {{ $user->department ? $user->department->name : '-' }}</p>
            <p><strong>Position:</strong> {{ $user->position ? $user->position->name : '-' }}</p>
            <p><strong>Permission Role:</strong> {{ $user->roles->first()->name ?? '-' }}</p>
            <p><strong>Hire Date:</strong> {{ $user->hire_date ? \Carbon\Carbon::parse($user->hire_date)->format('Y-m-d') : '-' }}</p>
            <p><strong>Salary:</strong> {{ $user->salary ? number_format($user->salary, 2) : '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('users.index') }}" class="btn btn-primary">Back to Users</a>
        @can('edit-users')
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Edit</a>
        @endcan
        @can('delete-users')
            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        @endcan
    </div>
</div>
@endsection