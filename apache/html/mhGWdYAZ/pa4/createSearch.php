<?php
  function createSearch() {
  $xml = simplexml_load_file('index/search.xml');
  $file = "search2.sql";
  $str = "INSERT INTO Album 
    (albumid, title, created, lastupdated, access, username) 
    values
    (5, ".$xml[@title]
    .", ". $xml[@created]
    .", ". $xml[@lastupdated]
    .", ".$xml[@permission]
    .", 'sportslover');";
  file_put_contents($file, $str, LOCK_EX);
  //for ($i = 0; $i < 200; $i++) {
  //  $current = $xml->photo[$i];
  //  $str = "INSERT INTO " $url[@caption]
  //}
  }
  createSearch();
?>
