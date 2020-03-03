<?php
require_once('./check.php');
require_once('db.inc.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require_once './tpl/head.php'
  ?>
  <link rel="stylesheet" type="text/css" href="./css/myCss.css" />
</head>

<body>
  <?php
  require_once './tpl/header.php';
  ?>
  <?php
  require_once './tpl/sideBar.php';
  ?>
  <!-- main content -->
  <main id="main-content">
    <section class="wrapper">

    </section>

    <a href='./admin.php'>返回主頁</a>|<a href='./editPwd.php'>修改密碼</a>


    <form method="POST" action="./editDataDetail.php" enctype="multipart/form-data">

        <?php

        $sql = "SELECT `vendorName`,`vendorPhone`,`vendorAddress`,`vendorImg`
    FROM `vendordata`
    WHERE `vendoraccount`= ?";

        $arrParam = [$_SESSION['vendorAccount']];

        $stmt = $pdo->prepare($sql);
        $stmt->execute($arrParam);

        // echo "<pre>";
        // print_r($_SESSION['vendorAccount']);
        // echo "<pre>";
        // exit();

        if ($stmt->rowCount() > 0) {
            $arr = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        ?>

            <ul>
                <li>廠商名稱:<input type=text name="vendorName" value="<?php echo $arr['vendorName']; ?>"></li>
                <li>廠商電話:<input type=text name="vendorPhone" value="<?php echo $arr['vendorPhone']; ?>"></li>
                <li>廠商地址:<input type=text name="vendorAddress" value="<?php echo $arr['vendorAddress']; ?>"></li>
                <li>廠商圖片:<?php if ($arr['vendorImg'] !== NULL) { ?>
                    <img class="w200" src="./images/vendor/<?php echo $arr['vendorImg']; ?>">
                <?php } ?><input type="file" name="vendorImg"></li>
            </ul>

            <input type="submit" value="送出">

            <input type="hidden" name="editId" value=" ?>">
    </form>
  </main>
  <!-- main end -->
  <?php
  require_once './tpl/footer.php';
  ?>
</body>

</html>