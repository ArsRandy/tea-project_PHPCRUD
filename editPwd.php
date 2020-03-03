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
  require_once './tpl/headerLogout.php';
  ?>
  <?php
  require_once './tpl/sideBar.php';
  ?>
  <!-- main content -->
  <main id="main-content">
    <section class="wrapper">



    <a href='./admin.php'>返回主頁</a>

    <form method="POST" action="editPwdInsert.php">
    <label>請輸入舊密碼</label>
    <input type="password" name="oldPwd">
    <br>
    <label>請輸入新密碼</label>
    <input type="password" name="newPwd">
    <br>
    <label>請再次輸入新密碼</label>
    <input type="password" name="newPwdAgain">

    <button type="submit">更改密碼</button>

    </section>


  </main>
  <!-- main end -->
  <?php
  require_once './tpl/footer.php';
  ?>
</body>

</html>