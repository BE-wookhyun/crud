<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>게시판</title>
</head>
<body>
    <div class="loginButton">
        <?php
            session_start();
            $name = $_SESSION['name'] ?? null;
        ?>
        <?php
        if (isset($_SESSION) === false){session_start();}
        if (isset($_SESSION['id']) === false){
        ?>
        <a href="login.php">로그인</a>
        <a href="register.php">회원가입</a>
        <?php
        } else{
        ?>
        <div>
            <?php echo $name; ?>  ✔
        </div>
        <a href="logout.php">로그아웃</a>
        <?php   
        }
        ?>
    </div>

    <div class="index">
        <h1>
            <a href="index.php">자유 게시판</a>
        </h1>
        <h4>자유롭게 글을 쓸 수 있는 게시판입니다.</h4>
        <button onclick="writePost()">글쓰기</button>

        <table>
            <tr>
                <th width="70">번호</th>
                <th width="500">제목</th>
                <th width="120">작성자</th>
                <th width="100">작성일</th>
                <th width="100">조회수</th>
            </tr>
            <?php 
            include 'db.php';
                // $list_num = 10;
                // $page_num = 10;
                // $num = query('SELECT * FROM board')->num_rows;
                // $page = isset($_GET['page']) ? $_GET['page'] : 1;
                // $total_page = ceil($num / $list_num);
                // $total_block = ceil($total_page/$page_num);
                // $now_block = ceil($page / $page_num);
                // $s_page = ($now_block * $page_num) - ($page_num - 1);

                $list_num = 10;
                $page_num = 10;

                // 게시글 총 개수
                $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM boards");
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $num = $row['cnt'];

                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                $total_page = ceil($num / $list_num);
                $total_block = ceil($total_page / $page_num);
                $now_block = ceil($page / $page_num);
                $s_page = ($now_block * $page_num) - ($page_num - 1);


                if ($s_page <= 0) {
                    $s_page = 1;
                };
                $e_page = $now_block * $page_num;
                if ($total_page < $e_page) {
                    $e_page = $total_page;
                };
                $start = ($page - 1) * $list_num;
                // $stmt = $conn->prepare("SELECT * FROM boards ORDER BY board_id DESC LIMIT $start, $list_num");
                // $stmt->execute();

                // $result = $stmt->get_result();
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


                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['board_id'] . '</td>';
                    echo '<td><a href="view.php?id=' . $row['board_id'] . '">' . $row['board_title'] . '</a></td>';
                    echo '<td>' . $row['user_name'] . '</td>';
                    echo '<td>' . $row['board_date'] . '</td>';
                    echo '<td>' . $row['board_views'] . '</td>';
                    echo '</tr>';
                }
                ?>
        </table>
        <div class="page">
            <?php
                if ($page <= 1) {
                    echo '<span class="fo_re"> 이전 </span>';
                } else {
                    echo '<a href="index.php?page=1"> 이전 </a>';
                }

                for($print_page = $s_page; $print_page <= $e_page; $print_page++) {
                    if ($print_page == $page) {
                        echo '<strong> ' . $print_page . ' </strong>';
                    } else {
                        echo '<a href="index.php?page=' . $print_page . '"> ' . $print_page . ' </a>';
                    }
                }

                if ($page >= $total_page) {
                    echo '<span class="fo_re"> 다음 </span>';
                } else {
                    echo '<a href="index.php?page=' . $total_page . '"> 다음 </a>';
                }
            ?>
        </div>
        <?php echo '<h4 class="pagenum"> 총 ' . $num . '개의 글이 있습니다</h4>' ?>
        

    </div>
        <script>
            function writePost() {
                <?php if (isset($_SESSION['id']) === false) { ?>
                    alert('로그인이 필요합니다.');
                    location.href='login.php';
                <?php } else { ?>
                    location.href='write.php';
                <?php } ?>
            }
        </script>



</body>
</html>