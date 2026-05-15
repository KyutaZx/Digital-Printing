<?php
/**
 * DEBUG SCRIPT: Direct PostgreSQL query to check design_files data
 * Access: http://127.0.0.1:8000/debug_direct.php
 */

$host = '127.0.0.1';
$port = '5432';
$dbname = 'printing_postgres';
$user = 'postgres';
$pass = 'postgres'; // Ganti sesuai .env Anda

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    // Coba password berbeda
    try {
        $pass = '';
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e2) {
        die(json_encode(['error' => 'DB Connection failed: ' . $e2->getMessage()]));
    }
}

// Query 1: Semua design files
$designs = $pdo->query("
    SELECT df.id, df.order_item_id, df.file_path, df.version, df.uploaded_by, df.created_at,
           oi.order_id,
           o.order_code, o.status as order_status
    FROM design_files df
    JOIN order_items oi ON oi.id = df.order_item_id
    JOIN orders o ON o.id = oi.order_id
    ORDER BY df.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Query 2: Semua orders dengan status
$orders = $pdo->query("
    SELECT o.id, o.order_code, o.status, 
           COUNT(oi.id) as item_count,
           COUNT(df.id) as design_count
    FROM orders o
    LEFT JOIN order_items oi ON oi.order_id = o.id
    LEFT JOIN design_files df ON df.order_item_id = oi.id
    GROUP BY o.id, o.order_code, o.status
    ORDER BY o.id DESC
    LIMIT 20
")->fetchAll(PDO::FETCH_ASSOC);

// Check file existence on disk
foreach ($designs as &$d) {
    $goApiPath = dirname(__DIR__) . '/golang-api/' . ltrim($d['file_path'], '/');
    $d['file_exists_on_disk'] = file_exists($goApiPath) ? 'YES' : 'NO';
    $d['full_disk_path'] = $goApiPath;
}

header('Content-Type: application/json');
echo json_encode([
    'total_designs' => count($designs),
    'designs' => $designs,
    'orders_summary' => $orders,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
