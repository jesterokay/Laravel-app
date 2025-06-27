@extends('layouts.app')
  @section('title', 'Position Details')
  @section('content')
      <h1>Position: {{ $position->name }}</h1>
      <p><strong>Description:</strong> {{ $position->description ?? '-' }}</p>
      <a href="{{ route('positions.index') }}" class="btn btn-primary">Back to Positions</a>
      @can('edit-positions')
          <a href="{{ route('positions.edit', $position) }}" class="btn btn-warning">Edit</a>
      @endcan
      @can('delete-positions')
          <form action="{{ route('positions.destroy', $position) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE //"DELETE" method for HTTP request')
              <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
          </form>
      @endcan
  @endsection