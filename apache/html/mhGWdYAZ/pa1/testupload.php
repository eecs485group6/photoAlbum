<!DOCTYPE html>
<html>
<head>
<?php include ("lib.php"); ?>
<?php page_header("Test"); ?>
</head>
<body>
<form action="uploadphoto.php" method="post" enctype="multipart/form-data">
<?php
echo "<input type=\"hidden\" name=\"albumid\" value=\"1\">";
?>
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="addSubmit" value="Add picture">

</form>
<?php page_footer(); ?>
</body>
</html>

