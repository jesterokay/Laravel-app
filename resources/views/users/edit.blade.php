@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <h1>Edit User: {{ $user->first_name }} {{ $user->last_name }}</h1>
    @include('users.partials.form', ['user' => $user])
@endsection
