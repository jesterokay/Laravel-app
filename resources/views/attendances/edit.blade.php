@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Attendance</h2>

    <form action="{{ route('attendances.update', $attendance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="{{ $attendance->date }}">
        </div>

        <div class="mb-3">
            <label>Check In</label>
            <input type="time" name="check_in" class="form-control" value="{{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}">
        </div>

        <div class="mb-3">
            <label>Check Out</label>
            <input type="time" name="check_out" class="form-control" value="{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '' }}">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
