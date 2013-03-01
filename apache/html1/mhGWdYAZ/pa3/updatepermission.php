<?php

include("lib.php");

db_connect();

   if(isset($_POST['albumid'])){
      $albumid=$_POST['albumid'];
    }
?>



<?php
 
 if (isset($_POST['adduserid'])&&($_POST['adduserid'])!="null"){
    $adduserlist = $_POST['adduserid'];
   // echo("You selected $N user(s): ");
     mysql_query("INSERT INTO AlbumAccess VALUES('$albumid','$adduserlist')");

$information = "Permission added!";
}
?>

<?php

 if (isset($_POST['deleteuserid'])&&($_POST['deleteuserid'])!="null"){
    $deleteuserlist = $_POST['deleteuserid'];
   // echo("You selected $N user(s): ");
    mysql_query("DELETE FROM AlbumAccess 
      WHERE username = '$deleteuserlist' 
        AND albumid = '$albumid' 
        AND username NOT IN (SELECT username FROM Album 
                              WHERE albumid = '$albumid')");

$information = "Permission deleted!";
}
  
//echo "adduserlist $adduserlist deleteuserlist $deleteuserlist information: $information ";



echo "<table>

<tbody>";

//echo "<h5><p class='text-info'>All Users who have access:</p></h5>";
//echo"<input type=\"hidden\" name=\"albumid\" value='$albumid' /> <br>";


$resultx = mysql_query("SELECT username FROM User");
 $numx=mysql_num_rows($resultx);

 for ($j = 0; $j < $numx; ++$j) {
  $user = mysql_result($resultx, $j, "username");
  $check = mysql_query("SELECT * FROM AlbumAccess WHERE albumid = '$albumid' AND username ='$user'" );
  $checkowner = mysql_query("SELECT * FROM Album WHERE albumid = '$albumid' AND username ='$user'" );
  $numcheck=mysql_num_rows($check);
  $numcheckowner=mysql_num_rows($checkowner);

 if ($numcheck){
      echo "<tr>";
      echo "<td><div class='drag_user' >$user</div><div class='dragremove' albumid = $albumid  usertodelete = $user style='display: none;'> Wanna move to trash? </div> </td>";
      echo "<tr>";

   }
 }

echo "</tbody></table>"; 


db_close();


?>








