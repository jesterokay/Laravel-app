<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\SalesSummary;
use Illuminate\Http\Request;

class SalesSummaryController extends Controller
{
    public function index()
    {
        $salesSummaries = SalesSummary::paginate(10);
        return view('sales_summaries.index', compact('salesSummaries'));
    }

    public function show(SalesSummary $salesSummary)
    {
        return view('sales_summaries.show', compact('salesSummary'));
    }
}