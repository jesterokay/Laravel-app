@extends('layouts.app')
  @section('title', 'Positions')
  @section('content')
      <h1>Positions</h1>
      @can('create-positions')
          <a href="{{ route('positions.create') }}" class="btn btn-primary mb-3">Create Position</a>
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
              @foreach ($positions as $position)
                  <tr>
                      <td>{{ $position->name }}</td>
                      <td>{{ $position->description ?? '-' }}</td>
                      <td>
                          @can('view-positions')
                              <a href="{{ route('positions.show', $position) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                          @endcan
                          @can('edit-positions')
                              <a href="{{ route('positions.edit', $position) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                          @endcan
                          @can('delete-positions')
                              <form action="{{ route('positions.destroy', $position) }}" method="POST" style="display:inline;">
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
      {{ $positions->links() }}
  @endsection