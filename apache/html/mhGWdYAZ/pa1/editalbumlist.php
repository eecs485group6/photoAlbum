<!DOCTYPE html>
<html>

<head>
  <?php include("lib.php"); ?>
  <?php page_header("Album List"); ?>
  <?php include ("default/head.php"); ?>
</head>

<body>
  <?php include ("default/top.php"); ?>

  <?php
  $information = NULL;


  //check wether the user has clicked the button

    if(isset($_POST['op'])) {
      $op = $_POST['op'];
      //get the username from POST
      $username = $_POST['username'];

      switch($op){

      case "delete": {
        $albumid = $_POST['albumid'];
        //    echo "underconstruct!the album to be deleted is  $albumid";
        db_connect();
 //        $query="SELECT * FROM Album WHERE albumid = '$albumid' ";
 //        $result = mysql_query($query);
         $albumtitle = mysql_result($result,0,"title");
        
        
        $query="DELETE FROM Album WHERE albumid = '$albumid' ";
        mysql_query($query);
        //$allimg = mysql_query("SELECT url FROM Contain WHERE albumid = '$albumid'");
        $allimg = mysql_query("SELECT url FROM Photo");
        while ($row = mysql_fetch_array($allimg)){
          $pic_del = $row['url'];
          //	mysql_query("DELETE FROM Contain WHERE albumid = '$albumid' AND url = '$pic_del'");
          $res = mysql_query("SELECT * FROM Contain WHERE url = '$pic_del'");
          if (mysql_num_rows($res) == 0){
            mysql_query("DELETE FROM Photo WHERE url = '$pic_del'");
            mysql_query("DELETE FROM Data WHERE url = '$pic_del'");
            mysql_query("DELETE FROM Comments WHERE url = '$pic_del'");

            unlink($pic_del);
          }
        }
        

//        $information = "Album ".$albumtitle." is deleted from the album list";
        db_close();
        break;

      }

      case "add": {
        $title = $_POST['title'];
        $access = $_POST['access'];
        $username = $_POST['username'];
        //    echo "underconstruct!the album to be added is username:$username title:$title access:$access";
        db_connect();
        //first check whether the album exists ot not
        $query = "SELECT * FROM Album WHERE title = '$title'";
        $result = mysql_query($query);
        //the album does not exist
        if (!mysql_num_rows($result)&&($title!="")){
          //get the albumid for the new album
          $query = "SELECT MAX(albumid) AS albumid FROM Album";
          $result = mysql_query($query);
          $row = mysql_fetch_row($result);
          $albumid = $row[0]+1;
          //add to album
          $query="INSERT INTO Album VALUES('$albumid','$title',CURDATE(),CURDATE(),'$access','$username')";
          mysql_query($query);
          //add to albumaccess
          $query="INSERT INTO AlbumAccess VALUES('$albumid','$username')";
          mysql_query($query);
//          $information = "Album ".$title." is added to the album list";

        }
        db_close();   
        break;
      }

  //unset the $op to avoid the resubmisson caused by refresh
  //unset($_POST['op']);
    }

//info start
/*
    if ($information != null)
      echo "
      <div class='alert alert-info'>
      <button type='button' class='close' data-dismiss='alert'>
      &times;
    </button>
      $information
      </div>";
*/ 
//  <!-- Info End-->


    }else{
      $username = $_GET['username'];
    }

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
       echo "<a href='#'>Edit</a>";
?>
    </li>
  </ul>
  </div>
  </div>
<!-- breadcrumb ends -->
<?php

    echo "<h2><p class='text-info'>$username's Album Edit List</p></h2>";
  ?>


<table class='table table-hover'>
 <thead><tr>
    <th>Album</th>
    <th>Access</th>
    <th>       </th>
    <th>         </th>
 </tr></thead>
<tbody>

  <?php
    db_connect();



    $query="SELECT * FROM Album WHERE username = '$username' ";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num ; ++$i) {
      $title =mysql_result($result,$i,"title");
      $access =mysql_result($result,$i,"access");
      $albumid =mysql_result($result,$i,"albumid");

      //get rid of resubmission
      //reference:http://bjw.co.nz/developer/general/75-how-to-prevent-form-resubmission


      echo "<tr><td>$title</td><td>$access</td>
        <td> <form action= \" editalbum.php \" method=  \"GET\">
        <input type=\"hidden\" name=\"albumid\" value=$albumid>
        <input class='btn btn-primary' type='submit' value='Edit'>
        </form></td>

        <td> <form action= \" editalbumlist.php \" method=  \"POST\">
        <input type=\"hidden\" name=\"op\" value=\"delete\">
        <input type=\"hidden\" name=\"albumid\" value=$albumid>
        <input type=\"hidden\" name=\"username\" value=$username>
        <input class='btn btn-danger' type='submit' value=\"Delete\">
        </form></td></tr>";
        $information = $albumid. " is deleted from the album list";
    }

    //echo "<tr><td>New:______________</td><td>_______</td><td>";
  ?>
</tbody></table>

  <?php


echo " <form action= \"editalbumlist.php \" method=  \"POST\">
<input type=\"hidden\"name=\"op\" value=\"add\">
<input type=\"hidden\"name=\"username\" value=$username>
New Album: Title:<input type=\"text\" name=\"title\" placeholder=\"Please input the album title\">
Access:
<select name=\"access\">
<option value=\"public\">Public</option>
<option value=\"private\">Private</option>
</select>
<input class='btn btn-success' type=\"submit\" value=\"Add\"></form>";
//      $information = "New album ". $title. " is deleted from album";
//echo"<td>         </td></tr>";

db_close();

  ?>

 <?php
    echo "<a href='viewalbumlist.php?username=$username'>Back to your albumlist</a>";
  ?>

</body>
  <?php page_footer(); ?>
</html>


