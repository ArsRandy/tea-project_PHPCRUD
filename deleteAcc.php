<?php
require_once('./check.php');
require_once('./db.inc.php');


$sqlGetImg = "SELECT `vendorImg` FROM `vendordata` WHERE `vendorAccount`= ?";
$stmtImg =$pdo->prepare($sqlGetImg);

$imgArr=[$_SESSION['vendorAccount']];

$stmtImg->execute($imgArr);

if($stmtImg->rowCount()>0){
    $arrImg =$stmtImg->fetch(PDO::FETCH_ASSOC);

    if($arrImg['vendorImg'] !==NULL){
        @unlink("./img/".$arrImg['vendorImg']);
    }
}


$sqlDelete = "DELETE FROM `vendordata` WHERE `vendorAccount` = ? ";

$stmt = $pdo->prepare($sqlDelete);

$arrParam = [$_SESSION['vendorAccount']];

$stmt->execute($arrParam);


// $sql = "DELETE FROM `vendordata` WHERE `vendordata`.`id` = ?";

// $arrParam = [
//     (int) $_GET['deleteId']
// ];


if ($stmt->rowCount() > 0) {
    header("Refresh:3;url=./index.php");
    echo "刪除成功";
} else {
    header("Refresh:3;url=./admin.php");
    echo "刪除失敗";
}
