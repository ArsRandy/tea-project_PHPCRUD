<?php
require_once('./check.php');
require_once('db.inc.php');

$newPwd = sha1($_POST['newPwd']);
if ($newPwd !== sha1($_POST['newPwdAgain'])) {
    header('Refresh:3;url=./editPwd.php');
    echo "新密碼與確認新密碼不一致";
    exit();
}

$oldPwdSql = "SELECT `vendorpassword` FROM `vendordata` WHERE `id` = ?";
$oldPwd_stmt = $pdo->prepare($oldPwdSql);
$oldPwd_stmt->execute([$_SESSION['id']]);
$oldPwd = $oldPwd_stmt->fetch(PDO::FETCH_NUM)[0];
if ($oldPwd !== sha1($_POST['oldPwd'])) {
    header('Refresh:2;url=./editPwd.php');
    echo "舊密碼輸入錯誤";
    exit();
}

$arr = [$newPwd, $_SESSION['id']];
$updateSql = "UPDATE `vendordata` SET `vendorPassword` = ? WHERE `id` = ?";
$stmt = $pdo->prepare($updateSql);
$stmt->execute($arr);

if ($stmt->rowCount() > 0) {
    header('Refresh:2;url=./editData.php');
    echo "更改密碼成功";
} else {
    echo $_SESSION['id'];
    echo "變更失敗";
}
