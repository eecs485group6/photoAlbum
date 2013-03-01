<?php
  session_start();
  include('session_start.php');
  my_session_start();
  if (isset($_POST['username']))
    $username = $_POST['username'];
  else $username = NULL;

  if (isset($_POST['password']))
    $password = $_POST['password'];
  else $password = NULL;

  if (isset($_POST['remember']))
    $remember = $_POST['remember'];
  else $remember = NULL;
  include ('lib.php');
  db_connect();

  $url = (isset($_SESSION['url']))? $_SESSION['url']: "index.php";
  //query if this username exist
  $query = "SELECT * FROM User WHERE username='$username'";
  $result = mysql_query($query);
  $num = mysql_num_rows($result);
  //query if the username/password pair exist
  $queryUser="SELECT * FROM User WHERE username='$username' AND password = '$password'";
  $resultUser = mysql_query($queryUser);
  $numUser = mysql_num_rows($resultUser);

//add admin check haixin
if ($numUser){
  $queryadmin="SELECT * FROM Admin WHERE username='$username'";
  $resultAdmin = mysql_query($queryadmin);
  $numAdmin = mysql_num_rows($resultAdmin);
  if ($numAdmin ) {
    $admin = true;
  }
//  error_log($numAdmin);
  error_log($_SESSION['admin']);
}

//admin check end

  if (!$num) {
    ob_start();
    header("Location:welcome.php?find=false");
     $_SESSION['inactivity'] = time();
    ob_end_flush();
  }
  else if (!$numUser) {
    ob_start();
    header("Location:welcome.php?find=true&username=$username");
    $_SESSION['inactivity'] = time();
    ob_end_flush();
  }
  else {
    session_destroy();
    if ($remember= 'ON') {
      my_session_start(3600*24*30); //save for one month
    } 
    else my_session_start(1800); //save for 30 mins 
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    $_SESSION['inactivity'] = time();
    $_SESSION['admin']=$admin;
  }
?>
<meta http-equiv="refresh" content="0; URL='<?php echo $url; ?>'" />
