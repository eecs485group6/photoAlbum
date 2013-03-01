<?php
include("lib.php");
if (isset($_GET['albumid'])) $albumid = $_GET['albumid'];
if (isset($_GET['index'])) $index = $_GET['index'];
if (isset($_GET['keyword'])) $keyword = $_GET['keyword'];
db_connect();
$query="SELECT * FROM Contain
    WHERE albumid = $albumid AND caption LIKE '%$keyword%'
    ORDER BY sequencenum LIMIT $index,1";
$result=mysql_query($query);
$row = mysql_fetch_array($result);
echo $row['url'];
?>
