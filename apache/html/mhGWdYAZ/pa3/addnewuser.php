<?php
  include('authentication.php');
  include ("lib.php");
  db_connect();
  if(isset($_GET['username'])) $username = $_GET['username']; 

  $queryUser="SELECT * FROM User WHERE username= '$username'";
  $resultUser = mysql_query($queryUser);
  $arrayUser = mysql_fetch_array($resultUser, MYSQL_ASSOC);
  $_SESSION['username'] = $username;
  $_SESSION['lastname'] = $arrayUser['lastname'];
  $_SESSION['firstname'] = $arrayUser['firstname'];
  $_SESSION['inactivity'] = time();
  $_SESSION['url'] = 'index.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>Thank you for registration</title><meta charset="utf-8">
<meta name="descript" content="EECS485 Group6 Photo Album Website"/>
<meta name="keywords" content="eecs485, web, mysql, php, photo album"/>
<meta HTTP-EQUIV="refresh" CONTENT="5;url=index.php">
<link rel="stylesheet" href="css/style.css"
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<style>
    body {
      height: 1000px;
    }
</style>

</head>
<h4><p class='text-info' align="center">Thank you for the registration.</p></h4>
<h4><p class='text-info' align="center">You have successfully signed up.</p></h4>
<h4><p class='text-info' align="center">Now directing to the login page in 5 seconds...<p></h4>

</html>



