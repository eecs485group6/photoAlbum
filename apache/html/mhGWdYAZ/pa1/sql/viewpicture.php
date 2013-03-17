<!DOCTYPE html>
<html>
<head>
<?php include ("lib.php"); ?>
<?php page_header("Picture"); ?>
<!-- useless css file> 
<style type="text/css">
 form #NavigationLeft { position: absolute;
                     top:       128px;
                     left:      80px;
                     width:     40px;
                     height:    21px;
                   }
 form #NavigationRight { position: absolute;
                     top:       128px;
                     left:      240px;
                     width:     40px;
                     height:    21px;
                   }
</style>
<-->
</head>
<body>
<?php


//    $url=$_GET['url'];

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

//    $albumid = $_GET['albumid'];

//changed by haixin end



    db_connect();
    echo "<div>";
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
                echo "<form action = \" viewpicture.php \" method = \"GET\">
                    <input type=\"hidden\" name=\"url\" value=".$array2['url']
                    .">
                    <input type=\"hidden\" name=\"albumid\" value=$albumid>
                    <input type=\"submit\" value=\"Previous\"></form>";
            }
        }
    }
?>
<?php
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
                echo "<form action = \" viewpicture.php \" method = \"GET\"
                    style = 'float:right;'>
                    <input type=\"hidden\" name=\"url\" value=".$array3['url']
                    .">
                    <input type=\"hidden\" name=\"albumid\" value=$albumid>
                    <input type=\"submit\" value=\"NEXT\"></form>";
            }
        }
    }
?>
</div>
<h2> Here is the picture. You may send it to your email by filling the email box and click on Email Picture button. </h2>

<?php
    // The Image
    $query="SELECT * FROM Photo WHERE url = '$url' ";
    $result=mysql_query($query) or die("Query failed. ". mysql_error());

    while ($array = mysql_fetch_array($result, MYSQL_ASSOC)) {
        echo "date:".$array['date']." format:".$array['format'];
        echo "<div><img src='".$array['url'] ."'></div>";
    }
    $queryTest="SELECT sequencenum FROM Contain 
        WHERE url = '$url' AND albumid = $albumid";
    $resultTest=mysql_query($queryTest) or die("Query failed. ". mysql_error());

    while ($arrayTest = mysql_fetch_array($resultTest, MYSQL_ASSOC)) {
        echo "Sequencenum=:".$arrayTest['sequencenum'];
    }
 
    db_close();
?>
 
<form action="" method="post">
    Your Email: <input type="text" name="email"><br>
    <input type="submit" value="Email Picture"/>
    <input type="hidden" name="button_pressed" value="1"/>

<!--change made by haixin start-->
<?php

 echo"       
       <input type=\"hidden\"name=\"url\" value=$url>
       <input type=\"hidden\"name=\"albumid\" value=$albumid>";
?>
<!--change made by haixin end-->

</form>
<?php
    if (isset($_POST['button_pressed'])) {
        $to = $_POST['email'];
        $from = "EECS485 Group6 Webservant <webservant@eecs485-02.eecs.umich.edu>";
        $subject = "Master, here is your lovely picture";
        $separator = md5(time());
        $message = "Master, here is the picture you choose. Enjoy it!";
        $eol = PHP_EOL;   //   "\r\n"
        
        
        $attachment = chunk_split(base64_encode(file_get_contents($url)));
        $headers = "From: ".$from.$eol;
        $headers .= "MIME-Version: 1.0".$eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator
            ."\"";
        
        $body = "--".$separator.$eol;
        $body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
        $body .= "This is a MIME encoded message.".$eol;

        //message
        $body .= "--".$separator.$eol;
        $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
        $body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
        $body .= $message.$eol;

        //attachment
        $body .= "--".$separator.$eol;
        $body .= "Content-Type: application/octet-stream; name=\""
            .$url."\"".$eol;
        $body .= "Content-Transfer-Encoding: base64".$eol;
        $body .= "Content-Disposition: attachment".$eol.$eol;
        $body .= $attachment.$eol;
        $body .= "--".$separator."--";


        mail($to, $subject, $body, $headers);

        echo 'Email Sent.';
    }






//Add by haixin start

if(isset($_POST['name'])&& isset($_POST['comment'])&&($_POST['name']!="")&&($_POST['comment']!="")) {
  $name = $_POST['name'];
  $comment = $_POST['comment'];
  $url = $_POST['url'];
//    echo "underconstruct!the album to be added is username:$username title:$title access:$access";
    db_connect();
//first check whether the album exists ot not
    $query = "SELECT * FROM Comments WHERE url = '$url' AND commentuser = '$name' AND comments = '$comment'";
    $result = mysql_query($query);
//the comment record  does not exist
    if (!mysql_num_rows($result)){
//get the commentid for the new comment
    $query = "SELECT MAX(commentid) AS commentid FROM Comments";
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    $commentid = $row[0]+1;
//add to album
    $query="INSERT INTO Comments VALUES('$commentid','$name','$url','$comment')";
    mysql_query($query);
    }
    db_close();   

 }else{

     if(isset($_POST['url'])){
      $url = $_POST['url'];
     }else{
      $url = $_GET['url'];
      }
}

$filename = basename($url).PHP_EOL;
echo "<h3>Comments on $filename </h3>";
?>


<table border="1">
 <tr>
    <th>Name</th>
    <th>Comment</th>
 </tr>

<?php
db_connect();

$query="SELECT * FROM Comments WHERE url = '$url'";
$result = mysql_query($query);
$num = mysql_numrows($result);
for ($i = 0; $i < $num ; ++$i) {
     $name =mysql_result($result,$i,"commentuser");
     $comment =mysql_result($result,$i,"comments");
      
//get rid of resubmission
//reference:http://bjw.co.nz/developer/general/75-how-to-prevent-form-resubmission
   
     echo "<tr><td>$name</td><td>$comment</td></tr>";

db_close();

}

//echo "<tr><td>New:______________</td><td>_______</td><td>";
?>
</table>

<br>
<p><h4>How do you like the picture?</h4></p>

<?php
echo " <form action= \"viewpicture.php \" method=  \"POST\">
       Name:<br><input type=\"text\" name=\"name\"> <br>
       Comment:<br>
       <textarea rows=\"4\" cols=\"50\" name=\"comment\">
       </textarea> <br>
       <input type=\"hidden\"name=\"url\" value=$url>
       <input type=\"hidden\"name=\"albumid\" value=$albumid>
       <input type=\"submit\" value=\"Send\"></form>";
//echo"<td>         </td></tr>";



//Add by haixin end

?>
   <?php page_footer(); ?>
</body>
</html>
