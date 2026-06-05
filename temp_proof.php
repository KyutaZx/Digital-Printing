<?php
$db = new PDO('pgsql:host=localhost;dbname=printing_postgres', 'printing_user', 'password123');
$stmt = $db->query("SELECT payment_proof FROM payment_transactions WHERE order_id=21");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
