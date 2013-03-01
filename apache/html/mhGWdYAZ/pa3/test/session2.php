
<?php
  include('session_start.php');
  my_session_start(5);
?>
<html>
<head>
<title>Using a session variable </title>
</head>
<body>
<?php
  print "Welecome to session number:";
  echo session_id();
?>
<br />
<?php
  //session_register("username");
  $username = "Goody";
  $_SESSION['username'] = $username;
  print "Your Name is:";
  print $_SESSION['username'];
?>

</body>
</html>

