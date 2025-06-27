@extends('layouts.app')
@section('title', 'Edit Discount')
@section('content')
    <div class="container">
        <h1>Edit Discount</h1>
        <form action="{{ route('discounts.update', $discount) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $discount->name }}" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="percentage" {{ $discount->type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    <option value="flat" {{ $discount->type == 'flat' ? 'selected' : '' }}>Flat</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="value" class="form-label">Value</label>
                <input type="number" name="value" id="value" class="form-control" value="{{ $discount->value }}" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control">{{ $discount->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="applies_to" class="form-label">Applies To</label>
                <select name="applies_to" id="applies_to" class="form-control" required>
                    <option value="product" {{ $discount->applies_to == 'product' ? 'selected' : '' }}>Product</option>
                    <option value="sale" {{ $discount->applies_to == 'sale' ? 'selected' : '' }}>Sale</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection