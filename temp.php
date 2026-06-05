<?php
$db = new PDO('pgsql:host=localhost;dbname=printing_postgres', 'printing_user', 'password123');
$stmt = $db->query("SELECT payment_proof FROM payments WHERE payment_proof IS NOT NULL LIMIT 5");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
