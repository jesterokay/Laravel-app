@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Module: {{ $modulemanagement->name }}</h1>
    <form method="POST" action="{{ route('modulemanagement.update', $modulemanagement->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $modulemanagement->description) }}</textarea>
        </div>
        <div class="form-group">
            <label for="enabled">Enabled</label>
            <select class="form-control" id="enabled" name="enabled">
                <option value="1" {{ old('enabled', $modulemanagement->enabled) ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !old('enabled', $modulemanagement->enabled) ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update Module</button>
    </form>
</div>
@endsection