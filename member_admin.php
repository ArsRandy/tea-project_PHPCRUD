<?php 
require_once("connMysql.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	header("Location: login.php");
}
//檢查權限是否足夠
if($_SESSION["memberLevel"]=="member"){
	header("Location: member_center.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: login.php");
}
//刪除會員
if(isset($_GET["action"])&&($_GET["action"]=="delete")){
	$query_delMember = "DELETE FROM memberdata WHERE m_id=?";
	$stmt=$db_link->prepare($query_delMember);
	$stmt->bind_param("i", $_GET["id"]);
	$stmt->execute();
	$stmt->close();
	//重新導向回到主畫面
	header("Location: member_admin.php");
}
//選取管理員資料
$query_RecAdmin = "SELECT m_id, m_name, m_logintime FROM memberdata WHERE m_username=?";
$stmt=$db_link->prepare($query_RecAdmin);
$stmt->bind_param("s", $_SESSION["loginMember"]);
$stmt->execute();
$stmt->bind_result($mid, $mname, $mlogintime);
$stmt->fetch();
$stmt->close();
//選取所有一般會員資料
//預設每頁筆數
$pageRow_records = 5;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
  $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的SQL敘述句
$query_RecMember = "SELECT * FROM memberdata WHERE m_level<>'admin' ORDER BY m_jointime DESC";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecMember = $query_RecMember." LIMIT {$startRow_records}, {$pageRow_records}";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $resultMember 中
$RecMember = $db_link->query($query_limit_RecMember);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_resultMember 中
$all_RecMember = $db_link->query($query_RecMember);
//計算總筆數
$total_records = $all_RecMember->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);
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
function deletesure(){
    if (confirm('\n您確定要刪除這個會員嗎?\n刪除後無法恢復!\n')) return true;
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
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
  
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
        <td><h2 class="form-login-heading">會員資料列表</h2>
        <div class="login-wrap">

          <table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#F0F0F0">
            <tr>
              <th width="10%" bgcolor="#CCCCCC">&nbsp;</th>
              <th width="20%" bgcolor="#CCCCCC"><p>姓名</p></th>
              <th width="20%" bgcolor="#CCCCCC"><p>帳號</p></th>
              <th width="20%" bgcolor="#CCCCCC"><p>加入時間</p></th>
              <th width="20%" bgcolor="#CCCCCC"><p>上次登入</p></th>
              <th width="10%" bgcolor="#CCCCCC"><p>登入</p></th>
            </tr>

			<?php while($row_RecMember=$RecMember->fetch_assoc()){ ?>
            <tr>
              <td width="10%" align="center" bgcolor="#FFFFFF"><p><a href="member_adminupdate.php?id=<?php echo $row_RecMember["m_id"];?>">修改</a><br>
                <a href="?action=delete&id=<?php echo $row_RecMember["m_id"];?>" onClick="return deletesure();">刪除</a></p></td>
              <td width="20%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_RecMember["m_name"];?></p></td>
              <td width="20%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_RecMember["m_username"];?></p></td>
              <td width="20%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_RecMember["m_jointime"];?></p></td>
              <td width="20%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_RecMember["m_logintime"];?></p></td>
              <td width="10%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_RecMember["m_login"];?></p></td>
            </tr>
			<?php }?>
          </table>          
          <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
            <tr>
              <td valign="middle"><p>資料筆數：<?php echo $total_records;?></p></td>
              <td align="right"><p>
                  <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
                  <a href="?page=1">第一頁</a> | <a href="?page=<?php echo $num_pages-1;?>">上一頁</a> |
                <?php }?>
                  <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
                  <a href="?page=<?php echo $num_pages+1;?>">下一頁</a> | <a href="?page=<?php echo $total_pages;?>">最末頁</a>
                  <?php }?>
              </p></td>
            </tr>
          </table><p>&nbsp;</p>
          </td>
        <td width="200">
        <div class="boxtl"></div><div class="boxtr"></div>

      <fieldset>
        <div class="regbox">
          <p align="center" class="form-login-heading"><strong>會員系統</strong></p>
          <div class="login-wrao">
            <p><strong><?php echo $mname;?></strong>您好!<br>
            本次登入的時間為：<br>
            <?php echo $mlogintime;?></p>
            <p align="center"><a href="member_adminupdate.php?id=<?php echo $mid;?>">修改資料</a> | <a href="?logout=true">登出系統</a></p>
        </div>
      </fieldset>
        <div class="boxbl"></div><div class="boxbr"></div></td>
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
