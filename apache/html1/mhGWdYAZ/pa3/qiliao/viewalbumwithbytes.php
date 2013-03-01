<?php
include("authentication.php");
include("lib.php");
db_connect();

//get info
if (isset($_GET['albumid']))
  $albumid = $_GET['albumid'];

$query = "SELECT * FROM Album WHERE albumid = '$albumid'";
$result = mysql_query($query);
$title = mysql_result($result, 0, 'title');
$owner = mysql_result($result, 0, 'username');
$access = mysql_result($result, 0, 'access');

//check if log in
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
  if ($access == 'private' && $owner != $username) {
    $queryAccess = "SELECT * FROM AlbumAccess WHERE username = '$username' AND albumid = '$albumid'";
    $resultAccess = mysql_query($queryAccess);
    $numAccess = mysql_num_rows($resultAccess);
    if (!$numAccess) {
      $_SESSION['url'] = $_SERVER['REQUEST_URI'];
        ob_start();
        header("Location:nopermission.php");
        ob_end_flush();
    }
  }
}
// no login. 
else if ($access == 'private' && empty($username)) {
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
  ob_start();
  header("Location:welcome.php");
  ob_end_flush();
}
// fire relogin.
else if ($access == 'private') {
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
  $_SESSION['relogin'] = true;
  ob_start();
  header("Location:welcome.php");
  ob_end_flush();
}

?>

<!DOCTYPE html>
<html>
<head>
<?php page_header("Album"); ?>
<?php include("default/head.php"); ?>
<style>
  body {
    height: 100%;
  }
  .center {
  height:300px;
  background-color:#b0e0e6;
  }
</style>
</head>
<body>
<?php
  if (isset($username) && isset($inactivity) && time() - $inactivity <= 300)
    include ("default/top_logged.php");
  else include("default/top.php");
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
        "<a href='editalbumlist.php?username=$owner'>$owner albumlist</a> <span class='divider' style='color: #0c0c0c'>></span>";
?>  
    </li>
    <li class="active">
<?php
       echo "<a href='#'>Album $title</a>";
?>
    </li>
  </ul>
  </div>
  </div>
<!-- breadcrumb ends -->








<?php    
    $prefix="viewpicturewithbytes.php?url=";
    $prefix2="&albumid=";
    echo "<h4><p class='text-info'>Here is the album \"$title\" of owner $owner with pics stored in database. Right Click on the pics to check the actual url. Click on the thumbnail to view the full photo.</p></h4>
      <h4><p class='text-warning'>You can also Click
      <a href='viewalbum.php?albumid=$albumid'>HERE</a>
      to see the same album with images displayed by url!</p></h4>
      <form action='editalbum.php' method='GET'>
      <input type='hidden' name='albumid' value=$albumid>
      <input class='btn btn-success' type='submit' value='Edit'>
      </form>";

    $query="SELECT Contain.caption, Contain.url, sequencenum, date 
            FROM Contain, Photo
            WHERE Contain.albumid = $albumid AND Contain.url = Photo.url
            ORDER BY sequencenum";
    $result=mysql_query($query);
    $num=mysql_num_rows($result);

    echo "<div><table width='100%' height='100%' align='center' valign='center'>";
    for ($i = 0; $i < $num; ++$i) {
        $url = mysql_result($result, $i, "url");
        $caption = mysql_result($result, $i, "caption");
        $date = mysql_result($result, $i, "date");
        //echo $caption;
        //echo "<br/>";
	// echo "<a href='".$prefix.$url.$prefix2.$albumid
        //  ."'><img src=\"data:image/jpeg;base64, showimage.php?url=".$url."\" width='240' height='240' /></a>";            
        //echo "<br/>";
    
	/*	
        $query2="SELECT * FROM Data WHERE url='$url'";
        $result2=mysql_query($query2);
        if (mysql_num_rows($result2) == 0) echo "<br>Could not find image data<br>";
        $image=mysql_fetch_array($result2);
        $imgData = $image['image_data'];
	$type = $image['mime_type'];	
	//echo "<img src='data:image/jpeg;base64, $imgData' />";
	 echo "<a href='".$prefix.$url.$prefix2.$albumid
          ."'><img src=\"data:".$type.";base64, $imgData\" width='240' height='240' /></a>";
   */
        if ($i % 2 == 0)
          echo "<tr align='center'>";

        echo "
          <td height = '400px' align='center'>
          <a href='".$prefix.$url.$prefix2.$albumid."'>
          <img class='img-rounded center' src=\"showimage.php?url=".$url."\"  alt='$caption' title='$caption'>
          </a> 
          <div> $caption </div>
          <div> $date </div>
          </td>";
        if ($i % 2 == 1)
          echo "</tr>";

    }
    echo "</table>";
    db_close();
?>
    <?php page_footer(); ?>
</body>
</html>


