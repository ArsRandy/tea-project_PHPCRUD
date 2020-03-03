<?php
require_once './check.php';
require_once './sup/Img.php';

$imgTemp = new Img('./temp/');
if ($_FILES['upload']['error'] === 0) {
  $imgName = $imgTemp->storeUpload($_FILES['upload']['name'], $_FILES['upload']['tmp_name']);
  $res = [
    "success" => true,
    "url" => "./temp/" . $imgName
  ];
  echo json_encode($res);
} else {
  $res = [
    "success" => false,
    'msg' => '圖片上傳失敗'
  ];
  echo json_encode($res);
}
