@extends('layouts.app')

@section('title', 'Create Employee')

@section('content')
    <h1>Create Employee</h1>
    @include('employees.partials.form', ['employee' => null])
@endsection
