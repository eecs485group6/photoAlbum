<?php
include("authentication.php");
?>
<!DOCTYPE html>
<html>
<head>
<?php include ("lib.php"); ?>
<?php page_header("Login"); ?>
<?php include ("default/head.php"); ?>
</head>
<body>
<?php include("default/top.php"); ?>
<?php
if (isset($_GET['success'])) {
  echo "
      <div class='alert alert-success'>
            <button type='button' class='close' data-dismiss='alert'>
                &times;
            </button>
          Your password successfully reset.
      </div>
  ";
}
if (isset($_GET['wrongEmail'])) {
    echo "
      <div class='alert alert-error'>
            <button type='button' class='close' data-dismiss='alert'>
                &times;
            </button>
          The Email you entered does not exist.
      </div>
  ";
}
?>
  <form class="form-horizontal"  id="LoginForm" action="reset.php" method="POST">
  <div class="control-group">
    <label class="control-label" for="inputEmail">Email</label>
    <div class="controls">
      <input type="text" id="email" 
              name="email" placeholder="Email">
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type='submit' class='btn btn-primary'>Reset My Password</button>
    </div>
  </div>
</form>
<!-- javascript begins -->
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- javascript ends -->
<?php page_footer(); ?>
</body>
</html>
