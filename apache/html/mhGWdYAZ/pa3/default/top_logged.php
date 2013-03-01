<header id="main-header">
  <div class="container"> <a id="logo" href="index.php" title="Group6">Group6</a>
    <nav>
      <ul>
        <li><a href="editaccount.php">Edit Account</a></li>
        <li><a href="editalbumlist.php">My Albums</a></li>
        <li><a href="logout.php">Log Out</a></li>
      </ul>
   </nav>
 </div>
</header>

<?php
  if (isset($_SESSION['admin']))
  echo "
        <div class='alert alert-info'>
            <button type='button' class='close' data-dismiss='alert'>
                &times;
  </button>
          Logged in as ".$_SESSION['firstname']." ".$_SESSION['lastname']
          ."(Administrator)</div>";
  else
echo "
        <div class='alert alert-info'>
            <button type='button' class='close' data-dismiss='alert'>
                &times;
  </button>
          Logged in as ".$_SESSION['firstname']." ".$_SESSION['lastname']
          ."</div>";
?>
