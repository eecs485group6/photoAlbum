<?php
  include("lib.php");
  if (isset($_GET['albumid'])) $albumid = $_GET['albumid'];
  if (isset($_GET['keyword'])) $keyword = $_GET['keyword'];
  db_connect();
  $query="SELECT Contain.caption, Contain.url, sequencenum, date
          FROM Contain, Photo
          WHERE Contain.albumid = $albumid 
            AND Contain.url = Photo.url
            AND Contain.caption LIKE '%$keyword%'
          ORDER BY sequencenum";
  $result = mysql_query($query);
  $num = mysql_num_rows($result);
  echo "<table width='100%' height='100%' align='center' valign='center'>";
    for ($i = 0; $i < $num; ++$i) {
      $url = mysql_result($result, $i, "url");
      $caption = mysql_result($result, $i, "caption");
      $date = mysql_result($result, $i, "date");
      if ($i % 2 == 0)
        echo "<tr align='center'>";
      echo "
          <td height = '400px' algin='center'>
            <a href='javascript:void(0)' onclick='load(".$num.", ".$i.", ".$albumid.")'> 
              <img class='img-rounded center' src='$url' alt='$caption' title='$caption'>
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
