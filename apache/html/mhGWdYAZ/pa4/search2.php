<!DOCTYPE html>
<html>
<head>
  <?php include ("lib.php"); ?>
  <?php page_header("SearchPhotos"); ?>
  <?php include ("default/head.php"); ?>
</head>
<body>
  <?php include ("default/top.php"); ?>

  <form name="wordquery" action="search.php" method="get">
  <text style='font-family:arial;font-size:18px;color:Crimson;'>Words Query: </text><input type="text" name="q">
  <input type="submit" value="Submit">
  </form>


<?php
  function compare_score($a, $b) {
    return strnatcmp($b['score'], $a['score']);
  }
  require "server.php";
  require "readxml.php";
  $query = "";
  if (isset($_GET['q'])) 
  {
  $query = $_GET['q'];
  $myResults = queryIndex(9000, "localhost", $query);
  usort($myResults, 'compare_score');
  //var_dump($myResults);
  $ct = count($myResults);
  echo "<br><br><h2 style=\"color:grey;\">".$ct." relevant photos are found:</h2>";
  echo "<table border=none>";

  for ($i = 0; $i < $ct; $i++)
    {
      $num = $myResults[$i]['id'];
      $url = readxml($num, true);
      $caption = readxml($num, false);
      $score = $myResults[$i]['score'];
      echo "<tr>";
      echo "<td width='600'><text style='font-size:18px;font-family:verdana;'>".$caption."</text></td>";
      echo "<td width='300'><img src='$url' height='60'></td>";
      //echo "<td width='200'><text style='font-size:18px;font-family:verdana;'>".$score."</tr>";
      echo "</tr>";
    }
  echo "</table>";
  }
?>
</body>
</html>
