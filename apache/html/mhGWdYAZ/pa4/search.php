<?php
  include("authentication.php");
  include("lib.php");
  db_connect();
  if (isset($inactivity) && time() - $inactivity <= 300) {
    $_SESSION['inactivity'] = time();
    if (empty($_SESSION['lastname']) && empty($_SESSION['firstname'])) {
        $queryUser="SELECT * FROM User WHERE username= '$username'";
        $resultUser = mysql_query($queryUser);
        while ($arrayUser = mysql_fetch_array($resultUser, MYSQL_ASSOC)) {
                $_SESSION['lastname'] = $arrayUser['lastname'];
                      $_SESSION['firstname'] = $arrayUser['firstname'];
                    }
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <?php page_header("SearchPhotos"); ?>
  <?php include ("default/head.php"); ?>
  <style>
    .center {
      height:300px;
      background-color:#b0e0e6;
    }
  </style>
</head>
<body>
<?php
  if (isset($username) && isset($inactivity) && time() - $inactivity <= 300)
    include ("default/top_logged.php");
  else include("default/top.php");
?>
  <form align='center' name="wordquery" class="form-search" action="search.php" method="get">
    <input type="text" class="input-medium search-query" name="q" placeholder="your query words...">
    <button class="btn btn-primary" type="submit">Search Words</button>
  </form>
  <form align='center' name="phrasequery" class="form-search" action="search.php" method="get">
    <input type="text" class="input-medium search-query" name="phr" placeholder="your query phrase...">
    <button class="btn btn-primary" type="submit">Search Phrase</button>
  </form>


<?php
  $prefix = "viewpicture.php?url=";
  $prefix2 = "&albumid=";
  $albumid = 5;
  function compare_score($a, $b) {
    return strcmp($b['score'], $a['score']);
  }
  require "server.php";
  $query = "";
  if (isset($_GET['q'])) 
  {
  $query = $_GET['q'];
  $myResults = queryIndex(2114, "localhost", $query);
  $ct = count($myResults);
  if ($ct > 0) usort($myResults, 'compare_score');
  echo "<br><h4><p class='text-info' align='center'>".$ct." relevant photos are found for query $query:</p></h4>";
  echo "<table width='100%' height='100%' align='center' valign='center'>";

  for ($i = 0; $i < $ct; $i++)
    {
      $num = $myResults[$i]['id'];
      $result = mysql_query("SELECT * FROM Contain WHERE albumid = '5' AND sequencenum='$num'");
      $row = mysql_fetch_array($result);
      $url = $row['url'];
      $caption = $row['caption'];
      $score = $myResults[$i]['score'];
      echo "
        <tr algin='center'>
          <td height='400px' align='center'>
            <a href='".$prefix.$url.$prefix2.$albumid."'>
              <img class='img-rounded center' src='$url' alt='$caption' title='$caption'>
              </a>
              <div> $caption </div>
              <div> Related Score: $score </div>
          </td>
        </tr>  ";
    }
  echo "</table>";
  }
  if (isset($_GET['phr']))
  {
  $queryphrase = $_GET['phr'];
  $myResults = queryIndex2(2114, "localhost", $queryphrase);
  $ct = count($myResults);
  if ($ct > 0) usort($myResults, 'compare_score');
  echo "<br><h4><p class='text-info' align='center'>".$ct." relevant photos are found for query $query:</p></h4>";
  echo "<table width='100%' height='100%' align='center' valign='center'>";

  for ($i = 0; $i < $ct; $i++)
    {
      $num = $myResults[$i]['id'];
      $result = mysql_query("SELECT * FROM Contain WHERE albumid = '5' AND sequencenum='$num'");
      $row = mysql_fetch_array($result);
      $url = $row['url'];
      $caption = $row['caption'];
      $score = $myResults[$i]['score'];
      echo "
        <tr algin='center'>
          <td height='400px' align='center'>
            <a href='".$prefix.$url.$prefix2.$albumid."'>
              <img class='img-rounded center' src='$url' alt='$caption' title='$caption'>
              </a>
              <div> $caption </div>
              <div> Related Score: $score </div>
          </td>
        </tr>  ";
    }
  echo "</table>";
  }

  db_close();
  ?>
  <?php page_footer(); ?>
</body>
</html>
