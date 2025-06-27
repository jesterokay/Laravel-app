@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Upload Module</h1>
    <form method="POST" action="{{ route('modulemanagement.upload') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <input type="file" class="form-control" name="file" required>
        </div>
        <button type="submit" class="btn btn-primary">Update File Zip</button>
    </form>
</div>
@endsection