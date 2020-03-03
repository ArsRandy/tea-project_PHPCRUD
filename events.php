<?php
require_once './check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require_once './tpl/head.php'
  ?>
  <!-- custom css -->
  <link rel="stylesheet" href="./css/events/event.css">
  <link rel="stylesheet" href="./css/events/editor.css">
</head>

<body>
  <?php
  require_once './tpl/header.php';
  ?>
  <?php
  require_once './tpl/sideBar.php';
  ?>
  <section id="main-content">
    <section class="wrapper position-relative">
      <div id="msg-success" class="msg alert alert-success fade" role="alert"></div>
      <div id="msg-warning" class="msg alert alert-warning fade" role="alert"></div>
      <div class="row pt-3">
        <div class="col-md-3 text-right">
          <button id="addNew" class="btn btn-sm btn-success bg-tea-light">新增</button>
          <ul class="list text-left mt-2 pl-0">
          </ul>
        </div>
        <div class="col-md-7">
          <form id="eventForm" class="mb-3">
          </form>
          <div id="editor">
          </div>
        </div>
      </div>
    </section>
  </section>
  <?php
  require_once './tpl/footer.php';
  ?>
  <!-- custom js -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="./js/events/main.js"></script>
</body>

</html>