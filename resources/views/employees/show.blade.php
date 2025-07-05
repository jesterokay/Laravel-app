@extends('layouts.app')
@section('title', 'Employee Details')
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
    <h1>Employee: {{ $employee->first_name }} {{ $employee->last_name }}</h1>
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
            <p><strong>Username:</strong> {{ $employee->username }}</p>
            <p><strong>Email:</strong> {{ $employee->email ?? '-' }}</p>
            <p><strong>Phone:</strong> {{ $employee->phone ?? '-' }}</p>
            <p><strong>Department:</strong> {{ $employee->department ? $employee->department->name : '-' }}</p>
            <p><strong>Position:</strong> {{ $employee->position ? $employee->position->name : '-' }}</p>
            <p><strong>Permission Role:</strong> {{ $employee->roles->first()->name ?? '-' }}</p>
            <p><strong>Hire Date:</strong> {{ $employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('Y-m-d') : '-' }}</p>
            <p><strong>Salary:</strong> {{ $employee->salary ? number_format($employee->salary, 2) : '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($employee->status) }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('employees.index') }}" class="btn btn-primary">Back to Employees</a>
        @can('edit-employees')
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">Edit</a>
        @endcan
        @can('delete-employees')
            <form action="{{ route('employees.destroy', $employee) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        @endcan
    </div>
</div>
@endsection