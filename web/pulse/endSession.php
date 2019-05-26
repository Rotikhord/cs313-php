<?php
  session_start();
  //Check to see if the current session is valid.
  if (!isset($_SESSION['user'])){
    $_SESSION['badSession'] = true;
  } else {
    session_unset();
    session_destroy();
  }
  header("Location: login.php");
  die();

  ?>
