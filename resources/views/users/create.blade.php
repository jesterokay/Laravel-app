@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <h1>Create User</h1>
    @include('users.partials.form', ['user' => null])
@endsection
