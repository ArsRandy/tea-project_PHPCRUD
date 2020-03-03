<?php 
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
    //case "url":
    //  $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_URL) : "";
    //  break;      
  }
  return $theValue;
}
require_once("connMysql.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	header("Location: index.php");
}
//檢查權限是否足夠
if($_SESSION["memberLevel"]=="member"){
	header("Location: member_center.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: index.php");
}
//執行更新動作
if(isset($_POST["action"])&&($_POST["action"]=="update")){	
	$query_update = "UPDATE memberdata SET m_passwd=?, m_name=?, m_sex=?, m_birthday=?, m_email=?, m_url=?, m_phone=?, m_address=? WHERE m_id=?";
	$stmt = $db_link->prepare($query_update);
	//檢查是否有修改密碼
	$mpass = $_POST["m_passwdo"];
	if(($_POST["m_passwd"]!="")&&($_POST["m_passwd"]==$_POST["m_passwdrecheck"])){
		$mpass = password_hash($_POST["m_passwd"], PASSWORD_DEFAULT);
	}
	$stmt->bind_param("ssssssssi", 
		$mpass,
		GetSQLValueString($_POST["m_name"], 'string'),
		GetSQLValueString($_POST["m_sex"], 'string'),		
		GetSQLValueString($_POST["m_birthday"], 'string'),
		GetSQLValueString($_POST["m_email"], 'email'),
		GetSQLValueString($_POST["m_url"], 'url'),
		GetSQLValueString($_POST["m_phone"], 'string'),
		GetSQLValueString($_POST["m_address"], 'string'),		
		GetSQLValueString($_POST["m_id"], 'int'));
	$stmt->execute();
	$stmt->close();
		//重新導向
	header("Location: member_admin.php");
}
//選取管理員資料
$query_RecAdmin = "SELECT * FROM memberdata WHERE m_username='{$_SESSION["loginMember"]}'";
$RecAdmin = $db_link->query($query_RecAdmin);	
$row_RecAdmin=$RecAdmin->fetch_assoc();
//繫結選取會員資料
$query_RecMember = "SELECT * FROM memberdata WHERE m_id='{$_GET["id"]}'";
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

<script language="javascript">
function checkForm(){
	if(document.formJoin.m_passwd.value!="" || document.formJoin.m_passwdrecheck.value!=""){
		if(!check_passwd(document.formJoin.m_passwd.value,document.formJoin.m_passwdrecheck.value)){
			document.formJoin.m_passwd.focus();
			return false;
		}
	}	
	if(document.formJoin.m_name.value==""){
		alert("請填寫姓名!");
		document.formJoin.m_name.focus();
		return false;
	}
	if(document.formJoin.m_birthday.value==""){
		alert("請填寫生日!");
		document.formJoin.m_birthday.focus();
		return false;
	}
	if(document.formJoin.m_email.value==""){
		alert("請填寫電子郵件!");
		document.formJoin.m_email.focus();
		return false;
	}
	if(!checkmail(document.formJoin.m_email)){
		document.formJoin.m_email.focus();
		return false;
	}
	return confirm('確定送出嗎？');
}
function check_passwd(pw1,pw2){
	if(pw1==''){
		alert("密碼不可以空白!");
		return false;
	}
	for(var idx=0;idx<pw1.length;idx++){
		if(pw1.charAt(idx) == ' ' || pw1.charAt(idx) == '\"'){
			alert("密碼不可以含有空白或雙引號 !\n");
			return false;
		}
		if(pw1.length<5 || pw1.length>10){
			alert( "密碼長度只能5到10個字母 !\n" );
			return false;
		}
		if(pw1!= pw2){
			alert("密碼二次輸入不一樣,請重新輸入 !\n");
			return false;
		}
	}
	return true;
}
function checkmail(myEmail) {
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(filter.test(myEmail.value)){
		return true;
	}
	alert("電子郵件格式不正確");
	return false;
}
</script>
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

<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
  
          <div class="dataDiv">
         
            <h2 class="form-login-heading">登入資訊</h2>
            <div class="login-wrap">

            <label><p name="m_username" type="text" class="form-control" id="m_username">使用帳號：<?php echo $row_RecMember["m_username"];?></p></label>

            <input name="m_passwd" type="password" class="form-control" id="m_passwd"placeholder="輸入新密碼">
            <input name="m_passwdo" type="hidden" id="m_passwdo" value="<?php echo $row_RecMember["m_passwd"];?>"></p>

            
            <input name="m_passwdrecheck" type="password" class="form-control" id="m_passwdrecheck" placeholder="再填入一次"><br></p>
          </fieldset>
          </div>
          <fieldset>
          <h2 class="form-login-heading">個人資訊</h2>
          <div class="login-wrap">

            <input name="m_name" type="text" class="form-control" id="m_name" value="<?php echo $row_RecMember["m_name"];?>"placeholder="請填入姓名">
            <font color="#FF0000">*</font> </p>

            <p>性　　別：
            <label><input name="m_sex" type="radio" value="女" <?php if($row_RecMember["m_sex"]=="女") echo "checked";?>>女</label>
            <label><input name="m_sex" type="radio" value="男" <?php if($row_RecMember["m_sex"]=="男") echo "checked";?>>男</label>
            <font color="#FF0000">*</font></p>

            
            <input name="m_birthday" type="date" class="form-control" id="m_birthday" value="<?php echo $row_RecMember["m_birthday"];?>">
            <font color="#FF0000">*</font><br></p>

            
            <input name="m_email" type="email" class="form-control" id="m_email" value="<?php echo $row_RecMember["m_email"];?>"placeholder="請填入信箱"require>
            <font color="#FF0000">*</font><br></p>

            
            <input name="m_phone" type="text" class="form-control" id="m_phone" value="<?php echo $row_RecMember["m_phone"];?>"placeholder="請填入手機號碼"></p>

            
            <input name="m_address" type="text" class="form-control" id="m_address" value="<?php echo $row_RecMember["m_address"];?>" size="40" placeholder="請填入住址"></p>

            <textarea name="m_url" rows="10" cols="48" type="text" class="form-control" id="m_url" value="<?php echo $row_RecMember["m_url"];?>" placeholder="沒有內容" ></textarea>
			      </p></label>

            <p><font color="#FF0000">*</font> 表示為必填的欄位</p>
          </div>
          </fieldset>

          <p align="center">
            <input class="btn btn-theme btn-block"name="m_id" type="hidden" id="m_id" value="<?php echo $row_RecMember["m_id"];?>">

            <input class="btn btn-theme btn-block"name="action" type="hidden" id="action" value="update">

            <input class="btn btn-theme btn-block"type="submit" name="Submit2" value="修改資料">
            <!-- <input type="reset" name="Submit3" value="重設資料"> -->
            <input class="btn btn-theme btn-block"type="button" name="Submit" value="回上一頁" onClick="window.history.back();">
          </p>
        </form></td>

      <fieldset>
        <div class="regbox">
          <p align="top" class="form-login-heading"><strong>會員系統</strong></p>
          <div class="login-wrap">
          
            <p><strong><?php echo $row_RecAdmin["m_name"];?></strong> 您好。</p>
            <p>本次登入的時間為：<br>
            <?php echo $row_RecAdmin["m_logintime"];?></p>
            <p align="center"><a href="member_admin.php">管理中心</a> | <a href="?logout=true">登出系統</a></p>
        </div>
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