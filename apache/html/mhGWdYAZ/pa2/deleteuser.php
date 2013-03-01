<?php
  $username = $_GET['username'];
  include_once("lib.php");
  db_connect();
  $result = mysql_query("SELECT * FROM Album WHERE username='$username'");
  while ($row=mysql_fetch_array($result)) {
    $albumid = $row['albumid'];
	deletealbum($albumid);
  }
  updateaccess($username);
  db_close();
?>
  
  
<?php 
  function deletealbum($alid) {
    $allimg = mysql_query("SELECT url FROM Contain WHERE albumid='$alid'");
    while ($row = mysql_fetch_array($allimg)){
      $pic_del = $row['url'];
	  mysql_query("DELETE FROM Contain WHERE albumid = '$alid' AND url = '$pic_del'") or die("Cannot delete image");
	  $inothers = mysql_query("SELECT * FROM Contain WHERE url = '$pic_del'");
      if (mysql_num_rows($inothers) == 0){
        mysql_query("DELETE FROM Photo WHERE url = '$pic_del'");
        //mysql_query("DELETE FROM Data WHERE url = '$pic_del'");
        unlink($pic_del);
      }
    }
	
  }
  function updateaccess($uname) {
    //mysql_query("DELETE FROM AlbumAccess WHERE username='$uname'");
	mysql_query("DELETE FROM User WHERE username='$uname'");
  }
?>
<!DOCTYPE html>
<html>
<head>
<meta HTTP-EQUIV="refresh" CONTENT="5;url=welcome.php">
<link rel="stylesheet" href="css/style.css">
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<style>
    body {
      height: 1000px;
    }
</style>

</head>
<h4><p class='text-info' align="center">Your account is successfully deleted.</p></h4>
<h4><p class='text-info' align="center">Now directing to the home page in 5 seconds...<p></h4>

</html>
