<?php
require_once("connMysql.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	header("Location: longin.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: login.php");
}
//繫結登入會員資料
$query_RecMember = "SELECT * FROM memberdata WHERE m_username = '{$_SESSION["loginMember"]}'";
$RecMember = $db_link->query($query_RecMember);	
$row_RecMember=$RecMember->fetch_assoc();
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <?php
  require_once './tpl/head.php';
  ?>
<title>網站會員系統</title>

<!-- Favicons -->
<link href="img/favicon.png" rel="icon">
<link href="img/apple-touch-icon.png" rel="apple-touch-icon">

<!-- Bootstrap core CSS -->
<link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
</head>

<body>
<?php
require_once './tpl/header.php';
require_once './tpl/sideBar.php';
?>
<main id="main-content">
    <section class="wrapper">
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
  
        <!-- <fieldset> -->
          <div id="login-page">
          <div class="container">
          <!-- <div class="regbox"> -->
          <form class="form-login" method="post" action="">
          <h2  align="center"class="form-login-heading">會員系統</h2>
          <div class="login-wrap">

            <p align="center"><strong><?php echo $row_RecMember["m_name"];?></strong> 您好!</p>
            <p align="center">您總共登入了 <?php echo $row_RecMember["m_login"];?> 次<br>
            本次登入的時間為：<br>
            <?php echo $row_RecMember["m_logintime"];?></p>
            <p align="center"><a href="member_update.php">修改資料</a> | <a href="?logout=true">登出系統</a></p></div>
        </fieldset>
        
      </tr>
    </table></td>
  </tr>
</table>
</section>
  </main>
<?php
require_once './tpl/footer.php'
?>
</body>
</html>
<?php
	$db_link->close();
?>