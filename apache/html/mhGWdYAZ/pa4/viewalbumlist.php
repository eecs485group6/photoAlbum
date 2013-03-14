<!DOCTYPE html>
<html>
<head>
  <?php include ("lib.php"); ?>
  <?php page_header("AlbumList"); ?>
  <?php include ("default/head.php"); ?>
</head>
<body>
  <?php include ("default/top.php"); ?>
  <?php
  if(isset($_GET['username']))
  {
    ob_start();
    header("Location:404.php");
    ob_end_flush();
  }

  ?>
  <div class="row">
  <div class="span10">
  <ul class="breadcrumb">
    <li>
      <a href="index.php">Home</a> <span class="divider" style="color: #0c0c0c">></span>
    </li>
    <li class="active">
<?php
     if (isset($username))
       echo "<a href='#'>$username albumlist</a>";
     else
       echo "<a href='#'>all albums</a>";
?>
    </li>
  </ul>
  </div>
  </div>
  
  <?php

    db_connect();
    if (isset($username))
    {
      $query="SELECT * FROM Album WHERE Album.username = '$username'";
      echo "<h4><p class='text-info'>
      Here is the album list of user ".$username.". 
      Click on the album to preview the album content (only public album is accessable).</p></h4>";
    }
    else {
      $query = "SELECT * FROM Album WHERE access = 'public'";
      echo "<h4><p class='text-info'>
        Here is the list of all public albums. 
        Click on the album to preview the album content (only public album is accessable).
        </p></h4>";
    }
    $result=mysql_query($query);
    $num=mysql_num_rows($result);

    $prefix="viewalbum.php?albumid=";
       echo "<table class='table table-hover'>";
    echo "<thead>";
    echo "<tr><th>Owner</th><th>Albums</th><th>created</th><th>last updated</th><th>Access</th></tr>";
    echo "</thead><tbody>";
    for ($i = 0; $i < $num; ++$i) {
      $albumid = mysql_result($result, $i, "albumid");
      $title = mysql_result($result, $i, "title");
      $created = mysql_result($result, $i, "created");
      $lastupdated = mysql_result($result, $i, "lastupdated");
      $access = mysql_result($result, $i, "access");
      $owner = mysql_result($result, $i, "username");

      echo "<tr>";
      echo "<th>$owner</th>";
      echo "<th>$title</th>";
      echo "<th>$created</th>";
      echo "<th>$lastupdated</th>";
      // use strcmp if we need know which one is greater.
      // i.e. (strcmp(str1, str2)) 
      // return < 0 if str1 < str2, 0 if equal, >0 if str1 > str2.
      // here dont matter. choose == for simplicity.
      if ($access == "public") {
        echo "<th><a href='".$prefix.$albumid."'>$access</a></th>"; 
      }
      else {
        echo "<th>$access</th>";
      }
      echo "</tr>";
    }
    echo "</tbody></table>";

    db_close();
  ?>
  <?php 
  //add by haixin
  if (isset($username))
    //echo " <a href = \"editalbumlist.php?username=$username\">Edit the Albums </a>"; 
    echo " <form action='editalbumlist.php' method='GET'>
    <input type = 'hidden' name='username' value = $username>
    <input class='btn btn-success' type='submit' value='Edit the Albums'></form>";
  else echo "<h4><p class='text-warning'>Please login to edit the album.</p></h4>";
  ?>

  <?php page_footer(); ?>
</body>
</html>
