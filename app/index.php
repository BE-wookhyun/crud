<?php
session_start();
// require_once __DIR__ . '/init_db.php';
require __DIR__ . '/db.php';

$result = $conn->query("SHOW TABLES LIKE 'users'");

if ($result->num_rows == 0) {
    require_once __DIR__ . '/init_db.php';
}

// 로그인 정보
$user_id = $_SESSION['id'] ?? null;
$user_name = $_SESSION['name'] ?? null;

// 페이지 설정
$list_num = 10;
$page_num = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// 총 게시글 수
$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM boards");
$stmt->execute();
$result = $stmt->get_result();
$num = $result->fetch_assoc()['cnt'];

// 페이지 계산
$total_page = ceil($num / $list_num);
$total_block = ceil($total_page / $page_num);
$now_block = ceil($page / $page_num);
$s_page = max(1, ($now_block * $page_num) - ($page_num - 1));
$e_page = min($total_page, $now_block * $page_num);
$start = ($page - 1) * $list_num;

// 게시글 조회
$stmt = $conn->prepare("
    SELECT b.*, u.user_name
    FROM boards b
    JOIN users u ON b.user_id = u.idx
    ORDER BY b.board_id DESC
    LIMIT ?, ?
");
$stmt->bind_param("ii", $start, $list_num);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>게시판</title>
</head>
<body>

<div class="loginButton">
    <?php if (!$user_id): ?>
        <a href="login.php">로그인</a>
        <a href="register.php">회원가입</a>
    <?php else: ?>
        <span><?= htmlspecialchars($user_name) ?>님</span>
        <a href="logout.php">로그아웃</a>
    <?php endif; ?>
</div>

<div class="index">
    <h1><a href="index.php">자유 게시판</a></h1>
    <h4>자유롭게 글을 쓸 수 있는 게시판입니다.</h4>

    <button onclick="writePost()">글쓰기</button>

    <table>
        <tr>
            <th>번호</th>
            <th>제목</th>
            <th>작성자</th>
            <th>작성일</th>
            <th>조회수</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['board_id'] ?></td>
            <td>
                <a href="view.php?id=<?= $row['board_id'] ?>">
                    <?= htmlspecialchars($row['board_title']) ?>
                </a>
            </td>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= $row['board_date'] ?></td>
            <td><?= $row['board_views'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="page">
        <?php if ($page <= 1): ?>
            <span>이전</span>
        <?php else: ?>
            <a href="?page=1">이전</a>
        <?php endif; ?>

        <?php for ($i = $s_page; $i <= $e_page; $i++): ?>
            <?php if ($i == $page): ?>
                <strong><?= $i ?></strong>
            <?php else: ?>
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page >= $total_page): ?>
            <span>다음</span>
        <?php else: ?>
            <a href="?page=<?= $total_page ?>">다음</a>
        <?php endif; ?>
    </div>

    <h4>총 <?= $num ?>개의 글이 있습니다</h4>
</div>

<script>
function writePost() {
    <?php if (!$user_id): ?>
        alert('로그인이 필요합니다.');
        location.href='login.php';
    <?php else: ?>
        location.href='write.php';
    <?php endif; ?>
}
</script>

</body>
</html>