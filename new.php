<?php
require_once './check.php';
require_once('./pdo.php'); //引用資料庫連線

//建立種類列表
function buildTree($pdo, $parentId = 0){
    $sql = "SELECT `title`,`tag`,`classIfy`,`price`,`unit`,`sTime`,`idVendor`,`feaTure`,`img`
            FROM `commodity` 
            WHERE `img` = ?";
    $stmt = $pdo->prepare($sql);
    $arrParam = [$parentId];
    // $stmt->execute();
    $stmt->execute($arrParam);
    if($stmt->rowCount() > 0) {
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < count($arr); $i++) {
            echo "<option value='".$arr[$i]['title']."'>";
            echo $arr[$i]['tag'];
            echo "</option>";
            buildTree($pdo, $arr[$i]['title']); 
        }
    }
}
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
            <?php require_once('./templates/title.php'); ?>
            <hr />
            <div class="table-title">
                <h4>新增商品</h4>
            </div>
            <form name="myForm" enctype="multipart/form-data" method="POST" action="add.php">
                <table class="border table table-striped table-dark custom-table">
                    <thead>
                        <tr>
                            <th class="border">項目</th>
                            <th class="border">內容</th>
                            <!-- <th class="border">名稱</th>
                            <th class="border">茶種</th>
                            <th class="border">分類</th>
                            <th class="border">價錢</th>
                            <th class="border">單位</th>
                            <th class="border">保存期限</th>
                            <th class="border">廠商ID</th>
                            <th class="border">商品內容</th>
                            <th class="border">圖片</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>名稱</td>
                            <td class="border">
                                <input type="text" name="title" value="" maxlength="20" />
                            </td>
                        </tr>
                        <tr>
                            <td>茶種</td>
                            <td class="border">
                                <select name="tag">
                                    <option value="紅茶" selected>紅茶</option>
                                    <option value="綠茶">綠茶</option>
                                    <option value="烏龍茶">烏龍茶</option>

                                </select>
                            </td class="border">
                        </tr>
                        <tr>
                            <td>分類</td>
                            <td class="border">
                                <input type="text" name="classIfy" value="" maxlength="20" />
                            </td>
                        </tr>
                        <tr>
                            <td>價錢</td>
                            <td class="border">
                                <input type="number" name="price" value="" maxlength="20" />
                            </td>
                        </tr>
                        <tr>
                            <td>單位</td>
                            <td class="border">
                                <input type="text" name="unit" value="" maxlength="20" />
                                <!-- <select name="tag">
                                    <option value="包" selected>包</option>
                                    <option value="克">克</option>
                                    <option value="盒">盒</option>
                                </select> -->
                            </td>
                        </tr>
                        <tr>
                            <td>保存期限</td>
                            <td class="border">
                                <input type="text" name="sTime" value="" maxlength="20" />
                            </td>
                        </tr>
                        <tr>
                            <td>廠商ID</td>
                            <td class="border">
                                <input type="text" name="idVendor" value="" maxlength="20" />
                            </td>
                        </tr>
                        <tr>
                            <td>商品內容</td>
                            <td class="border">
                                <textarea name="feaTure" id="" cols="50" rows="10"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <!-- 圖片-->
                            </td>
                            <td class="border td-white">
                                <label for="update-img" class="update-img">
                                    <div>上傳圖片</div>
                                    <input id="update-img" type="file" name="img" value="" />
                                </label>
                            </td>
                        </tr>

                        <!-- <tr>
                            <td class="border">
                                <input type="text" name="title" value="" maxlength="20" />
                            </td>

                            <td class="border">
                                <select name="tag">
                                    <option value="紅茶" selected>紅茶</option>
                                    <option value="綠茶">綠茶</option>
                                    <option value="烏龍茶">烏龍茶</option>

                                </select>
                            </td>

                            <td class="border">
                                <input type="text" name="classIfy" value="" maxlength="20" />
                            </td>

                            <td class="border">
                                <input type="number" name="price" value="" maxlength="20" />
                            </td>

                            <td class="border">
                                <input type="number" name="unit" value="" maxlength="20" />
                                <select name="tag">
                                    <option value="包" selected>包</option>
                                    <option value="克">克</option>
                                    <option value="盒">盒</option>
                                </select>
                            </td>

                            <td class="border">
                                <input type="number" name="sTime" value="" maxlength="20" />
                            </td>

                            <td class="border">
                                <input type="text" name="idVendor" value="" maxlength="20" />
                            </td>

                            <td class="border">
                                <textarea name="feaTure" id="" cols="50" rows="10"></textarea>
                            </td>

                            <td class="border">
                                <input type="file" name="img" value="" />
                            </td>

                        </tr> -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="border" colspan="9"><input type="submit" name="smb" value="新增"
                                    class="btn btn-danger"></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </section>
    </main>
</body>

</html>