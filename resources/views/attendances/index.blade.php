@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Attendance Records</h2>
    <a href="{{ route('attendances.create') }}" class="btn btn-primary mb-3">Add Attendance</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee ID</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->id }}</td>
                    <td>{{ $attendance->employee_id }}</td>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->check_in }}</td>
                    <td>{{ $attendance->check_out }}</td>
                    <td>
                        <a href="{{ route('attendances.show', $attendance->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" style="display:inline-block;">
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
