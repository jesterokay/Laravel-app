@extends('layouts.app')
@section('title', 'Edit Promotion')
@section('content')
    <div class="container">
        <h1>Edit Promotion</h1>
        <form action="{{ route('promotions.update', $promotion) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $promotion->name }}" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="percentage" {{ $promotion->type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    <option value="buy_x_get_y" {{ $promotion->type == 'buy_x_get_y' ? 'selected' : '' }}>Buy X Get Y</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="value" class="form-label">Value</label>
                <input type="number" name="value" id="value" class="form-control" value="{{ $promotion->value }}" step="0.01">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control">{{ $promotion->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $promotion->start_date->format('Y-m-d') }}" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $promotion->end_date->format('Y-m-d') }}" required>
            </div>
            <div class="mb-3">
                <label for="applies_to" class="form-label">Applies To</label>
                <select name="applies_to" id="applies_to" class="form-control" required>
                    <option value="product" {{ $promotion->applies_to == 'product' ? 'selected' : '' }}>Product</option>
                    <option value="sale" {{ $promotion->applies_to == 'sale' ? 'selected' : '' }}>Sale</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="conditions" class="form-label">Conditions (JSON)</label>
                <textarea name="conditions" id="conditions" class="form-control">{{ json_encode($promotion->conditions) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection