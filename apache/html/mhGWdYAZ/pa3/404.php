<?php
  include('authentication.php');
  include("lib.php");
  if (isset($inactivity)  && time() - $inactivity <= 300) {
      $queryUser="SELECT * FROM User WHERE username= '$username'";
      $resultUser = mysql_query($queryUser);
      while ($arrayUser = mysql_fetch_array($resultUser, MYSQL_ASSOC)) {
        $_SESSION['lastname'] = $arrayUser['lastname'];
        $_SESSION['firstname'] = $arrayUser['firstname'];
      }
      $_SESSION['inactivity'] = time();
    }
?>
<!DOCTYPE html>
<html>
<head>
<?php page_header("404"); ?>
<?php include ("default/head.php"); ?>
<style>
  body {
    background: #000000;
  }
</style>
</head>
<body>
<?php
  if (isset($username)  && isset($inactivity) && time() - $inactivity <= 300)
    include ("default/top_logged.php");
  else include("default/top.php");
?> 
<img class='img-round'  width='100%'  src='img/404.png'>
<?php page_footer(); ?>
</body>
</html>
