@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Module</h1>
    <form method="POST" action="{{ route('modulemanagement.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Module Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="enabled">Enabled</label>
            <select class="form-control" id="enabled" name="enabled">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Create Module</button>
    </form>
</div>
@endsection