<?php
$host = getenv('DB_HOST');
$username = getenv('DB_USER');
$database = getenv('DB_NAME');
// secret에서 비밀번호 읽기
$password = trim(file_get_contents('/run/secrets/db_password'));

// $conn = mysqli_connect($host, $username, $password, $database);

// if (!$conn){
//     die("DB 연결 실패 : " . mysqli_connect_error());
// }

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

// 테이블 생성 쿼리
$user_create_sql = "
CREATE TABLE IF NOT EXISTS users (
    idx INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(50),
    user_pw VARCHAR(255),
    user_name VARCHAR(20),
    register_date DATETIME DEFAULT CURRENT_TIMESTAMP
)";

// 실행
if (!mysqli_query($conn, $user_create_sql)) {
    die("USER 테이블 생성 실패: " . mysqli_error($conn));
}

// 테이블 생성 쿼리
$board_create_sql = "
CREATE TABLE IF NOT EXISTS boards (
    board_id INT AUTO_INCREMENT PRIMARY KEY,
    board_title VARCHAR(255),
    board_content TEXT,
    board_date DATETIME DEFAULT NOW(),
    board_views INT DEFAULT 0,
    user_id INT
)";

// 실행
if (!mysqli_query($conn, $board_create_sql)) {
    die("BOARD 테이블 생성 실패: " . mysqli_error($conn));
}


// 결과 해제
// mysqli_free_result($result);

// db 연결 종료
// $mysqli_close($dbcon);
?>
