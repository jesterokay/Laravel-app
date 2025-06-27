@extends('layouts.app')
@section('title', 'Employee Details')
@section('content')
    <h1>Employee: {{ $employee->first_name }} {{ $employee->last_name }}</h1>
    <div class="row">
        <div class="col-md-4">
            <img src="{{ $employee->image }}" alt="Profile Image" class="img-thumbnail" style="max-width: 200px;">
        </div>
        <div class="col-md-8">
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
    <a href="{{ route('employees.index') }}" class="btn btn-primary mt-3">Back to Employees</a>
    @can('edit-employees')
        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning mt-3">Edit</a>
    @endcan
    @can('delete-employees')
        <form action="{{ route('employees.destroy', $employee) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger mt-3" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    @endcan
@endsection