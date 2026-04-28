<?php
session_start();
require_once __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['id'] ?? null;

// 조회수 증가
$stmt = $conn->prepare("UPDATE boards SET board_views = board_views + 1 WHERE board_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// 게시글 + 작성자 JOIN으로 한 번에 가져오기 ⭐
$stmt = $conn->prepare("
    SELECT b.*, u.user_name
    FROM boards b
    JOIN users u ON b.user_id = u.idx
    WHERE b.board_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$board = $result->fetch_assoc();

// 게시글 없을 때
if (!$board) {
    die("존재하지 않는 게시글입니다.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>게시판</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="view">
    <h2><?= htmlspecialchars($board['board_title']) ?></h2>

    <div class="user_info">
        <p>
            <b>작성자</b> <?= htmlspecialchars($board['user_name']) ?>
            |
            <?= $board['board_date'] ?>
            |
            <b>조회수</b> <?= $board['board_views'] ?>
        </p>
    </div>

    <hr>

    <div class="content">
        <?= nl2br(htmlspecialchars($board['board_content'])) ?>
    </div>

    <div class="viewButton">
        <ul>
            <li>
                <button onclick="location.href='index.php'">목록</button>
            </li>

            <?php if ($user_id && $user_id == $board['user_id']): ?>
                <li>
                    <button onclick="location.href='modify.php?id=<?= $board['board_id'] ?>'">수정</button>
                </li>
                <li>
                    <button onclick="location.href='delete.php?id=<?= $board['board_id'] ?>'">삭제</button>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

</body>
</html>