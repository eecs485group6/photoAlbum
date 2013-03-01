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
      $username=$_GET['username'];
    else $username = null;

  ?>
  <div class="row">
  <div class="span10">
  <ul class="breadcrumb">
    <li>
      <a href="index.php">Home</a> <span class="divider" style="color: #0c0c0c">></span>
    </li>
    <li class="active">
<?php
     if ($username != null)
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
    if ($username != null)
      $query="SELECT * FROM Album WHERE Album.username = '$username'";
    else $query = "SELECT * FROM Album";
    $result=mysql_query($query);
    $num=mysql_num_rows($result);

    $prefix="viewalbum.php?albumid=";
    echo "<h4><p class='text-info'>
      Here is the album list for user ".$username.". 
      Click on the album to preview the album content (only public album is accessable).
      </p></h4>";
    echo "<table class='table table-hover'>";
    echo "<thead>";
    echo "<tr><th>Albums</th><th>created</th><th>last updated</th><th>Access</th></tr>";
    echo "</thead><tbody>";
    for ($i = 0; $i < $num; ++$i) {
      $albumid = mysql_result($result, $i, "albumid");
      $title = mysql_result($result, $i, "title");
      $created = mysql_result($result, $i, "created");
      $lastupdated = mysql_result($result, $i, "lastupdated");
      $access = mysql_result($result, $i, "access");

      echo "<tr>";
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
  if ($username != null)
    //echo " <a href = \"editalbumlist.php?username=$username\">Edit the Albums </a>"; 
    echo " <form action='editalbumlist.php' method='GET'>
    <input type = 'hidden' name='username' value = $username>
    <input class='btn btn-success' type='submit' value='Edit the Albums'></form>";
  else echo "<h4><p class='text-warning'>Please login to edit the album.</p></h4>";
  ?>

  <?php page_footer(); ?>
</body>
</html>
