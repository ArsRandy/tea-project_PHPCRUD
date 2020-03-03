<?php
function GetSQLValueString($theValue, $theType)
{
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

if (isset($_POST["action"]) && ($_POST["action"] == "join")) {
	require_once("connMysql.php");
	//找尋帳號是否已經註冊
	$query_RecFindUser = "SELECT m_username FROM memberdata WHERE m_username='{$_POST["m_username"]}'";
	$RecFindUser = $db_link->query($query_RecFindUser);
	if ($RecFindUser->num_rows > 0) {
		header("Location: member_join.php?errMsg=1&username={$_POST["m_username"]}");
	} else {
		//若沒有執行新增的動作	
		$query_insert = "INSERT INTO memberdata (m_name, m_username, m_passwd, m_sex, m_birthday, m_email, m_url, m_phone, m_address, m_jointime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
		$stmt = $db_link->prepare($query_insert);
		$stmt->bind_param(
			"sssssssss",
			GetSQLValueString($_POST["m_name"], 'string'),
			GetSQLValueString($_POST["m_username"], 'string'),
			password_hash($_POST["m_passwd"], PASSWORD_DEFAULT),
			GetSQLValueString($_POST["m_sex"], 'string'),
			GetSQLValueString($_POST["m_birthday"], 'string'),
			GetSQLValueString($_POST["m_email"], 'email'),
			GetSQLValueString($_POST["m_url"], 'url'),
			GetSQLValueString($_POST["m_phone"], 'string'),
			GetSQLValueString($_POST["m_address"], 'string')
		);
		$stmt->execute();
		$stmt->close();
		$db_link->close();
		header("Location: member_join.php?loginStats=1");
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
	<link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!--external css-->
	<link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<!-- Custom styles for this template -->
	<link href="css/style.css" rel="stylesheet">
	<link href="css/style-responsive.css" rel="stylesheet">

	<script language="javascript">
		function checkForm() {
			if (document.formJoin.m_username.value == "") {
				alert("請填寫帳號!");
				document.formJoin.m_username.focus();
				return false;
			} else {
				uid = document.formJoin.m_username.value;
				if (uid.length < 5 || uid.length > 12) {
					alert("您的帳號長度只能5至12個字元!");
					document.formJoin.m_username.focus();
					return false;
				}
				if (!(uid.charAt(0) >= 'a' && uid.charAt(0) <= 'z')) {
					alert("您的帳號第一字元只能為小寫字母!");
					document.formJoin.m_username.focus();
					return false;
				}
				for (idx = 0; idx < uid.length; idx++) {
					if (uid.charAt(idx) >= 'A' && uid.charAt(idx) <= 'Z') {
						alert("帳號不可以含有大寫字元!");
						document.formJoin.m_username.focus();
						return false;
					}
					if (!((uid.charAt(idx) >= 'a' && uid.charAt(idx) <= 'z') || (uid.charAt(idx) >= '0' && uid.charAt(idx) <= '9') || (uid.charAt(idx) == '_'))) {
						alert("您的帳號只能是數字,英文字母及「_」等符號,其他的符號都不能使用!");
						document.formJoin.m_username.focus();
						return false;
					}
					if (uid.charAt(idx) == '_' && uid.charAt(idx - 1) == '_') {
						alert("「_」符號不可相連 !\n");
						document.formJoin.m_username.focus();
						return false;
					}
				}
			}
			if (!check_passwd(document.formJoin.m_passwd.value, document.formJoin.m_passwdrecheck.value)) {
				document.formJoin.m_passwd.focus();
				return false;
			}
			if (document.formJoin.m_name.value == "") {
				alert("請填寫姓名!");
				document.formJoin.m_name.focus();
				return false;
			}
			if (document.formJoin.m_birthday.value == "") {
				alert("請填寫生日!");
				document.formJoin.m_birthday.focus();
				return false;
			}
			if (document.formJoin.m_email.value == "") {
				alert("請填寫電子郵件!");
				document.formJoin.m_email.focus();
				return false;
			}
			if (!checkmail(document.formJoin.m_email)) {
				document.formJoin.m_email.focus();
				return false;
			}
			return confirm('確定送出嗎？');
		}

		function check_passwd(pw1, pw2) {
			if (pw1 == '') {
				alert("密碼不可以空白!");
				return false;
			}
			for (var idx = 0; idx < pw1.length; idx++) {
				if (pw1.charAt(idx) == ' ' || pw1.charAt(idx) == '\"') {
					alert("密碼不可以含有空白或雙引號 !\n");
					return false;
				}
				if (pw1.length < 5 || pw1.length > 10) {
					alert("密碼長度只能5到10個字母 !\n");
					return false;
				}
				if (pw1 != pw2) {
					alert("密碼二次輸入不一樣,請重新輸入 !\n");
					return false;
				}
			}
			return true;
		}

		function checkmail(myEmail) {
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (filter.test(myEmail.value)) {
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
			<?php if (isset($_GET["loginStats"]) && ($_GET["loginStats"] == "1")) { ?>
				<script language="javascript">
					alert('會員新增成功\n請用申請的帳號密碼登入。');
					window.location.href = 'index.php';
				</script>
			<?php } ?>

			<div id="login-page">
				<div class="container">
					<form class="form-login" name="formJoin" id="formJoin" method="post" action="" onsubmit="return checkForm();">

						<h2 class="form-login-heading">加入會員</h2>

						<?php if (isset($_GET["errMsg"]) && ($_GET["errMsg"] == "1")) { ?>
							<font color=red>
								<div class="errDiv">帳號 <?php echo $_GET["username"]; ?> 已經有人使用！</div>
							</font>
						<?php } ?>
						<div class="login-wrap">


							<input name="m_username" type="text" class="form-control" id="m_username" placeholder="帳號：請填入英文字母、數字">
							<font color="#FF0000">*</font><br>


							<input name="m_passwd" type="password" class="form-control" id="m_passwd" placeholder="密碼：請填入英文字母、數字">
							<font color="#FF0000">*</font><br>


							<input name="m_passwdrecheck" type="password" class="form-control" id="m_passwdrecheck" placeholder="確認密碼：再填入一次">
							<font color="#FF0000">*</font> <br>
							</fieldset>
						</div>

						<fieldset>
							<h2 class="form-login-heading">個人資訊</h2>
							<div class="login-wrap">


								<input name="m_name" type="text" class="form-control" id="m_name" placeholder="請填入姓名">
								<font color="#FF0000">*</font>


								<label>
									<p>性　　別
								</label>：
								<label><input name="m_sex" type="radio" value="女" checked>女</label>
								<label><input name="m_sex" type="radio" value="男">男</label>
								<font color="#FF0000">*</font>
								</p>


								<input name="m_birthday" type="date" class="form-control" id="m_birthday">
								<font color="#FF0000">*</font> <br></p>


								<input name="m_email" type="email" class="form-control" id="m_email" placeholder="請填入信箱" requare>
								<font color="#FF0000">*</font><br></p>


								<input name="m_phone" type="text" class="form-control" id="m_phone" placeholder="請填入手機號碼"></p>


								<input name="m_address" type="text" class="form-control" id="m_address" placeholder="請填入住址"></P>


								<textarea name="m_url" rows="10" cols="80" type="text" class="form-control" id="m_url" placeholder="個人簡介" value="<?php echo $row_RecMember["m_url"]; ?>"></textarea>
								</p></label>

								<p>
									<font color="#FF0000">*</font> 表示為必填的欄位
								</p>
							</div>

							<p align="center">
								<input class="btn btn-theme btn-block" name="action" type="hidden" id="action" value="join">
								<input class="btn btn-theme btn-block" type="submit" name="Submit2" value="送出申請">
								<!-- <input type="reset" name="Submit3" value="重設資料"> -->
								<input class="btn btn-theme btn-block" type="button" name="Submit" value="回上一頁" onClick="window.history.back();">
							</p>
					</form>
					</td>
					</table>
		</section>
	</main>
	<?php
	require_once './tpl/footer.php'
	?>

</body>

</html>