<?php
  $error="";
  if (isset($_POST['submit'])) {
    include_once ('lib.php');
    db_connect();
    $username=$_POST['username'];
    $firstname=$_POST['firstname'];
    $lastname=$_POST['lastname'];
    $password=$_POST['password'];
    $cpassword=$_POST['confirmpassword'];
    $email=$_POST['email'];
	$encryptpass=$_POST['encryptpass'];
	$encrypted=$_POST['encrypted'];
    $res = mysql_query("SELECT username FROM User WHERE username='$username'");
    $row = mysql_num_rows($res);
    if( empty($username) || empty($password) || empty($firstname) || empty($lastname) || empty($cpassword) || empty($email))
    {
      $error = "All fields must be filled out.<br>";
    } 
	if( $row > 0 ) {
	  $error = $error . "Username has already been taken.<br>";
    } 
	if(!preg_match('/^\w{3,}$/', $username)) {
	  $error = $error. "Username needs to be at least 3 letters long and contains only letters, numbers and underscores.<br>";
    } 
	if (strlen($password) < 5 || strlen($password) > 15) {
      $error = $error. "Password needs to be between 5 and 15 characters.<br>";
    } 
	if($password!=$cpassword) {
      $error = $error. "Passwords do not match. Please try again.<br>";
    } 
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      $error = $error ."E-mail is not valid.";
    }
	if (!$error) {
	  if ($encrypted==0) $encryptpass=hash('sha256', $password, false);
      mysql_query("INSERT INTO User VALUES ('$username', '$firstname', '$lastname', '$encryptpass', '$email')");
	  db_close();
	  //These are the variables for the email
      $sendto = "$firstname $lastname <$email>"; // this is the email address collected form the form
      $subject = "Email confirmation-your registration with Group6 Photo Albums"; // Subject
      $message = "Your account with EECS485 Group6 Photo Album has been set up, $firstname $lastname."."\n".
                  "Your Username: $username"."\n".
                  "Your E-Mail: $email"."\n".
				  "Please visit us often at http://".$_SERVER[HTTP_HOST].dirname($_SERVER[REQUEST_URI])."/welcome.php"."\n";
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-Type: text/plain; charset=\"iso-8859-1\"' . "\r\n";
      $headers .= 'From: EECS485 Group6 Webservant <webservant@eecs.umich.edu>' . "\r\n";
      $headers .= 'Cc: qiliao@umich.edu' . "\r\n";
	  // This is the function to send the email
      mail($sendto, $subject, $message, $headers);  
      header("location: addnewuser.php?username=$username");
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
<title>Registration</title>
<meta charset="utf-8">
<meta name="descript" content="EECS485 Group6 Photo Album Website"/>
<meta name="keywords" content="eecs485, web, mysql, php, photo album"/>
<link rel="stylesheet" href="css/style.css">
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<style>
    body {
      height: 1000px;
    }
</style>
<!-- javascript begins -->
<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js"></script>
<script type="text/javascript">
function validateForm() {
  var Username = document.getElementById('username').value;
  var Firstname = document.getElementById('firstname').value;
  var LastName = document.getElementById('lastname').value;
  var Password = document.getElementById('password').value;
  var Cpassword = document.getElementById('confirmpassword').value;
  var Email = document.getElementById('email').value;
  if (Username == "") {alert("Enter Username"); return false;}
  if (Firstname == "") {alert("Enter First Name"); return false;}
  if (LastName == "") {alert("Enter Last Name"); return false;}
  if (Password == "") {alert("Enter Password"); return false;}
  if (Cpassword == "") {alert("Confirm Password"); return false;}
  if (Email == "") {alert("Enter Email"); return false;}
  if (Username.length < 3) {
    alert("Username must be at least 3 letters long");  
	return false;}
  var nameRegex = /^[a-zA-Z0-9\_]+$/;
  var validUsername = Username.match(nameRegex);
  if(validUsername == null){
    alert("Username must contain only letters, digits and underscores");
    return false; }
  if (Password.length < 5 || Password.length > 15) {
    alert("Passwords must be at least 5 and at most 15 characters long");
	return false;
	}
  if (Password != Cpassword) { 
    alert('Passwords do not match');	
	return false; } 
  var atpos=Email.indexOf("@");
  var dotpos=Email.lastIndexOf(".");
  if (atpos<1 || dotpos<atpos+2 || dotpos+2>=Email.length) {
    alert("Not a valid e-mail address");
    return false; }
  document.getElementById("encryptpass").value = CryptoJS.SHA256(Password);
  document.getElementById("encrypted").value = 1;
  return true;
}
</script>
<!-- javascript ends -->

</head>
<body>
<header id="main-header">
  <div class="container"> <a id="logo" href="index.php" title="Group6">Group6</a>
    <nav>
      <ul>
        <li><a href="viewalbumlist.php">AlbumList</a></li>
      </ul>
   <nav>
 </div>
</header>
<h4><p class='text-info'>Welcome, new user! Please enter all fields below and click Submit to register.</p></h4>
  <form class="form-horizontal" id="RegistrationForm" action="newuser.php" onsubmit="return validateForm();" method="post">
  <div class="control-group">
    <label class="control-label" for="inputUsername">Username</label>
    <div class="controls">
      <input type="text" id="username"
          name="username" placeholder="Username">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputFirstname">First Name</label>
    <div class="controls">
      <input type="text" id="firstname"
          name="firstname" placeholder="Firstname">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputLastname">Last Name</label>
    <div class="controls">
      <input type="text" id="lastname"
          name="lastname" placeholder="Lastname">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputPassword">Password</label>
    <div class="controls">
      <input type="password" id="password" 
              name="password" placeholder="Password">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="confirmPassword">Confirm Password</label>
    <div class="controls">
      <input type="password" id="confirmpassword" 
              name="confirmpassword" placeholder="Confirm password">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputEmail">Email</label>
    <div class="controls">
      <input type="text" id="email"
          name="email" placeholder="Email">	  
    </div>
  </div>
  <div STYLE="position: relative; left: 180px;">
    <input type="hidden" name="encryptpass" id="encryptpass" value="" />
	<input type="hidden" name="encrypted" id="encrypted" value="0" />
    <input name="submit" type="submit" value="Submit" />
  </div>
</form>
<?php
  if ($error){ //If any errors display them
    echo "<div STYLE=\"position: relative; left: 180px;\"><font color=\"Crimson\"><p><b>Error:</b></p> <p>$error</p></font></div>";
  }
?>
<footer class='footer'>Copyright &copy EECS485 Group6, 2013.</footer></body>
</html>

