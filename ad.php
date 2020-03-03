<?php
//引入判斷是否登入機制
require_once('./checkSession.php');

//引用資料庫連線
require_once('./ad_db.inc.php');

//SQL 敘述: 取得 advertisement 資料表總筆數
$sqlTotal = "SELECT count(1) FROM `advertisement`";

//取得總筆數
$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0];

//每頁幾筆
$numPerPage = 5;

// 總頁數
$totalPages = ceil($total/$numPerPage); 

//目前第幾頁
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

//若 page 小於 1，則回傳 1
$page = $page < 1 ? 1 : $page;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require_once './tpl/head.php'
  ?>
<style>
    table{
        border-radius: 7px;  
    }
    .border {
        border: 1px solid;
    }
    img.img {
        width: 100px;
    }
    .text-nowrap{
        white-space: nowrap;
    }
    td{
        vertical-align: middle !important;
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
            <h4>廣告列表</h4>
        </div>

                <form name="myForm" enctype= "multipart/form-data" method="POST" action="ad_deleteIds.php">
                    <table class="border table table-striped table-dark">
                        <thead>
                            <tr>
                                <th class="border">選擇</th>
                                <th class="border">廣告名稱</th>
                                <th class="border">廣告路徑</th>
                                <th class="border">廣告圖片</th>
                                <th class="border">廣告標語</th>
                                <th class="border">廣告狀態</th>
                                <th class="border">更新時間</th>
                                <th class="border">功能</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        //SQL 敘述
                        $sql = "SELECT `id`, `ad_name`, `ad_url`, `ad_img`, `ad_slogan`,`ad_status`,`updated_at`
                                FROM `advertisement` 
                                ORDER BY `id` ASC 
                                LIMIT ?, ? ";

                        //設定繫結值
                        $arrParam = [($page - 1) * $numPerPage, $numPerPage];

                        $stmt = $pdo->prepare($sql);
                        $stmt->execute($arrParam);

                        //資料數量大於 0，則列出所有資料
                        if($stmt->rowCount() > 0) {
                            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            for($i = 0; $i < count($arr); $i++) {
                        ?>
                            <tr>
                                <td class="border">
                                    <input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['id']; ?>" />
                                </td>
                                <td class="border"><?php echo $arr[$i]['ad_name']; ?></td>
                                <td class="border"><?php echo $arr[$i]['ad_url']; ?></td>
                                <td class="border">
                                <?php if($arr[$i]['ad_img'] !== NULL) { ?>
                                    <img class="img" src="./images/advertisement/<?php echo $arr[$i]['ad_img']; ?>">
                                <?php } ?>
                                </td>

                                <td class="border"><?php echo nl2br($arr[$i]['ad_slogan']); ?></td>
                                <td class="border"><?php echo $arr[$i]['ad_status']; ?></td>
                                <td class="border"><?php echo $arr[$i]['updated_at']; ?></td>
                                <td class="border">
                                    <a href="./ad_edit.php?editId=<?php echo $arr[$i]['id']; ?>">編輯</a>
                                    <a href="./ad_delete.php?deleteId=<?php echo $arr[$i]['id']; ?>">刪除</a>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td class="border" colspan="9">沒有資料</td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="border" colspan="9">
                                <?php for($i = 1; $i <= $totalPages; $i++){ ?>
                                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                                <?php } ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <input type="submit" name="smb" value="刪除">
                </form>
        </section>
    </main>
        <!-- main end -->
        <?php require_once './tpl/footer.php';?>
</body>
    
</html>