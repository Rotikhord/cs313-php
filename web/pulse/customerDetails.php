<?php
  require_once 'database.php';
    $record = intval($_GET["record"]);

    //connect to the database and make a query
    $db = new Database();
    $query = $db->getDB()->prepare("SELECT * FROM customer
            INNER JOIN address ON cus_add_fk = add_pk
            WHERE cus_pk = $record");
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

  ?>
  <div class="detailBlock" id="customerHeader">
    <div class="detailInnerBlock">
      <h4> Customer Info:</h4>
        <?php
          $blockFields = array('cus_company_name', 'cus_contact_fname', 'cus_contact_lname', 'add_street', 'add_city', 'add_zip');
          $blockLabels = array('Customer Name', 'Contact First Name', 'Contact Last Name', 'Mailing Address', 'City', 'ZIP');

          for ($i = 0; $i < sizeof($blockFields); $i++){
            if ($result[$blockFields[$i]] != ""){
              echo "<label for='" . $blockFields[$i] . "'>" . $blockLabels[$i] . ":</label><br>";
              echo "<input class='lockedInput' id='". $blockFields[$i] . "' type='text' value='" . $result[$blockFields[$i]] . "'><br>";
            }
          }
        ?>
    </div>
    <div class="detailInnerBlock">
      <h4> Contact Info:</h4>
        <?php
          $blockFields = array('cus_phone1', 'cus_phone2', 'cus_fax', 'cus_email');
          $blockLabels = array('Primary Phone', 'Secondary Phone', 'Fax Number', 'Email Address');

          for ($i = 0; $i < sizeof($blockFields); $i++){
            if ($result[$blockFields[$i]] != ""){
              echo "<label for='" . $blockFields[$i] . "'>" . $blockLabels[$i] . ":</label><br>";
              echo "<input class='lockedInput' id='". $blockFields[$i] . "' type='text' value='" . $result[$blockFields[$i]] . "'><br>";
            }
          }
        ?>
    </div>
  </div>
