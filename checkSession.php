<?php
session_start();

// 判斷是否登入 (確認先前指派的 session 索引是否存在)
if( !isset($_SESSION['id']) ) {
// 關閉 session
session_destroy();

//3 秒後跳頁
    header("Refresh: 3; url=./login.html");
    echo "請確實登入…3秒後自動回登入頁";
    exit();
}

// session_start();

// if (!isset($_SESSION['id'])) {
//   header("Refer:3; url=./login.html");
//   session_destroy();
//   echo "請確實登入";
//   exit();
// }
