<?php
  $vowel = $_SESSION["product"]->vowel;
  $qty = $_SESSION["product"]->qty;
  $total = $_SESSION["product"]->qty * $_SESSION["product"]->price;
  ?>
  <div id="card">
    <div id="cardHeader">
      <div id="cardImage">
        <?php echo "<img src='" . $_SESSION["product"]->path ."''>"; ?>
      </div>
      <div id="cardTitle">
        <h2><?php echo $_SESSION["product"]->name; ?></h2>
        <h3>Price: $
          <?php
            echo "<span id='price_" . $vowel . "'>" . $_SESSION["product"]->price . "</span>";
          ?></h3>
      </div>
    </div>
    <?php
    if ($_SESSION["reqPage"] == "browse"){
      echo "<div id=\"cardBody\"><p>". $_SESSION["product"]->desc . "</p></div>";
    }
    ?>
    <div id="cardFooter">
      <table>
        <tr>
          <td id="firstCol">Qty:</td>
          <td id="column">
            <?php
            if ($_SESSION["reqPage"] != "confirm"){
              echo "<input type='text' id='input_" . $vowel ."' value='" . $qty . "' oninput='validateQty(this)'>";
            } else {
              echo $qty;
            }
            ?>
         </td>
        </tr>
        <tr>
          <td>Total:</td>
          <td>$
            <?php
              echo "<span id='total_" . $vowel . "'>" . $total . "</span>"
              ?>
          </td>
        </tr>
        <?php
          if ($_SESSION["reqPage"] != "confirm"){
            echo "<tr><td colspan='2'><button id='button" . $vowel . "' onclick='updateQty(this)'>";
            if ($_SESSION["reqPage"] == "browse"){
              echo "Add to Cart";
            } else {
              echo "Update";
            }
            echo "</button></td></tr>";
          }
        ?>
      </table>
    </div>
  </div>
