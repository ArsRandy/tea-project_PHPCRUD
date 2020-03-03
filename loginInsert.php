<?php
session_start();
header("Content-Type: text/html; chartset=utf-8");
require_once('./db.inc.php');




//index輸入的帳號密碼
if (isset($_POST['vendorAccount']) && isset($_POST['vendorPwd'])) {
    $sql = "SELECT `vendorAccount`, `vendorPassword`, `id` FROM `vendordata` WHERE `vendorAccount`= ? AND `vendorPassword` = ?";

    $arrParam = [
        $_POST['vendorAccount'],
        sha1($_POST['vendorPwd'])
    ];

    $pdo_stmt = $pdo->prepare($sql);
    $pdo_stmt->execute($arrParam);

    // echo "<pre>";
    // print_r($arrParam);
    // echo "</pre>";
    //     exit();

    if ($pdo_stmt->rowCount() > 0) {
        header("Refresh:3; url=./admin.php");

        $getId = $pdo_stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['vendorAccount'] = $_POST['vendorAccount'];
        $_SESSION['id'] = $getId['id'];
        echo "登入成功 等待跳轉至廠商頁面";
    } else {
        header("Refresh:3; url=./index.php");
        echo "登入失敗";
        exit();
    }
} else {
    header("Refresh:3; url=./index.php");
    echo "請確實登入";
    exit();
}
