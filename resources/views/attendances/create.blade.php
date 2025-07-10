@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Attendance</h2>

    <form action="{{ route('attendances.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Employee</label>
            <select name="employee_id" class="form-control">
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->username }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
        </div>

        <div class="mb-3">
            <label>Check In</label>
            <input type="time" name="check_in" class="form-control">
        </div>

        <div class="mb-3">
            <label>Check Out</label>
            <input type="time" name="check_out" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection