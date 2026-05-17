<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/debug-db', function () {
    $designs = DB::connection('pgsql')->select('SELECT * FROM design_files');
    $orderItems = DB::connection('pgsql')->select('SELECT * FROM order_items');
    $orders = DB::connection('pgsql')->select('SELECT * FROM orders');
    
    return response()->json([
        'designs' => $designs,
        'order_items' => $orderItems,
        'orders' => $orders
    ]);
});

// require __DIR__.'/web.php';
