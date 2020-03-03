<?php
require_once './check.php';
function GetSQLValueString($theValue, $theType) {
  switch ($theType) {
    case "string":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_MAGIC_QUOTES) : "";
      break;
    case "int":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_NUMBER_INT) : "";
      break;
    case "email":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_EMAIL) : "";
      break;
    case "url":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_URL) : "";
      break;      
  }
  return $theValue;
}
require_once("connMysql.php");
session_start();
//函式：自動產生指定長度的密碼
function MakePass($length) { 
	$possible = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
	$str = ""; 
	while(strlen($str)<$length){ 
	  $str .= substr($possible, rand(0, strlen($possible)), 1); 
	}
	return($str); 
}
//檢查是否經過登入，若有登入則重新導向
if(isset($_SESSION["loginMember"]) && ($_SESSION["loginMember"]!="")){
	//若帳號等級為 member 則導向會員中心
	if($_SESSION["memberLevel"]=="member"){
		header("Location: member_center.php");
	//否則則導向管理中心
	}else{
		header("Location: member_admin.php");	
	}
}
//檢查是否為會員
if(isset($_POST["m_username"])){
	$muser = GetSQLValueString($_POST["m_username"], 'string');
	//找尋該會員資料
	$query_RecFindUser = "SELECT m_username, m_email FROM memberdata WHERE m_username='{$muser}'";
	$RecFindUser = $db_link->query($query_RecFindUser);	
	if ($RecFindUser->num_rows==0){
		header("Location: admin_passmail.php?errMsg=1&username={$muser}");
	}else{	
	//取出帳號密碼的值
		$row_RecFindUser=$RecFindUser->fetch_assoc();
		$username = $row_RecFindUser["m_username"];
		$usermail = $row_RecFindUser["m_email"];	
		//產生新密碼並更新
		$newpasswd = MakePass(6);
		$mpass = password_hash($newpasswd, PASSWORD_DEFAULT);
		$query_update = "UPDATE memberdata SET m_passwd='{$mpass}' WHERE m_username='{$username}'";
		$db_link->query($query_update);
		//補寄密碼信
		$mailcontent ="您好，<br />您的帳號為：{$username} <br/>您的新密碼為：{$newpasswd} <br/>";
		$mailFrom="=?UTF-8?B?" . base64_encode("會員管理系統") . "?= <service@e-happy.com.tw>";
		$mailto=$usermail;
		$mailSubject="=?UTF-8?B?" . base64_encode("補寄密碼信"). "?=";
		$mailHeader="From:".$mailFrom."\r\n";
		$mailHeader.="Content-type:text/html;charset=UTF-8";
		if(!@mail($mailto,$mailSubject,$mailcontent,$mailHeader)) die("郵寄失敗！");
		header("Location: admin_passmail.php?mailStats=1");
	}
}
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
  <link href="lib/bootstrap/css/bootstrap.min.css" 
  rel="stylesheet">
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
      <div id="login-page">
      <div class="container">
      <form class="form-login" method="post" action="">

      <h2 class="form-login-heading">Forget Password</h2>
        <div class="login-wrap">
          <?php if(isset($_GET["mailStats"]) && ($_GET["mailStats"]=="1")){?>
          <script>alert('密碼信補寄成功！');window.location.href='index.php';</script>
          <?php }?>
  
          <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
          <font color = red><div class="errDiv">帳號<strong><?php echo $_GET["username"];?></strong>沒有人使用！</div></font>
          <?php }?>
          <form name="form1" method="post" action="">
            <p>請輸入您申請的帳號，系統將自動產生一個6位數的密碼寄到您註冊的信箱。</p>
              <input name="m_username" type="text" class="form-control" id="m_mail" placeholder="請填入帳號"></p></label>
            <p align="center">
              <input  class="btn btn-theme btn-block"type="submit" name="button" id="button" value="Send Password">
              <input  class="btn btn-theme btn-block" type="button" name="button2" id="button2" value="Back" onClick="window.history.back();">
            </p>
            <hr>
          <form name="form1" method="post" action="">
          <fieldset>
            <!-- </form> -->
          <div class="registration">
            Don't have an account yet?<br/>
            <a class="" href="member_join.php">
              Create an account
              </a>
          </div>
        </div>
  
</table>
</section>
  </main>
<?php
require_once './tpl/footer.php'
?>

</body>

</html>