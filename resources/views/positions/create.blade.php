@extends('layouts.app')
  @section('title', 'Create Position')
  @section('content')
      <h1>Create Position</h1>
      <form action="{{ route('positions.store') }}" method="POST">
          @csrf
          <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
              @error('name')
                  <div class="text-danger">{{ $message }}</div>
              @enderror
          </div>
          <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
              @error('description')
                  <div class="text-danger">{{ $message }}</div>
              @enderror
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
          <a href="{{ route('positions.index') }}" class="btn btn-secondary">Cancel</a>
      </form>
  @endsection