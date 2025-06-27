@extends('layouts.app')
@section('title', 'Employees')
@section('content')
    <h1>Employees</h1>
    @can('create-employees')
        <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">Create Employee</a>
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
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                    <td>{{ $employee->email ?? '-' }}</td>
                    <td>{{ $employee->department ? $employee->department->name : '-' }}</td>
                    <td>{{ $employee->position ? $employee->position->name : '-' }}</td>
                    <td>{{ ucfirst($employee->status) }}</td>
                    <td>
                        @can('view-employees')
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-info"><i
                                    class="fas fa-eye"></i></a>
                        @endcan
                        @can('edit-employees')
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning"><i
                                    class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete-employees')
                            @if (auth()->user()->hasRole('superadmin') && $employee->id !== auth()->user()->id || 
                                (!auth()->user()->hasRole('superadmin') && $employee->id !== auth()->user()->id && 
                                 !$employee->hasRole('admin') && !$employee->hasRole('superadmin')))
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        @endcan
                        @hasrole('superadmin')
                            <a href="{{ route('impersonate', $employee->id) }}" class="btn btn-sm btn-primary"><i
                                    class="fas fa-sign-in-alt"></i> Login</a>
                        @endhasrole
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $employees->links() }}
@endsection