@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Attendance Details</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $attendance->id }}</p>
            <p><strong>Employee ID:</strong> {{ $attendance->employee_id }}</p>
            <p><strong>Date:</strong> {{ $attendance->date }}</p>
            <p><strong>Check In:</strong> {{ $attendance->check_in }}</p>
            <p><strong>Check Out:</strong> {{ $attendance->check_out }}</p>
        </div>
    </div>

    <a href="{{ route('attendances.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
