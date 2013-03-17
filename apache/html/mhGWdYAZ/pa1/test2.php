<!DOCTYPE html><html>
<head>
<link rel="stylesheet" href="css/style.css">
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<?php include ("lib.php"); ?>
<?php page_header("Picture"); ?>
</head>
<body>
  <header id="main-header">
   <div class="container"><a id="logo" href="/" title="Four">Four</a>
        <nav>
          <ul>
            <li><a href="/page/about">About</a></li>
            <li><a href="/page/links">Friends</a></li>
          </ul>
        </nav>
      </div> 
  </header>
<h3><p class="text-info"> You can send pictures to your email!!</p></h3>
<p>
Your Email:
<input id= "email" type="text" name="email" 
placeholder="Your email address..."><br>
</p>
<button class="btn btn-primary" type="button" id="filter" name="filter">
Send Email
</button>
<form action="uploadphoto.php" method="post" enctype="multipart/form-data">
<?php
echo "<input type='hidden' name='albumid' value='$albumid'>";
?>
<div class="fileupload fileupload-new" data-provides="fileupload">
  <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>
  <div>
    <span class="btn btn-file">
      <span class="fileupload-new">Select image</span>
      <span class="fileupload-exists">Change</span>
    <input type="file" name="file" id="file" /></span>
    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
  </div>
</div>
<input class="btn btn-success" type="submit" name="addSubmit" value="Add Picture">
</form>

<?php page_footer(); ?>
<!-- javascript start-->
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type='text/javascript' src='ajax.js'></script>
<script>
$(function(){
    $("#filter").click(function() {
        var email = $("#email").val();
        MakeRequest(email);
    });
});
</script>
<!-- javascript end-->
</body>
</html>
