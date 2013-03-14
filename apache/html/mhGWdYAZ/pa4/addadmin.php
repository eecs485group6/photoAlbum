<?php

include("authentication.php");
include("lib.php");

db_connect();
if (isset($inactivity) && time() - $inactivity <= 300) {
  $_SESSION['inactivity'] = time();
  if (empty($_SESSION['lastname']) && empty($_SESSION['firstname'])) {
    $queryUser="SELECT * FROM User WHERE username= '$username'";
    $resultUser = mysql_query($queryUser);
    while ($arrayUser = mysql_fetch_array($resultUser, MYSQL_ASSOC)) {
      $_SESSION['lastname'] = $arrayUser['lastname'];
      $_SESSION['firstname'] = $arrayUser['firstname'];
    }
  }
//  if ($owner != $username) {
 if ((!isset($_SESSION['admin']))) {
  
    ob_start();
    $_SESSION ['url']="Root User Management Page";
    header("Location:nopermission.php");
    ob_end_flush();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <?php page_header("AddAdmin"); ?>

  <?php include ("default/head.php"); ?>


</head>

<body>

  <?php include ("default/top_logged.php"); ?>

  <?php

   echo "<h3><p class='text-info'>Root User Management Page</p></h3>";
   $information = NULL;
  ?>


<?php
 
 if (isset($_POST['adduserid'])){
    $adduserlist = $_POST['adduserid'];
    $N = count($adduserlist);
   // echo("You selected $N user(s): ");
    for($i=0; $i < $N; $i++)
    {
     mysql_query("INSERT INTO Admin VALUES('$adduserlist[$i]')");
    }
$information = "Administrator added!";
}
?>

<?php

 if (isset($_POST['deleteuserid'])){
    $deleteuserlist = $_POST['deleteuserid'];
    $N = count($deleteuserlist);
   // echo("You selected $N user(s): ");
    for($i=0; $i < $N; $i++)
    {
     mysql_query("DELETE FROM Admin WHERE username = '$deleteuserlist[$i]'");
    }
$information = "Administrator deleted!";
}
?>

<!--add permission-->

<form action ="addadmin.php" method = "POST">

<?php


echo "<h5><p class='text-info'>Users can be added:</p></h5>";
//echo"<input type=\"hidden\" name=\"albumid\" value='$albumid' /> <br>";

 $resultU = mysql_query("SELECT * FROM User"); 
 $numU=mysql_num_rows($resultU);

 for ($i = 0; $i < $numU; ++$i) {
  $userAdd = mysql_result($resultU, $i, "username");
  $check = mysql_query("SELECT * FROM Admin WHERE username ='$userAdd'" );
//  $checkowner = mysql_query("SELECT * FROM Admin WHERE username ='$username'" );
  $numcheck=mysql_num_rows($check);
//  $numcheckowner=mysql_num_rows($checkowner);

  if ((!$numcheck)){
   echo" <input type=\"checkbox\" name=\"adduserid[]\" value=\"$userAdd\"/> $userAdd<br>"; 
  }
}
?>
<br>
<input class ="btn btn-primary"  type = "submit" name="checkboxSubmit"  value="Add Administrator">
</form><br>


<!--delete permission-->

<form action ="addadmin.php" method = "POST">

<?php

echo "<h5><p class='text-info'>Users can be deleted:</p></h5>";
//echo"<input type=\"hidden\" name=\"albumid\" value='$albumid' /> <br>";

 $result = mysql_query("SELECT * FROM User");
 $num=mysql_num_rows($result);
 for ($i = 0; $i < $num; ++$i) {
  $user = mysql_result($result, $i, "username");
  $check = mysql_query("SELECT * FROM Admin WHERE username ='$user'" );
//  $checkowner = mysql_query("SELECT * FROM Admin WHERE username ='$username'" );
  $numcheck=mysql_num_rows($check);
//  $numcheckowner=mysql_num_rows($checkowner);

 if ($numcheck){
//  echo "$username";
   if ($user != $username){
  echo " <input type=\"checkbox\" name=\"deleteuserid[]\" value=\"$user\"/> $user<br>";
 }
}
}
?>
<br>
<input class ="btn btn-danger"  type = "submit" name="checkboxSubmit"  value="Delete Aministrator">
</form>




<?php

    if ($information != null)
      echo "
      <div class='alert alert-info'>
      <button type='button' class='close' data-dismiss='alert'>
      &times;
    </button>
      $information
      </div>";
  ?>

   <!--change album name haixin-->



  <?php 
    db_close();
  ?>
  <br>

  <?php
//    echo "<a href='viewalbum.php?albumid=$albumid'><p><b>view album</b></p></a>";
  ?>

 <a href='managealbum.php'><h4><b>Back to Admistrative Page</b></h4></a>

  <br><br>
  <!-- javascript start-->
  <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
  <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- javascript end-->
</body>
  <?php page_footer(); ?>
</html>
