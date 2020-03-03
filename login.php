<?php
require_once("connMysql.php");
session_start();
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
//執行會員登入
if(isset($_POST["username"]) && isset($_POST["passwd"])){
	//繫結登入會員資料
	$query_RecLogin = "SELECT m_username, m_passwd, m_level FROM memberdata WHERE m_username=?";
	$stmt=$db_link->prepare($query_RecLogin);
	$stmt->bind_param("s", $_POST["username"]);
	$stmt->execute();
	//取出帳號密碼的值綁定結果
	$stmt->bind_result($username, $passwd, $level);	
	$stmt->fetch();
	$stmt->close();
	//比對密碼，若登入成功則呈現登入狀態
	if(password_verify($_POST["passwd"],$passwd)){
		//計算登入次數及更新登入時間
		$query_RecLoginUpdate = "UPDATE memberdata SET m_login=m_login+1, m_logintime=NOW() WHERE m_username=?";
		$stmt=$db_link->prepare($query_RecLoginUpdate);
	    $stmt->bind_param("s", $username);
	    $stmt->execute();	
	    $stmt->close();
		//設定登入者的名稱及等級
		$_SESSION["loginMember"]=$username;
		$_SESSION["memberLevel"]=$level;
		//使用Cookie記錄登入資料
		if(isset($_POST["rememberme"])&&($_POST["rememberme"]=="true")){
			setcookie("remUser", $_POST["username"], time()+60*60*24);
			setcookie("remPass", $_POST["passwd"], time()+60*60*24);
		}else{
			if(isset($_COOKIE["remUser"])){
				setcookie("remUser", $_POST["username"], time()-100);
				setcookie("remPass", $_POST["passwd"], time()-100);
			}
		}
		//若帳號等級為 member 則導向會員中心
		if($_SESSION["memberLevel"]=="member"){
			header("Location: member_center.php");
		//否則則導向管理中心
		}else{
			header("Location: member_admin.php");	
		}
	}else{
		header("Location: login.php?errMsg=1");
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <?php
  require_once './tpl/head.php';
  ?>

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
  <div id="login-page">
    <div class="container">
      <form class="form-login" method="post" action="">
        
        <h2 class="form-login-heading">sign in now</h2>
        <div class="login-wrap">
        <?php 
        if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
          <div class="errDiv"> 登入帳號或密碼錯誤！</div><?php }?>

        <input name="username" type="text" class="form-control" id="username" placeholder="輸入帳號" value="<?php if(isset($_COOKIE["remUser"]) && ($_COOKIE["remUser"]!="")) echo $_COOKIE["remUser"];?>">
          <br>
          <input name="passwd" type="password" class="form-control" id="passwd" placeholder="輸入密碼" value="<?php if(isset($_COOKIE["remPass"]) && ($_COOKIE["remPass"]!="")) echo $_COOKIE["remPass"];?>">
          <label class="checkbox">
          <input name="rememberme" type="checkbox" id="rememberme" value="true">記住我的帳號密碼。</p></label>
            <span class="pull-right">
            <!-- <a data-toggle="modal" href="admin_passmail.php"> Forgot Password?</a> -->
            <a class="" href="admin_passmail.php"> Forgot password?</a>
            </span>
            </label>
          <button class="btn btn-theme btn-block" href="index.html" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>
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
        <!-- Modal -->
      
        <!-- <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><a href="admin_passmail.php">Forgot Password ?</a></h4>
              </div>
              <div class="modal-body">
                <p>Enter your e-mail address below to reset your password.</p>
                <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
              </div>
              <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                <button class="btn btn-theme" type="button">Submit</button>
              </div>
            </div>
          </div>
        </div> -->
        <!-- modal -->
      </form>
    </div>
  </div>
  <!-- js placed at the end of the document so the pages load faster -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <!--BACKSTRETCH-->
  <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
  <script type="text/javascript" src="lib/jquery.backstretch.min.js"></script>
  <script>
    $.backstretch("img/login-bg.jpg", {
      speed: 500
    });
  </script>
  </section>
  </main>
  <?php
require_once './tpl/footer.php'
?> 
</body>

</html>
