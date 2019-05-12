<?php
  session_start();

  class product{
    public $vowel;
    public $name;
    public $path;
    public $price;
    public $qty;
    public $desc;

    public function __construct($vowel, $name, $path, $price, $desc){
      $this->vowel=$vowel;
      $this->name=$name;
      $this->path=$path;
      $this->price=$price;
      $this->qty = 0;
      $this->desc=$desc;
    }
  }

  $prodA = new product("a","The Vowel A","a.png",12.34, "The vowel A is an excellent choice for those just getting into the vowel market.
    With a variety of sounds and uses, this is sure to be an excellent choice!");
  $prodE = new product("e","The Vowel E","e.png",23.45, "The vowel E is easily one of the most popular vowels on the market. If you are
    looking for a crowd pleaser, your search is over. Look no further than the magnificent E!");
  $prodI = new product("i","The Vowel I","i.png",45.67, "The vowel I is a unique vowel. Of all the vowels this one stands alone in it's ability
    to stand alone. Seriously, with just this one vowel, you get a proper noun! How is that for value?");
  $prodO = new product("o","The Vowel O","o.png",34.56, "The vowel O is particularly fun. With just the single vowel, you get the value of two letters! (oh)
    Where else can you get that kind of bargain? In addition, it's one of Santa's favorite vowels. Hohoho indeed Santa.");
  $prodU = new product("u","The Vowel U","u.png",43.21, "The vowel U is a popular choice among our vowel connoisseurs. It's one of the less common vowels,
    but with that uncommonality, comes an exquisite-ness unmatched among the vowels. If you are looking to round out your vowel collection, this is the vowel you want!");
  $prodY = new product("y","Sometimes Y","y.png",56.78, "For a limited time only, we have our exlusive Y. While not technically a vowel, Y is an excellent
    choice for those individuals who like to march to the beat of their own drum. After all, life is short. Be unique!");

  $prodList= array($prodA, $prodE, $prodI, $prodO, $prodU, $prodY);
  if (!isset($_SESSION["list"])){
    $_SESSION["list"] = $prodList;
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
    $_SESSION["reqPage"] = "browse";
    include "header.php";
  ?>
  <div id="content">
    <?php

     foreach ($_SESSION["list"] as $prod){
       $_SESSION["reqPage"] = "browse";
       $_SESSION["product"] = $prod;
       include "product.php";
     }
    ?>
  </div>
</body>
</html>
