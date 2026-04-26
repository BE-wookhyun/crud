<?php
include 'db.php';

$stmt = $conn->prepare("SELECT * FROM boards WHERE board_id = ?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// $row['board_id']


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (empty($title) || empty($content)) {
        echo "빈칸을 채워주세요";
    } else {
        $stmt = $conn->prepare("UPDATE boards SET board_title = ?, board_content = ? WHERE board_id = ?");
        $stmt->bind_param("ssi", $title, $content, $id);
        $stmt->execute();

        // 이동
        header("Location: view.php?id=$id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="style.css">    
</head> 
<body>
    <div class="write">
        <h1>글을 수정하세요</h1>
        <hr/>
        <form method="POST" action="modify.php?id=<?php echo $board['board_id']; ?>">
            <table class="writeTable">
                <tr>
                    <th width="50">제목</th>
                    <td><input type="text" name="title" value="<?php echo $board['board_title']; ?>" required></td>
                </tr>
                <tr>
                    <th>내용</th>
                    <td><textarea name="content" rows="5" cols="40" required><?php echo $board['board_content']; ?></textarea></td>
                </tr>
            </table>
            <input type="hidden" name="id" value="<?php echo $board['board_id']; ?>">
            <ul>
                <li><button type="button" onclick="location.href='view.php?id=<?php echo $board['board_id']; ?>'">취소</button></li>
                <li><input class="button" type="submit" value="수정 완료"></li>
            </ul>
        </form>
    </div>
</body>
</html>