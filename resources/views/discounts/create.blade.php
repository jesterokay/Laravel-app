@extends('layouts.app')
@section('title', 'Create Discount')
@section('content')
    <div class="container">
        <h1>Create Discount</h1>
        <form action="{{ route('discounts.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="percentage">Percentage</option>
                    <option value="flat">Flat</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="value" class="form-label">Value</label>
                <input type="number" name="value" id="value" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                'product' => 'Product',
                'sale' => 'Sale',
            ];
            <label for="applies_to" class="form-label">Applies To</label>
            <select name="applies_to" id="applies_to" class="form-control" required>
                @foreach ($appliesToOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection