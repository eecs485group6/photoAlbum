<!DOCTYPE html>
<html>
<head>
<?php include ("lib.php"); ?>
<?php page_header("Album"); ?>
<?php include("default/head.php"); ?>
<style>
    body {
      height: 100%;
    }
    .nwthumbs {
      text-align:center;
    }

    .nwthumbs > li {
      display: inline-block;
      *display:inline; /* ie7 fix */
      float: none; /* this is the part that makes it work */
    }
</style>
</head>
<body>
<?php
    include ("default/top.php");
    $albumid=$_GET['albumid'];
    db_connect();
    $query = "SELECT * FROM Album WHERE albumid = '$albumid'";
    $result = mysql_query($query);
    $title = mysql_result($result, 0, "title");
    $username = mysql_result($result, 0, "username");
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
        "<a href='viewalbumlist.php?username=$username'>$username albumlist</a> <span class='divider' style='color: #0c0c0c'>></span>";
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
    $query="SELECT * FROM Contain "
        ." WHERE albumid = $albumid ORDER BY sequencenum";
    $result=mysql_query($query);
    $num=mysql_num_rows($result);

    $prefix="viewpicturewithbytes.php?url=";
    $prefix2="&albumid=";
    echo "<h4><p class='text-info'>Here is the album \"$title\" with pics stored in database. Right Click on the pics to check the actual url. Click on the thumbnail to view the full photo.</p></h4>
      <h4><p class='text-warning'>You can also Click
      <a href='viewalbum.php?albumid=$albumid'>HERE</a>
      to see the same album with images displayed by url!</p></h4>
      <form action='editalbum.php' method='GET'>
      <input type='hidden' name='albumid' value=$albumid>
      <input class='btn btn-success' type='submit' value='Edit'>
      </form>";

    echo "<ul class='thumbnails' id='nwthumbs'>";
    for ($i = 0; $i < $num; ++$i) {
        $url = mysql_result($result, $i, "url");
        $caption = mysql_result($result, $i, "caption");
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
          echo "<li class='span5'>";
        echo "<a href='".$prefix.$url.$prefix2.$albumid
          ."' class='thumbnail'>
          <img src=\"showimage.php?url=".$url."\"  alt='$caption' title='$caption'>
          </a> ";
        if ($i % 2 == 1)
          echo "</li>";

    }
    echo "</ul>";
    db_close();
?>
    <?php page_footer(); ?>
</body>
</html>


