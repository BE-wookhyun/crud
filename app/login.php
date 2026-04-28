<?php
session_start();
require_once __DIR__ . '/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pw = trim($_POST['pw'] ?? '');

    // 입력값 검증
    if ($email === '' || $pw === '') {
        $error = '이메일과 비밀번호를 입력해주세요.';
    } else {
        $stmt = $conn->prepare("SELECT idx, user_pw, user_name FROM users WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        // 로그인 검증
        if ($user && password_verify($pw, $user['user_pw'])) {
            $_SESSION['id'] = $user['idx'];
            $_SESSION['name'] = $user['user_name'];

            header("Location: index.php");
            exit();
        } else {
            $error = '이메일 또는 비밀번호가 일치하지 않습니다.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>로그인</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login">
        <h1>로그인</h1>
        <form method="post" action="login.php">
            <table>
                <tr>
                    <td style="text-align:right; font-weight:bolder;">이메일</td>
                    <td style="text-align:left;"><input type="email" name="email" required placeholder="이메일" size="40"></td>
                </tr>
                <tr>
                    <td style="text-align:right; font-weight:bolder;">비밀번호</td>
                    <td style="text-align:left;"><input type="password" name="pw" required placeholder="비밀번호" size="40"></td>
                </tr>
                <tr class="loginSubmit">
                    <td colspan="2"><input type="submit" value="로그인"></td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 15px;">아직 회원이 아니신가요? <a href="register.php">회원가입</a></td>
                </tr>
        </form>
    </div>
</body>
</html>