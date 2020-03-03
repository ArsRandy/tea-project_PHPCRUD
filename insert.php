<?php
header("Content-Type: text/html; chartset=utf-8");

//引入判斷是否登入機制
require_once('./checkSession.php');

//引用資料庫連線
require_once('./db.inc.php');

//SQL 敘述
$sql = "INSERT INTO `advertisement` 
        (`ad_name`, `ad_url`,`ad_slogan`,`ad_status`,`ad_img`) 
        VALUES (?, ?, ?, ?, ?)";

if( $_FILES["ad_img"]["error"] === 0 ) {
    //為上傳檔案命名
    $ad_img = date("YmdHis");
    
    //找出副檔名
    $extension = pathinfo($_FILES["ad_img"]["name"], PATHINFO_EXTENSION);

    //建立完整名稱
    $imgFileName = $ad_img.".".$extension;

    //若上傳成功，則將上傳檔案從暫存資料夾，移動到指定的資料夾或路徑
    if( !move_uploaded_file($_FILES["ad_img"]["tmp_name"], "./images/advertisement/".$imgFileName) ) {
        header("Refresh: 3; url=./ad.php");
        echo "圖片上傳失敗";
        exit();
    }
}

//繫結用陣列
$arr = [
    $_POST['ad_name'],
    $_POST['ad_url'],
    $_POST['ad_slogan'],
    $_POST['ad_status'],
    $imgFileName
];

$pdo_stmt = $pdo->prepare($sql);
$pdo_stmt->execute($arr);
if($pdo_stmt->rowCount() === 1) {
    header("Refresh: 3; url=./ad.php");
    echo "新增成功";
} else {
    header("Refresh: 3; url=./ad.php");
    echo "新增失敗";
}