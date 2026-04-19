<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Start Production
    |--------------------------------------------------------------------------
    */

    public function start(Order $order)
    {
        ProductionLog::create([
            'order_id' => $order->id,
            'operator_id' => Auth::id(),
            'status' => 'in_production',
            'notes' => 'Production started'
        ]);

        $order->update([
            'status' => 'in_production'
        ]);

        return redirect()->back()->with('success', 'Production started');
    }

    /*
    |--------------------------------------------------------------------------
    | Update Production Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, ProductionLog $log)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $log->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Production status updated');
    }

    /*
    |--------------------------------------------------------------------------
    | Finish Production
    |--------------------------------------------------------------------------
    */

    public function finish(Order $order)
    {
        ProductionLog::create([
            'order_id' => $order->id,
            'operator_id' => Auth::id(),
            'status' => 'finished',
            'notes' => 'Production finished'
        ]);

        $order->update([
            'status' => 'finished'
        ]);

        return redirect()->back()->with('success', 'Production finished');
    }
}