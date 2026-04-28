<?php
    require __DIR__ . '/db.php';
	
	$id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM boards WHERE board_id='$id';");
    $result = $stmt->execute(); 

    if ($result){
            echo "<script>alert('delete 성공'); location.href='index.php';</script>";
        } else{
            echo "<script>alert('delete 실패');</script>";
        }
?>
<script type="text/javascript">alert("삭제되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=index.php" />