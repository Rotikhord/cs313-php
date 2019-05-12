<?php
  session_start();

  class product{
    public $vowel;
    public $name;
    public $path;
    public $price;
    public $qty;
    public $desc;
    }


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
    $_SESSION["reqPage"] = "cart";
    include "header.php";
  ?>
  <div id="content">
    <h1 class="message"> Your Cart </h1>
    <?php
    $count = 0;
    foreach ($_SESSION["list"] as $prod){
      if($prod->qty > 0){
        $_SESSION["reqPage"] = "cart";
        $_SESSION["product"] = $prod;
        include "product.php";
        $count++;
      }
    }
    if ($count == 0){
      echo "<h2 class='message'> Uh-oh, It seems to be empty.</h2>";
    } else {
      echo "<a href='checkout.php' class='button'>Check out</a>";
    }
    echo "<a href='browse.php' class='button'>Click here to keep shopping</a>";
    ?>
  </div>
</body>
</html>
