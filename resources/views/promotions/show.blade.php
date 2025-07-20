@extends('layouts.app')
@section('title', 'Promotion Details')
@section('content')
    <div class="container">
        <h1>{{ $promotion->name }}</h1>
        <div class="card">
            <div class="card-body">
                <p><strong>Name:</strong> {{ $promotion->name }}</p>
                <p><strong>Product:</strong> {{ $promotion->product->name }}</p>
                <p><strong>Type:</strong> {{ $promotion->type }}</p>
                <p><strong>Value:</strong> {{ $promotion->value }}</p>
                <p><strong>Description:</strong> {{ $promotion->description }}</p>
                <p><strong>Start Date:</strong> {{ $promotion->start_date->format('M d, Y') }}</p>
                <p><strong>End Date:</strong> {{ $promotion->end_date->format('M d, Y') }}</p>
                <p><strong>Applies To:</strong> {{ $promotion->applies_to }}</p>
            </div>
        </div>
        <a href="{{ route('promotions.index') }}" class="btn btn-primary mt-3">Back to Promotions</a>
    </div>
@endsection
