@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Sales Summary Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Date:</strong> {{ $salesSummary->sale_date->format('Y-m-d') }}</p>
            <p><strong>Total Sales:</strong> {{ $salesSummary->total_sales }}</p>
            <p><strong>Total Tax:</strong> {{ $salesSummary->total_tax }}</p>
            <p><strong>Total Quantity:</strong> {{ $salesSummary->total_quantity }}</p>
        </div>
    </div>
@endsection