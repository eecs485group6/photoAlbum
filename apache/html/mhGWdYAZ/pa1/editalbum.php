<!DOCTYPE html>
<html>
<head>
  <?php include ("lib.php"); ?>
  <?php page_header("EditAlbum"); ?>
  <?php include ("default/head.php"); ?>
</head>
<body>
  <?php include ("default/top.php"); ?>
  <?php
    if(isset($_GET['albumid'])){
      $albumid=$_GET['albumid'];
    }
    if(isset($_POST['albumid'])){
      $albumid=$_POST['albumid'];
    }
    db_connect();
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
      "<a href='viewalbumlist.php?username=$username'>$username albumlist</a> <span class='divider' style='color: #0c0c0c'>></span>";
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
    
    echo "<h3><p class='text-info'>Edit Album: $album_title</p></h3>";
    $information = NULL;
  ?>

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
  <!-- Info End-->
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
  
  <br>
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







