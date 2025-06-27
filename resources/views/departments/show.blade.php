@extends('layouts.app')
  @section('title', 'Department Details')
  @section('content')
      <h1>Department: {{ $department->name }}</h1>
      <p><strong>Description:</strong> {{ $department->description ?? '-' }}</p>
      <a href="{{ route('departments.index') }}" class="btn btn-primary">Back to Departments</a>
      @can('edit-departments')
          <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning">Edit</a>
      @endcan
      @can('delete-departments')
          <form action="{{ route('departments.destroy', $department) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
          </form>
      @endcan
  @endsection