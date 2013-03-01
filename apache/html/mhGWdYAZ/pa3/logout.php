<?php
/*
 * logout.php: Destory the session and redirect to welcome page.
   */
  include('authentication.php');
  session_destroy();
  // remember username for easy login.
  if ($username != NULL) {
    session_start();
    $_SESSION['username'] = $username;
  }
  ob_start();
  header("Location:welcome.php");
  ob_end_flush();
?>
