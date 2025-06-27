@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
    <h1>Edit Employee: {{ $employee->first_name }} {{ $employee->last_name }}</h1>
    @include('employees.partials.form', ['employee' => $employee])
@endsection
