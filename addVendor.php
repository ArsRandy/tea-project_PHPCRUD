<?php
session_start();
require_once('./db.inc.php');


// if ($_POST["vendorAccount"] == "" || $_POST["vendorPwd"] == "" || $_POST["vendorEmail"] == "" || $_POST["name"] == "" || $_POST["phone"] = "" || $_POST["address"] = "") {
//     header("Refresh:3 ; url=./signUp.php");
//     echo "請輸入完整資料";
//     exit();
// }

if ($_FILES["vendorImg"]["error"] === 0) {
    $vendorImg = date("YmdHis");

    $extension = pathinfo($_FILES["vendorImg"]["name"], PATHINFO_EXTENSION);

    $imgName = $vendorImg . "." . $extension;

    if (!move_uploaded_file($_FILES["vendorImg"]["tmp_name"], "./img/{$imgName}")) {
        header("Refresh:3; url=./insertData.php");
        echo "圖片上傳失敗";
        exit();
    }
}


$sql = "INSERT INTO `vendordata` (`vendorAccount`,`vendorPassword`,`vendorEmail`,`vendorName`,`vendorPhone`,`vendorAddress`,`vendorImg`)
VALUE (?,?,?,?,?,?,?)";




$arrParam = [
    $_POST['vendorAccount'],
    sha1($_POST['vendorPwd']),
    $_POST['vendorEmail'],
    $_POST['name'],
    $_POST['phone'],
    $_POST['address'],
    $imgName
];

// echo "<pre>";
// print_r($arrParam);
// echo "<pre>";
// exit();


$stmt = $pdo->prepare($sql);
$stmt->execute($arrParam);

if ($stmt->rowCount() > 0) {
    header("Refresh:3; url=./commodity.php");

    $_SESSION["vendorAccount"] = $_POST['vendorAccount'];
    echo "註冊成功 請重新登入";
    exit();
} else {
    header("Refresh:3; url=./login.php");
    echo "註冊失敗 請重新註冊";
}
