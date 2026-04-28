<?php
$host = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$database = getenv('DB_NAME') ?: 'mydb';
$password = getenv('DB_PASS') ?: '1234';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>