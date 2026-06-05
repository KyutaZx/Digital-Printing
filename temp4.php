<?php
$db = new PDO('pgsql:host=localhost;dbname=printing_postgres', 'printing_user', 'password123');
$stmt = $db->query("SELECT id, order_code FROM orders WHERE order_code='ORD-1217806'");
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if ($order) {
    print_r($order);
    $stmt2 = $db->query("SELECT * FROM payment_transactions WHERE order_id=" . $order['id']);
    print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
} else {
    echo "Order not found\n";
}
