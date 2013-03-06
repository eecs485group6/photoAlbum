<?php
  function compare_score($a, $b) {
    return strnatcmp($b['score'], $a['score']);
  }
  require "server.php";
  $query = "";
  if (isset($_GET['q'])) $query = $_GET['q'];
  $myResults = queryIndex(9000, "localhost", $query);
  usort($myResults, 'compare_score');
  var_dump($myResults);
?>
