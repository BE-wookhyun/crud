<?php
session_start();
require_once __DIR__ . '/db.php';

$user_id = $_SESSION['id'] ?? null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    // 로그인 체크
    if (!$user_id) {
        header("Location: login.php");
        exit();
    }

    // 입력값 검증
    if ($title === '' || $content === '') {
        $error = '모든 값을 입력해주세요.';
    } else {
        // ✅ prepare 제대로 사용
        $stmt = $conn->prepare("
            INSERT INTO boards (board_title, board_content, user_id)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("ssi", $title, $content, $user_id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = '작성 실패';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>글쓰기</title>
</head>
<body>

<div class="write">
    <h1>글을 작성하세요</h1>
    <hr/>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <table class="writeTable">
            <tr>
                <th>제목</th>
                <td>
                    <input type="text" name="title" required>
                </td>
            </tr>
            <tr>
                <th>내용</th>
                <td>
                    <textarea name="content" rows="5" cols="40" required></textarea>
                </td>
            </tr>
        </table>

        <ul>
            <li>
                <button type="button" onclick="location.href='index.php'">취소</button>
            </li>
            <li>
                <input type="submit" value="작성 완료">
            </li>
        </ul>
    </form>
</div>

</body>
</html>