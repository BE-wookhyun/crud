<?php
require_once __DIR__ . '/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pw = trim($_POST['pw'] ?? '');
    $name = trim($_POST['name'] ?? '');

    // 입력값 검증
    if ($email === '' || $pw === '' || $name === '') {
        $error = '모든 값을 입력해주세요.';
    } else {

        // 이메일 중복 체크
        $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM users WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['cnt'];
        $stmt->close();

        if ($count > 0) {
            $error = '이미 존재하는 이메일입니다.';
        } else {
            // 🔥 비밀번호 해싱 (필수)
            $hash_pw = password_hash($pw, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (user_email, user_pw, user_name) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $hash_pw, $name);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = '회원가입 실패';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>회원가입</title>
</head>
<body>
<div class="login">
    <h1>회원가입</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <table>
            <tr>
                <td>이메일</td>
                <td><input type="email" name="email" required></td>
            </tr>
            <tr>
                <td>비밀번호</td>
                <td><input type="password" name="pw" required></td>
            </tr>
            <tr>
                <td>이름</td>
                <td><input type="text" name="name" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="회원가입">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    이미 회원이신가요? <a href="login.php">로그인</a>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>