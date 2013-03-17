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
</style>
</head>
<body>
  <?php
    include ("default/top.php");
    $albumid=$_GET['albumid'];
    db_connect();
    $query="SELECT * FROM Contain " 
        ." WHERE albumid = $albumid ORDER BY sequencenum";
    $result=mysql_query($query);
    $num=mysql_num_rows($result);

    $prefix="viewpicture.php?url=";
    $prefix2="&albumid=";
    echo "<h4><p class ='text-info'>Here is the album. Click on the thumbnail to view the full photo. </p></h4>
      <form action='editalbum.php' method='GET'>
      <input type = 'hidden' name='albumid' value = $albumid>
      <input class='btn btn-success' type='submit' value ='Edit'>
      </form>";
//	<form method=\"post\" action=\"editalbum.php\"><input type=\"hidden\" name=\"albumid\" value=\"$albumid\"><input type=\"submit\" value=\"Back to edit album\"></form>

    echo "<ul class='thumbnails'>";
    for ($i = 0; $i < $num; ++$i) {
        $url = mysql_result($result, $i, "url");
        $caption = mysql_result($result, $i, "caption");
        if ($i % 2 == 0)
          echo "<li class='span5'>";

        echo "
          <a href='".$prefix.$url.$prefix2.$albumid
          ."' class='thumbnail'> 
            <img src='$url' alt=''>
            </a> ";
        if ($i % 2 == 1)
          echo "</li>";
        //echo "<a href='".$prefix.$url.$prefix2.$albumid
        //    ."'><img src='$url'".' width="240" height="240"'."></a>";

        //echo "<br/>";
    }
    echo "</ul>";

    db_close();
  ?>
  <?php page_footer(); ?>
</body>
</html>
