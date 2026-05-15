<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$pdo = DB::connection()->getPdo();
$stmt = $pdo->query('SELECT * FROM design_files');
$designs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $pdo->query('SELECT * FROM order_items');
$items = $stmt2->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>design_files</h3>";
echo "<pre>";
print_r($designs);
echo "</pre>";

echo "<h3>order_items</h3>";
echo "<pre>";
print_r($items);
echo "</pre>";
