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

    $_SESSION["name"] = htmlspecialchars($_GET["name"]);
    $_SESSION["address"] = htmlspecialchars($_GET["address"]);
    $_SESSION["city"] = htmlspecialchars($_GET["city"]);
    $_SESSION["state"] = htmlspecialchars($_GET["state"]);
    $_SESSION["zip"] = htmlspecialchars($_GET["zip"]);

    if($_SESSION["name"] == "" || $_SESSION["address"] == "" || $_SESSION["city"] == "" || $_SESSION["state"] == "" || $_SESSION["zip"] == ""){
      $_SESSION["isValid"] = false;
      include "checkout.php";
    } else {
      $_SESSION["isValid"] = true;
      include "confirm.php";
    }




?>
