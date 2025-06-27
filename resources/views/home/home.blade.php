@extends('layouts.app')

@section('title', 'Home')

@push('styles')
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .chart-container {
            margin-top: 2rem;
            height: 300px;
            width: 100%;
            border-radius: 0.5rem;
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    <div class="container dashboard-content">
        <!-- Welcome Header -->
        <div class="dashboard-header">
            <h1 class="text-3xl font-bold">Welcome {{ auth()->user()->username }}</h1>
        </div>

        <!-- Cards Section -->
        <div class="cards grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Total Sales</h2>
                <p class="mt-2 text-gray-600">${{ number_format($totalSales, 2) }}</p>
                <a href="/sales" class="btn btn-primary mt-3 text-sm">Details</a>
            </div>
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Total Customers</h2>
                <p class="mt-2 text-gray-600">{{ $totalCustomers }}</p>
                <a href="/customers" class="btn btn-success mt-3 text-sm">Report</a>
            </div>
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Total Products</h2>
                <p class="mt-2 text-gray-600">{{ $totalProducts }}</p>
                <a href="/products" class="btn btn-warning mt-3 text-sm">Manage</a>
            </div>
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Total Purchases</h2>
                <p class="mt-2 text-gray-600">${{ number_format($totalPurchases, 2) }}</p>
                <a href="/purchases" class="btn btn-info mt-3 text-sm">Check</a>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
            <div class="chart-container">
                <canvas id="monthlySalesChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="yearlySalesChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Monthly Sales Chart
            new Chart(document.getElementById('monthlySalesChart'), {
                type: 'bar',
                data: {
                    labels: @json($monthlySales->pluck('month')),
                    datasets: [{
                        label: 'Monthly Sales ($)',
                        data: @json($monthlySales->pluck('total')),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Yearly Sales Chart
            new Chart(document.getElementById('yearlySalesChart'), {
                type: 'line',
                data: {
                    labels: @json($yearlySales->pluck('year')),
                    datasets: [{
                        label: 'Yearly Sales ($)',
                        data: @json($yearlySales->pluck('total')),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush