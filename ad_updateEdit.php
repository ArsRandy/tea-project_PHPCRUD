<?php
//引入判斷是否登入機制
require_once('./checkSession.php');

//引用資料庫連線
require_once('./ad_db.inc.php');

//先對其它欄位，進行 SQL 語法字串連接
$sql = "UPDATE `advertisement` 
        SET 
        `ad_name` = ?, 
        `ad_url` = ?,
        `ad_slogan` = ?,
        `ad_status` = ?";

//先對其它欄位進行資料繫結設定
$arrParam = [
    $_POST['ad_name'],
    $_POST['ad_url'],
    $_POST['ad_slogan'],
    $_POST['ad_status']
];

//判斷檔案上傳是否正常，error = 0 為正常
if( $_FILES["ad_img"]["error"] === 0 ) {
    //為上傳檔案命名
    $strDatetime = date("YmdHis");
        
    //找出副檔名
    $extension = pathinfo($_FILES["ad_img"]["name"], PATHINFO_EXTENSION);

    //建立完整名稱
    $ad_img = $strDatetime.".".$extension;

    //若上傳成功，則將上傳檔案從暫存資料夾，移動到指定的資料夾或路徑
    if( move_uploaded_file($_FILES["ad_img"]["tmp_name"], "./images/advertisement/".$ad_img) ) {
        /**
         * 刪除先前的舊檔案: 
         * 一、先查詢出特定 id (editId) 資料欄位中的大頭貼檔案名稱
         * 二、刪除實體檔案
         * 三、更新成新上傳的檔案名稱
         *  */ 

        //先查詢出特定 id (editId) 資料欄位中的大頭貼檔案名稱
        $sqlGetImg = "SELECT `ad_img` FROM `advertisement` WHERE `id` = ? ";
        $stmtGetImg = $pdo->prepare($sqlGetImg);

        //加入繫結陣列
        $arrGetImgParam = [
            (int)$_POST['editId']
        ];

        //執行 SQL 語法
        $stmtGetImg->execute($arrGetImgParam);

        //若有找到 ad_img 的資料
        if($stmtGetImg->rowCount() > 0) {
            //取得指定 id 的廣告資料 (1筆)
            $arrImg = $stmtGetImg->fetchAll(PDO::FETCH_ASSOC);

            //若是 ad_img 裡面不為空值，代表過去有上傳過
            if($arrImg[0]['ad_img'] !== NULL){
                //刪除實體檔案
                @unlink("./images/advertisement/".$arrImg[0]['ad_img']);
            } 
            
            $sql.= ",";

            //ad_img SQL 語句字串
            $sql.= "`ad_img` = ? ";

            //僅對 ad_img 進行資料繫結
            $arrParam[] = $ad_img;
            
        }
    }
}

//SQL 結尾
$sql.= " WHERE `id` = ? ";
$arrParam[] = (int)$_POST['editId'];

$stmt = $pdo->prepare($sql);
$stmt->execute($arrParam);

if( $stmt->rowCount() > 0 ){
    header("Refresh: 3; url=./ad.php");
    echo "更新成功";
} else {
    header("Refresh: 3; url=./ad_edit.php");
    echo "沒有任何更新";
}
?>