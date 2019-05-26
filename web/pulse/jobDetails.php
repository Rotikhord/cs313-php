<?php
  require_once 'database.php';
    $record = intval($_GET["record"]);

    //connect to the database and make a query
    $db = new Database();
    $query = $db->getDB()->prepare("SELECT * FROM job
            INNER JOIN customer ON job_cus_fk = cus_pk
            INNER JOIN address ON job_add_fk = add_pk
            WHERE job_pk = $record");
    if ($query->execute()){
      $result = $query->fetch(PDO::FETCH_ASSOC);
    }
  ?>
  <div class="detailBlock" id="customerHeader">
    <div class="detailInnerBlock">
      <h4> Customer Info:</h4>
        <?php
          $blockFields = array('cus_company_name', 'cus_contact_fname', 'cus_contact_lname');
          $blockLabels = array('Customer Name', 'Contact First Name', 'Contact Last Name');

          for ($i = 0; $i < sizeof($blockFields); $i++){
            if ($result[$blockFields[$i]] != ""){
              echo "<label for='" . $blockFields[$i] . "'>" . $blockLabels[$i] . ":</label><br>";
              echo "<input class='lockedInput' id='". $blockFields[$i] . "' type='text' readonly value='" . $result[$blockFields[$i]] . "'><br>";
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
              echo "<input class='lockedInput' id='". $blockFields[$i] . "' type='text' readonly value='" . $result[$blockFields[$i]] . "'><br>";
            }
          }
        ?>
    </div>
  </div>
  <div class="detailBlock" id="jobHeader">
    <div class="detailInnerBlock">
      <h4> Job Info:</h4>
        <?php
          $blockFields = array('job_number', 'job_date', 'add_street', 'add_city', 'add_zip');
          $blockLabels = array('Job Number', 'Start Date', 'Address', 'City', 'ZIP');

          for ($i = 0; $i < sizeof($blockFields); $i++){
            if ($result[$blockFields[$i]] != ""){
              echo "<label for='" . $blockFields[$i] . "'>" . $blockLabels[$i] . ":</label><br>";
              echo "<input class='lockedInput' id='". $blockFields[$i] . "' type='text' readonly value='" . $result[$blockFields[$i]] . "'><br>";
            }
          }
        ?>
      </div>
      <div class="detailInnerBlock">
        <h4> Job Details:</h4>
          <?php
            echo "<label for='job_balance'>Remaining Balance</label><br>";
            echo "<input class='lockedInput' id='job_balance' type='text' readonly value='$" . $result['job_balance'] . "'><br>";
            echo "<label for='job_description'>Description</label><br>";
            echo "<textarea class='lockedInput' id='job_description'  readonly>" . $result['job_description'] . "</textarea><br>";
          ?>
        </div>
  </div>
