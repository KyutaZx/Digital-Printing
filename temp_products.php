<?php
$db = new PDO('pgsql:host=localhost;dbname=printing_postgres', 'printing_user', 'password123');
$stmt = $db->query("SELECT name, image FROM products LIMIT 5");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
