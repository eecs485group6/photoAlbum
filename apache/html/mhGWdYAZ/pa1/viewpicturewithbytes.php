<!DOCTYPE html>
<html>
<head>
<?php include ("lib.php"); ?>
<?php page_header("Picture"); ?>
<?php include("default/head.php"); ?>
<style>
  body {
    height: 100%;
  }   
.center-table
{
  margin: 0 auto;
  float: none;
}   
</style>
</head>
<body>
<?php


  include("default/top.php");
//changed by haixin start
if (isset($_POST['url'])){
   $url=$_POST['url'];  
  }else{
$url=$_GET['url'];
}


if (isset($_POST['albumid'])){
   $albumid=$_POST['albumid'];
  }else{
$albumid=$_GET['albumid'];
}


db_connect();
   
  $query = "SELECT * FROM Album WHERE albumid = '$albumid'";
  $result = mysql_query($query);
  $title = mysql_result($result, 0, "title");
  $username = mysql_result($result, 0, "username");

  $getcap=mysql_query("SELECT * FROM Contain WHERE albumid = '$albumid' AND url = '$url'") or die("Query failed. ". mysql_error());
  $caprow=mysql_fetch_array($getcap, MYSQL_ASSOC);
  $caption = $caprow['caption'];


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
        "<a href='viewalbumlist.php?username=$username'>$username albumlist</a> <span class='divider' style='color: #0c0c0c'>></span>";
?>     
    </li>
    <li>
<?php
    echo
      "<a href='viewalbum.php?albumid=$albumid'>Album $title</a> <span class='divider' style='color: #0c0c0c'>></span>";
?>
    </li>

    <li class="active">
<?php
       echo "<a href='#'>Picture $caption</a>";
?>
    </li>
  </ul>
  </div>
  </div>
<!-- breadcrumb ends -->

<?php
//    $albumid = $_GET['albumid'];

//changed by haixin end

echo "<h4><p class ='text-info'>Hi!  You can change the way the photo is loaded (by url or from database) by Clicking on the switch button under the photo. </p></h4>";



    db_connect();
    echo "<ul class='pager'>";
    echo "<li class='previous'>";
    $query2="SELECT url From Contain 
        WHERE albumid = $albumid AND sequencenum IN 
            (SELECT MAX(sequencenum) FROM Contain, 
                (SELECT sequencenum AS s FROM Contain 
                 WHERE url = '$url' AND albumid = $albumid) C 
             WHERE albumid = $albumid AND sequencenum < s)";
    $result2=mysql_query($query2);
    if ($result2) {
        while ($array2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
          if ($array2['url'] != NULL) {
                echo "<a class='btn' href='viewpicturewithbytes.php?url=".$array2['url']
                  ."&albumid=".$albumid."'><i class='icon-arrow-left'></i></a>";
               /*
                echo "<form action = \" viewpicture.php \" method = \"GET\">
                    <input type=\"hidden\" name=\"url\" value=".$array2['url']
                    .">
                    <input type=\"hidden\" name=\"albumid\" value=$albumid>
                    <input class='btn' type='submit'><i class='icon-arrow-left'></i></form>";
                <input type=\"submit\" value=\"Previous\"></form>";
                */
            }
        }
    }
    echo "</li>";
    echo "<li class='next'>";
    //Next Button
    $query3="SELECT url FROM Contain 
        WHERE albumid=$albumid AND sequencenum IN
        (SELECT MIN(sequencenum) FROM Contain, 
            (SELECT sequencenum AS s FROM Contain 
             WHERE url = '$url' AND albumid = $albumid) C 
        WHERE albumid = $albumid AND sequencenum > s)";
    $result3=mysql_query($query3);
    if ($result3) {
        while ($array3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
          if ($array3['url'] != NULL) {
                echo "<a class='btn' href='viewpicturewithbytes.php?url=".$array3['url']
                  ."&albumid=".$albumid."'><i class='icon-arrow-right'></i></a>";
                /*
                echo "<form action = \" viewpicture.php \" method = \"GET\"
                    style = 'float:right;'>
                    <input type=\"hidden\" name=\"url\" value=".$array3['url']
                    .">
                    <input type=\"hidden\" name=\"albumid\" value=$albumid>
                    <input type=\"submit\" value=\"NEXT\"></form>";
                 */
            }
        }
    }
    echo "</li></ul>";
?>

	<form action='viewalbumwithbytes.php' method='GET'>
        <input type = 'hidden' name='albumid' value = <?php echo $albumid; ?>>
        <input class='btn btn-success' type='submit' value ='Back to album'>
        </form>

<!-- Info text
<h3><p class="text-info"> 
Here is the picture. You may send it to your email by filling the email box and click on Email Picture button. 
</p></h3>
-->
<div style="text-align:center">
<?php
    // The Image
    $query="SELECT * FROM Photo WHERE url = '$url' ";
    $result=mysql_query($query) or die("Query failed. ". mysql_error());
    $getcap=mysql_query("SELECT * FROM Contain WHERE albumid = '$albumid' AND url = '$url'") or die("Query failed. ". mysql_error());
    $caprow=mysql_fetch_array($getcap, MYSQL_ASSOC);
    $caption = $caprow['caption'];

    while ($array = mysql_fetch_array($result, MYSQL_ASSOC)) {
      echo "<div><img src=\"showimage.php?url=".$url."\" class='img-rounded'></div>";
      echo "caption:".$caption." date:".$array['date']." format:".$array['format'];
    }
    $queryTest="SELECT sequencenum FROM Contain 
        WHERE url = '$url' AND albumid = $albumid";
    $resultTest=mysql_query($queryTest) or die("Query failed. ". mysql_error());

    while ($arrayTest = mysql_fetch_array($resultTest, MYSQL_ASSOC)) {
        echo "<br>Sequencenum:".$arrayTest['sequencenum'];
    }
 
    db_close();
?>
<?php
    echo "<a class='btn' href='viewpicture.php?url=$url&albumid=$albumid'>
          <i class='icon-globe'></i></a>";
?>

<!-- button to trigger modal -->
<a href="#myModal" role="button" class="btn" data-toggle="modal"><i class="icon-envelope"></i></a>
</div>
<!-- Modal-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h4 id="myModalLabel">Mail the picture</h4>
  </div>
  <div class="modal-body">
    <p>Your Email:
    <input id = "email" type="text" name="email"
    placeholder="Your email address..."><br>
    <?php
      echo "<input id = 'url' type='hidden' name='url' value='$url'>";
    ?>
    </p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary" type="button" id="sendEmailButton">Send Email</button>
  </div>
</div>

<div style="text-align:center">

<!--change made by haixin start-->
<!--change made by haixin end-->
<?php
//Add by haixin start

if(isset($_POST['name'])&& isset($_POST['comment'])&&($_POST['name']!="")&&($_POST['comment']!="")) {
  $name = trim($_POST['name']);
  $comment = trim($_POST['comment']);
  $url = $_POST['url'];
//    echo "underconstruct!the album to be added is username:$username title:$title access:$access";
    db_connect();
//first check whether the album exists ot not
    $query = "SELECT * FROM Comments WHERE url = '$url' AND commentuser = '$name' AND comments = '$comment'";
    $result = mysql_query($query);
//the comment record  does not exist
//    if (!mysql_num_rows($result)){
//get the commentid for the new comment
    $query = "SELECT MAX(commentid) AS commentid FROM Comments";
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    $commentid = $row[0]+1;
//add to album
    $query="INSERT INTO Comments VALUES('$commentid','$name','$url','$comment')";
    mysql_query($query);
//    }
    db_close();   

 }else{

     if(isset($_POST['url'])){
      $url = $_POST['url'];
     }else{
      $url = $_GET['url'];
      }
}

$filename = basename($url).PHP_EOL;

?>

<?php
echo " <form action= \"viewpicturewithbytes.php \" method=  \"POST\">
       Name:<br><input type=\"text\" name=\"name\" placeholder=\"Please enter your name\"> <br>
       Comment:<br>
       <textarea rows=\"4\" cols=\"50\" name=\"comment\">
       </textarea> <br>
       <input type=\"hidden\"name=\"url\" value=$url>
       <input type=\"hidden\"name=\"albumid\" value=$albumid>
       <input class=\"btn btn-primary\" type=\"submit\" value=\"Send Comment\"></form>";
//echo"<td>         </td></tr>";
?>



<?php
db_connect();

$query="SELECT * FROM Comments WHERE url = '$url'";
$result = mysql_query($query);

$num = mysql_numrows($result);

if($num > 0){

echo "<h3>Comments on $filename </h3>";

echo "<div class=\"span12 center-table\">
 <table class=\"table table-hover\">
 <thead><tr>
    <th>Name</th>
    <th>Comment</th>
 </tr></thead>
 <tbody>";


for ($i = 0; $i < $num ; ++$i) {
     $name =mysql_result($result,$i,"commentuser");
     $comment =mysql_result($result,$i,"comments");

//get rid of resubmission
//reference:http://bjw.co.nz/developer/general/75-how-to-prevent-form-resubmission

     echo "<tr><td>$name</td><td>$comment</td></tr>";
 }

echo "
</tbody></table>
</div>
<br>
<p><h4>How do you like the picture?</h4></p>";

} else {

echo "<h3>There is no comment on $filename ,be the first to comment!</h3>";
}

db_close();


//add by haixin end

//echo "<tr><td>New:______________</td><td>_______</td><td>";
?>


</div>
<?php page_footer(); ?>
<!-- javascript start-->
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type='text/javascript' src='ajax.js'></script>
<script>
$(function(){
    $("#sendEmailButton").click(function() {
      var email = $("#email").val();
      var url = $("#url").val();
      MakeRequest(email, url);
      $('#myModal').modal('hide');
    });
});
</script>
<!-- javascript end-->
</body>
</html>
