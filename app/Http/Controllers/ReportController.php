<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductionLog;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Sales Report
    |--------------------------------------------------------------------------
    */

    public function sales()
    {
        $orders = Order::where('status', 'completed')
            ->latest()
            ->paginate(20);

        $totalRevenue = Order::where('status', 'completed')
            ->sum('total_price');

        return view('reports.sales', compact('orders', 'totalRevenue'));
    }

    /*
    |--------------------------------------------------------------------------
    | Order Report
    |--------------------------------------------------------------------------
    */

    public function orders()
    {
        $orders = Order::latest()->paginate(20);

        return view('reports.orders', compact('orders'));
    }

    /*
    |--------------------------------------------------------------------------
    | Production Report
    |--------------------------------------------------------------------------
    */

    public function production()
    {
        $logs = ProductionLog::with('order')
            ->latest()
            ->paginate(20);

        return view('reports.production', compact('logs'));
    }
}