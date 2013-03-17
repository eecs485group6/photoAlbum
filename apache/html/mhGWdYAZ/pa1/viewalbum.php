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

?>


<?php
    db_connect();
    
    $query = "SELECT * FROM Album WHERE albumid = '$albumid'";
    $result = mysql_query($query);
    $title = mysql_result($result, 0, "title"); 
    $username = mysql_result($result, 0, "username");

    $query="SELECT * FROM Contain " 
      ." WHERE albumid = $albumid ORDER BY sequencenum";
    $result=mysql_query($query);
    $num=mysql_num_rows($result);
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
    $prefix="viewpicture.php?url=";
    $prefix2="&albumid=";
    echo "<h4><p class ='text-info'>Here is the album  \"$title\"  with referring pics by url. Click on the thumbnail to view the full photo. </p></h4>
      <h4><p class='text-warning'>You can also Click 
      <a href='viewalbumwithbytes.php?albumid=$albumid'>HERE</a> 
      to see the same album with pictures stored in the database!</p></h4>
      <form action='editalbum.php' method='GET'>
      <input type = 'hidden' name='albumid' value = $albumid>
      <input class='btn btn-success' type='submit' value ='Edit'>
      </form>";
    //	<form method=\"post\" action=\"editalbum.php\"><input type=\"hidden\" name=\"albumid\" value=\"$albumid\"><input type=\"submit\" value=\"Back to edit album\"></form>

    echo "<ul class='thumbnails' id='nwthumbs'>";
    for ($i = 0; $i < $num; ++$i) {
      $url = mysql_result($result, $i, "url");
      $caption = mysql_result($result, $i, "caption");
      if ($i % 2 == 0)
        echo "<li class='span5'>";

      echo "
        <a href='".$prefix.$url.$prefix2.$albumid
        ."' class='thumbnail'> 
        <img src='$url' alt='$caption' title='$caption'>
        </a> ";
      if ($i % 2 == 1)
        echo "</li>";
      /*
       echo "<a href='".$prefix.$url.$prefix2.$albumid
          ."'><img src='$url'".' width="240" height="240"'."></a>";

      echo "<br/>";
       */
    }
    echo "</ul>";

    db_close();
  ?>
  <?php page_footer(); ?>
</body>
</html>
