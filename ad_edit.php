<?php
//引入判斷是否登入機制
require_once('./checkSession.php');

//引用資料庫連線
require_once('./ad_db.inc.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
require_once './tpl/head.php'
?>
    <style>
    .border {
        border: 1px solid;
    }
    img.img {
        width: 100px;
    }
    </style>
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
<?php require_once('./templates/ad_title.php'); ?>
<hr />
<div class="table-title">
       <h4>新增廣告</h4>
  </div>
<form name="myForm" method="POST" action="updateEdit.php" enctype="multipart/form-data">
    <table class="border table table-striped table-dark custom-table">
    <thead>
            <tr>
            <th class="border">項目</th>
            <th class="border">內容</th>
            </tr>
            </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `id`, `ad_name`, `ad_url`, `ad_img`, `ad_slogan`,`ad_status`
                FROM `advertisement` 
                WHERE `id` = ?";

        //設定繫結值
        $arrParam = [(int)$_GET['editId']];

        //查詢
        $stmt = $pdo->prepare($sql);
        $stmt->execute($arrParam);
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($arr) > 0) {
        ?>
            <tr>
                <td class="border">廣告名稱</td>
                <td class="border">
                    <input type="text" name="ad_name"" value="<?php echo $arr[0]['ad_name']; ?>" maxlength="9" />
                </td>
            </tr>
            <tr>
                <td class="border">廣告圖片</td>
                <td class="border td-white">
                <?php if($arr[0]['ad_img'] !== NULL) { ?>
                    <img class="img" src="./images/advertisement/<?php echo $arr[0]['ad_img']; ?>" />
                <?php } ?>
                <input type="file" name="ad_img" />
                </td>
            </tr>
            <tr>
                <td class="border">廣告狀態</td>
                <td class="border">
                    <select name="ad_status">
                        <option value="<?php echo $arr[0]['ad_status']; ?>" selected><?php echo $arr[0]['ad_status']; ?></option>
                        <option value="On">On</option>
                        <option value="Off">Off</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="border">廣告路徑</td>
                <td class="border">
                    <input type="text" name="ad_url" value="<?php echo $arr[0]['ad_url']; ?>" maxlength="100" />
                </td>
            </tr>
            <tr>
                <td class="border">廣告標語</td>
                <td class="border">
                    <input type="text" name="ad_slogan" value="<?php echo $arr[0]['ad_slogan']; ?>" maxlength="15" />
                </td>
            </tr>
            <tr>
                <td class="border">功能</td>
                <td class="border">
                    <a href="./ad_delete.php?deleteId=<?php echo $arr[0]['id']; ?>">刪除</a>
                </td>
            </tr>
        <?php
        } else {
        ?>
            <tr>
                <td class="border" colspan="12">沒有資料</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
            <td class="border" colspan="12"><input type="submit" name="smb" value="修改"></td>
            </tr>
        </tfoot>
    </table>
    <input type="hidden" name="editId" value="<?php echo (int)$_GET['editId']; ?>">
</form>
</section>
  </main>
  <!-- main end -->
  <?php
  require_once './tpl/footer.php';
  ?>
</body>
</html>