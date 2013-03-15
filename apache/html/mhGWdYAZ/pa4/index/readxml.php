<?php
  //$num = 5;
  //$res = readxml($num, false);
  //$res2 = readxml($num, true);
  //echo $res;
  //echo "<img src='$res2' width='120' height='120'>";
  //$sxml = simplexml_load_file('search.xml');
  //$res = $sxml->xpath('//photo[@sequencenum='$num']');
  //$url = $sxml->photo[$num-1];
  //echo $url;
  //echo $url[@url]; 
  //echo "<br>";
  //echo $url[@caption];
  include("../lib.php");
  db_connect();
  $albumid = 5;
  $owner = 'sportslover';
  echo "<h3>Extracting data from xml and adding to database. Will go to homepage when complete.</h3>";
  $sxml = simplexml_load_file('search.xml');
  $title =$sxml['title'];
  $permission = $sxml['permission'];
  $lastupd = $sxml['lastupdated'];
  $created = $sxml['created'];
  echo $title.$lastupd."<br>";
  mysql_query("INSERT INTO Album VALUES ($albumid, '$title', '$created', '$lastupd', '$permission', '$owner')") or die("error insert to Album");

  /* older version of loop:w
  for ($n=1; $n <=200; $n++)
  {
    $row = $sxml->photo[$n-1];
    $seq = $row['sequencenum'];
    $url = $row['url'];
    $filename = $row['filename'];
    $caption = $row['caption'];
    $datetaken = $row['datetaken'];
    echo $seq." ".$url." ".$filename." ".$caption." ".$datetaken."<br>";
    mysql_query("INSERT INTO Photo VALUES ('$url', 'JEG', '$datetaken')") or die("error insert to Photo");
    mysql_query("INSERT INTO Contain VALUES ('$albumid', '$url', '$caption', '$seq')") or die("error insert to Contain");
  }*/
  foreach($sxml->photo as $p) 
  {
    $seq = $p['sequencenum'];
    $url = $p['url'];
    $filename = $p['filename'];
    $caption = $p['caption'];
    $datetaken = $p['datetaken'];
    echo "Adding ".$seq." ".$url." ".$filename." ".$caption." ".$datetaken." to database<br>";
    mysql_query("INSERT INTO Photo VALUES ('$url', 'JEG', '$datetaken')") or die("error insert to Photo");
    mysql_query("INSERT INTO Contain VALUES (\"$albumid\", \"$url\", \"$caption\", \"$seq\")") or die("error insert to Contain");

  }
  db_close();
  /*function readxml($n, $isurl) 
  {
    $sxml = simplexml_load_file('search.xml');
    $url = $sxml->photo[$n-1];
    if ($isurl == true) 
    {
      return $url['url'];
    }else {
      return $url['caption'];
    }
  }*/
?>
<!DOCTYPE html>
  <head>
  <!--<META HTTP-EQUIV="refresh" CONTENT="5; URL='../index.php'">-->
  </head>
