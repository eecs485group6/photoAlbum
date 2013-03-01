<?php
include("authentication.php");
?>
<!DOCTYPE html>
<html>
<head>
<?php include ("lib.php"); ?>
<?php page_header("Login"); ?>
<?php include ("default/head.php"); ?>
</head>
<body>
<?php include("default/top.php"); ?>
<?php
  //$_SESSION['url'] = "index.php";
if (isset($_SESSION['url']) && $_SESSION['url'] != "index.php")
  echo "<h4><p class='text-warning'>
            Please login to view ".$_SESSION['url']
        ."</p></h4>";
?>
  <form class="form-horizontal"  id="LoginForm" action="update.php" method="POST">
  <div class="control-group">
    <label class="control-label" for="inputUsername">Username</label>
    <div class="controls">
<?php
  if(isset($username))
    echo "<input type='text' id='username' name='username' placeholder='Username' value='$username'>";
  else echo 
      "<input type='text' id='username'
        name='username' placeholder='Username'>";
?>
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
    <div class="controls">
      <label class="checkbox">
        <input type="checkbox" name="remember" id="remember"> Remember me
      </label>
      <a href='resetpassword.php'>Forget Password?</a>
      <button onclick="encryptValue()" class='btn btn-primary'>Sign in</button>
    </div>
  </div>
</form>
<!-- javascript begins -->
<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js"></script>
<script>
function encryptValue() {
  //document.loginForm.password.value
  var val = document.getElementById("password").value;
  document.getElementById("password").value = CryptoJS.SHA256(val);
  //document.forms["LoginForm"].submit();

}
</script>
<!-- javascript ends -->
<?php page_footer(); ?>
</body>
</html>
