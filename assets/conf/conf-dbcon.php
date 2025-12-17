<?php
$host = "92.113.24.51";
$db_name = "u847989251_giame";
$username = "u847989251_giame";
$password = "Giame@112233";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}