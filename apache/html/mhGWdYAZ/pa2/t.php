<html>
<body

<form action="t.php" method="POST">
 
Which buildings do you want access to?<br />
<input type="checkbox" name="formDoor[]" value="A" />Acorn Building<br />
<input type="checkbox" name="formDoor[]" value="B" />Brown Hall<br />
<input type="checkbox" name="formDoor[]" value="C" />Carnegie Complex<br />
<input type="checkbox" name="formDoor[]" value="D" />Drake Commons<br />
<input type="checkbox" name="formDoor[]" value="E" />Elliot House
 
<input type="submit" name="formSubmit" value="Submit" />
 
</form>

<?php

if(isset($_POST['formDoor'])){
  $aDoor = $_POST['formDoor'];
  if(empty($aDoor))
  {
    echo("You didn't select any buildings.");
  }
  else
  {
    $N = count($aDoor);
 
    echo("You selected $N door(s): ");
    for($i=0; $i < $N; $i++)
    {
      echo($aDoor[$i] . " ");
    }
  }
}
?>
</body>



</html>
