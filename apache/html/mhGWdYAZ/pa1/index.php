<!DOCTYPE html>
<html>
<head>
<?php include ("lib.php"); ?>
<?php page_header("Index"); ?>
<?php include ("default/head.php"); ?>
</head>
<body>
<?php include ("default/top.php"); ?>
<!-- add breadcrumbs-->
<div class="row">
<div class="span10">
<ul class="breadcrumb">
  <li class="active">
    <a href="#">Home</a> <span class="divider"></span>
  </li>
</ul>
</div>
</div>

<?php
  $prefix="viewalbumlist.php?username=";
  echo "<h4><p class='text-info'>
    Hi Guest, here is a list of all the users. Click to Browser into their album lists.</p></h4>";
  echo "<table class='table table-hover'>";
  echo "<thead><tr>";
  echo "<th>Username</th>";
  echo "<th>First Name</th><th>Last Name</th></tr></thead>";
  echo "<tbody>";
  $con= db_connect();
  $query="SELECT * FROM User";
  $result=mysql_query($query);
  $num=mysql_num_rows($result);

  for ($i = 0; $i < $num; ++$i) {
    $username = mysql_result($result, $i, "username");
    $firstname = mysql_result($result, $i, "firstname");
    $lastname = mysql_result($result, $i, "lastname");
    echo "<tr><th><a href='".$prefix.$username."'>$username</a></th>";
    echo "<th>$firstname</th><th>$lastname</th></tr>";
  }
  echo "</tbody></table>";

  db_close();
?>
<?php page_footer(); ?>
</body>
</html>
