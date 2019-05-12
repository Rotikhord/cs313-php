<?php
  session_start();




  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Week 03 - Shopping Cart Web-App</title>
  <meta name="description" content="A shopping cart web app">
  <meta name="keywords" content="">
  <meta name="author" content="Doug Barlow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="week3style.css">
  <link rel="stylesheet" href="week3headerstyle.css">
  <link rel="stylesheet" href="week3cardstyle.css">
  <script src="week03script.js"></script>
</head>
<body onload="onLoad()">
  <?php
    $_SESSION["reqPage"] = "confirm";
    include "header.php";
  ?>
  <div id="content">
    <h1 class="message"> Your order has been placed! </h1>
    <div id="formCard">
      <h3 class="message">Shipping Address:</h3>
      <?php
        echo "<h3 class='shipMessage'>" . $_SESSION["name"] . "</h3>";
        echo "<h3 class='shipMessage'>" . $_SESSION["address"] . "</h3>";
        echo "<h3 class='shipMessage'>" . $_SESSION["city"] . ", "  . $_SESSION["state"] . " "  . $_SESSION["zip"] . "</h3>";
      ?>
    </div>
    <?php
    foreach ($_SESSION["list"] as $prod){
      if($prod->qty > 0){
        $_SESSION["reqPage"] = "confirm";
        $_SESSION["product"] = $prod;
        include "product.php";
      }
    }
    ?>
  </div>
</body>
</html>
