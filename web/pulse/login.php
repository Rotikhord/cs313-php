<?php
  session_start();
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Pulse Login</title>
  <meta name="description" content="A project tracking application">
  <meta name="keywords" content="">
  <meta name="author" content="Doug Barlow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="pulseLogin.css">
  <script src="pulseLogin.js"></script>
</head>
<body>
  <?php
    include "header.php";
    ?>
  <div id="loginBlock">
    <div id="loginMessageBlock">
      <?php
        if (isset($_SESSION['badSession']) && $_SESSION['badSession']){
          echo "<h2 id='loginMessage' style='color:darkred'> You must sign in first </h2>";
        } else {
          echo "<h2 id='loginMessage'> Please sign in </h2>";
        }
      ?>
    </div>
    <div id="loginEmailBlock">
      <label id="emailLabel" for="email">Email</label>
      <input id="email" type="email" name="email">
    </div>
    <div id="loginPWBlock">
     <label id="passwordLabel" for="password">Password</label>
     <input id="password" type="password" name="password">
    </div>
    <div id="loginButtonBlock">
      <button id="submit" onclick="validateLogin()">Sign In</button>
    </div>
    <div id='testMessage'>
    </div>
  </div>
</body>
</html>
