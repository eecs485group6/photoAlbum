<?php include ("lib.php");
	
        db_connect();
        $url=$_GET['url'];
        $query="SELECT * FROM Data WHERE url='$url'";
        $result=mysql_query($query);
        if (mysql_num_rows($result) == 0) echo "<br>could not find data";
        $image=mysql_fetch_array($result);
        $imgData = base64_decode( $image['image_data']);
	header("Content-type: " . $image['mime_type']);
    	header("Content-size: " . $image['file_size']);
	//echo $image['file_size'];
	echo $imgData;
	//echo "<img src='data:image/jpeg;base64, $imgData' />";
	//echo "<img src='data:image/jpeg;$imgData' />";
?>

