<?php
include("lib.php");
db_connect();
if (isset($_POST['email']))
{
  $email = $_POST['email'];
  $query = "SELECT * From User WHERE email = '$email'";
  $result = mysql_query($query);
  $num = mysql_num_rows($result);
  if ($num == 0) {
    header("Location:resetpassword.php?wrongEmail");
  }
  else {
    $str = randString(10);
    $encrypted_str = hash('sha256', $str);
    while ($array = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $username = $array['username'];
      echo $username, $encrypted_str;
      mysql_query("UPDATE User SET password = '$encrypted_str' WHERE username = '$username'") 
        or die ("cannot update the new password");
      sendEmail($username, $email, $str);
    }
    header("Location:resetpassword.php?success");
  }
}


function randString($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
{
    $str = '';
    $count = strlen($charset);
    while ($length--) {
        $str .= $charset[mt_rand(0, $count-1)];
    }
    return $str;
}
function sendEmail($username, $to, $str)
{
  $subject = "Reset email";
  $message = "Master, the password of your account $username has been reseted to $str. Please login to change it.";
  $from = "EECS485 Group6 Webservant <webservant@eecs485-02.eecs.umich.edu>";
  $headers = "From:" . $from;
  mail($to,$subject,$message,$headers);
  //echo "Mail Sent.";
}
?>
