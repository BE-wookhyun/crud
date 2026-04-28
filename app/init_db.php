<?php
require_once __DIR__ . '/db.php';

$conn->query("
CREATE TABLE IF NOT EXISTS users (
    idx INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(50) UNIQUE,
    user_pw VARCHAR(255),
    user_name VARCHAR(20),
    register_date DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("
CREATE TABLE IF NOT EXISTS boards (
    board_id INT AUTO_INCREMENT PRIMARY KEY,
    board_title VARCHAR(255),
    board_content TEXT,
    board_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    board_views INT DEFAULT 0,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(idx) ON DELETE CASCADE
)");

echo "DB 초기화 완료";