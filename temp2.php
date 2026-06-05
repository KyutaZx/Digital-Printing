<?php
$db = new PDO('pgsql:host=localhost;dbname=printing_postgres', 'printing_user', 'password123');
$stmt = $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
