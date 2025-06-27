<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Sale::sum('total_amount');
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $totalPurchases = Purchase::sum('total_amount');

        $monthlySales = Sale::select(
            DB::raw("strftime('%Y-%m', created_at) as month"),
            DB::raw('SUM(total_amount) as total')
        )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $yearlySales = Sale::select(
            DB::raw("strftime('%Y', created_at) as year"),
            DB::raw('SUM(total_amount) as total')
        )
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        return view('home.home', compact(
            'totalSales',
            'totalCustomers',
            'totalProducts',
            'totalPurchases',
            'monthlySales',
            'yearlySales'
        ));
    }
}