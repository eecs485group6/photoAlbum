<?php
include("authentication.php");
include("lib.php");
db_connect();
if (1) {
  $_SESSION['inactivity'] = time();
  if (empty($_SESSION['lastname']) && empty($_SESSION['firstname'])) {
    $queryUser="SELECT * FROM User WHERE username= '$username'";
    $resultUser = mysql_query($queryUser);
    while ($arrayUser = mysql_fetch_array($resultUser, MYSQL_ASSOC)) {
      $_SESSION['lastname'] = $arrayUser['lastname'];
      $_SESSION['firstname'] = $arrayUser['firstname'];
    }
  }
  if(isset($_GET['albumid'])){
      $albumid=$_GET['albumid'];
    }
  else if(isset($_POST['albumid'])){
      $albumid=$_POST['albumid'];
    }
 //no extension with albumid
  else {
    ob_start();
    header("Location:404.php");
    ob_end_flush();
  }
  $query = "SELECT username FROM Album WHERE albumid = $albumid";
  $result = mysql_query($query);
  while ($array = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $owner = $array['username'];
  }
//  if ($owner != $username) {
 if ($owner != $username && (!$_SESSION['admin'])) {
  
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    ob_start();
    header("Location:nopermission.php");
    ob_end_flush();
  }
}
else {
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
  ob_start();
  header("Location:welcome.php");
  ob_end_flush();
}
?>

<!DOCTYPE html>
<html>
<head>
  <?php page_header("Permission"); ?>
  <?php include ("default/head.php"); ?>

</head>

<body>
  <?php include ("default/top_logged.php"); ?>


  <?php

  db_connect();
//    db_connect();
    $get_name = mysql_query("SELECT title,username FROM Album WHERE albumid='$albumid'");
    $row = mysql_fetch_array($get_name);
    $album_title = $row['title'];
    $username = $row['username'];

?>


<!-- breadcrumb begins -->
<div class="row">
  <div class="span10">
  <ul class="breadcrumb">
    <li>
      <a href="index.php">Home</a> <span class="divider" style="color: #0c0c0c">></span>
    </li>
     <li>
<?php
      echo
      "<a href='editalbumlist.php?username=$username'>$username albumlist</a> <span class='divider' style='color: #0c0c0c'>></span>";
?>
    </li>
    <li>
<?php
       echo
      "<a href='viewalbum.php?albumid=$albumid'>Album $album_title</a> <span class='divider' style='color: #0c0c0c'>></span>";
?>
    </li>
    <li>
<?php
       echo "<a href='editalbum.php?albumid=$albumid'>Edit</a><span class='divider' style='color: #0c0c0c'>></span>";
?>
    </li>
   <li class="active">
     <a href='#'>Grant Permission</a>
   </li>
  </ul>
  </div>
  </div>
<!-- breadcrumb ends -->


<?php
    
    echo "<h3><p class='text-info'>Edit Album AccessList of $album_title</p></h3>";
    $information = NULL;
  ?>


<?php
 
 if (isset($_POST['adduserid'])){
    $adduserlist = $_POST['adduserid'];
    $N = count($adduserlist);
   // echo("You selected $N user(s): ");
    for($i=0; $i < $N; $i++)
    {
     mysql_query("INSERT INTO AlbumAccess VALUES('$albumid','$adduserlist[$i]')");
    }
$information = "Permission added!";
}
?>

<?php

 if (isset($_POST['deleteuserid'])){
    $deleteuserlist = $_POST['deleteuserid'];
    $N = count($deleteuserlist);
   // echo("You selected $N user(s): ");
    for($i=0; $i < $N; $i++)
    {
     mysql_query("DELETE FROM AlbumAccess WHERE username = '$deleteuserlist[$i]' AND albumid = '$albumid'");
    }
$information = "Permission deleted!";
}
?>

<!--add permission-->

<form action ="permission.php" method = "POST">

<?php

echo "<h5><p class='text-info'>Users can be  added:</p></h5>";
echo"<input type=\"hidden\" name=\"albumid\" value='$albumid' /> <br>";

 $resultU = mysql_query("SELECT username FROM User"); 
 $numU=mysql_num_rows($resultU);
 for ($i = 0; $i < $numU; ++$i) {
  $userAdd = mysql_result($resultU, $i, "username");
  $check = mysql_query("SELECT * FROM AlbumAccess WHERE albumid = '$albumid' AND username ='$userAdd'" );
  $checkowner = mysql_query("SELECT * FROM Album WHERE albumid = '$albumid' AND username ='$userAdd'" );
  $numcheck=mysql_num_rows($check);
  $numcheckowner=mysql_num_rows($checkowner);

  if ((!$numcheck)&&(!$numcheckowner)){
   echo" <input type=\"checkbox\" name=\"adduserid[]\" value=\"$userAdd\"/> $userAdd<br>"; 
  }
}
?>
<br>
<input class ="btn btn-primary"  type = "submit" name="checkboxSubmit"  value="Add permission">
</form><br>


<!--delete permission-->

<form action ="permission.php" method = "POST">

<?php

echo "<h5><p class='text-info'>Users can be deleted:</p></h5>";
echo"<input type=\"hidden\" name=\"albumid\" value='$albumid' /> <br>";

$result = mysql_query("SELECT username FROM User");
 $num=mysql_num_rows($result);
 for ($i = 0; $i < $num; ++$i) {
  $user = mysql_result($result, $i, "username");
  $check = mysql_query("SELECT * FROM AlbumAccess WHERE albumid = '$albumid' AND username ='$user'" );
  $checkowner = mysql_query("SELECT * FROM Album WHERE albumid = '$albumid' AND username ='$user'" );
  $numcheck=mysql_num_rows($check);
  $numcheckowner=mysql_num_rows($checkowner);

 if ($numcheck&&(!$numcheckowner)){
  echo " <input type=\"checkbox\" name=\"deleteuserid[]\" value=\"$user\"/> $user<br>";
 }
}
?>
<br>
<input class ="btn btn-danger"  type = "submit" name="checkboxSubmit"  value="Delete permission">
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
    echo "<a href='viewalbum.php?albumid=$albumid'><p><b>view album</b></p></a>";
  ?>
  <br><br>
  <!-- javascript start-->
  <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
  <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- javascript end-->
</body>
  <?php page_footer(); ?>
</html>


