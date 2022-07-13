<?php

$host = "db";
$port = "3306";
$database = "db";
$user = "user";
$password = "password";
$results = null;
$connection = null;

try {
    $connection = new PDO("mysql:host=$host;port=$port;charset=utf8mb4;dbname=$database", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo 'Connection Failed' . $e->getMessage();
}
