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

    <h5 class="badge-success">廠商會員資料區<h5>
    <a class="delete btn btn-danger" onClick="return confirm('確定要註銷帳號?確定就無法回復')" href='./deleteAcc.php' type='button'>註銷帳號</a>
            <?php


            $sql = "SELECT `id`,`vendorName`,`vendorPhone`,`vendorAddress`,`vendorImg`
            FROM `vendordata`
            WHERE `vendorAccount`= ?";

            $IdData = [$_SESSION['vendorAccount']];
            $stmt = $pdo->prepare($sql);
            $stmt->execute($IdData);


            if ($stmt->rowCount() > 0) {
                $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                for ($i = 0; $i < count($arr); $i++) {

                    // echo "<pre>";
                    // print_r($arr);
                    // echo "<pre>";
                    // exit();
            ?>

                    <ul>
                        <li> <a href='./editData.php?editId=<?php echo $arr[$i]['id']; ?>'>編輯個人資料</a>|</li>
                        <li>廠商名稱:<?php echo $arr[$i]['vendorName']; ?></li>
                        <li>廠商電話:<?php echo $arr[$i]['vendorPhone']; ?></li>
                        <li>廠商地址:<?php echo $arr[$i]['vendorAddress']; ?></li>
                        <li>廠商圖片:<?php if ($arr[$i]['vendorImg'] !== NULL) { ?>
                            <img class="w200" src="./images/vendor/<?php echo $arr[$i]['vendorImg'] ?>">
                        <?php } ?></li>

                    </ul>
                <?php
                }
            } else {
                ?>
                <div>沒有資料</div>
            <?php
            }
            ?>




  </main>
  <!-- main end -->
  <?php
  require_once './tpl/footer.php';
  ?>
</body>

</html>