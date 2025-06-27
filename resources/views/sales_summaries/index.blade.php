@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Sales Summaries</h1>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Date</th>
                    <th class="py-2 px-4 border-b">Total Sales</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salesSummaries as $salesSummary)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $salesSummary->sale_date->format('Y-m-d') }}</td>
                        <td class="py-2 px-4 border-b">{{ $salesSummary->total_sales }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('sales_summaries.show', $salesSummary) }}" class="text-blue-500">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $salesSummaries->links() }}
    </div>
@endsection