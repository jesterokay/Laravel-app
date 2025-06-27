@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($dashboardWidgets as $widget)
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold">{{ $widget->widget_type }}</h2>
                    <p>{{ json_decode($widget->configuration)->description ?? 'No description' }}</p>
                </div>
            @endforeach
        </div>
        <h2 class="text-xl font-bold mt-6">Recent Sales</h2>
        <table class="min-w-full bg-white border mt-4">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Customer</th>
                    <th class="py-2 px-4 border-b">Total Amount</th>
                    <th class="py-2 px-4 border-b">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentSales as $sale)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $sale->customer ? $sale->customer->first_name : 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $sale->total_amount }}</td>
                        <td class="py-2 px-4 border-b">{{ $sale->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h2 class="text-xl font-bold mt-6">Recent Metrics</h2>
        <table class="min-w-full bg-white border mt-4">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Metric Type</th>
                    <th class="py-2 px-4 border-b">Value</th>
                    <th class="py-2 px-4 border-b">Period</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($analyticsMetrics as $metric)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $metric->metric_type }}</td>
                        <td class="py-2 px-4 border-b">{{ $metric->value }}</td>
                        <td class="py-2 px-4 border-b">{{ $metric->period }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection