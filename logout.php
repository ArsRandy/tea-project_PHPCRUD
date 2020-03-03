<?php
session_start();
if(isset($_GET['logout']) && $_GET['logout']== '1'){

header("Refresh:3; url=./login.html");
echo "您已登出";
session_destroy();
}
?>

