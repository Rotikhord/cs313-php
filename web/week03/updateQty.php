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

  foreach ($_SESSION["list"] as $prod){
    if($prod->vowel == $_GET["vowel"]){
      $prod->qty = $_GET["count"];
      echo "updated qty";
      break;
    }
  }

  ?>
