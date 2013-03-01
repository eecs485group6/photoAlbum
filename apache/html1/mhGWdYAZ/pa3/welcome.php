<?php
include("authentication.php");
?>
<!DOCTYPE html>
<html>
<head>
<?php include ("lib.php"); ?>
<?php page_header("Index"); ?>
<?php include ("default/head.php"); ?>
<style>
.footer 
{
position: relative;
margin-top: -150px; /* negative value of footer height */
height: 150px;
clear:both;
padding-top:20px;
} 
</style>
</head>
<body>
<?php include ("default/top.php"); ?>

<?php
  if (isset($_GET['find'])) {
    $find = $_GET['find'];
    if ($find == 'true')
    {
      $username = $_GET['username'];
      echo "<h4><p class='text-warning'> Sorry. The password is invalid. Please try again.</p></h4>";
    }
    else
    echo "<h4><p class='text-warning'>
    Sorry. The provided combination of username/password does not exist. Please login again or register as new user.</p></h4>";
  }
  else if (isset($_SESSION['relogin']))
    echo "<h4><p class='text-warning'>
    Sorry. You are inactive for long time. Please login again.</p></h4>";
  else
    echo "<h4><p class='text-info'>
    Welcome Guest! This is our lovely photo album website. Please login or register.</p></h4>";
?>
  <div class='well span6 offset4' style='display:block;'>
<?php
  if (isset($username))
    echo "<a href='login.php?username=$username' class='btn btn-large btn-block btn-primary'>Login</a>";
  else 
    echo "<a href='login.php' class='btn btn-large btn-block btn-primary'>Login</a>";
     
?>
    <a href="newuser.php" class='btn btn-large btn-block btn-success'>Register</a>
  </div>
<br />
<?php page_footer(); ?>
</body>
</html>
