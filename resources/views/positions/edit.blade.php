@extends('layouts.app')
  @section('title', 'Edit Position')
  @section('content')
      <h1>Edit Position</h1>
      <form action="{{ route('positions.update', $position) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $position->name) }}">
              @error('name')
                  <div class="text-danger">{{ $message }}</div>
              @enderror
          </div>
          <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea name="description" id="description" class="form-control">{{ old('description', $position->description) }}</textarea>
              @error('description')
                  <div class="text-danger">{{ $message }}</div>
              @enderror
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
          <a href="{{ route('positions.index') }}" class="btn btn-secondary">Cancel</a>
      </form>
  @endsection