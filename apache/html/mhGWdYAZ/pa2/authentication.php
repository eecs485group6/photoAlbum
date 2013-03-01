<?php
/* 
 * This is the library for basic authentication on each page. 
 * Provide current url will check if the user is logged in. 
 * More customized authentications may be included in each individual pages.
 */
  session_start();
  if (isset($_SESSION['username']))
    $username = $_SESSION['username'];
  if (isset($_SESSION['password']))
    $password = $_SESSION['password'];
  if (isset($_SESSION['inactivity']))
    $inactivity = $_SESSION['inactivity'];
 
?>

