@extends('layouts.app')
  @section('title', 'Departments')
  @section('content')
      <h1>Departments</h1>
      @can('create-departments')
          <a href="{{ route('departments.create') }}" class="btn btn-primary mb-3">Create Department</a>
      @endcan
      <table class="table table-striped">
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody>
              @foreach ($departments as $department)
                  <tr>
                      <td>{{ $department->name }}</td>
                      <td>{{ $department->description ?? '-' }}</td>
                      <td>
                          @can('view-departments')
                              <a href="{{ route('departments.show', $department) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                          @endcan
                          @can('edit-departments')
                              <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                          @endcan
                          @can('delete-departments')
                              <form action="{{ route('departments.destroy', $department) }}" method="POST" style="display:inline;">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                              </form>
                          @endcan
                      </td>
                  </tr>
              @endforeach
          </tbody>
      </table>
  @endsection