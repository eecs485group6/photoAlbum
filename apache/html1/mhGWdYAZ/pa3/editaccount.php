<?php
include("authentication.php");
include("lib.php");
db_connect();

$res = mysql_query("SELECT * FROM User WHERE username='$username'");
$user = mysql_fetch_array($res, MYSQL_ASSOC);
$email = $user['email']; 
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
} else {
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
  ob_start();
  header("Location:welcome.php");
  ob_end_flush();
}
?>
<?php
  $error="";
  $information=false;
  if (isset($_GET['information'])) {
    $information = $_GET['information'];
  }
  
  if (isset($_POST['submit'])) {
    $newfirstname=$_POST['newfirstname'];
    $newlastname=$_POST['newlastname'];
    $newpassword=$_POST['newpassword'];
    $Newcpassword=$_POST['newconfirmpassword'];
    $newemail=$_POST['newemail'];
	$encryptpass=$_POST['encryptpass'];
	$encrypted=$_POST['encrypted'];
    if ($newpassword !="" && (strlen($newpassword) < 5 || strlen($newpassword) > 15)) {
      $error = $error. "Password needs to be between 5 and 15 characters.<br>";
    }
	if ($newpassword != "" && $Newcpassword == "") {
	  $error = $error. "Please confirm the new password.<br>";
	} else if ($newpassword!=$Newcpassword) {
      $error = $error. "Passwords entered do not match. Please try again.<br>";
    }
    if ($newemail != "") {
	  if(!filter_var($newemail, FILTER_VALIDATE_EMAIL))
      {
        $error = $error. "The new e-mail is not in a valid form.<br>";
      }
	}
    if (!$error) {
      if ($newfirstname!="") {
        mysql_query("UPDATE User SET firstname = '$newfirstname' WHERE username = '$username'");
        $_SESSION['firstname']=$newfirstname; 
      }  
      if ($newlastname!="") {
        mysql_query("UPDATE User SET lastname = '$newlastname' WHERE username = '$username'");
        $_SESSION['lastname']=$newlastname;
      }
      if ($newpassword!="") {
        if ($encrypted == 0) $encryptpass=hash('sha256', $newpassword, false);
		mysql_query("UPDATE User SET password = '$encryptpass' WHERE username = '$username'");
	  }
      if ($newemail!="") mysql_query("UPDATE User SET email = '$newemail' WHERE username = '$username'");
      db_close();
      $information = true;
      header("location: editaccount.php?information=".$information);
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
<?php page_header("EditAccount"); ?>
<?php include ("default/head.php"); ?>
<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js"></script>
<script type="text/javascript">
function validateForm() {
  var NewFirstname = document.getElementById('newfirstname').value;
  var NewLastName = document.getElementById('newlastname').value;
  var NewPassword = document.getElementById('newpassword').value;
  var Newcpassword = document.getElementById('newconfirmpassword').value;
  var NewEmail = document.getElementById('newemail').value;
  if (NewPassword != "") {
    if (NewPassword.length < 5 || NewPassword.length > 15) { 
      alert("Password must be at least 5 and at most 15 characters long");
      return false; }
	if (Newcpassword == "")  {
	  alert('Please confirm the new password');
	  return false; } else 
	if (Newcpassword != "" && NewPassword != Newcpassword) {
	  alert('Passwords entered do not match');
	  return false; }
  } else if (Newcpassword != "") {
      alert('Passwords entered do not match');
      return false;}
  if (NewEmail != "") {
    var atpos=NewEmail.indexOf("@");
    var dotpos=NewEmail.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=NewEmail.length) {
      alert("New email entered is not a valid e-mail address");
      return false; }
  }
    document.getElementById("encryptpass").value = CryptoJS.SHA256(NewPassword);
	document.getElementById("encrypted").value = 1;
    return true;
}
function confirmAction(){
      var confirmed = confirm("Are you sure about the deletion? This will remove all the pictures in your albums from the database.");
      return confirmed;
}

</script>
<!-- javascript ends -->

</head>
<body>
<?php include ("default/top_logged.php"); ?>

<?php 
  if ($information)
  echo "<p><font color=\"red\">Your account has been successfully updated</font></p>"; 
  ?>

<!-- breadcrumb begins -->
<div class="row">
  <div class="span10">
  <ul class="breadcrumb">
    <li>
      <a href="index.php">Home</a> <span class="divider" style="color: #0c0c0c">></span>
    </li>
      <a href='#'>Edit account</a>
    </li>
  </ul>
  </div>
  </div>
<!-- breadcrumb ends -->



<h4><p class='text-info'>Please enter the fields you want to change below and click Submit to edit your account.</p></h4>
  <form class="form-horizontal" id="EditAccountForm" action="editaccount.php" onsubmit="return validateForm();" method="post">
  <div class="control-group">
    <label class="control-label" for="inputFirstname"> New First Name</label>
    <div class="controls">
      <input type="text" id="newfirstname"
          name="newfirstname" placeholder=<?php echo $_SESSION['firstname']; ?>>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputLastname">New Last Name</label>
    <div class="controls">
      <input type="text" id="newlastname"
          name="newlastname" placeholder=<?php echo $_SESSION['lastname']; ?>>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputPassword">New Password</label>
    <div class="controls">
      <input type="password" id="newpassword"
              name="newpassword" placeholder="**">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="confirmPassword">Confirm New Password</label>
    <div class="controls">
      <input type="password" id="newconfirmpassword"
              name="newconfirmpassword" placeholder="**">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputEmail">New Email</label>
    <div class="controls">
      <input type="text" id="newemail"
          name="newemail" placeholder="<?php echo $email; ?>">
    </div>
  </div>
  <div STYLE="position: relative; left: 180px;">
    <input type="hidden" name="encryptpass" id="encryptpass" value="" />
	<input type="hidden" name="encrypted" id="encrypted" value="0" />
    <input name="submit" type="submit" value="Submit" />
  </div>
</form>
 <h4><b>Decide to delete your account? Click <a href=<?php echo "deleteuser.php?username=".$username ?> onclick="return confirmAction()">
 <font color="Crimson">Here</font></a>.</b></h4>
<?php
  if ($error){ //If any errors display them
    echo "<div STYLE=\"position: relative; left: 180px;\"><font color=\"Crimson\"><p><b>Error:</b></p> <p>$error</p></font></div>";
  }
?>
<footer class='footer'>Copyright &copy EECS485 Group6, 2013.</footer></body>
</html>


