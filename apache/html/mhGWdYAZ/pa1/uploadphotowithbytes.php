<!DOCTYPE html>
<html>
<head>
<?php include("lib.php");?>
<?php page_header("Upload new photo");?>
</head>
<body>
<?php
    if(isset($_GET['albumid'])){
		$albumid=$_GET['albumid'];
	}
	if(isset($_POST['albumid'])){
		$albumid=$_POST['albumid'];
	}
    db_connect();
	$get_name = mysql_query("SELECT title FROM Album WHERE albumid='$albumid'");
	$row = mysql_fetch_array($get_name);
	$album_title = $row['title'];
	echo "<h2>Album: $album_title</h2>";
?>

<?php
$allowedExts = array("jpg", "jpeg", "gif", "png", "bmp", "tiff", "tif");
$extension = end(explode(".", $_FILES["file"]["name"]));
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/bmp")
|| ($_FILES["file"]["type"] == "image/tiff"))
&& ($_FILES["file"]["size"] < 20000000)
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
   {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . round($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

    /*if (file_exists("pa1_images/images/" . $_FILES["file"]["name"]))
      {
      echo "<font color='blue'>" . $_FILES["file"]["name"] . " already exists.</font>";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "pa1_images/images/" . $_FILES["file"]["name"]);
      echo "Stored in: " . "pa1_images/images/" . $_FILES["file"]["name"];
	  
	  }*/
	  
	  $info = pathinfo($_FILES["file"]["name"]);
	  $image_name = basename($_FILES["file"]["name"],'.'.$info['extension']);
		
	  $image_format = strtoupper(end(explode('.', $_FILES["file"]["name"])));
	  $image_date = date("Y-m-d");
	  $image_url = "pa1_images/images/" . $_FILES["file"]["name"];
	  $image_type = $_FILES["file"]["type"];
	  $image_size = $_FILES["file"]["size"];
	  $image_data = chunk_split(base64_encode(file_get_contents($_FILES["file"]["tmp_name"])));
	  $in_album = mysql_query("SELECT * FROM Contain WHERE albumid = '$albumid' AND url = '$image_url'");
      $in_database = mysql_query("SELECT * FROM Photo WHERE url = '$image_url'");
      if(mysql_num_rows($in_album) == 0) {
                // photo not exist in the album
                        if(mysql_num_rows($in_database) == 0) {
                        // photo not exist in the database, need to add to both tables

                                mysql_query("INSERT INTO Photo VALUES ('$image_url', '$image_format', '$image_date')") or die("Cannot add image to database");
								mysql_query("INSERT INTO Data VALUES ('$image_url', '$image_type', '$image_size', '$image_data')") or die("Cannot save image data in database");
								echo $image_name . " is added into the photo database" . "<br>";
                       
move_uploaded_file($_FILES["file"]["tmp_name"],
      "pa1_images/images/" . $_FILES["file"]["name"]);
      echo "Stored in: " . "pa1_images/images/" . $_FILES["file"]["name"];
 } else {
							echo $image_name . " is found exist in the photodatabase" . "<br>";
						}
                        // attach photo to the album
                        $max_seq = mysql_query("SELECT MAX(sequencenum) AS max FROM Contain WHERE Contain.albumid = '$albumid'");
                        $row = mysql_fetch_array($max_seq);
                        $seq_num = $row['max'] + 1;
                        mysql_query("INSERT INTO Contain VALUES ('$albumid', '$image_url', '$image_name', '$seq_num')") or die("Cannot attach image to album");
						echo $_FILES["file"]["name"] . " is added into album" . "<br>";
                } else {
                // image already exist in the album...
				echo $_FILES["file"]["name"] . " is already in album" . "<br>";
                }
				$update_date = date("Y-m-d");
				mysql_query("UPDATE Album SET lastupdated='$update_date' WHERE albumid = '$albumid'") or die("Cannot update lastupdated");
	  
    }
  }
else
  {
  echo "<font color='red'>Invalid image type</font>";
  }
?>

<?php 
db_close();

//echo "<a href='viewalbum.php?albumid=$albumid'>view album</a><br>";
echo "<br><a href='editalbum.php?albumid=$albumid'>back to edit album</a><br>";
?>

<br><br>
<?php page_footer(); ?>
</body>
</html>




