@extends('layouts.app')
  @section('content')
  <div class="container mt-4">
      <h1>Welcome, {{ auth()->user()->username }}!</h1>
      <p>Time to manage some chaos, eh?</p>
  </div>
  @endsection