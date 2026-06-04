<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=library system;charset=utf8mb4', 'root', '');
$stmt = $pdo->query("SELECT id,email,password,role FROM users WHERE email='admin@booking.com'");
foreach ($stmt as $row) {
    var_dump($row);
}
