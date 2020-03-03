<?php
require_once('./check.php');
require_once('./db.inc.php');

$sql = "UPDATE `vendordata`
    SET `vendorName`=?,
        `vendorPhone`=?,
        `vendorAddress`=?";


$arr = [
    $_POST['vendorName'],
    $_POST['vendorPhone'],
    $_POST['vendorAddress'],
];

if ($_FILES["vendorImg"]["error"] === 0) {
    $vendorImg = date("YmdHis");

    $extension = pathinfo($_FILES["vendorImg"]["name"], PATHINFO_EXTENSION);

    $imgName = $vendorImg . "." . $extension;

    if (move_uploaded_file($_FILES["vendorImg"]["tmp_name"], "./img/{$imgName}")) {

        $sqlGetImg = "SELECT `vendorImg` FROM `vendordata` WHERE `vendorAccount`=?";

        $stmtGetImg = $pdo->prepare($sqlGetImg);

        $arrGetImgParam = [
            $_SESSION['vendorAccount']
        ];

        $stmtGetImg->execute($arrGetImgParam);


        if ($stmtGetImg->rowCount() > 0) {
            $arrImg = $stmtGetImg->fetch(PDO::FETCH_ASSOC);

            if ($arrImg['vendorImg'] !== NULL) {
                @unlink("./img/" . $arrImg['vendorImg']);
                $sql .= " ,`vendorImg`=?";
                $arr[] = $imgName;
            }
        }
    }
}



$sql .= " WHERE `vendorAccount` = ?";
$arr[] = $_SESSION['vendorAccount'];


// echo "<pre>";
// print_r($arr);
// echo "</pre>";
// exit();



$pdo_stmt = $pdo->prepare($sql);


$pdo_stmt->execute($arr);



if ($pdo_stmt->rowCount() > 0) {
    header("Refresh:3;url=./admin.php");
    echo "更新成功";
    exit();
} else {
    header("Refresh:3;url=./editData.php");
    echo "無更新";
    exit();
}
