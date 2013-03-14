<?php
  function page_header($title)
  {
      echo "<title>".$title."</title>";
  }
  function page_footer()
  {
      echo "<footer class='footer'>Copyright &copy EECS485 Group6, 2013.</footer>";
  }

  function db_connect() {
      $user="group6";
      $password = "01302013";
      $con = mysql_connect('127.0.0.1', $user, $password);
      if (!$con)
      {
          die('Could not connect: '.mysql_error());
      }
      mysql_select_db("group6", $con);
      return $con;
  }

  function db_close() {
      mysql_close();
  }
?>
