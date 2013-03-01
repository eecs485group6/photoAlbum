<?php
  include("authentication.php");
  $url = (isset($_SESSION['url']))? $_SESSION['url']:'index.php';
  //if (isset($_SESSION['username']))
  //  $username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
<?php include ("lib.php"); ?>
<?php page_header("Index"); ?>
<?php include ("default/head.php"); ?>
</head>
<body>
<?php include ("default/top_logged.php"); ?>

<?php
     echo "<h4><p class='text-error'> Sorry, but you have no permission to view $url.</p></h4>";
  page_footer(); 
?>
</body>
</html>
