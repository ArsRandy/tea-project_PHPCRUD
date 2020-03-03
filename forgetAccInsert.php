<?php
require_once './check.php';
require_once('./sendmail/mailtest.php');
// require_once('./db.inc.php');

$sql = "SELECT `vendorAccount`,`vendorEmail`,`vendorName` FROM `vendordata` WHERE `vendorEmail`=?";

$param = [$_POST['sentEmail']];

// echo "<pre>";
// print_r($param);
// echo "</pre>";
// exit();


$stmt = $pdo->prepare($sql);
$stmt->execute($param);


if ($stmt->rowCount() > 0) {

    $arr = $stmt->fetch(PDO::FETCH_ASSOC);
    $vendorAcc = $arr['vendorAccount'];
    $vendorEmail = $arr['vendorEmail'];
    $vendorName = $arr['vendorName'];
    sendAcc($vendorAcc, $vendorEmail, $vendorName);

} else {
    header("Refresh:3;url=./index.php");
    echo "此Email無註冊過，請使用別的E-mail";
}
