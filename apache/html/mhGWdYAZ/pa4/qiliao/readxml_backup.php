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
  function readxml($n, $isurl) 
  {
    $sxml = simplexml_load_file('search.xml');
    $url = $sxml->photo[$n-1];
    if ($isurl == true) 
    {
      return $url['url'];
    }else {
      return $url['caption'];
    }
  }
?>
