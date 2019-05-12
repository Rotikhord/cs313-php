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
    $_SESSION["reqPage"] = "check";
    include "header.php";
  ?>
  <div id="content">
    <h1 class="message"> Please Confirm Your Billing Address</h1>
    <?php
      if($_SESSION["isValid"] == false){
        echo "<h2 class='message'>All fields are required</h2>";
      }
      ?>
    <form action="validate.php">
      <div id="formCard">
        <div class="inputBlock">
          <label>Name</label>
          <input name="name" type="text"><br>
        </div>
        <div class="inputBlock">
          <label>Street Address</label>
          <input name="address" type="text"><br>
        </div>
        <div class="inputBlock">
          <label>City</label>
          <input name="city" type="text"><br>
        </div>
        <div class="inputBlock">
          <label>State</label>
          <input name="state" type="text"><br>
        </div>
        <div class="inputBloc">
          <label>Zip Code</label>
          <input name="zip" type="text"><br>
        </div>
      </div>
      <button type="submit" class="button">Place Order</button>
    </form>
    <?php
    echo "<a href='cart.php' class='button'>Return to cart</a>";
    echo "<a href='browse.php' class='button'>Continue shopping</a>";
    ?>
  </div>
</body>
</html>
