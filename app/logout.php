<?php
    session_start();
    session_destroy();
    echo "<script> alert('로그아웃 되어습니다.'); location.href='index.php';</script>";
    session_destroy();
?>
