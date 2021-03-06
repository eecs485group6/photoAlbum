<?php
include("authentication.php");
include("lib.php");
db_connect();

//get info
if (isset($_GET['albumid']))
$albumid = $_GET['albumid'];

$query = "SELECT * FROM Album WHERE albumid = '$albumid'";
$result = mysql_query($query);
$title = mysql_result($result, 0, 'title');
$owner = mysql_result($result, 0, 'username');
$access = mysql_result($result, 0, 'access');

//check if log in
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
  if ($access == 'private' && $owner != $username && empty($_SESSION['admin'])) {
    $queryAccess = "SELECT * FROM AlbumAccess WHERE username = '$username' AND albumid = '$albumid'";
    $resultAccess = mysql_query($queryAccess);
    $numAccess = mysql_num_rows($resultAccess);

    if (!$numAccess)  {
//    if (!$numAccess) {
      $_SESSION['url'] = $_SERVER['REQUEST_URI'];
        ob_start();
        header("Location:nopermission.php");
        ob_end_flush();
    }
  }   
} 
// no login. 
 else if ($access == 'private' && empty($username)) {
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
  ob_start();
  header("Location:welcome.php");
  ob_end_flush();
}
// fire relogin.
else if ($access == 'private') {
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
  $_SESSION['relogin'] = true;
  ob_start();
  header("Location:welcome.php");
  ob_end_flush();
} 

?>
<!DOCTYPE html>
<html>
<head>
  <?php page_header("Album"); ?>
  <?php include("default/head.php"); ?>
<style>
body 
{
height: 100%;
}
.center 
{
height:300px;
background-color:#b0e0e6;
}
#pf
{
position:fixed;
left:500px;
top:8px;
width:500px;
height:650px;
background-color:blue;
overflow:hidden;
z-index:5000;
}
#swo
{
position:relative;
top:0px;
right:0px;
padding-left:250px;
padding-right:250px;
height: 650px;
background-color:black;
}
.container_img
{
position:relative
top:0px;
margin:0;
border:0;
padding:0;
width: 500px;
height: 100%;
background-color:black;
opacity:1;
text-align:center;
top:0px;
display: inline-block;
}
.image
{
position:relative;
bottom: 25px;
width: 400px;
height: auto;
}
.captiontext {
position:relative;
bottom: 20px;
text-align: center;
}

.shadecover
{
position:fixed; 
right:0px; top:0px; 
width: 100%;
height: 100%; 
background-color:LightGrey; 
opacity:0.7; 
z-index:4999;
}
.cl
{
color:black;
position:relative;
float:right;
right: 25px;
top:0px;
}
</style>
</head>
<body>
<?php
  if (isset($username)  && isset($inactivity) && time() - $inactivity <= 300)
    include ("default/top_logged.php");
  else include("default/top.php");

?>
<!-- breadcrumb begins -->
<div class="row">
  <div class="span10">
  <ul class="breadcrumb">
    <li>
      <a href="index.php">Home</a> <span class="divider" style="color: #0c0c0c">></span>
    </li>
    <li>
<?php
      echo 
        "<a href='editalbumlist.php?username=$owner'>$owner albumlist</a> <span class='divider' style='color: #0c0c0c'>></span>";
?>
    </li>
    <li class="active">
<?php
       echo "<a href='#'>Album $title</a>";
?>
    </li>
  </ul>
  </div>
  </div>
<!-- breadcrumb ends -->
  <?php
      $prefix="viewpicture.php?url=";
      $prefix2="&albumid=";
      echo "<h4><p class ='text-info'>Here is the album  \"$title\" of owner $owner with referring pics by url. 
        Click on the thumbnail to view the full photo. You can also filter the album by providing keywords. </p></h4>
        <form action='editalbum.php' method='GET'>
        <input type = 'hidden' name='albumid' value = $albumid>
        <input class='btn btn-success' type='submit' value ='Edit'>
        </form>";
      echo "
        <div>
          <input type='text' id='keyword' placeholder='search keyword...'>
          <button class='btn btn-primary' onclick='loadSearchResult($albumid)'>
            Search
        </button>
        <input id='current_keyword' type='hidden' value=''>
        </div>";
?>
  <?php
    $query="SELECT Contain.caption, Contain.url, sequencenum, date
    FROM Contain, Photo
    WHERE Contain.albumid = $albumid AND Contain.url = Photo.url
    ORDER BY sequencenum";
    $result=mysql_query($query);
    $num=mysql_num_rows($result);
    
    echo "<div id='photoTable'><table width='100%' height='100%' align='center' valign='center'>";
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
    echo "</table></div>";
    db_close();
  ?>
  <?php page_footer(); ?>
<script type="text/javascript" src="js/draghandler4.js"></script>
<script type="text/javascript">
function load(tot,  index, albumid) {
  document.body.style.overflow = "hidden";
  //var albumid = document.getElementById("albumid").value;
  var shade_div = document.createElement("div");
  shade_div.className = "shadecover";
  shade_div.id = "mask";
  //shade_div.style.z-index="4999";
  var close_a = document.createElement("a");
  close_a.className = "cl";
  close_a.id = "close";
  close_a.innerHTML = "<h4>Close</h4>";
  close_a.setAttribute('href', 'javascript:unload()');
  shade_div.appendChild(close_a);
  
  var frame = document.createElement("div");
  frame.className = "frame";
  frame.id = "pf";
  var dragLayer = document.createElement("div");
  dragLayer.className = "Dragable";
  dragLayer.id = "swo";
  dragLayer.style.width = (tot * 500) + 'px';
  dragLayer.style.left = (0 - 250 - index * 500) + 'px';
  for (var i = 0; i < tot; i++) {
    var container_img = document.createElement("div");
    container_img.className = "container_img";
    var name = "container" + i.toString();
    container_img.id = name;
    dragLayer.appendChild(container_img);
  }
  frame.appendChild(dragLayer);
  //shade_div.style.top = window.pageYoffset + "px";
  frame.style.top = window.pageYoffset + "px";
  document.body.appendChild(shade_div);
  document.body.appendChild(frame);
  loadimage(albumid, index);
  if (index-1>=0) loadimage(albumid, index-1);
  if (index+1<tot) loadimage(albumid, index+1);
  SetupDragDrop(tot, index, albumid);
  
}
function unload() {
  var maskObject=document.getElementById("mask");
  document.body.removeChild(maskObject);
  maskObject=document.getElementById("pf");
  document.body.removeChild(maskObject);
  document.body.style.overflow = "visible";
  
}
function loadXMLData(element,url,albumid,index)
{
var a = albumid.toString();
var i = index.toString();
var keyword = document.getElementById('current_keyword').value;
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      if (url === 'url.php')
        element.src=xmlhttp.responseText;
      else element.innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET",
  url+"?"+"albumid="+a+"&index="+i+"&keyword="+keyword,true);
xmlhttp.send();
}

function loadimage(albumid,i){
  var image = document.createElement("img");
  image.className = "image";
  loadXMLData(image,'url.php',albumid,i);
  var textP = document.createElement("p");
  textP.className = "captiontext";
  textP.style.color="pink";
  loadXMLData(textP,'caption.php',albumid,i);
  var name = "container" + i.toString();
  var ele = document.getElementById(name);
  ele.appendChild(image);
  ele.appendChild(textP);
}
function deleteimage(i){
  var name = "container" + i.toString();
  var ele = document.getElementById(name);
  while (ele.firstChild) {
    ele.removeChild(ele.firstChild);
  }
}

function loadSearchResult(albumid) {
  var keyword = document.getElementById('keyword').value;
  //set current keyword for later use
  document.getElementById('current_keyword').value = keyword;
  var xmlhttp;
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  }
  else {
    // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200){
      document.getElementById("photoTable").innerHTML=xmlhttp.responseText;
    }
  }
  xmlhttp.open("GET","search.php?"+"albumid="+albumid+"&keyword="+keyword,true);
  xmlhttp.send();
}


</script>
</body>
</html>
