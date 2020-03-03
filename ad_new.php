
<!DOCTYPE html>
<html>
<head>
<?php 
require_once './tpl/head.php'
  ?>
    <meta charset="UTF-8">
    <title>我的 PHP 程式</title>
    <style>
    .border {
        border: 1px solid;
    }
    img.img {
            width: 250px;
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
<form name="myForm" method="POST" action="./ad_insert.php" enctype="multipart/form-data">
<table class="border table table-striped table-dark custom-table">
    <thead>
        <tr>
            <th class="border">項目</th>
            <th class="border">內容</th>
            <!-- <th class="border">廣告名稱</th>
            <th class="border">廣告路徑</th>
            <th class="border">廣告圖片</th>
            <th class="border">廣告標語</th>
            <th class="border">廣告狀態</th> -->
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>廣告名稱</td>
            <td class="border">
                <input type="text" name="ad_name" id="ad_name" value="" maxlength="20" />
            </td>
        </tr>
        <tr>
            <td>廣告路徑</td>
            <td class="border">
                <input type="text" name="ad_url" id="ad_url" value="" maxlength="100" />
            </td>
        </tr>
        <tr>
            <td>廣告圖片</td>
            <td class="border">
                <input type="file" name="ad_img" />
            </td>
        </tr>
        <tr>
            <td>廣告標語</td>
            <td class="border">
                <textarea name="ad_slogan"></textarea>
            </td>
        </tr>
        <tr>
            <td>廣告狀態</td>
            <td class="border">
                <select name="ad_status" id="ad_status">
                    <option value="On" selected>On</option>
                    <option value="Off">Off</option>
                </select>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td class="border" colspan="9"><input type="submit" name="smb" value="新增" class="btn btn-danger" ></td>
        </tr>
                </tfoot>
            </table>
        </section>
    </main>
</form>
<?php
  require_once './tpl/footer.php';
  ?>
</body>

</html>