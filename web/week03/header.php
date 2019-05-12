<?php   ?>
  <div id="header">
    <div id="logoArea">
      <a href="browse.php"><img src="buyvowel.png"></a>
    </div>
    <div id="titleArea" >
      <h1>Buy-A-Vowel</h1>
      <h2>Your one-stop-shop location for high quality, premium, gluten free and fully organic vowels!</h2>
    </div>
    <?php
      if($_SESSION["reqPage"] != "check" && $_SESSION["reqPage"] != "confirm"){
        echo "<div id='cartArea'><p id='cartItems'>Items: 0</p><p id='cartTotal'>Total: $0.00 </p>
              <a href='cart.php'><img src='cart_img.png' width='75'></a></div>";
      }
      ?>
  </div>
