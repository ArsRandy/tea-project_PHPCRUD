<?php
require_once './check.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require_once './tpl/head.php'
  ?>
  <link rel="stylesheet" href="./css/events/admin.css">
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
      <!-- <div id="msg-success" class="msg alert alert-success fade" role="alert"></div>
      <div id="msg-warning" class="msg alert alert-warning fade" role="alert"></div> -->
      <div class="row pt-3">
        <div class="col-10 offset-1">
          <select name="companys" id="companys">
          </select>
          <table class="event-table table table-bordered table-striped table-condensed">
            <thead>
              <tr>
                <th></th>
                <th width="150">banner</th>
                <th>title</th>
                <th width="100">company</th>
                <th width="80">date</th>
                <th>location</th>
                <th>content</th>
              </tr>
            </thead>
            <tbody id="tableBody">
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <div class="modal fade" id="contentModal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php
  require_once './tpl/footer.php';
  ?>
  <!-- custom js -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="./js/events/event_admin.js"></script>
</body>

</html>