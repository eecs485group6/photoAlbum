<?php
  include('authentication.php');
  include ("lib.php");
  db_connect();
  // direct from login page
  if (isset($inactivity)  && time() - $inactivity <= 300) {
    $queryUser="SELECT * FROM User WHERE username= '$username'";
    $resultUser = mysql_query($queryUser);
    while ($arrayUser = mysql_fetch_array($resultUser, MYSQL_ASSOC)) {
      $_SESSION['lastname'] = $arrayUser['lastname'];
      $_SESSION['firstname'] = $arrayUser['firstname'];
    }
    $_SESSION['inactivity'] = time();
  }
  else {
      $_SESSION['url'] = 'index.php';
      ob_start();
      header("Location:welcome.php");
      ob_end_flush();
  }
?>
<!DOCTYPE html>
<html>
<head>
<?php page_header("Index"); ?>
<?php include ("default/head.php"); ?>
</head>
<body>
<?php include ("default/top_logged.php"); ?>
<!-- add breadcrumbs-->
<div class="row">
<div class="span10">
<ul class="breadcrumb">
  <li class="active">
    <a href="#">Home</a> <span class="divider"></span>
  </li>
  <?php
    if (isset($_SESSION['admin'])){
      // is admin
      $show_manage_the_albums = true;
    } else {
      // not admin
      $show_manage_the_albums = false;
    }
  ?>
</ul>
</div>
</div>

<!--manage the album-->

<div class="row">
<div class="span10">
</div>
</div>

<?php
  if ($show_manage_the_albums)
  echo 
    "<a href='managealbum.php' class='btn btn-primary'>Administrative Page</a>";
?>

<!-- -->


<?php
  $prefix="viewalbumlist.php?username=";
   
  if ($show_manage_the_albums)
    $queryAlbum = "SELECT * FROM Album";
  else  
    $queryAlbum="SELECT * FROM Album 
               WHERE albumid in 
                (SELECT albumid FROM AlbumAccess WHERE username = '$username')
               OR access = 'public'";
  $resultAlbum=mysql_query($queryAlbum);
  $num=mysql_num_rows($resultAlbum);

  $prefix="viewalbum.php?albumid=";
  echo "<table class='table table-hover'>";
  echo "<thead>";
  echo "<tr><th>Owner</th><th>Album</th><th>created</th><th>last updated</th><th>Access</th></tr>";
  echo "</thead><tbody>";
  for ($i = 0; $i < $num; ++$i) {
    $albumid = mysql_result($resultAlbum, $i, "albumid");
    $title = mysql_result($resultAlbum, $i, "title");
    $created = mysql_result($resultAlbum, $i, "created");
    $lastupdated = mysql_result($resultAlbum, $i, "lastupdated");
    $access = mysql_result($resultAlbum, $i, "access");
    $owner = mysql_result($resultAlbum, $i, "username");

    echo "<tr>";
    echo "<th>$owner</th>";
    echo "<th>$title</th>";
    echo "<th>$created</th>";
    echo "<th>$lastupdated</th>";
    // use strcmp if we need know which one is greater.
    // i.e. (strcmp(str1, str2)) 
    // return < 0 if str1 < str2, 0 if equal, >0 if str1 > str2.
    // here dont matter. choose == for simplicity.
    echo "<th><a href='".$prefix.$albumid."'>$access</a></th>";
    echo "</tr>";
  }
  echo "</tbody></table>";

  db_close();
?> 

<?php page_footer(); ?>
<!-- javascript start-->
  <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
  <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
  <script src="js/bootstrap.min.js"></script>
<!-- javascript end-->
</body>
</html>
