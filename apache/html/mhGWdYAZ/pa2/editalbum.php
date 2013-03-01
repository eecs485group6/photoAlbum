<?php
include("authentication.php");
include("lib.php");
db_connect();

if (isset($inactivity) && time() - $inactivity <= 300) {
  $redirect_flag = true;
  $_SESSION['inactivity'] = time();
  //echo $_SERVER['REQUEST_URI'];
  if (empty($_SESSION['lastname']) && empty($_SESSION['firstname'])) {
    $queryUser="SELECT * FROM User WHERE username= '$username'";
    $resultUser = mysql_query($queryUser);
    while ($arrayUser = mysql_fetch_array($resultUser, MYSQL_ASSOC)) {
      $_SESSION['lastname'] = $arrayUser['lastname'];
      $_SESSION['firstname'] = $arrayUser['firstname'];
    }
  }

  if (isset($_GET['albumid'])&& (!$_SESSION['admin'])) {

//  if (isset($_GET['albumid'])) {
    $albumid = $_GET['albumid'];
    $queryOwner = "SELECT username FROM Album WHERE albumid = $albumid";
    $resultOwner = mysql_query($queryOwner);
    while ($arrayOwner = mysql_fetch_array($resultOwner, MYSQL_ASSOC)) {
      $owner = $arrayOwner['username'];  
    }
    if (empty($owner) || $owner == $username) $redirect_flag = false; 
  }
  else $redirect_flag = false;
}
else {
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
  ob_start();
  header("Location:welcome.php");
  ob_end_flush();
}

if ($redirect_flag) {
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
  $_SESSION['no_permission'] = true;
  ob_start();
  header("Location:nopermission.php");
  ob_end_flush();
}

?>


<!DOCTYPE html>
<html>
<head>
  <?php page_header("EditAlbum"); ?>
  <?php include ("default/head.php"); ?>
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
  <?php include ("default/top_logged.php"); ?>


  <?php
    date_default_timezone_set('EST');

    if(isset($_GET['albumid'])){
      $albumid=$_GET['albumid'];
    }
    if(isset($_POST['albumid'])){
      $albumid=$_POST['albumid'];

  //update the album name haixin
    if(isset($_POST['newtitle'])){
      $newtitle=trim($_POST['newtitle']);
      if ($newtitle !=''){
      mysql_query("UPDATE Album SET title='$newtitle' WHERE albumid = '$albumid'") or die("Cannot update album name");
      $information = "Album name is updated to " . $newtitle;
      $update_date = date("Y-m-d");
      mysql_query("UPDATE Album SET lastupdated='$update_date' WHERE albumid = '$albumid'") or die("Cannot update lastupdated");
      }
     }
    }
//    db_connect();
    $get_name = mysql_query("SELECT title,username FROM Album WHERE albumid='$albumid'");
    $row = mysql_fetch_array($get_name);
    $album_title = $row['title'];
    $username = $row['username'];
    echo "<h3><p class='text-info'>Edit Album: $album_title</p></h3>";
    $information = NULL;
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
      "<a href='editalbumlist.php?username=$username'>$username albums</a> <span class='divider' style='color: #0c0c0c'>></span>";
?>
    </li>
    <li>
<?php
       echo
      "<a href='viewalbum.php?albumid=$albumid'>Album $album_title</a> <span class='divider' style='color: #0c0c0c'>></span>";
?>
    </li>
    <li class="active">
<?php
       echo "<a href='#'>Edit</a>";
?>
    </li>
  </ul>
  </div>
  </div>
<!-- breadcrumb ends -->



  <?php
    if(isset($_POST['access'])){
      //db_connect();
      //$albumid =$_POST['albumid'];
      $new_access=$_POST['access'];
      mysql_query("UPDATE Album SET access='$new_access' WHERE albumid = '$albumid'") or die("Cannot update access");
      $information = "Access is updated to " . $new_access;
      $update_date = date("Y-m-d");
      mysql_query("UPDATE Album SET lastupdated='$update_date' WHERE albumid = '$albumid'") or die("Cannot update lastupdated");
    }
  ?>



  <?php
    if(isset($_POST['pic_to_delete'])){
      //db_connect();
      //$albumid =$_POST['albumid'];
      $pic_del=$_POST['pic_to_delete'];
      mysql_query("DELETE FROM Contain WHERE albumid = '$albumid' AND url = '$pic_del'") or die("Cannot delete image");
      $res = mysql_query("SELECT * FROM Contain WHERE url = '$pic_del'");
      if (mysql_num_rows($res) == 0){
        mysql_query("DELETE FROM Photo WHERE url = '$pic_del'");
        mysql_query("DELETE FROM Data WHERE url = '$pic_del'");
        unlink($pic_del);
      }
      $update_date = date("Y-m-d");
      mysql_query("UPDATE Album SET lastupdated='$update_date' WHERE albumid = '$albumid'") or die("Cannot update lastupdated");
      $information = $pic_del. " is deleted from album";
    }
  ?>
  <!-- Info Begin -->
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

 <form action="editalbum.php" method="post">
 New album name:
 <?php   
    echo "<input type=\"hidden\" name=\"albumid\" value=\"$albumid\">";
  ?>
  <input type= "text" name="newtitle" placeholder = "Type in the name">

  <input class ="btn btn-primary" type="submit" name="titleSubmit" value="Change">
  </form>

<?php
$result = mysql_query("SELECT access FROM Album WHERE albumid='$albumid'");
$rowget = mysql_fetch_array($result);
$accessget = $rowget['access'];

if ($accessget == 'private') { 
//<!--link to permission.php-->
 
echo "
<form action=\"permission.php\" method=\"get\">
 
  <input type=\"hidden\" name=\"albumid\" value=\"$albumid\">
 
  <input class =\"btn btn-primary\" type=\"submit\" name=\"permitSubmit\" value=\"Change the album Permission\">
  </form>";
}
?>



<form action="editalbum.php" method="post">
  <?php
    echo "<input type=\"hidden\" name=\"albumid\" value=\"$albumid\">";
  ?>
  Change album access: 
  <input type="radio" name="access" value="public">Public
  <input type="radio" name="access" value="private">Private
  <input class ="btn btn-primary" type="submit" name="accessSubmit" value="Change">
  </form>
  <!--
  <form action="uploadphoto.php" method="post" enctype="multipart/form-data">
  <?php
    /*
      echo "<input type=\"hidden\" name=\"albumid\" value=\"$albumid\">";
     */
  ?>
  Upload new picture: 
  <input  type="file" name="file" id="file">
  <input class="btn btn-success" type="submit" name="addSubmit" value="Add picture">
  </form>
  -->
  <form action="uploadphotowithbytes.php" method="post" enctype="multipart/form-data">
  <?php
    echo "<input type=\"hidden\" name=\"albumid\" value=\"$albumid\">";
  ?>
  <div class="fileupload fileupload-new" data-provides="fileupload">
    <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>
    <div>
      <span class="btn btn-file">
        <span class="fileupload-new">Select image</span>
        <span class="fileupload-exists">Change</span>
      <input type="file" name="file" id="file" /></span>
      <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
    </div>
  </div>
  <input class="btn btn-success" type="submit" name="addSubmit" value="Add picture">
  </form>
  <p><font color='LimeGreen'>Notice: the limit on the image size is 700K.</font></p>


  <form action="editalbum.php" method="post">
  <?php
    echo "<input type=\"hidden\" name=\"albumid\" value=\"$albumid\">";
  ?>
  Delete picture from album: 
  <select name="pic_to_delete">
    <option value="NULL"> Choose a picture </option>
    <?php
      $result=mysql_query("SELECT * FROM Contain WHERE Contain.albumid = '$albumid'");
      while ($row = mysql_fetch_array($result)) {
    ?>
    <option value="<?php echo $row['url'];?>"> <?php echo $row['caption'];?> </option>

    <?php
      }
    ?>
  </select>
  <input class="btn btn-danger" type="submit" name="delSubmit" value="Delete picture">
  </form>

<?php
    $prefix="viewpicture.php?url=";
    $prefix2="&albumid=";

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
      if ($i % 2 == 0)
        echo "<tr align='center'>";
      echo "
          <td height = '400px' algin='center'>
            <a href='".$prefix.$url.$prefix2.$albumid."'> 
              <img class='img-rounded center' src='$url' alt='$caption' title='$caption'>
            </a> 
            <div> $caption </div>
            <div> $date </div>
            </td>";
      if ($i % 2 == 1)
        echo "</tr>";
      /*
       echo "<a href='".$prefix.$url.$prefix2.$albumid
          ."'><img src='$url'".' width="240" height="240"'."></a>";

      echo "<br/>";
       */
    }
    echo "</table></div>";
  ?>

  

  <?php 
    db_close();
  ?>
 
<br>

  <?php
    echo "<a href='viewalbum.php?albumid=$albumid'><h4><b>view album</b></h4></a>";
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







